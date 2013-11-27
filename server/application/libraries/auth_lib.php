<?php
/**
 * @Author: odirus@163.com
 */
class Auth_lib {

    private $_CI;
    public $err_msg = array();

    public function __construct() {
        $this->_CI =& get_instance();
    }

    private function _check_cookies() {
        if (isset($_COOKIE['maoejie_session_id'])) {
            $this->_CI->load->model('cookie_model', 'cookie_m');
            if ($res = $this->_CI->cookie_m->get_user_cookie($_COOKIE['maoejie_session_id'])) {
                if ($res['user_agent'] == $_SERVER['HTTP_USER_AGENT']) {
                    $_SESSION['object_user_id'] = (int) $res['user_id'];
                }
            }
        }
    }

    /**
     * 检查用户是否已经登录
     *
     * @return boolean
     */
    public function user_is_login() {
        if (isset($_SESSION['object_user_id']) && is_numeric($_SESSION['object_user_id'])) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * 执行用户登录功能
     *
     * @return array $res => [boolean, [string]]
     */
    public function do_login($username, $password, $remember) {
        $this->_CI->load->library('regulation');
        $this->_CI->load->model('user_model', 'user_m');
        $this->_CI->load->model('cookie_model', 'cookie_m');

        if ($this->user_is_login()) {
            return [ 'res' => FALSE , 'msg' => ['user has logged in'] ];
        }

        $arr = array('username' => $username, 'password' => $password);
        foreach ($arr as $key => $value) {
            $this->_CI->regulation->validate($key, $value);
        }

        //用户输入非法
        if (count($this->_CI->regulation->err_msg) > 0) {
            $this->err_msg = $this->_CI->regulation->err_msg;
            $this->_CI->regulation->err_msg = array();
            return array(
                'res' => FALSE,
                'msg' => $this->err_msg
            );
        }

        if($hash_password_db = $this->_CI->user_m->get_hash_password($username)) {
            if($hash_password_db['password'] === md5($password)) {
                //设置session
                $_SESSION['object_user_id'] = $this->_CI->user_m->get_user_id($username);
                if ($remember == 'on') {
                    //设置cookie
                    setcookie('maoejie_session_id', session_id(), time() + 3600 * 24 * 7);
                    $this->_CI->cookie_m->write_user_cookie($_SESSION['object_user_id'], session_id(), $_SERVER['HTTP_USER_AGENT']);
                }
                return array(
                    'res' => TRUE,
                    'data' => NULL
                );
            }
        }

        $this->err_msg[] = 'Username or password wrong';
        return array(
            'res' => FALSE,
            'msg' => $this->err_msg
        );
    }

    /**
     * 获取用户基本信息
     */
    public function user_info() {
        $this->_CI->load->model('user_model', 'user_m');
        if (!empty($_SESSION['object_user_id'])) {
            $user_id = $_SESSION['object_user_id'];
            if ($user_info = $this->_CI->user_m->get_user_info($user_id)) {
                $user_info['user_id'] = $user_info['id'];
                $user_info['user_role'] = $user_info['role'];
                unset($user_info['role']);
                unset($user_info['id']);
                return $user_info;
            }
        }
        return FALSE;
    }

    /**
     * 执行用户登出功能
     */
    public function do_logout() {
        $this->_CI->load->model('cookie_model', 'cookie_m');
        unset($_SESSION['object_user_id']);
        setcookie('maoejie_session_id', '', time() - 1);
        $this->_CI->cookie_m->delete_user_cookie(session_id());
        if(empty($_SESSION['object_user_id'])) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * 执行注册用户功能，同时注册卖家的店铺
     */
    public function do_register(array $register_info) {
        $register_info_validate = $register_info;
        $register_info_validate['user_role'] = $register_info['role'];
        unset($register_info_validate['role']);
        $this->_CI->load->library('regulation');
        foreach ($register_info_validate as $key => $value) {
            $this->_CI->regulation->validate($key, $value);
        }
        if (count($this->_CI->regulation->err_msg) > 0) {
            $this->err_msg = $this->_CI->regulation->err_msg;
            $this->_CI->regulation->err_msg = array();
            return array(
                'res' => FALSE,
                'msg' => $this->err_msg
            );
        }


        $this->_CI->load->model('user_model', 'user_m');
        $this->_CI->load->model('shop_model', 'shop_m');
        $this->_CI->load->model('view_model', 'view_m');
        $register_code_res = $this->verify_register_code($register_info['register_code']);
        $username_res = $this->verify_username($register_info['username']);
        if(!$register_code_res['res'] || !$username_res['res']) {
            return array(
                'res' => FALSE,
                'msg' => $this->err_msg
            );
        }
        
        //注册码
        $register_code = $register_info['register_code'];
        unset($register_info['register_code']);
        
        $this->_CI->db->trans_begin();
        //注册新用户信息
        $user_id = $this->_CI->user_m->register_new_user($register_info);
        //注册新的店铺信息
        $shop_info = $this->_CI->apply_m->shop_base_info($register_code);
        $new_shop_info['shopkeeper_id'] = $user_id;
        $new_shop_info['register_code'] = $shop_info['register_code'];
        $new_shop_info['shop_name']     = $shop_info['shop_name'];
        $new_shop_info['shop_tel']      = $shop_info['shopkeeper_tel'];
        $new_shop_info['shop_address']  = $shop_info['shop_address'];
        $shop_id = $this->_CI->shop_m->creat_shop($new_shop_info);
        $this->_CI->apply_m->shop_has_registered($register_code);
        $this->_CI->view_m->add_shop($shop_id);
        if($this->_CI->db->trans_status() ==  FALSE) {
            $this->_CI->db->trans_rollback();
            $this->err_msg[] = 'Register new user failed';
            return array(
                'res' => FALSE,
                'msg' => $this->err_msg
            );
        } else {
            $this->_CI->db->trans_commit();
            return array(
                'res' => TRUE,
                'data' => NULL
            );
        }
    
    }

    /**
     * 验证注册码是否可用
     */
    public function verify_register_code($register_code) {
        $this->_CI->load->library('regulation');
        $this->_CI->regulation->validate('register_code', $register_code);
        if (count($this->_CI->regulation->err_msg) > 0) {
            $this->err_msg = $this->_CI->regulation->err_msg;
            $this->_CI->regulation->err_msg = array();
            return array(
                'res' => FALSE,
                'msg' => $this->err_msg
            );
        }

        $this->_CI->load->model('apply_model', 'apply_m');
        if($this->_CI->apply_m->register_code_is_available($register_code)) {
            return array(
                'res' => TRUE,
                'data' => NULL
            );
        } else {
            $this->err_msg[] = 'Register code is not available';
            return array(
                'res' => FALSE,
                'msg' => $this->err_msg
            );
        }
    }
   
    /**
     * 验证用户名是否可用
     */
    public function verify_username($username) {
        $this->_CI->load->library('regulation');
        $this->_CI->regulation->validate('username', $username);
        if (count($this->_CI->regulation->err_msg) > 0) {
            $this->err_msg = $this->_CI->regulation->err_msg;
            $this->_CI->regulation->err_msg = array();
            return array(
                'res' => FALSE,
                'msg' => $this->err_msg
            );
        }

        $this->_CI->load->model('user_model', 'user_m');
        if($this->_CI->user_m->username_is_available($username)) {
            return array(
                'res' => TRUE,
                'data' => NULL
            );
        } else {
            $this->err_msg[] = 'Username is not available';
            return array(
                'res' => FALSE,
                'msg' => $this->err_msg
            );
        }
    }

    /**
     * 修改当前登录用户密码
     */
    public function change_password($old_password, $new_password) {
        $this->_CI->load->library('regulation');
        $this->_CI->regulation->validate('password', $new_password);
        if (count($this->_CI->regulation->err_msg) > 0) {
            $this->err_msg = $this->_CI->regulation->err_msg;
            $this->_CI->regulation->err_msg = array();
            return array(
                'res' => FALSE,
                'msg' => $this->err_msg
            );
        }

        $this->_CI->load->model('user_model', 'user_m');
        $user_id = $_SESSION['object_user_id'];
        $password = $this->_CI->user_m->get_password($user_id)['password'];
        if (md5($old_password) == $password) {
            $this->_CI->user_m->base_update(array('id' => $user_id), array('password' => md5($new_password)));
            return array(
                'res' => TRUE,
                'data' => NULL
            );
        } else {
            $this->err_msg[] = 'Change password failed';
            return array(
                'res' => FALSE,
                'msg' => $this->err_msg
            );
        }
    }
}
?>

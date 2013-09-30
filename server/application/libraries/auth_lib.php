<?php
/**
 * @Author: odirus@163.com
 */

class Auth_lib {
    
    private $_CI;

    public function __construct() {
        $this->_CI =& get_instance();
    }

    public function do_login($username, $password) {
        //@todo format
        $this->_CI->load->model('user_model', 'user_m');
        if($hash_password_db = $this->_CI->user_m->get_hash_password($username)) {
            if($hash_password_db['password'] == md5($password)) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
        
    }

    /**
     * 执行注册用户功能，同时注册卖家的店铺
     */
    public function do_register(array $register_info) {
        //@todo 检查各个字段的有效性
        $this->_CI->load->model('user_model', 'user_m');
        $this->_CI->load->model('shop_model', 'shop_m');
        if(!$this->verify_register_code($register_info['register_code']) ||
           !$this->verify_username($register_info['username'])) {
            return FALSE;
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
        $this->_CI->shop_m->creat_shop($new_shop_info);
        $this->_CI->apply_m->shop_has_registered($register_code);
        if($this->_CI->db->trans_status() ==  FALSE) {
            $this->_CI->db->trans_rollback();
            return FALSE;
        } else {
            $this->_CI->db->trans_commit();
            return TRUE;
        }
    
    }

    /**
     * 验证注册码是否可用
     */
    public function verify_register_code($register_code) {
        //@todo format
        $this->_CI->load->model('apply_model', 'apply_m');
        if($this->_CI->apply_m->register_code_is_available($register_code)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
   
    /**
     * 验证用户名是否可用
     */
    public function verify_username($username) {
        //@todo format
        $this->_CI->load->model('user_model', 'user_m');
        if($this->_CI->user_m->username_is_available($username)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
?>
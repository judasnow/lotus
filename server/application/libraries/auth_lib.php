<?php
/**
 * @Author: odirus@163.com
 */

class Auth_lib {
    
    private $_CI;

    public function __construct() {
        $this->_CI =& get_instance();
    }

    //Execute function below
    public function do_login($username, $password) {
        //@todo format
        $this->_CI->load->model('user_model', 'user_m');
        if($hash_password_db = $this->_CI->user_m->get_hash_password($username)) {
            if($hash_password_db['password'] == $password) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
        
    }

    public function do_register(array $register_info) {
        //@todo format
        //@todo Code can be use only once
        $this->_CI->load->model('user_model', 'user_m');
        $this->_CI->load->model('shop_model', 'shop_m');
        if(!$this->verify_register_code($register_info['register_code']) ||
           !$this->verify_username($register_info['username'])) {
            return FALSE;
        }
        
        $register_code = $register_info['register_code'];
        unset($register_info['register_code']);
        
        //Register new user
        $user_id = $this->_CI->user_m->register_new_user($register_info);
        //Register new shop
        $shop_info = $this->_CI->invite_m->get_shop_base_info($register_code);
        $new_shop_info['owner_id'] = $user_id;
        $new_shop_info['owner_name'] = $shop_info['shop_owner_name'];
        $new_shop_info['tel'] = $shop_info['shop_tel'];
        $new_shop_info['address'] = $shop_info['shop_address'];
        
        if($this->_CI->shop_m->creat_shop($new_shop_info)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    //Query function below
    public function verify_register_code($register_code) {
        //@todo format
        $this->_CI->load->model('invite_model', 'invite_m');
        if($this->_CI->invite_m->verify_register_code($register_code)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
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
<?php 
/**
 * @author: odirus@163.com
 */
class Admin_auth_lib {

    private $_CI;

    public function __construct() {
        $this->_CI =& get_instance();
    }

    public function do_login($username, $password) {
        $this->_CI->load->model('admin_model', 'admin_m');
        if ($hash_password = $this->_CI->admin_m->get_hash_password($username)) {
            if($hash_password['password'] == md5($password)) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    public function do_logout() {
        
    }
}

?>
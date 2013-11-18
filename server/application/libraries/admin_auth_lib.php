<?php 
/**
 * @author: odirus@163.com
 */
class Admin_auth_lib {

    private $_CI;

    public function __construct() {
        $this->_CI =& get_instance();
        $this->load->library('session');
    }

    public function do_login($username, $password) {
        $this->_CI->load->model('admin_model', 'admin_m');
        if ($hash_password = $this->_CI->admin_m->get_hash_password($username)) {
            if ($hash_password['password'] == md5($password)) {
                $admin_info = $this->_CI->admin_m->get_admin_id($username);
                if ($admin_info) {
                    $_SESSION['admin_id'] = $admin_info['id'];
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    public function do_logout() {
        unset($_SESSION['admin_id']);
        return TRUE;
    }

    public function admin_is_login() {
        if (!empty($_SESSION['admin_id'])) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}

?>

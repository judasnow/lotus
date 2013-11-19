<?php 
/**
 * @author: odirus@163.com
 */
class Admin_auth_lib {

    private $_CI;

    public function __construct() {
        $this->_CI =& get_instance();
        $this->_CI->load->library('session');
    }

    /**
     * (string, string) => boolean
     */
    public function do_login($username, $password) {

        $this->_CI->load->model('admin_model', 'admin_m');

        if ($hash_password = $this->_CI->admin_m->get_hash_password($username)) {

            if ($hash_password['password'] == md5($password)) {

                $admin_id = $this->_CI->admin_m->get_admin_id($username);

                if ($admin_id) {

                    $this->_CI->session->set_userdata( 'admin_id' , $admin_id );
                    $this->_CI->session->set_userdata(
                        'admin_info' , 
                        json_encode(['id'=>$admin_id , 'username'=>$username]) );

                    return TRUE;
                }

            }
        }

        return FALSE;
    }

    /**
     * (void) => void
     *
     * because function unset_userdata return void
     */
    public function do_logout() {
        $this->_CI->session->unset_userdata( 'admin_id' );
    }

    /**
     * (void) => boolean
     */
    public function admin_is_login() {
        if (!empty($this->_CI->session->userdata('admin_id'))) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}


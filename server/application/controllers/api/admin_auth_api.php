<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Admin auth api
 *
 * @author odirus@163.com
 */
require_once(APPPATH . '/libraries/REST_Controller.php');

class Admin_auth_api extends REST_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function do_login_post() {
        $this->load->library('admin_auth_lib');

        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('password', TRUE);
        if($this->admin_auth_lib->do_login($username, $password)) {
            $this->response("ok", 200);
        } else {
            $this->response("fail", 500);
        }
    }

    public function do_logout_post() {
        $this->load->library('admin_auth_lib');

        if ($this->admin_auth_lib->do_logout()) {
            $this->response("ok", 200);
        } else {
            $this->response("fail", 500);
        }
    }

    public function admin_is_login_get() {
        $this->load->library('admin_auth_lib');

        if ($this->admin_auth_lib->admin_is_login()) {
            $this->response("ok", 200);
        } else {
            $this->response("fail", 500);
        }
    }
}

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Admin auth api
 *
 * @author: odirus@163.com
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
            $this->response(
                array(
                    'result' => 'ok',
                    'msg'    => 'login success',
                    'data'   => NULL
                )
            );
        } else {
            $this->response(
                array(
                    'result' => 'fail',
                    'msg'    => 'login failed',
                    'data'   => NULL
                )
            );
        }
    }
}
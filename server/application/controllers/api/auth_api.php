<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Authentication
 *
 * @Author: odirus@163.com
 */
require_once(APPPATH . '/libraries/REST_Controller.php');

class Auth_api extends REST_Controller {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Login system
     *
     * @param string   username
     * @param string   password
     * 
     * @return restful
     */
    public function do_login_post() {
        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('password', TRUE);
        
        $this->load->library('auth_lib'); 
        if($this->auth_lib->do_login($username, $password)) {
            $this->response(
                array(
                    'result' => TRUE,
                    'msg' => 'Login success',
                    'data' => NULL
                )
            );
        } else {
            $this->response(
                array(
                    'result' => FALSE,
                    'msg' => 'Username or password wrong',
                    'data' => NULL
                ) 
            );
        }
        
    }
    
    /**
     * Verify register code whether is available
     *
     * @param string   register_code
     * 
     * @return restful
     */
    public function register_code_is_available_post() {
        $register_code = $this->input->post('register_code', TRUE);

        $this->load->library('auth_lib');
        if($this->auth_lib->verify_register_code($register_code)) {
            $this->response(
                array(
                    'result' => TRUE,
                    'msg' => 'Register code is available',
                    'data' => NULL
                )
            );
        } else {
            $this->response(
                array(
                    'result' => FALSE,
                    'msg' => 'Register code is not available',
                    'data' => NULL
                )
            );
        }
    }


    /**
     * Verify username whether is available
     *
     * @param string   username
     * 
     * @return restful
     */
    public function username_is_available_post() {
        $username = $this->input->post('username', TRUE);

        $this->load->library('auth_lib');
        if($this->auth_lib->verify_username($username)) {
            $this->response(
                array(
                    'result' => TRUE,
                    'msg' => 'Username is available',
                    'data' => NULL
                )
            );
        } else {
            $this->response(
                array(
                    'result' => FALSE,
                    'msg' => 'Username is not available',
                    'data' => NULL
                )
            );
        }
    }

    /**
     * Register new user
     *
     * @param string   username
     * @param string   password
     * @param string   role
     * @param string   regisger_code
     * 
     * @return restful
     */
    public function do_register_post() {
        $username      = $this->input->post('username', TRUE);
        $password      = $this->input->post('password', TRUE);
        $role          = $this->input->post('role', TRUE);
        $register_code = $this->input->post('register_code', TRUE);
 
        $this->load->library('auth_lib');
        if($this->auth_lib->do_register(array(
            'username'      => $username,
            'password'      => $password,
            'role'          => $role,
            'register_code' => $register_code
        ))) {
            $this->response(
                array(
                    'result' => TRUE,
                    'msg' => 'Register new user success',
                    'data' => NULL
                )
            );
        } else {
            $this->response(
                array(
                    'result' => FALSE,
                    'msg' => 'Register new user failed',
                    'data' => NULL
                )
            );
        }
    }
    
    public function do_apply_shop_post() {
        
    }
}
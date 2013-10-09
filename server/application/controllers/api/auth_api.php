<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Authentication
 *
 * @Author: odirus@163.com
 */
require_once(APPPATH . '/libraries/REST_Controller.php');
require_once(APPPATH . '/libraries/auth.php');

class Auth_api extends REST_Controller {
    
    public function __construct() {
        parent::__construct();
        session_start();
    }

    /**
     * 验证用户是否已经登录
     */
    public function is_login() {
        if(!auth::is_login()) {
            $this->response(
                array(
                    'result' => 'fail',
                    'msg'    => 'User did not login',
                    'data'   => NULL
                )
            );
        }
    }

    /**
     * 登录系统
     *
     * @param string   username
     * @param string   password
     * @param string   remember
     * 
     * @return restful
     */
    public function do_login_post() {
        //@toto remember ? on : off
        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('password', TRUE);
        
        $this->load->library('auth_lib'); 
        if($this->auth_lib->do_login($username, $password)) {
            $this->response(
                array(
                    'result' => 'ok',
                    'msg' => 'Login success',
                    'data' => NULL
                )
            );
        } else {
            $this->response(
                array(
                    'result' => 'fail',
                    'msg' => 'Username or password wrong',
                    'data' => NULL
                ) 
            );
        }
        
    }

    /**
     * 当前登录用户信息
     *
     * @param void
     */
    public function user_info_get() {
        $this->is_login();
        $this->load->library('auth_lib');
        if ($user_info = $this->auth_lib->user_info()) {
            $this->response(
                array(
                    'result' => 'ok',
                    'msg'    => 'Get user info success',
                    'data'   => $user_info
                )
            );
        } else {
            $this->response(
                array(
                    'result' => 'fail',
                    'msg'    => 'Get user info failed',
                    'data'   => NULL
                )
            );
        }
    }
    
    /**
     * 登出系统
     *
     * @param void
     */
    public function do_logout_post() {
        $this->load->library('auth_lib');
        if($this->auth_lib->do_logout()) {
            $this->response(
                array(
                    'result' => 'ok',
                    'msg' => 'Logout success',
                    'data' => NULL
                )
            );
        } else {
            $this->response(
                array(
                    'result' => 'fail',
                    'msg' => 'Logout failed',
                    'data' => NULL
                ) 
            );
        }
    }

    /**
     * 注册码是否可用，是否存在，是否被使用，格式是否正确
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
                    'result' => 'ok',
                    'msg' => 'Register code is available',
                    'data' => NULL
                )
            );
        } else {
            $this->response(
                array(
                    'result' => 'fail',
                    'msg' => 'Register code is not available',
                    'data' => NULL
                )
            );
        }
    }

    /**
     * 验证用户名是否可用，是否已经被注册，格式是否正确
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
                    'result' => 'ok',
                    'msg' => 'Username is available',
                    'data' => NULL
                )
            );
        } else {
            $this->response(
                array(
                    'result' => 'fail',
                    'msg' => 'Username is not available',
                    'data' => NULL
                )
            );
        }
    }

    /**
     * 注册新用户接口
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
        $role          = $this->input->post('user_role', TRUE);
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
                    'result' => 'ok',
                    'msg' => 'Register new user success',
                    'data' => NULL
                )
            );
        } else {
            $this->response(
                array(
                    'result' => 'fail',
                    'msg' => 'Register new user failed',
                    'data' => NULL
                )
            );
        }
    }
<<<<<<< HEAD
}
=======

    /**
     * 修改用户密码
     *
     * @param string   old_password
     * @param string   new_password
     *
     * @return restful
     */
    public function change_password_post() {
        //验证用户是否登录
        $this->is_login();
        $old_password = $this->input->post('old_password', TRUE);
        $new_password = $this->input->post('new_password', TRUE);
        $this->load->library('auth_lib');
        if ($this->auth_lib->change_password($old_password, $new_password)) {
            $this->response(
                array(
                    'result' => 'ok',
                    'msg'    => 'Change password success',
                    'data'   => NULL
                )
            );
        } else {
            $this->response(
                array(
                    'result' => 'fail',
                    'msg'    => 'Change password failed',
                    'data'   => NULL
                )
            );
        }
    }
}
>>>>>>> 59ba746da1b96db8fa4533ddc50797b408067641

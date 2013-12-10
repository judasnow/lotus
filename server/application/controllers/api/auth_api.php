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
        $this->load->library('auth_lib');
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
        //@TODO remember ? on : off
        $this->load->library('auth_lib');

        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $remember = $this->input->post('remember');

        //[boolean, [string]]
        $res = $this->auth_lib->do_login($username, $password, $remember);

        if($res['res']) {
            //登录成功返回 session_id 
            $this->response([
                'session_id' => session_id()
            ], 200);
        } else {
            $msg = 'Username or password wrong';

            if (count($res['msg']) > 0) {
                $msg = implode('; ', $res['msg']);
            }

            $this->response($msg, 400);
        }
    }

    /**
     * 卖家是否已经登录
     *
     * @param void
     */
    public function user_is_login_get() {
        if ($this->auth_lib->user_is_login()) {
            $this->response('ok', 200);
        } else {
            $this->response('fail', 400);
        }
    }

    /**
     * 当前登录用户信息
     *
     * @param void
     */
    public function user_info_get() {
        if (! $this->auth_lib->user_is_login()) {
            $this->response('User did not login', 500);
        }
        $user_info = $this->auth_lib->user_info();
        if ($user_info) {
            $this->response($user_info, 200);
        } else {
            $this->response('Get user info failed', 400);
        }
    }
    
    /**
     * 登出系统
     *
     * @param void
     */
    public function do_logout_post() {
        if($this->auth_lib->do_logout()) {
            $this->response('ok', 200);
        } else {
            $this->response('fail', 400);
        }
    }

    /**
     * 注册码是否可用，是否存在，是否被使用，格式是否正确
     *
     * @param string register_code
     * 
     * @return restful
     */
    public function register_code_is_available_post() {
        $register_code = $this->input->post('register_code', TRUE);

        $res = $this->auth_lib->verify_register_code($register_code);
        if($res['res']) {
            $this->response('ok', 200);
        } else {
            $msg = 'Register code is not available';
            if (count($res['msg']) > 0) {
                $msg = implode('; ', $res['msg']);
            }
            $this->response($msg, 400);
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
        $res = $this->auth_lib->verify_username($username);
        if($res['res']) {
            $this->response('ok', 200);
        } else {
            $msg = 'Usernameis not available';
            if (count($res['msg']) > 0) {
                $msg = implode('; ', $res['msg']);
            }
            $this->response($msg, 500);
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
        $username      = $this->input->post('username');
        $password      = $this->input->post('password');
        $role          = $this->input->post('user_role');
        $register_code = $this->input->post('register_code');
 
        $res = $this->auth_lib->do_register(array(
            'username'      => $username,
            'password'      => $password,
            'role'          => $role,
            'register_code' => $register_code
        ));

        if($res['res']) {
            $this->response("ok", 200);
        } else {
            $msg = 'Register new user failed';
            if (count($res['msg']) > 0) {
                $msg = implode('; ', $res['msg']);
            }
            $this->response($msg, 500);
        }
    }

    /**
     * 修改用户密码
     *
     * @param string   old_password
     * @param string   new_password
     *
     * @return restful
     */
    public function change_password_post() {
        if (! $this->auth_lib->user_is_login()) {
            $this->response('User did not login', 500);
        }
        //验证用户是否登录
        $old_password = $this->input->post('old_password');
        $new_password = $this->input->post('new_password');
        $res = $this->auth_lib->change_password($old_password, $new_password);
        if ($res['res']) {
            $this->response('ok', 200);
        } else {
            $msg = 'Change password failed';
            if (count($res['msg']) > 0) {
                $msg = implode('; ', $res['msg']);
            }
            $this->response($msg, 500);
        }
    }
}

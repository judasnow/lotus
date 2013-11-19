<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * admin page for lotus
 *
 * @author <judasnow@gmail.com>
 */
class Main extends CI_Controller {

    public function __construct() {
    //{{{
        parent::__construct();

        $this->load->model('apply_model', 'apply_m');
        $this->load->library('admin_auth_lib');
        $this->load->library('session');
    }//}}}

    //default page
    public function index() {
    //{{{
        if( ! $this->admin_auth_lib->admin_is_login() ) {
            $this->auth();
        } else {
            $this->applied();
        }
    }//}}}

    public function auth() {
    //{{{
        $auth_error_info = $this->session->flashdata( 'auth_error_info' );

        if( ! $this->admin_auth_lib->admin_is_login() ) {
            $this->load->view( 'admin/auth' , ['auth_error_info' => $auth_error_info] );
        } else {
            $this->applied();
        }
    }//}}}

    public function do_login() {
    //{{{
        $username = $this->input->post( 'username' , '' );
        $password = $this->input->post( 'password' , '' );

        if( $this->admin_auth_lib->do_login( $username , $password ) ) {
            header( 'Location: /admin/main/' );
        } else {
            $this->session->set_flashdata( 'auth_error_info' , '管理员用户名或密码错误' );
            header( 'Location: /admin/main/auth/' );
        }
    }//}}}

    public function do_logout() {
        $this->admin_auth_lib->do_logout();
        header( 'Location: /admin/main/auth/' );
    }

    public function applied() {
    //{{{
        $applies_info = $this->apply_m->applied();
        $admin_info = $this->session->userdata( 'admin_info' );

        //这里就提现了类型系统的重要性
        if( $admin_info === false ) {
            $admin_info = ['username'=>''];
        }

        $this->load->view( 'admin/home' , ['applies_info'=>$applies_info , 'admin_info'=>$admin_info] );
    }//}}}

    public function applying() {
    //{{{
        $this->load->view( 'admin/home' );
        $applies_info = $this->apply_m->applying();
    }//}}}
}


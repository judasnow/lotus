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
        $this->load->library('apply_lib');
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
            exit;
        } else {
            $this->session->set_flashdata( 'auth_error_info' , '管理员用户名或密码错误' );
            header( 'Location: /admin/main/auth/' );
            exit;
        }
    }//}}}

    public function do_logout() {
        $this->admin_auth_lib->do_logout();
        header( 'Location: /admin/main/auth/' );
        exit;
    }

    public function applied( $page_no = 0 ) {
    //{{{
        $applies_info = $this->apply_m->applied();
        $admin_info = $this->session->userdata( 'admin_info' );

        //@XXX 这里就提现了类型系统的重要性
        //应该将其实现为一个 class type 以封装其有效性验证等操作
        if( $admin_info === false ) {
            $admin_info = ['username'=>'','id'=>''];
        }

        $this->load->view(
            'admin/home' ,
            [
                'applies_info' => $applies_info,
                'admin_info' => json_decode( $admin_info , TRUE ),
                'page_name' => 'applied'
            ]
        );
    }//}}}

    public function applying( $page_no = 0 ) {
    //{{{
        $applies_info = $this->apply_m->applying();
        $admin_info = $this->session->userdata( 'admin_info' );

        if( $admin_info === false ) {
            $admin_info = ['username'=>'','id'=>''];
        }

        $this->load->view(
            'admin/home' ,
            [
                'applies_info' => $applies_info,
                'admin_info' => json_decode( $admin_info , TRUE ),
                'page_name' => 'applying'
            ]
        );
    }//}}}

    public function failed_message() {
        $id = $this->input->post( 'id' , -1 );
        $msg = $this->input->post( 'msg' , '' );

        if( $id !== -1 ) {
            $result = $this->apply_lib->apply_verifying_failed( $id , $msg );
            if( $result['res'] === TRUE ) {
                echo 'ok';
                return;
            }
        }

        http_response_code( 500 );
    }

    public function pass( $id = -1 ) {
    //{{{
        if( $id !== -1 ) {
            $this->apply_lib->apply_verifying_pass( $id );
            header( 'Location: /admin/main/applying/' );
            exit;
        } else {
            die( '输入的 apply_id 非法' );
        }
    }//}}}
}


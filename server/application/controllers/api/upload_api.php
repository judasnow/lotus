<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Apply api, Apply for shop
 *
 * @Author: odirus@163.com
 */
require_once(APPPATH . '/libraries/REST_Controller.php');
require_once(APPPATH . '/libraries/auth.php');

class Upload_api extends REST_Controller {
    
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


    public function do_upload_image_post() {
        $this->is_login();

        $type = $this->input->post('image_type', TRUE);//可选值shop,product
        
        $this->load->library('upload_lib');
        if ($image_name = $this->upload_lib->do_upload_image($type)) {
            $this->response(
                array(
                    'result' => 'ok',
                    'msg'    => 'Upload image success',
                    'data'   => array(
                        'image_name' => $image_name
                    )
                )
            );
        } else {
            $this->response(
                array(
                    'result' => 'fail',
                    'msg'    => 'Upload image failed',
                    'data'   => NULl
                )
            );
        }
    }

}
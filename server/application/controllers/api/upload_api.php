<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Apply api, Apply for shop
 *
 * @Author: odirus@163.com
 */
require_once(APPPATH . '/libraries/REST_Controller.php');

class Upload_api extends REST_Controller {
    
    public function __construct() {
        parent::__construct();
        session_start();
    }

    /**
     * 验证用户是否已经登录
     */
    public function is_login() {
        if (!isset($_SESSION['object_user_id'])) {
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

        $image_type = $this->input->post('image_type', TRUE);//可选值shop,product
        
        $this->load->library('upload_lib');
        if ($res = $this->upload_lib->do_upload_image($image_type)) {
            $this->response(
                array(
                    'result' => 'ok',
                    'msg'    => 'Upload image success',
                    'data'   => array(
                        'image_name' => $res
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
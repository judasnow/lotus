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

        $this->load->libraries('auth_lib');
        $this->load->library('upload_lib');
    }

    public function do_upload_image_post() {
        if (!$this->auth_lib->user_is_login()) {
            $this->response('User did not login', 500);
        } else {

            //image_type : [show|product]
            $image_type = $this->input->post('image_type');
            $res = $this->upload_lib->do_upload_image($image_type);

            if ($res['res']) {
                $this->response(['image_name' => $res['data']], 200);
            } else {
                $msg = 'Upload image failed';
                if (count($res['msg']) > 0) {
                    $msg = implode('; ', $res['msg']);
                }
                $this->response($msg, 500);
            }
        }
    }

}

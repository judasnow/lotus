<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Authentication
 *
 * @Author: odirus@163.com
 */
require_once(APPPATH . '/libraries/REST_Controller.php');

class Shop_api extends REST_Controller {

    public function __construct() {
        parent::__construct();
        session_start();
    }

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

    /**
     * 根据登录用户获取店铺基本信息
     */
    public function base_info_get() {
        $this->is_login();
        $this->load->library('shop_lib');
        if ($shop_base_info = $this->shop_lib->shop_base_info()) {
            $this->response(
                array(
                    'result' => 'ok',
                    'msg'    => 'Get shop base info success',
                    'data'   => $shop_base_info
                )
            );
        } else {
            $this->response(
                array(
                    'result' => 'fail',
                    'msg'    => 'Get shop base info failed',
                    'data'   => NULL
                )
            );
        }
    }
   
    /**
     * 修改店铺的基本信息
     */
    public function update_base_info_post() {
        $this->is_login();
        $base_info['shop_tel'] = $this->input->post('shop_tel', TRUE);
        $base_info['shop_address'] = $this->input->post('shop_address', TRUE);
        $base_info['shop_image_name'] = $this->input->post('shop_image_name', TRUE);
        $this->load->library('shop_lib');
        if($this->shop_lib->update_shop_base_info($base_info)) {
            $this->response(
                array(
                    'result' => 'ok',
                    'msg'    => 'Update shop base info success',
                    'data'   => NULL
                )
            );
        } else {
            $this->response(
                array(
                    'result' => 'fail',
                    'msg'    => 'Update shop base info failed',
                    'data'   => NULL
                )
            );
        }
    }

    /**
     * 是否显示联系方式
     */
    public function show_shop_tel_get() {
        $this->is_login();
        $this->load->library('shop_lib');
        if ($this->shop_lib->show_shop_tel()) {
            $this->response(
                array(
                    'result' => 'ok',
                    'msg'    => 'Show shop tel',
                    'data'   => NULL
                )
            );
        } else {
            $this->response(
                array(
                    'result' => 'fail',
                    'msg'    => 'Do not show shop tel',
                    'data'   => NULL
                )
            );
        }
    }
     
    /**
     * 修改隐私设置，是否显示联系方式
     */
    public function update_show_shop_tel_post() {
        $this->is_login();
        $this->load->library('shop_lib');
        if ($this->shop_lib->update_show_shop_tel()) {
            $this->response(
                array(
                    'result' => 'ok',
                    'msg'    => 'Update success',
                    'data'   => NULL
                )
            );
        } else {
            $this->response(
                array(
                    'result' => 'fail',
                    'msg'    => 'Update failed',
                    'data'   => NULL
                )
            );
        }
    }
}
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
    }

    public function is_login() {
        $this->load->library('auth_lib');
        $this->auth_lib->user_is_login();
        if (!isset($_SESSION['object_user_id'])) {
            $this->response("User did not login", 500);
            /**
            $this->response(
                array(
                    'result' => 'fail',
                    'msg'    => 'User did not login',
                    'data'   => NULL
                )
            );
            */
        }
    }

    /**
     * 根据登录用户获取店铺基本信息
     */
    public function base_info_get() {
        $this->is_login();
        $this->load->library('shop_lib');
        if ($shop_base_info = $this->shop_lib->shop_base_info()) {
            $this->response($shop_base_info, 200);
            /**
            $this->response(
                array(
                    'result' => 'ok',
                    'msg'    => 'Get shop base info success',
                    'data'   => $shop_base_info
                )
            );
            */
        } else {
            $this->response("Get shop base info failed", 500);
            /**
            $this->response(
                array(
                    'result' => 'fail',
                    'msg'    => 'Get shop base info failed',
                    'data'   => NULL
                )
            );
            */
        }
    }
   
    /**
     * 卖家修改店铺的基本信息
     */
    public function update_base_info_post() {
        $this->is_login();
        $base_info['shop_tel'] = $this->input->post('shop_tel', TRUE);
        $base_info['shop_address'] = $this->input->post('shop_address', TRUE);
        $base_info['shop_image_name'] = $this->input->post('shop_image_name', TRUE);
        $this->load->library('shop_lib');
        $res = $this->shop_lib->update_shop_base_info($base_info);
        if($res['res']) {
            $this->response("ok", 200);
            /**
            $this->response(
                array(
                    'result' => 'ok',
                    'msg'    => 'Update shop base info success',
                    'data'   => NULL
                )
            );
            */
        } else {
            $msg = 'Update shop base info failed';
            if (count($res['msg']) > 0) {
                $msg = implode(': ', $res['msg']);
            }
            $this->response($msg, 500);
            /**
            $this->response(
                array(
                    'result' => 'fail',
                    'msg'    => $msg,
                    'data'   => NULL
                )
            );
            */
        }
    }

    /**
     * 游客获取店铺信息
     */
    public function info_get() {
        $shop_id = (int) ($this->input->get('shop_id', TRUE));
        $this->load->library('shop_lib');
        $res = $this->shop_lib->shop_info($shop_id);
        if ($res['res']) {
            $this->response($res['data'], 200);
            /**
            $this->response(
                array(
                    'result' => 'ok',
                    'msg'    => 'Get shop info success',
                    'data'   => $res['data']
                )
            );
            */
        } else {
            $msg = 'Get shop info failed';
            if (count($res['msg']) > 0) {
                $msg = implode('; ', $res['msg']);
            }
            $this->response($msg, 500);
            /**
            $this->response(
                array(
                    'result' => 'fail',
                    'msg'    => $msg,
                    'data'   => NULL
                )
            );
            */
        }
    }

    /**
     * 游客获取店铺中的商品数量
     */
    public function product_count_get() {
        $shop_id = (int) ($this->input->get('shop_id', TRUE));
        $this->load->library('shop_lib');
        $res = $this->shop_lib->product_count($shop_id);
        if ($res['res']) {
            $this->response(array('shop_product_count' => $res['data']), 200);
            /**
            $this->response(
                array(
                    'result' => 'ok',
                    'msg'    => 'Get products count success',
                    'data'   => array(
                        'shop_product_count' => $res['data']
                    )
                )
            );
            */
        } else {
            $msg = 'Get product count failed';
            if (count($res['msg']) > 0) {
                $msg = implode('; ', $res['msg']);
            }
            $this->response($msg, 500);
            /**
            $this->response(
                array(
                    'result' => 'fail',
                    'msg'    => $msg,
                    'data'   => NULL
                )
            );
            */
        }
    }

    /**
     * 游客获取店铺中的商品分页数量
     */
    public function product_page_count_get() {
        $shop_id = (int) ($this->input->get('shop_id', TRUE));
        $this->load->library('shop_lib');
        $res = $this->shop_lib->product_page_count($shop_id);
        if ($res['res']) {
            $this->response(array('product_page_count' => $res['data']), 200);
            /**
            $this->response(
                array(
                    'result' => 'ok',
                    'msg' => 'Get product page count success',
                    'data' => array(
                        'product_page_count' => $res['data']
                    )
                )
            );
            */
        } else {
            $msg = 'Get product page count failed';
            if (count($res['msg'])> 0) {
                $msg = implode(';', $res['msg']);
            }
            $this->response("fail", 500);
            /**
            $this->response(
                array(
                    'res' => 'fail',
                    'msg' => $msg,
                    'data' => NULL
                )
            );
            */
        }
    } 

    //游客指定店铺的基本信息
    public function products_get() {
        $shop_id = (int) ($this->input->get('shop_id', TRUE));
        $page    = (int) ($this->input->get('page', TRUE));
        $flag    = $this->input->get('flag', TRUE);//可选值 time, price, disount
        $this->load->library('shop_lib');
        $res = $this->shop_lib->products($shop_id, $page, $flag);
        if ($res['res']) {
            $this->response($res['data'], 200);
            /**
            $this->response(
                array(
                    'result' => 'ok',
                    'msg'    => 'Get products info success',
                    'data'   => $res['data']
                )
            );
            */
        } else {
            $msg = 'Get products info failed';
            if (count($res['msg']) > 0) {
                $msg = implode('; ', $res['msg']);
            }
            $this->response($msg, 500);
            /**
            $this->response(
                array(
                    'result' => 'fail',
                    'msg'    => $msg,
                    'data'   => NULL
                )
            );
            */
        }
        

    }

    /**
     * 是否显示联系方式
     */
    public function show_shop_tel_get() {
        $this->is_login();
        $this->load->library('shop_lib');
        if ($this->shop_lib->show_shop_tel()) {
            $this->response("ok", 200);
            /**
            $this->response(
                array(
                    'result' => 'ok',
                    'msg'    => 'Show shop tel',
                    'data'   => NULL
                )
            );
            */
        } else {
            $this->response("fail", 500);
            /**
            $this->response(
                array(
                    'result' => 'fail',
                    'msg'    => 'Do not show shop tel',
                    'data'   => NULL
                )
            );
            */
        }
    }
     
    /**
     * 修改隐私设置，是否显示联系方式
     */
    public function update_show_shop_tel_post() {
        $this->is_login();
        $this->load->library('shop_lib');
        if ($this->shop_lib->update_show_shop_tel()) {
            $this->response("ok", 200);
            /**
            $this->response(
                array(
                    'result' => 'ok',
                    'msg'    => 'Update success',
                    'data'   => NULL
                )
            );
            */
        } else {
            $this->response("fail", 500);
            /**
            $this->response(
                array(
                    'result' => 'fail',
                    'msg'    => 'Update failed',
                    'data'   => NULL
                )
            );
            */
        }
    }
}

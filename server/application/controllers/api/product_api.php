<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Authentication
 *
 * @Author: odirus@163.com
 */
require_once(APPPATH . '/libraries/REST_Controller.php');

class Product_api extends REST_Controller {
 
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
     * 获取产品信息
     */
    public function product_get() {
        $product_id = $this->input->get('product_id', TRUE);
        $this->load->library('product_lib');
        $res = $this->product_lib->product($product_id);
        if($res['res']) {
            $this->response(
                array(
                    'result' => 'ok',
                    'msg'    => 'Get product info success',
                    'data'   => $res['data']
                )
            );
        } else {
            $msg = 'Get product info failed';
            if (count($res['msg'])) {
                $msg = implode('; ', $res['msg']);
            }
            $this->response(
                array(
                    'result' => 'fail',
                    'msg'    => $msg,
                    'data'   => NULL
                )
            );
        }
    }

    /**
     * 发布新产品
     */
    public function new_product_post() {
        $this->is_login();
        $product_info = array(
            'product_class_a' => $this->input->post('product_class_a', TRUE),
            'product_class_b' => $this->input->post('product_class_b', TRUE),
            'product_name'    => $this->input->post('product_name', TRUE),
            'product_describe' => $this->input->post('product_describe', TRUE),
            'product_image'   => $this->input->post('product_image', TRUE),
            'product_detail_image' => $this->input->post('product_detail_image', TRUE),
            'product_original_price'   => $this->input->post('product_original_price', TRUE),
            'product_discount' => $this->input->post('product_discount', TRUE),
            'product_quantity' => $this->input->post('product_quantity', TRUE),
        );

        $this->load->library('product_lib');
        $res = $this->product_lib->new_product($product_info);
        if ($res['res']) {
            $this->response(
                array(
                    'result' => 'ok',
                    'msg'    => 'New product releases success',
                    'data'   => NULL
                )
            );
        } else {
            $msg = 'New product releases failed';
            if (count($res['msg']) > 0) {
                $msg = implode('; ', $res['msg']);
            }
            $this->response(
                array(
                    'result' => 'fail',
                    'msg'    => $msg,
                    'data'   => NULL
                )
            );
        }
    }

    /**
     * 修改产品信息
     */
    public function product_update_post() {
        //检查用户是否登录
        $this->is_login();
        $product_info = array(
            'product_id'      => $this->input->post('product_id', TRUE),
            'product_class_a' => $this->input->post('product_class_a', TRUE),
            'product_class_b' => $this->input->post('product_class_b', TRUE),
            'product_name'    => $this->input->post('product_name', TRUE),
            'product_describe' => $this->input->post('product_describe', TRUE),
            'product_image'   => $this->input->post('product_image', TRUE),
            'product_detail_image' => $this->input->post('product_detail_image', TRUE),
            'product_original_price'   => $this->input->post('product_original_price', TRUE),
            'product_discount' => $this->input->post('product_discount', TRUE),
            'product_quantity' => $this->input->post('product_quantity', TRUE),
        );

        //检查用户是否具有操作权限
        $this->load->library('access_lib');
        $this->access_lib->validate_privilege('product', $product_info['product_id']);
        $this->load->library('product_lib');
        $res = $this->product_lib->product_update($product_info);
        if (!empty($this->access_lib->error)) {
            $this->response(
                array(
                    'result' => 'fail',
                    'msg'    => $this->access_lib->error,
                    'data'   => NULL
                )
            );
        } elseif ($res['res']) {
            $this->response(
                array(
                    'result' => 'ok',
                    'msg'    => 'Update product info success',
                    'data'   => NULL
                )
            );
        } else {
            $msg = 'Update product info failed';
            if (count($res['msg']) > 0) {
                $msg = implode('; ', $res['msg']);
            }
            $this->response(
                array(
                    'result' => 'fail',
                    'msg'    => $msg,
                    'data'   => NULL
                )
            );
        }
    }
   
}

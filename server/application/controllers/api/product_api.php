<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Authentication
 *
 * @Author: odirus@163.com
 */
require_once(APPPATH . '/libraries/REST_Controller.php');
require_once(APPPATH . '/libraries/auth.php');

class Product_api extends REST_Controller {
 
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
     * 获取产品信息
     */
    public function product_get() {
        
    }

    /**
     * 发布新产品
     */
    public function new_product_post() {
        $product_info = array(
             'product_class_a' => $this->input->post('product_class_a', TRUE);
             'product_class_b' => $this->input->post('product_class_b', TRUE);
             'product_name'    => $this->input->post('product_name', TRUE);
             'product_describe' => $this->input->post('product_describe', TRUE);
             'product_image'   => $this->input->post('product_image', TRUE);
             'product_detail_image' => $this->input->post('product_detail_image', TRUE);
             'product_original_price'   => $this->input->post('product_original_price', TRUE);
             'product_discount' => $this->input->post('product_discount', TRUE);
             'product_quantity' => $this->input->post('product_quantity', TRUE);
        );
        $this->load->library('product_lib');
        if ($this->product_lib->new_product($product_info)) {
            $this->response(
                array(
                    'result' => 'ok',
                    'msg'    => 'New product releases success',
                    'data'   => NULL
                )
            );
        } else {
            $this->response(
                array(
                    'result' => 'fail',
                    'msg'    => 'New product releases failed',
                    'data'   => NULL
                )
            );
        }
    }
   
}
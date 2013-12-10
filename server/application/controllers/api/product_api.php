<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Authentication
 *
 * @Author odirus@163.com
 */
require_once(APPPATH . '/libraries/REST_Controller.php');
require_once('response_api.php');

class Product_api extends REST_Controller {
 
    public $response_api;

    public function __construct() {
        parent::__construct();
        $this->load->library('auth_lib');
        $this->load->library('product_lib');
        $this->load->library('access_lib');
        $this->response_api = new Response_api;
    }

    /**
     * 获取产品信息
     */
    public function product_get() {
        $product_id = $this->input->get('product_id', TRUE);
        $res = $this->product_lib->product($product_id);
        $this->response_api->api_response($this->product_lib->product($product_id));
    }

    /**
     * 发布新产品
     */
    public function new_product_post() {
        if (! $this->auth_lib->user_is_login()) {
            $this->response('User did not login', 401);
        } else {
            $product_info = [
                'product_class_a' => $this->post('class_a'),
                'product_class_b' => $this->post('class_b'),
                'product_name'    => $this->post('name'),
                'product_describe' => $this->post('describe'),
                'product_image'   => $this->post('image'),
                'product_detail_image' => $this->post('detail_image'),
                'product_original_price'   => $this->post('original_price'),
                'product_discount' => $this->post('discount'),
                'product_quantity' => $this->post('quantity'),
            ];
            $this->response_api->api_response($this->product_lib->new_product($product_info));
        }
    }

    /**
     * 修改产品信息
     */
    public function product_update_post() {
        if (! $this->auth_lib->user_is_login()) {
            $this->response('User did not login', 401);
        } else {
            $product_info = array(
                'product_id'      => $this->input->post('id', TRUE),
                'product_class_a' => $this->input->post('class_a', TRUE),
                'product_class_b' => $this->input->post('class_b', TRUE),
                'product_name'    => $this->input->post('name', TRUE),
                'product_describe' => $this->input->post('describe', TRUE),
                'product_image'   => $this->input->post('image', TRUE),
                'product_detail_image' => $this->input->post('detail_image', TRUE),
                'product_original_price'   => $this->input->post('original_price', TRUE),
                'product_discount' => $this->input->post('discount', TRUE),
                'product_quantity' => $this->input->post('quantity', TRUE),
            );

            //检查用户是否具有操作权限
            $this->access_lib->validate_privilege('product', $product_info['product_id']);
            if (!empty($this->access_lib->error)) {
                $this->response("User has no privilege", 401);
            }
            $this->response_api->api_response($this->product_lib->product_update($product_info));
        }
    }
}

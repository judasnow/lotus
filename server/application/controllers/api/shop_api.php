<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Authentication
 *
 * @Author: odirus@163.com
 */
require_once(APPPATH . '/libraries/REST_Controller.php');
require_once('response_api.php');

class Shop_api extends REST_Controller {

    public $response_api;

    public function __construct() {
        parent::__construct();
        $this->load->library('auth_lib');
        $this->load->library('shop_lib');
        $this->response_api = new Response_api;
    }

    /**
     * 店铺信息（根据商品编号访问指定信息）
     */
    public function info_get() {
        $shop_id = (int) ($this->input->get('shop_id', TRUE));
        $this->response_api->api_response($this->shop_lib->shop_info($shop_id));
    }

    /**
     * 店铺信息（根据登录用户返回所属店铺信息）
     */
    public function owner_shop_info_get() {
        if (! $this->auth_lib->user_is_login()) {
            $this->response('User did not login', 401);
        }
        $this->response_api->api_response($this->shop_lib->owner_shop_info());
    }

    /**
     * 登录用户修改店铺信息
     */
    public function update_shop_info_post() {
        if (! $this->auth_lib->user_is_login()) {
            $this->response('User did not login', 401);
        }
        $base_info['shop_tel'] = $this->input->post('shop_tel', TRUE);
        $base_info['shop_address'] = $this->input->post('shop_address', TRUE);
        $base_info['shop_image_name'] = $this->input->post('shop_image_name', TRUE);
        $this->response_api->api_response($this->shop_lib->update_shop_info($base_info));
    }


    /**
     * 店铺中的商品数量
     */
    public function product_count_get() {
        $shop_id = (int) ($this->input->get('shop_id', TRUE));
        $this->response_api->api_response($this->shop_lib->product_count($shop_id));
    }

    /**
     * 店铺中的商品分页数
     */
    public function product_page_count_get() {
        $shop_id = (int) ($this->input->get('shop_id', TRUE));
        $this->response_api->api_response($this->shop_lib->product_page_count($shop_id));
    } 
    
    /**
     * 店铺中的商品基本信息
     */
    public function products_get() {
        $shop_id = (int) ($this->input->get('shop_id', TRUE));
        $page    = (int) ($this->input->get('page', TRUE));
        $flag    = $this->input->get('flag', TRUE);//可选值 time, price, disount
        $this->response_api->api_response($this->shop_lib->products($shop_id, $page, $flag));
    }

    /**
     * 是否显示联系方式
     */
    public function show_shop_tel_get() {
         if (! $this->auth_lib->user_is_login()) {
            $this->response('User did not login', 401);
         }
         $this->response_api->api_response($this->shop_lib->show_shop_tel());
    }
     
    /**
     * 修改隐私设置，是否显示联系方式
     */
    public function update_show_shop_tel_post() {
        if (! $this->auth_lib->user_is_login()) {
            $this->response('User did not login', 401);
        }
        $this->response_api->api_response($this->shop_lib->update_show_shop_tel());
    }
}

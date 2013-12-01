<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Apply api, Apply for shop
 *
 * @Author: odirus@163.com
 */
require_once(APPPATH . '/libraries/REST_Controller.php');
require_once('response_api.php');

class Home_api extends REST_Controller {

    public $response_api;

    public function __construct() {
        parent::__construct();
        $this->load->library('home_lib');
        $this->response_api = new Response_api;
    }
    
    /**
     * 根据搜索字符串获取返回内容
     */
    public function search_get() {
        $search_string = $this->input->get('search_string', TRUE);
        $page_num = $this->input->get('page_num', TRUE);
        $this->response_api->api_response($this->home_lib->search($search_string, $page_num));
    }

    /**
     * 根据搜索字符串返回相应的页数
     */
    public function search_result_page_get() {
        $search_string = $this->input->get('search_string', TRUE);
        $this->response_api->api_response($this->home_lib->search_result_page($search_string));
    }

    /**
     * 获取 class_a 目录
     */
    public function class_a_get() {
        $this->response_api->api_response($this->home_lib->class_a());
    }

    /**
     * 根据给定的 class_a id 获取相应的 class_b 目录
     */
    public function class_b_get() {
        $this->load->library('home_lib');
        $class_a_id = $this->input->get('class_a_id', TRUE);
        $this->response_api->api_response($this->home_lib->class_b($class_a_id));
    }

    /*
     * 热门商品推荐
     */
    public function popular_products_get() {
        $this->load->library('home_lib');
        $this->response_api->api_response($this->home_lib->popular_product());
    }

    /**
     * 热门店铺推荐
     */
    public function popular_shop_get() {
        $this->load->library('home_lib');
        $res = $this->home_lib->popular_shop();
        $this->response_api->api_response($this->home_lib->popular_shop());
    }
    
    /**
     * 根据分类获取商品信息
     */
    public function category_products_get() {
        $class_a = $this->input->get('class_a', TRUE);
        $class_b = $this->input->get('class_b', TRUE);
        $page    = (int) ($this->input->get('page', TRUE));
        $this->response_api->api_response($this->home_lib->category_products($class_a, $class_b, $page));
    }

    /**
     * 根据分类获取商品分页数
     */
    public function category_products_page_get() {
        $class_a = $this->input->get('class_a', TRUE);
        $class_b = $this->input->get('class_b', TRUE);
        $this->response_api->api_response($this->home_lib->category_products_page($class_a, $class_b));
    }
  
}

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Apply api, Apply for shop
 *
 * @Author: odirus@163.com
 */
require_once(APPPATH . '/libraries/REST_Controller.php');

class Home_api extends REST_Controller {
  
    public function __construct() {
        parent::__construct();
        session_start();
    }

    /**
     * 热门商品推荐
     */
    public function popular_product_get() {
        $this->load->library('home_lib');
        if ($res = $this->home_lib->popular_product()) {
            $this->response(
                array(
                    'result' => 'ok',
                    'msg'    => 'Get popular product success',
                    'data'   => $res
                )
            );
        }
    }

    /**
     * 热门店铺推荐
     */
    public function popular_shop_get() {
        $this->load->library('home_lib');
        if ($res = $this->home_lib->popular_shop()) {
            $this->response(
                array(
                    'result' => 'ok',
                    'msg'    => 'Get popular product success',
                    'data'   => $res
                )
            );
        }
    }

    /**
     * 根据分类获取商品信息
     */
    public function products_get() {
        //@todo 获取商品信息根据一级分类标识
        $class_a = $this->input->get('class_a', TRUE);
        $class_b = $this->input->get('class_b', TRUE);
        $page    = $this->input->get('page', TRUE);
        $this->load->library('home_lib');
        if ($res = $this->home_lib->product($class_a, $class_b, $page)) {
            $this->response(
                array(
                    'result' => 'ok',
                    'msg'    => 'Get popular product success',
                    'data'   => $res
                )
            );
        }
    }
  
}
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
    }

    /**
     * 根据搜索字符串获取返回内容
     */
    public function search_get() {
        $this->load->library('home_lib');
        $search_string = $this->input->get('search_string', TRUE);
        $page_num = $this->input->get('page_num', TRUE);
        $res = $this->home_lib->search($search_string, $page_num);
        if ($res['res']) {
            $this->response($res['data'], 200);
        } else {
            $msg = 'Get products failed';
            if (count($res['msg']) > 0) {
                $msg = implode("; ", $res['msg']);
            }
            $this->response($msg, 500);
        }
    }

    /**
     * 获取 class_a 目录
     */
    public function class_a_get() {
        $this->load->library('home_lib');
        $res = $this->home_lib->class_a();
        if($res['res']) {
            $this->response($res['data'], 200);
        } else {
            $this->response("fail", 500);
        }
    }

    /**
     * 根据给定的 class_a id 获取相应的 class_b 目录
     */
    public function class_b_get() {
        $this->load->library('home_lib');
        $class_a_id = $this->input->get('class_a_id', TRUE);
        $res = $this->home_lib->class_b( $class_a_id );
        if($res['res']) {
            $this->response($res['data'], 200);
        } else {
            $this->response("fail", 500);
        }
    }

    /**
<<<<<<< HEAD
     * 获取商品目录
     */
    public function products_class_get() {
        $this->load->library('home_lib');
        if ($res = $this->home_lib->products_class()) {
            $this->response($res, 200);
        }
    }

    /**
=======
>>>>>>> cdaedfce906f5782d7691eb2eaac691b789c774a
     * 热门商品推荐
     */
    public function popular_products_get() {
        $this->load->library('home_lib');
        $res = $this->home_lib->popular_product();
        if ($res['res']) {
            $this->response($res['data'], 200);
        } else {
            $msg = 'Get products failed';
            if (count($res['msg']) > 0) {
                $msg = implode("; ", $res['msg']);
            }
            $this->response($msg, 500);
        }
    }

    /**
     * 热门店铺推荐
     */
    public function popular_shop_get() {
        $this->load->library('home_lib');
        $res = $this->home_lib->popular_shop();
        if ($res['res']) {
            $this->response($res['data'], 200);
        } else {
            $msg = 'Get popular shop failed';
            if (count($res['msg']) > 0) {
                $msg = implode("; ", $res['msg']);
            }
            $this->response($msg, 500);
        }
    }
    
    /**
     * 根据分类获取商品信息
     */
    public function products_get() {
        $class_a = $this->input->get('class_a', TRUE);
        $class_b = $this->input->get('class_b', TRUE);
        $page    = (int) ($this->input->get('page', TRUE));
        $this->load->library('home_lib');
        $res = $this->home_lib->product($class_a, $class_b, $page);
        if ($res['res']) {
            $this->response($res['data'], 200);
        } else {
            $msg = 'Get products failed';
            if (count($res['msg']) > 0) {
                $msg = implode("; ", $res['msg']);
            }
            $this->response($msg, 500);
        }
    }
  
}

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
     * 获取 class_a 目录
     */
    public function class_a_get() {
        $this->load->library('home_lib');

        if($res = $this->home_lib->class_a()) {
            $this->response($res, 200);
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

        if($res = $this->home_lib->class_b( $class_a_id )) {
            $this->response($res, 200);
        } else {
            $this->response("fail", 500);
        }
    }

    /**
     * 获取商品目录
     */
    public function products_class_get() {
        $this->load->library('home_lib');
        if ($res = $this->home_lib->products_class()) {
            $this->response($res, 200);
            /**
            $this->response(
                array(
                    'result' => 'ok',
                    'msg'    => 'Get product class success',
                    'data'   => $res
                )
            );
            */
        }
    }


    /**
     * 热门商品推荐
     */
    public function popular_products_get() {
        $this->load->library('home_lib');
        if ($res = $this->home_lib->popular_product()) {
            $this->response($res, 200);
            /**
            $this->response(
                array(
                    'result' => 'ok',
                    'msg'    => 'Get popular products success',
                    'data'   => $res
                )
            );
            */
        }
    }

    /**
     * 热门店铺推荐
     */
    public function popular_shop_get() {
        $this->load->library('home_lib');
        if ($res = $this->home_lib->popular_shop()) {
            $this->response($res, 200);
            /**
            $this->response(
                array(
                    'result' => 'ok',
                    'msg'    => 'Get popular product success',
                    'data'   => $res
                )
            );
            */
        }
    }

    /**
     * 根据分类获取商品的分页数
     */
    public function products_page_get() {
        $class_a = $this->input->get('class_a', TRUE);
        $class_b = $this->input->get('class_b', TRUE);
        $this->load->library('home_lib');
        $res = $this->home_lib->products_page($class_a, $class_b);
        if ($res['res']) {
            $this->response(array('pages' => $res['data']), 200);
            /**
            $this->response(
                array(
                    'result' => 'ok',
                    'msg'    => 'Get products page success',
                    'data'   => array(
                        'pages' => $res['data']
                    )
                )
            );
            */
        } else {
            //默认获取失败时返回的消息
            $msg = 'Get products page failed';
            //获取上一层返回消息，并且格式化为字符串形式
            if (count($res['msg'] > 0)) {
                $msg = implode("; ", $res['msg']);
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
            /**
            $this->response(
                array(
                    'result' => 'ok',
                    'msg'    => 'Get products success',
                    'data'   => $res['data']
                )
            );
            */
        } else {
            $msg = 'Get products failed';
            if (count($res['msg']) > 0) {
                $msg = implode("; ", $res['msg']);
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
  
}

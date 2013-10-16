<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 首页
 */
class Home_lib {

    private $_CI;
    private $_page_num = 5;//首页显示的热门店铺数量
    private $_page_count = 12;//首页按分类显示的每页商品数量

    public function __construct() {
        $this->_CI =& get_instance();
    }

    public function popular_shop() {
        $this->_CI->load->model('view_model', 'view_m');
        $this->_CI->load->library('shop_lib');
        $shops_info = array();
        $i = 0;
        if ($shops = $this->_CI->view_m->count_view_rank('shop', $this->_page_num)) {
            foreach ($shops as $key => $value) {
                if ($res = $this->_CI->shop_lib->shop_info($value['id'])) {
                    $shops_info[$i] = $res;
                    $i++;
                }
            }
            return $shops_info;
        } else {
            return FALSE;
        }
    }

    public function popular_product() {
        $this->_CI->load->model('view_model', 'view_m');
        $this->_CI->load->model('product_model', 'product_m');
        $this->_CI->load->library('product_lib');
        $products_info = array();
        $i = 0;
        if ($products = $this->_CI->view_m->count_view_rank('product', $this->_page_num)) {
            foreach ($products as $key => $value) {
                if ($res = $this->_CI->product_m->product_info($value['id'])) {
                    $products_info[$i] = $this->_CI->product_lib->product($res['class_a'] . $res['class_b'] . $res['id']);
                    $i++;
                } 
            }
            return $products_info;
        } else {
            return FALSE;
        }
        
    }

    public function product($class_a, $class_b, $page) {
        $this->_CI->load->library('product_lib');
        $start = ($page - 1) * $this->_page_count;
        $end   = $start + $this->_page_count - 1;
        if (!$class_b) {
            $sql = "SELECT id, class_a, class_b FROM product WHERE class_a = $class_a LIMIT $start, $end";
        } else {
            $sql = "SELECT id, class_a, class_b FROM product WHERE class_a = $class_a AND class_b = $class_b LIMIT $start, $end";
        }       
        
        $res_object = $this->_CI->db->query($sql);
        $res_array  = $res_object->result_array();
        foreach ($res_array as $key => $value) {
            $res[$key]['id'] = $value['id'];
            $res[$key]['class_a'] = $value['class_a'];
            $res[$key]['class_b'] = $value['class_b'];
        }
        $products_info = array();
        $i = 0;
        foreach ($res as $key => $value) {
            if ($product = $this->_CI->product_lib->product($value['class_a'] . $value['class_b'] . $value['id'])) {
                $products_info[$i] = $product;
                $i++;
            }
        }
        return $products_info;
    }

}
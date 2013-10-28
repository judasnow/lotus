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

    public function class_a() {
        $this->_CI->load->model('class_model', 'class_m');
        return $this->_CI->class_m->class_a();
    }

    public function class_b( $class_a_id ) {
        $this->_CI->load->model('class_model', 'class_m');
        return $this->_CI->class_m->class_b( $class_a_id );
    }

    public function products_class() {
        $this->_CI->load->model('class_model', 'class_m');
        $class_a = array();
        $class_a_array = $this->_CI->class_m->class_a();
        //echo '<pre>';
        //var_dump($class_a_array);die;
        foreach ($class_a_array as $key => $value) {
            $class_a_array[$key]['class_b_content'] = $this->_CI->class_m->class_b($value['class_a']);
        }
        return $class_a_array;
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
        $res = array();
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

    public function products_page($class_a, $class_b) {
        $this->_CI->load->library('product_lib');
        if (!$class_b) {
            $sql = "SELECT id, class_a, class_b FROM product WHERE class_a = $class_a";
        } else {
            $sql = "SELECT id, class_a, class_b FROM product WHERE class_a = $class_a AND class_b = $class_b";
        }

        $res_object = $this->_CI->db->query($sql);
        $res_array = $res_object->result_array();
        return (int) (count($res_array) / $this->_page_num) + 1;
    }

}

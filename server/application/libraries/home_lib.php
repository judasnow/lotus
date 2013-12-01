<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 首页
 */
class Home_lib {

    private $_CI;
    private $_popular_shop_num;
    private $_popular_shop_page_num;
    private $_popular_product_page_num;
    private $_search_reuslt_product_page_num;
    public  $err_msg = array();

    public function __construct() {
        $this->_CI =& get_instance();
        $this->_CI->load->model('view_model', 'view_m');
        $this->_CI->load->model('product_model', 'product_m');
        $this->_CI->load->model('view_model', 'view_m');
        $this->_CI->load->model('class_model', 'class_m');
        $this->_CI->load->library('regulation');
        $this->_CI->load->library('shop_lib');
        $this->_CI->load->library('product_lib');
        $this->_CI->load->library('cache_lib');
        $this->_CI->load->config('variable');
        $this->_popular_shop_num = $this->_CI->config->item('popular_shop_num');
        $this->_popular_product_num = $this->_CI->config->item('popular_product_num');
        $this->_search_result_product_page_num = $this->_CI->config->item('search_result_product_page_num');
    }

    //根据指定搜索条件返回页数
    public function search_result_page($search_string) {
        if (empty($search_string)) {
            $this->err_msg[] = 'Search string or page num is empty';
            return array(
                'code' => 400,
                'msg'  => $this->err_msg,
                'data' => NULL
            );
        }
        $res = $this->_CI->product_m->search($search_string);
        $result = [];
        foreach ($res as $key => $value) {
            $result[$key] = $value['id'];
        }
        return array(
            'code' => 200,
            'msg'  => [],
            'data' => array((int)((count($result) + $this->_search_result_product_page_num - 1) / $this->_search_result_product_page_num))
        );
    }

    //根据指定的搜索字符和相应的页数返回内容
    //被函数 search 调用
    private function search_id($search_string, $page) {
        //Cache 需要重新设计数据结构
        $cache = $this->_CI->cache_lib->get_cache_search_product($search_string, $page);
        if ($cache['res'] =  FALSE) {
            return array(
                'res' => TRUE,
                'data' => $cache['data']
            );
        } else {
            //缓存中不存在该条记录，则写入缓存并且返回数据
            $res = $this->_CI->product_m->search($search_string);
            
            $cache = $this->_CI->cache_lib->set_cache_search_product($search_string);
            
            $result = [];
            foreach ($res as $key => $value) {
                $result[$key] = $value['id'];
            }
            $start = ($page - 1) * $this->_search_result_product_page_num;
            $list  = array_slice($result, $start, $this->_search_result_product_page_num);
            return array(
                'res' => TRUE,
                'data' => $list
            );
        }
    }

    //根据制定搜索记录返回结果
    public function search($search_string, $page) {
        $result_page_res = $this->search_result_page($search_string);
        $result_page     = $result_page_res['data'][0];
        if (empty($search_string) || empty($page)) {
            $this->err_msg[] = 'Search string or page num is empty';
            return array(
                'code' => 400,
                'msg' => $this->err_msg,
                'data' => []
            );
        } elseif ($page > $result_page) {
            return array(
                'code' => 404,
                'msg'  => [],
                'data' => []
            );
        }
        //需要获取的商品编号列表
        $result = $this->search_id($search_string, $page);
        $res = $result['data'];
        $res_not_cache = [];
        $products_info = [];
        //先查询缓存池中是否有该商品信息
        $k = 0;
        foreach ($res as $key => $value) {
            $cache = $this->_CI->cache_lib->get_cache_product_info($value);
            if ($cache['res']) {
                $products_info[$k] = $cache['data'];
            } else {
                $res_not_cache[] = $value;
            }
            $k++;
        }
        //如果没有缓存数据，则从数据库中获取信息
        $k = count($products_info);
        foreach ($res_not_cache as $key => $product_id) {
            $product_info = $this->_CI->product_lib->product_info($product_id);
            if ($product_info['code'] == 200) {
                $products_info[$k] = $product_info['data'];
                $this->_CI->cache_lib->set_cache_product_info($product_id);
            }
            $k++;
        }
        if (count($products_info) > 1) {
            return array(
                'code' => 200,
                'msg'  => [],
                'data' => $products_info
            );
        } else {
            return array(
                'code' => 404,
                'msg'  => [],
                'data' => []
            );
        }
    }
    
    //获取商品一级目录
    public function class_a() {
        //关闭缓存
        $cache = $this->_CI->cache_lib->get_cache_class_a_detail();
        if ($cache['res'] = FALSE) {
            $class_a = $cache['data'];
        } else {
            $class_a = $this->_CI->class_m->class_a();
            //写入缓存数据
            $this->_CI->cache_lib->set_cache_class_a_detail();
        }
        return array(
            'code' => 200,
            'msg'  => [],
            'data' => $class_a
        );
    }

    //获取商品二级目录
    public function class_b($class_a_id) {
        //Cache 需要重新设计
        $cache = $this->_CI->cache_lib->get_cache_class_b_detail($class_a_id);
        if ($cache['res'] = FALSE) {
            $class_b = $cache['data'];
        } else {
            $class_b = $this->_CI->class_m->class_b($class_a_id);
            //设置缓存
            //$this->_CI->cache_lib->set_cache_class_b_detail($class_a_id);
        }
        if (count($class_b) > 0) {
            return array(
                'code' => 200,
                'msg'  => [],
                'data' => $class_b
            );
        } else {
            return array(
                'code' => 404,
                'msg'  => [],
                'data' => NULL
            );
        }
    }

    //热门店铺信息
    public function popular_shop() {
        $shops_info = array();
        $i = 0;
        if ($shops = $this->_CI->view_m->count_view_rank('shop', $this->_popular_shop_num)) {
            foreach ($shops as $key => $value) {
                $cache = $this->_CI->cache_lib->get_cache_shop_info($value['id'], array(
                    'shop_id',
                    'shop_name',
                    'shop_tel',
                    'shop_address',
                    'shop_image_url',
                    'shop_register_time'
                ));
                
                //关闭缓存
                //$cache['res'] = FALSE;
                
                if ($cache['res']) {
                    $shops_info[$i] = $cache['data'];
                } else {
                    $res = $this->_CI->shop_lib->shop_info($value['id']);
                    $shops_info[$i] = $res['data'];
                    //添加缓存信息
                    $this->_CI->cache_lib->set_cache_shop_info($value['id']);
                }
                $i++;
            }
            return array(
                'code' => 200,
                'msg'  => [],
                'data' => $shops_info
            );
        } else {
            $this->err_msg[] = 'Error when get hot shop info';
            return array(
                'code' => 404,
                'msg'  => $this->err_msg,
                'data' => []
            );
        }
    }

    //热门商品信息
    public function popular_product() {
        $products_info = array();
        $i = 0;
        if ($products = $this->_CI->view_m->count_view_rank('product', $this->_popular_product_num)) {
            foreach ($products as $key => $value) {
                //先从缓存中获取数据信息
                $cache = $this->_CI->cache_lib->get_cache_product_info($value['id']);
                if ($cache['res']) {
                    $products_info[$i] = $cache['data'];
                } else {
                    //从数据库中读取数据信息
                    $res = $this->_CI->product_m->product_info($value['id']);
                    $result = $this->_CI->product_lib->product($res['class_a'] . $res['class_b'] . $res['id']);
                    $products_info[$i] = $result['data'];
                    //向redis中写入缓存信息
                    $this->_CI->cache_lib->set_cache_product_info($value['id']);
                }
                $i++;
            }
            return array(
                'code' => 200,
                'msg'  => [],
                'data' => $products_info
            );
        } else {
            $this->err_msg[] = 'Error: get data from view failed';
            return array(
                'code' => 404,
                'msg'  => $this->err_msg,
                'data' => []
            );
        }
    }

    //根据指定分类获取商品分页数
    public function category_products_page($class_a, $class_b) {
        //检查传入参数
        $params = array(
            'product_class_a' => $class_a,
            'product_class_b' => $class_b
        );
        if (empty($class_b)) {
            unset($params['product_class_b']);
        }
        foreach ($params as $key => $value) {
            $this->_CI->regulation->validate($key, $value);
        }
        if (count($this->_CI->regulation->err_msg) > 0) {
            $this->err_msg = $this->_CI->regulation->err_msg;
            $this->_CI->regulation->err_msg = array();
            return array(
                'code' => 400,
                'msg'  => $this->err_msg,
                'data' => []
            );
        }
        if (!$class_b) {
            $sql = "SELECT id, class_a, class_b FROM product WHERE class_a = $class_a";
        } else {
            $sql = "SELECT id, class_a, class_b FROM product WHERE class_a = $class_a AND class_b = $class_b";
        }
        $res_object = $this->_CI->db->query($sql);
        $res_array = $res_object->result_array();
        return array(
            'code' => 200,
            'msg'  => [],
            'data' => array((int) ((count($res_array) + $this->_search_result_product_page_num -1) / $this->_search_result_product_page_num)));
    }
    
    //根据指定分类获取商品信息
    public function category_products($class_a, $class_b, $page) {
        $category_products_page_res = $this->category_products_page($class_a, $class_b);
        if ($category_products_page_res['code'] == 200) {
            $category_products_page = $category_products_page_res['data'][0];
        } else {
            return $category_products_page_res;
        }
        if ($page > $category_products_page) {
            return array(
                'code' => 404,
                'msg'  => [],
                'data' => NULL
            );
        }
        $params = array(
            'product_class_a' => $class_a,
            'product_class_b' => $class_b,
            'page_num'    => $page
        );
        $cond = array(
            'class_a' => $class_a,
            'class_b' => $class_b
        );
        if (empty($params['product_class_b'])) {
            unset($params['product_class_b']);
            unset($cond['class_b']);
        }

        foreach ($params as $key => $value) {
            $this->_CI->regulation->validate($key, $value);
        }
        if (count($this->_CI->regulation->err_msg) > 0) {
            $this->err_msg = $this->_CI->regulation->err_msg;
            $this->_CI->regulation->err_msg = array();
            return array(
                'code' => 400,
                'msg'  => $this->err_msg,
                'data' => NULL
            );
        }

        $start = ($page - 1) * $this->_search_result_product_page_num;
        
        //关闭缓存
        $id_cache = $this->_CI->cache_lib->get_cache_class_search_product($cond);
        if ($id_cache['res'] = FALSE) {
            $res_array = $id_cache['data'];
        } else {
            $res_array = $this->_CI->product_m->class_product($cond);
            //设置缓存
            //$this->_CI->cache_lib->set_cache_class_search_product($cond);
            if ($res_array['res']) {
                $res_array = $res_array['data'];
            }
        }
        $res_array = array_slice($res_array, $start, $this->_search_result_product_page_num);

        $res = array();
        foreach ($res_array as $key => $value) {
            $res[$key]['id'] = $value['id'];
        }
        
        $products_info = array();
        $i = 0;
        foreach ($res as $key => $value) {
            //关闭缓存
            $product_cache = $this->_CI->cache_lib->get_cache_product_info($value['id']);
            if ($product_cache['res'] = FALSE) {
                $products_info[$i] = $product_cache['data'];
            } else {
                $product = $this->_CI->product_lib->product_info($value['id']);
                $products_info[$i] = $product['data'];
                //设置缓存
                $this->_CI->cache_lib->set_cache_product_info($value['id']);
            }
            $i++;
        }
        return array(
            'code' => 200,
            'msg'  => [],
            'data' => $products_info
        );
    }

}

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 首页
 */
class Home_lib {

    private $_CI;
    private $_redis;
    private $_page_num = 4;//首页显示的热门店铺和商品的数量
    private $_page_count = 12;//首页按分类显示的每页商品数量
    public  $err_msg = array();

    public function __construct() {
        $this->_CI =& get_instance();
        $this->_redis = new \Predis\Client([
            'scheme' => $this->_CI->config->item('scheme'),
            'host'   => $this->_CI->config->item('host'),
            'port'   => $this->_CI->config->item('port')
        ]);
    }

    public function search_id($search_string, $page) {
        //查询缓存，根据查询字符串进行 hash
        $replies = $this->_redis->pipeline(function ($pipe) use ($search_string){
            $pipe->select(3);
            $pipe->smembers('search_' . md5($search_string));
            $pipe->expire('search_' . md5($search_string), $this->_CI->config->item('cache_time'));
        });
        if ($replies[0] && $replies[1]) {
            //根据指定搜索条件查询成功，此处应检验返回结果是否为需要的格式
            $start = ($page - 1) * $this->_page_count;
            $list  = array_slice($replies[1], $start, $this->_page_count);
            return array(
                'res' => TRUE,
                'data' => array(
                    'total_page' => (int)((count($replies[1]) + $this->_page_count - 1) / $this->_page_count),
                    'content'    => $list
                )
            );
        } else {
            //缓存中不存在该条记录，则写入缓存并且返回数据
            $this->_CI->load->model('product_model', 'product_m');
            $res = $this->_CI->product_m->search($search_string);
            $replies = $this->_redis->pipeline(function ($pipe) use ($search_string, $res){
                $pipe->select(3);
                foreach ($res as $key => $value) {
                    $pipe->sadd('search_' . md5($search_string), $value['id']);
                }
            });
            $result = [];
            foreach ($res as $key => $value) {
                $result[$key] = $value['id'];
            }
            $start = ($page - 1) * $this->_page_count;
            $list  = array_slice($result, $start, $this->_page_count);
            return array(
                'res' => TRUE,
                'data' => array(
                    'total_page' =>  (int)((count($result) + $this->_page_count - 1) / $this->_page_count),
                    'content'    => $list
                )
            );
        }
    }

    public function search($search_string, $page) {
        //Todo 加入规则验证
        if (empty($search_string) || empty($page)) {
            $this->err_msg[] = 'Search string or page num is empty.';
            return array(
                'res' => FALSE,
                'msg' => $this->err_msg
            );
        }
        
        $this->_CI->load->library('product_lib');
        //需要获取的商品编号列表
        $result = $this->search_id($search_string, $page);
        $res = $result['data']['content'];
        $res_not_cache = [];

        $products_info = [];

        //先查询缓存池中是否有该商品信息
        $k = 0;
        foreach ($res as $key => $value) {
            $reply = $this->_redis->pipeline(function ($pipe) use ($value) {
                $pipe->select(2);
                $pipe->hmget('product' . $value . 'info', 
                             array(
                                 'product_id',
                                 'product_class_a',
                                 'product_class_b',
                                 'product_name',
                                 'product_describe',
                                 'product_image_url',
                                 'product_original_price',
                                 'product_discount',
                                 'product_now_price',
                                 'product_quantity'
                             ));
                $pipe->smembers('product' . $value . 'detail_image');
            });
            if ($reply[0] && $reply[1] && $reply[2]) {
                $products_info[$k]['product_id']             = $reply[1][0];
                $products_info[$k]['product_class_a']        = $reply[1][1];
                $products_info[$k]['product_class_b']        = $reply[1][2];
                $products_info[$k]['product_name']           = $reply[1][3];
                $products_info[$k]['product_describe']       = $reply[1][4];
                $products_info[$k]['product_image_url']      = $reply[1][5];
                $products_info[$k]['product_original_price'] = $reply[1][6];
                $products_info[$k]['product_discount']       = $reply[1][7];
                $products_info[$k]['product_now_price']      = $reply[1][8];
                $products_info[$k]['product_quantity']       = $reply[1][9];
                $products_info[$k]['product_detail_image']   = $reply[2];
                $k++;
            } else {
                $res_not_cache[] = $value;
            }
        }
        $k = count($products_info);
        foreach ($res_not_cache as $key => $product_id) {
            $product_info = $this->_CI->product_lib->product_info($product_id);
            if ($product_info['res']) {
                
                $products_info[$k] = $product_info['data'];
                
                //echo '<pre>';var_dump($products_info);die;
                $reply = $this->_redis->pipeline(function ($pipe) use ($product_info) {
                    $pipe->select(2);
                    $pipe->hmset('product' . substr($product_info['data']['product_id'], 10) . 'info',
                                 array(
                                     'product_id' => $product_info['data']['product_id'],
                                     'product_class_a' => $product_info['data']['product_class_a'],
                                     'product_class_b'=> $product_info['data']['product_class_b'],
                                     'product_name' => $product_info['data']['product_name'],
                                     'product_describe' => $product_info['data']['product_describe'],
                                     'product_image_url' => $product_info['data']['product_image_url'],
                                     'product_original_price' => $product_info['data']['product_original_price'],
                                     'product_discount' => $product_info['data']['product_discount'],
                                     'product_now_price' => $product_info['data']['product_now_price'],
                                     'product_quantity' => $product_info['data']['product_quantity']
                                 )
                                 
                    );
                    $pipe->expire('product' . substr($product_info['data']['product_id'], 10) . 'info', $this->_CI->config->item('cache_time'));
                    foreach ($product_info['data']['product_detail_image_url'] as $key => $value) {
                        $pipe->sadd('product' . substr($product_info['data']['product_id'], 10) . 'detail_image', $value);
                    }
                    $pipe->expire('product' . substr($product_info['data']['product_id'], 10) . 'detail_image', $this->_CI->config->item('cache_time'));
                });
            }
            $k++;
        }
        //var_dump($products_info);die;
        return array(
            'res' => TRUE,
            'data' => array(
                'total_page' => $result['data']['total_page'],
                'products_info' => $products_info
            )
        );
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
        $this->_CI->load->library('cache_lib');
        $products_info = array();
        $i = 0;
        if ($products = $this->_CI->view_m->count_view_rank('product', $this->_page_num)) {
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
                'res' => TRUE,
                'data' => $products_info
            );
        } else {
            $this->err_msg[] = 'Error: get data from view.';
            return array(
                'res' => TRUE,
                'msg' => $this->err_msg
            );
        }
    }

    public function product($class_a, $class_b, $page) {
        $params = array(
            'product_class_a' => $class_a,
            'product_class_b' => $class_b,
            'page_num'    => $page
        );
        if (empty($params['product_class_b'])) {
            unset($params['product_class_b']);
        }
        $this->_CI->load->library('regulation');
        foreach ($params as $key => $value) {
            $this->_CI->regulation->validate($key, $value);
        }
        if (count($this->_CI->regulation->err_msg) > 0) {
            $this->err_msg = $this->_CI->regulation->err_msg;
            $this->_CI->regulation->err_msg = array();
            return array(
                'res' => FALSE,
                'msg' => $this->err_msg
            );
        }
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
        return array(
            'res' => TRUE,
            'data' => $products_info
        );
    }

    public function products_page($class_a, $class_b) {
        //检查传入参数
        $params = array(
            'product_class_a' => $class_a,
            'product_class_b' => $class_b
        );
        if (empty($class_b)) {
            unset($params['product_class_b']);
        }
        $this->_CI->load->library('regulation');
        foreach ($params as $key => $value) {
            $this->_CI->regulation->validate($key, $value);
        }
        if (count($this->_CI->regulation->err_msg) > 0) {
            $this->err_msg = $this->_CI->regulation->err_msg;
            $this->_CI->regulation->err_msg = array();
            return array(
                'res' => FALSE,
                'msg' => $this->err_msg
            );
        }

        $this->_CI->load->library('product_lib');
        if (!$class_b) {
            $sql = "SELECT id, class_a, class_b FROM product WHERE class_a = $class_a";
        } else {
            $sql = "SELECT id, class_a, class_b FROM product WHERE class_a = $class_a AND class_b = $class_b";
        }

        $res_object = $this->_CI->db->query($sql);
        $res_array = $res_object->result_array();
        return array(
            'res' => TRUE,
            'data' => (int) ((count($res_array) + $this->_page_count -1) / $this->_page_count)
        );
    }

    private function cache_product_info ($product_id) {
        $this->_CI->load->library('product_lib');
        $product_info = $this->_CI->product_lib->product_info($product_id);
        if ($product_info['res']) {
            $reply = $this->_redis->pipeline(function ($pipe) use ($product_info) {
                $pipe->select(2);
                $pipe->hmset('product' . substr($product_info['data']['product_id'], 10) . 'info',
                             array(
                                 'product_id' => $product_info['data']['product_id'],
                                 'product_class_a' => $product_info['data']['product_class_a'],
                                 'product_class_b'=> $product_info['data']['product_class_b'],
                                 'product_name' => $product_info['data']['product_name'],
                                 'product_describe' => $product_info['data']['product_describe'],
                                 'product_image_url' => $product_info['data']['product_image_url'],
                                 'product_original_price' => $product_info['data']['product_original_price'],
                                 'product_discount' => $product_info['data']['product_discount'],
                                 'product_now_price' => $product_info['data']['product_now_price'],
                                 'product_quantity' => $product_info['data']['product_quantity']
                             )
                             
                );
                $pipe->expire('product' . substr($product_info['data']['product_id'], 10) . 'info', $this->_CI->config->item('cache_time'));
                foreach ($product_info['data']['product_detail_image_url'] as $key => $value) {
                    $pipe->sadd('product' . substr($product_info['data']['product_id'], 10) . 'detail_image', $value);
                }
                $pipe->expire('product' . substr($product_info['data']['product_id'], 10) . 'detail_image', $this->_CI->config->item('cache_time'));
            });
        }
    }
}

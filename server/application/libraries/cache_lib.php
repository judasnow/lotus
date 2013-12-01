<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cache_lib {

    private $_CI;
    private $_popular_shop_page_num;
    private $_popular_product_page_num;
    private $_search_result_product_page_num;
    private $_cache_time;
    private $_class_cache_time;
    
    public function __construct() {
        $this->_CI =& get_instance();
        $this->_CI->load->config('variable');
        $this->_CI->load->model('product_model', 'product_m');
        $this->_CI->load->model('shop_model', 'shop_m');
        $this->_CI->load->model('class_model', 'class_m');
        $this->_CI->load->library('qiniuyun_lib');
        $this->_popular_shop_num = $this->_CI->config->item('popular_shop_num');
        $this->_popular_product_num = $this->_CI->config->item('popular_product_num');
        $this->_search_result_product_page_num = $this->_CI->config->item('search_result_product_page_num');
        $this->_cache_time = $this->_CI->config->item('cache_time');
        $this->_class_cache_time = $this->_CI->config->item('class_cache_time');
        $this->_redis = new \Predis\Client([
            'scheme' => $this->_CI->config->item('scheme'),
            'host'   => $this->_CI->config->item('host'),
            'port'   => $this->_CI->config->item('port')
        ]);
    }
    
    /**
     * 缓存商品信息
     *
     * @param product_id   商品在数据库中的索引ID
     */
    public function set_cache_product_info ($product_id) {
        $this->_CI->load->library('product_lib');
        $product_info = $this->_CI->product_lib->product_info($product_id);
        if ($product_info['code'] == 200) {
            $reply = $this->_redis->pipeline(function ($pipe) use ($product_info) {
                $pipe->select(2);
                $pipe->hmset('product' . substr($product_info['data']['product_id'], 10) . 'info',
                             array(
                                 'product_id' => $product_info['data']['product_id'],
                                 'product_class_a' => $product_info['data']['product_class_a'],
                                 'product_class_b'=> $product_info['data']['product_class_b'],
                                 'product_class_a_name' => $product_info['data']['product_class_a_name'],
                                 'product_class_b_name' => $product_info['data']['product_class_b_name'],
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
            if ($reply[0] & $reply[1] & $reply[2]) {
                return array(
                    'res' => TRUE,
                    'data' => NULL
                );
            }
        }
        return array(
            'res' => FALSE,
            'msg' => "Error: Product id $product_id has no content in mysql db or cache fail."
        );
    }

    /**
     * 获取商品缓存信息
     * 
     * @param product_id      商品在数据库中的索引ID
     */
    public function get_cache_product_info ($product_id) {
        $product_info = [];
        $reply = $this->_redis->pipeline(function ($pipe) use ($product_id) {
            $pipe->select(2);
            $pipe->hmget('product' . $product_id . 'info', 
                         array(
                             'product_id',
                             'product_class_a',
                             'product_class_b',
                             'product_class_a_name',
                             'product_class_b_name',
                             'product_name',
                             'product_describe',
                             'product_image_url',
                             'product_original_price',
                             'product_discount',
                             'product_now_price',
                             'product_quantity'
                         ));
            $pipe->smembers('product' . $product_id . 'detail_image');
        });
        if ($reply[0] && $reply[1] && $reply[2]) {
            $product_info['product_id']             = $reply[1][0];
            $product_info['product_class_a']        = $reply[1][1];
            $product_info['product_class_b']        = $reply[1][2];
            $product_info['product_class_a_name']   = $reply[1][3];
            $product_info['product_class_b_name']   = $reply[1][4];
            $product_info['product_name']           = $reply[1][5];
            $product_info['product_describe']       = $reply[1][6];
            $product_info['product_image_url']      = $reply[1][7];
            $product_info['product_original_price'] = $reply[1][8];
            $product_info['product_discount']       = $reply[1][9];
            $product_info['product_now_price']      = $reply[1][10];
            $product_info['product_quantity']       = $reply[1][11];
            $product_info['product_detail_image_url']   = $reply[2];
            
            //关闭商品缓存
            return array(
                'res' => FALSE,
                'msg' => 'Has closed product cache'
            );
            
            return array(
                'res' => TRUE,
                'data' => $product_info
            );
        } else {
            return array(
                'res' => FALSE,
                'msg' => "No product $product_id info cache in redis."
            );
        }
    }

    /**
     * 缓存搜索商品结果
     */
    public function set_cache_search_product($search_string) {
        $res = $this->_CI->product_m->search($search_string);
        $reply = $this->_redis->pipeline(function ($pipe) use ($search_string, $res){
            $pipe->select(3);
            foreach ($res as $key => $value) {
                $pipe->sadd('search_' . md5($search_string), $value['id']);
            }
        });
        if ($reply[0]) {
            return array(
                'res' => TRUE,
                'data' => NULL
            );
        } else {
            return array(
                'res' => FALSE,
                'msg' => 'No product search cache in redis.'
            );
        }
    }

    /**
     * 缓存分类商品搜索结果
     */
    public function set_cache_class_search_product(array $search) {
        if (count($search) == 1 && $search['class_a']) {
            //设置一级分类标识
            $search_string = 'search_' . md5($search['class_a']);
            $res = $this->_CI->product_m->class_product(array('class_a' => $search['class_a']));
            if (!$res['res']) {
                return array(
                    'res' => FALSE,
                    'msg' => 'Query error'
                );
            }
        } elseif(count($search) == 2) {
            //设置二级分类标识
            $search_string = 'serach_' . md5($search['class_a'] . $search['class_b']);
            $res = $this->_CI->product_m->class_product(array(
                'class_a' => $search['class_a'],
                'class_b' => $search['class_b']));
            if (!$res['res']) {
                return array(
                    'res' => FALSE,
                    'msg' => 'Query error'
                );
            }
        } else {
            //参数不正确
            return array(
                'res' => FALSE,
                'msg' => 'Parameter error'
            );
        }
        $reply = $this->_redis->pipeline(function ($pipe) use ($search_string, $res) {
            $pipe->select(5);
            foreach ($res['data'] as $key => $value) {
                $pipe->sadd($search_string, $value['id']);
            }
        });
        if ($reply[0]) {
            return array(
                'res' => TRUE,
                'data' => NULL
            );
        } else {
            return array(
                'res' => TRUE,
                'data' => NULL
            );
        }
        
        
    }

    
    /**
     * 获取缓存的商品分类搜索结果
     */
    public function get_cache_class_search_product(array $search) {
        if (count($search) == 1 && $search['class_a']) {
            //设置一级分类标识
            $search_string = 'search_' . md5($search['class_a']);
        } elseif(count($search) == 2) {
            //设置二级分类标识
            $search_string = 'serach_' . md5($search['class_a'] . $search['class_b']);
        } else {
            //参数不正确
            return array(
                'res' => FALSE,
                'msg' => 'Parameter error'
            );
        }
        
        $replies = $this->_redis->pipeline(function ($pipe) use ($search_string){
            $pipe->select(5);
            $pipe->smembers($search_string);
        });
        $result = [];
        if ($replies[0] && $replies[1]) {
            foreach($replies[1] as $key => $value) {
                $result[$key]['id'] = $value;
            }
        } else {
            return array(
                'res' => FALSE,
                'msg' => 'No class product cache in redis'
            );
        }
    }

    /**
     * 获取缓存搜索商品结果
     */
    public function get_cache_search_product($search_string, $page) {
        $replies = $this->_redis->pipeline(function ($pipe) use ($search_string){
            $pipe->select(3);
            $pipe->smembers('search_' . md5($search_string));
            $pipe->expire('search_' . md5($search_string), $this->_CI->config->item('cache_time'));
        });
        if ($replies[0] && $replies[1]) {
            //根据指定搜索条件查询成功，此处应检验返回结果是否为需要的格式
            
            $start = ($page - 1) * $this->_search_result_product_page_num;
            $list  = array_slice($replies[1], $start, $this->_search_result_product_page_num);
            return array(
                'res' => TRUE,
                'data' => array(
                    'total_page' => (int)((count($replies[1]) + $this->_search_result_product_page_num - 1) / $this->_search_result_product_page_num),
                    'content'    => $list
                )
            );
        } else {
            return array(
                'res' => FALSE,
                'msg' => 'No product search cache in redis.'
            );
        }
    }

    /**
     * 缓存热门店铺信息
     */
    public function set_cache_shop_info($shop_id) {
        $res = $this->_CI->shop_m->shop_info_detail($shop_id);
        if ($res) {
            $reply = $this->_redis->pipeline(function($pipe) use ($res) {
                $pipe->select(4);
                $pipe->hmset('shop' . $res['id'] . 'info', array(
                    'shop_id' => $res['id'],
                    'shop_name' => $res['shop_name'],
                    'shop_tel' => $res['shop_tel'],
                    'shop_address' => $res['shop_address'],
                    'shop_image_url' => $this->_CI->qiniuyun_lib->thumbnail_private_url($res['shop_image'] . '.jpg', 'large', 'shop'),
                    'shop_register_time' => $res['register_time'],
                    'shop_product_count' => $this->_CI->shop_m->product_count($res['id']),
                    'show_shop_tel' => $res['show_shop_tel'],
                    'show_shop_address' => $res['show_shop_address'],
                ));
                $pipe->expire('shop' . $res['id'] . 'info', $this->_cache_time);
            });
            return array(
                'res' => TRUE,
                'data' => NULL
            );
        } else {
            return array(
                'res' => FALSE,
                'msg' => 'Cache shop info fail.'
            );
        }
    }

    /**
     * 获取缓存店铺信息，注意 select 的可选字段，参照上面的函数
     */
    public function get_cache_shop_info($shop_id, array $select) {
        $reply = $this->_redis->pipeline(function ($pipe) use ($shop_id, $select) {
            $pipe->select(4);
            $pipe->hmget('shop' . $shop_id . 'info', $select);
        });
        $shop_info = array();
        if ($reply[0] && $reply[1][0]) {
            foreach ($select as $key => $field) {
                $shop_info[$field] = $reply[1][$key];
            }
            return array(
                'res' => TRUE,
                'data' => $shop_info
            );
        } else {
            return array(
                'res' => FALSE,
                'msg' => 'Get shop_info cache fail'
            );
        }
    }

    /**
     * 缓存首页一级目录
     */
    public function set_cache_class_a_detail() {
        $class_a = $this->_CI->class_m->class_a();
        $reply = $this->_redis->pipeline(function ($pipe) use ($class_a) {
            $pipe->select(1);
            foreach ($class_a as $key => $value) {
                $pipe->hmset('firstclassinfo', array(
                    $value['class_a'] => $value['content']
                ));
            }
        });
        return array(
            'res' => TRUE,
            'data' => NULL
        );
    }

    /**
     * 获取首页一级目录缓存
     */
    public function get_cache_class_a_detail() {
        $reply = $this->_redis->pipeline(function ($pipe) {
            $pipe->select(1);
            $pipe->hgetall('firstclassinfo');
        });
        if ($reply[0] && $reply[1]) {
            $class_a_detail = $reply[1];
            $i = 0;
            $class_a = [];
            foreach ($class_a_detail as $key => $value) {
                $class_a[$i]['class_a'] = (string) $key;
                $class_a[$i]['content'] = $value;
                $i++;
            }
            return array(
                'res' => TRUE,
                'data' => $class_a
            );
        }
        return array(
            'res' => FALSE,
            'msg' => 'No class cache in redis.'
        );
    }

    /**
     * 缓存首页二级目录
     */
    public function set_cache_class_b_detail($class_a_id) {
        $class_b = $this->_CI->class_m->class_b($class_a_id);
        $reply = $this->_redis->pipeline(function ($pipe) use ($class_b, $class_a_id) {
            $pipe->select(2);
            $i = 0;
            foreach ($class_b as $key => $value) {
                $pipe->hmset('firstclass' . $class_a_id . 'secondclassinfo', array(
                    'class_b_' . $i => $value['class_b'],
                    'content_' . $i => $value['content']
                ));
                $i++;
            }
        });
        return array(
            'res' => TRUE,
            'data' => NULL
        );
    }

    /**
     * 获取首页二级目录缓存
     */
    public function get_cache_class_b_detail($class_a_id) {
        $reply = $this->_redis->pipeline(function ($pipe) use ($class_a_id) {
            $pipe->select(2);
            $pipe->hgetall('firstclass' . $class_a_id . 'secondclassinfo');
        });
        $class_b = [];
        if ($reply[0] && $reply[1]) {
            $i = 0;
            for (; $i < (count($reply[1]) / 2); $i++) {
                $key_str = 'class_b_' . $i;
                $value_str = 'content_' . $i;
                $class_b[$i]['class_b'] = $reply[1][$key_str];
                $class_b[$i]['content'] = $reply[1][$value_str];
            }
            return array(
                'res' => TRUE,
                'data' => $class_b
            );
        } else {
            return array(
                'res' => FALSE,
                'msg' => 'No class b cache in redis.'
            );
        }
        
    }
}
?>
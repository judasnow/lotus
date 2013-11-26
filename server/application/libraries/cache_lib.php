<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cache_lib {

    private $_CI;
    private $_popular_shop_page_num;
    private $_popular_product_page_num;
    private $_search_result_product_page_num;
    
    public function __construct() {
        $this->_CI =& get_instance();
        $this->_CI->load->config('variable');
        $this->_CI->load->model('product_model', 'product_m');
        $this->_popular_shop_num = $this->_CI->config->item('popular_shop_num');
        $this->_popular_product_num = $this->_CI->config->item('popular_product_num');
        $this->_search_result_product_page_num = $this->_CI->config->item('search_result_product_page_num');
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
            $product_info['product_name']           = $reply[1][3];
            $product_info['product_describe']       = $reply[1][4];
            $product_info['product_image_url']      = $reply[1][5];
            $product_info['product_original_price'] = $reply[1][6];
            $product_info['product_discount']       = $reply[1][7];
            $product_info['product_now_price']      = $reply[1][8];
            $product_info['product_quantity']       = $reply[1][9];
            $product_info['product_detail_image']   = $reply[2];
            
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
}
?>
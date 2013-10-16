<?php
/**
 * 店铺信息处理库文件
 *
 * @author: odirus@163.com
 */ 
class Shop_lib {

    private $_CI;
    private $_page_count = 12;//店铺每页显示的商品数量

    public function __construct() {
        $this->_CI =& get_instance();
    }

    //卖家访问自己的店铺基本信息
    public function shop_base_info() {
        $this->_CI->load->library('qiniuyun_lib');
        $this->_CI->load->model('shop_model', 'shop_m');
        if(!empty($_SESSION['object_user_id'])) {
            if($shop_base_info = $this->_CI->shop_m->shop_base_info($_SESSION['object_user_id'])) {
                $shop_base_info['shop_image_url'] = $this->_CI->qiniuyun_lib->thumbnail_private_url($shop_base_info['shop_image'] . ',jpg', 'small');
                $shop_base_info['shop_id']         = $shop_base_info['id'];
                unset($shop_base_info['id']);
                unset($shop_base_info['shop_image']);
                return $shop_base_info;
            }
        }
        return FALSE;
    }

    //卖家更新店铺基本信息
    public function update_shop_base_info($base_info) {
        if(!is_array($base_info)) {
            return FALSE;
        }
        $info['shop_image'] = $base_info['shop_image_name'];
        $info['shop_tel']  = $base_info['shop_tel'];
        $info['shop_address']   = $base_info['shop_address'];
        $this->_CI->load->model('shop_model', 'shop_m');
        if(!empty($_SESSION['object_user_id'])) {
            if($shop_id = $this->_CI->shop_m->get_shop_id($_SESSION['object_user_id'])) {
                if($this->_CI->shop_m->update_shop_info($shop_id, $info)) {
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    //游客获取店铺基本信息
    public function shop_info($shop_id) {
        $this->_CI->load->model('shop_model', 'shop_m');
        $this->_CI->load->library('qiniuyun_lib');

        //统计店铺浏览量
        $this->_CI->load->library('view_lib');
        $this->_CI->view_lib->add_view('shop', $shop_id);
        
        if ($res = $this->_CI->shop_m->shop_info($shop_id)) {
            $shop['shop_name'] = $res['shop_name'];
            if ($res['show_shop_tel']) {
                $shop['shop_tel'] = $res['shop_tel'];
            }
            $shop['shop_address'] = $res['shop_address'];
            if (!empty($res['shop_image'])) {
                $shop['shop_image_url'] = $this->_CI->qiniuyun_lib->thumbnail_private_url($res['shop_image'] . ',jpg', 'small');
            } else {
                $shop['shop_image_url'] = '';
            }
            $shop['shop_register_time'] = date('Y-m-d', strtotime($res['register_time']));
            return $shop;
        } else {
            return FALSE;
        }
    }

    public function show_shop_tel() {
        $this->_CI->load->model('shop_model', 'shop_m');
        if(!empty($_SESSION['object_user_id'])) {
            if($shop_id = $this->_CI->shop_m->get_shop_id($_SESSION['object_user_id'])) {
                if($this->_CI->shop_m->show_shop_tel($shop_id)) {
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    public function update_show_shop_tel() {
        $this->_CI->load->model('shop_model', 'shop_m');
        if(!empty($_SESSION['object_user_id'])) {
            if($shop_id = $this->_CI->shop_m->get_shop_id($_SESSION['object_user_id'])) {
                if($this->_CI->shop_m->update_show_shop_tel($shop_id)) {
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    //获取店铺内商品的数量
    public function product_count($shop_id) {
        $this->_CI->load->model('shop_model', 'shop_m');
        return $this->_CI->shop_m->product_count($shop_id);
    }
    
    //获取店铺内商品信息,page >= 1, flag 可选值 time, price, disount
    public function products($shop_id, $page, $flag) {
        
        //输入条件格式化查询
        if ($flag == 'price') {
            $flag = 'original_price';
        }

        $this->_CI->load->model('shop_model', 'shop_m');
        $start = ($page -1) * $this->_page_count;
        $end   = $start + $this->_page_count - 1;
        $this->_CI->load->model('shop_model', 'shop_m');
        $product_id = $this->_CI->shop_m->product_id($shop_id, $start, $end, $flag);
        $this->_CI->load->library('product_lib');
        $products =array();
        foreach ($product_id as $key => $value) {
            $products[$key] = $this->_CI->product_lib->product($value);
        }
        return $products;
    }

}
?>
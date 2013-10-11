<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Admin model
 *
 * @author: odirus@163.com
 */
require_once('base_model.php');

class Product_model extends Base_model {
    
    private $_CI;
    private $_table = 'product';

    public function __construct() {
        parent::__construct($this->_table);
        $this->_CI =& get_instance();
    }

    public function product($product) {
        $res_object = $this->base_query(array('id' => $product['id']), '');
        $res_array  = $res_object->result_array();
        if ($this->result_rows($res_array) == 1) {
            return $res_array[0];
        } else {
            return FALSE;
        }
    }

    public function new_product($product_info) {
        $this->base_write($product_info);
        if ($this->affected_rows() == 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function product_update($product) {
        $product_id = $product['id'];
        unset($product['id']);
        $res_object = $this->base_update(array('id' => $product_id), $product);
        if ($this->affected_rows() == 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    //获取该产品所属卖家用户编号
    public function saler_id($product_id) {
        $res_object = $this->base_query(array('id' => $product_id), 'shop_id');
        $res_array  = $res_object->result_array();
        if($this->result_rows($res_array) == 1) {
            $shop_id = $res_array[0]['shop_id'];
        } else {
            return FALSE;
        }
        $this->_CI->load->model('shop_model', 'shop_m');
        if ($user_id = $this->_CI->shop_m->saler_id($shop_id)) {
            return $user_id;
        } else {
            return FALSE;
        }
    }

}
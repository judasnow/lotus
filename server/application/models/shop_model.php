<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Shop model
 *
 * @Author: odirus@163.com
 */
require_once 'base_model.php';

class Shop_model extends Base_model {
    
    private $_CI;
    private $_table = 'shop';

    public function __construct() {
        parent::__construct($this->_table);
        $this->_CI =& get_instance();
    }

    /**
     * 创建新店铺
     */
    public function creat_shop($info) {
        $this->base_write($info);
        if ($this->affected_rows() == 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * 获取店铺基本信息
     */
    public function shop_base_info($user_id) {
        $res_object = $this->base_query(array('shopkeeper_id' => $user_id), 'id, shop_name, shop_tel, shop_image, shop_address');
        $res_array  = $res_object->result_array();
        if ($this->result_rows($res_array) == 1) {
            return $res_array[0];
        } else {
            return FALSE;
        }
    }

    /**
     * 更新店铺信息
     */
    public function update_shop_info($shop_id, array $info) {
        $this->base_update(array('id' => $shop_id), $info);
        if ($this->affected_rows() == 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * 根据用户ID（卖家ID）获取店铺ID
     */
    public function get_shop_id($user_id) {
        $res_object = $this->base_query(array('shopkeeper_id' => $user_id), 'id');
        $res_array  = $res_object->result_array();
        if ($this->result_rows($res_array) == 1) {
            return $res_array[0]['id'];
        } else {
            return FALSE;
        }
    }

    /**
     * 是否显示店铺联系方式
     */
    public function show_shop_tel($shop_id) {
        $res_object = $this->base_query(array('id' => $shop_id), 'show_shop_tel');
        $res_array  = $res_object->result_array();
        if ($this->result_rows($res_array) == 1) {
            $res = $res_array[0]['show_shop_tel'];
            if($res == 1) {
                return TRUE;
            }
        }
        return FALSE;
    }

    /**
     * 更新隐私设置，显示店铺联系方式
     */
    public function update_show_shop_tel($shop_id) {
        $res_object = $this->base_query(array('id' => $shop_id), 'show_shop_tel');
        $res_array  = $res_object->result_array();
        if ($this->result_rows($res_array) == 1) {
            $res = $res_array[0]['show_shop_tel'];
            if($res == 1) {
                $this->base_update(array('id' => $shop_id), array('show_shop_tel' => 0));
            } else {
                $this->base_update(array('id' => $shop_id), array('show_shop_tel' => 1));
            } 
        }
        if ($this->affected_rows() == 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
?>
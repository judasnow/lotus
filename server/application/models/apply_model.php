<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Apply model
 *
 * @Author: odirus@163.com
 */
require_once('base_model.php');

class Apply_model extends Base_model {
    
    private $_CI;
    private $_table = 'apply';

    public function __construct() {
        parent::__construct($this->_table);
        $this->_CI =& get_instance();
    }

    public function apply(array $info) {
        $this->base_write($info);

        if($this->affected_rows() == 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function applying() {
        $res_object = $this->sort_query(
            array('status' => 'verifying'),
            'id, shopkeeper_name, shopkeeper_tel, apply_time',
            'apply_time',
            1
        );
        return $res_object->result_array();
    }

    public function applying_detail($apply_id) {
        $res_object = $this->base_query(
            array(
                'id' => $apply_id,
                'status' => 'verifying'
            ),
            'id, shopkeeper_name, shopkeeper_tel, apply_time'
        );
        $res_array = $res_object->result_array();
        if($this->result_rows($res_array) == 1) {
            return $res_array[0];
        } else {
            return FALSE;
        }
    }

    //( int , array ) => boolean
    public function apply_verifying_pass($apply_id, array $content) {
        $this->base_update(
            array('id' => $apply_id), $content
        );
        if($this->affected_rows() == 1) {
            return TRUE;
        } else {
            throw new Exception('Affected ' . $this->affected_rows() . ' rows, but one except where apply_id is ' . $apply_id);
        }
    }

    public function apply_verifying_failed($apply_id, array $content) {
        $this->base_update(
            array('id' => $apply_id), $content
        );
        if($this->affected_rows() == 1) {
            return TRUE;
        } else {
            throw new Exception('Affected ' . $this->affected_rows() . ' rows, but one except where apply_id is ' . $apply_id);
        }
    }

    public function applied() {
        $res_object =  $this->sort_query(
            array('status' => 'verified'),
            'id, shopkeeper_name, shopkeeper_tel, apply_time,
             verified_time, decision, register_code, failed_message',
            'verified_time',
            1
        );
        return $res_object->result_array();
    }

    public function register_code($apply_id) {
        //todo Exception
        $res_object = $this->base_query(array('id' => $apply_id), 'register_code, code_available');
        $res_array  = $res_object->result_array();
        //@todo Check here
        if($this->result_rows($res_array) == 1) {
            if($res_array[0]['code_available'] == 'y') {
                return $res_array[0];
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    /**
     * 验证注册码是否可用，验证 register_code 和 code_available 字段即可
     */
    public function register_code_is_available($register_code) {
        $res_object = $this->base_query(array('register_code' => $register_code, 'code_available' => 'y'), 'id');
        $res_array  = $res_object->result_array();
        if($this->result_rows($res_array) == 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * 根据注册码来获取店铺的基本信息
     */
    public function shop_base_info($register_code) {
        $shop_info = array();
        $res_object = $this->base_query(array('register_code' => $register_code), 'shopkeeper_name, shopkeeper_tel, shop_name, shop_address');
        $res_array  = $res_object->result_array();
        if($this->result_rows($res_array) == 1) {
            $shop_info['register_code']   = $register_code;
            $shop_info['shopkeeper_name'] = $res_array[0]['shopkeeper_name'];
            $shop_info['shopkeeper_tel']  = $res_array[0]['shopkeeper_tel'];
            $shop_info['shop_name']       = $res_array[0]['shop_name'];
            $shop_info['shop_address']    = $res_array[0]['shop_address'];
            return $shop_info;
        } else {
            return FALSE;
        }
    }
    
    /**
     * 完成店铺注册功能，需要更改code_available字段的相关值
     */
    public function shop_has_registered($register_code) {
        $this->base_update(array('register_code' => $register_code), array('code_available' => 'n'));
        if($this->affected_rows() == 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
?>

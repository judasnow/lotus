<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * User model
 *
 * @Author: odirus@163.com
 */
require_once 'base_model.php';

Class User_model extends Base_model {
    
    private $_CI;
    private $_table = 'user';

    public function __construct() {
        parent::__construct($this->_table);
        $this->_CI =& get_instance();
    }

    public function get_user_id($email) {
        $res_object = $this->base_query(
            array('email' => $email), 'id'
        );
        $res_array = $res_object->result_array();
        if($this->result_rows($res_array) == 1) {
            return $res_array[0]['id'];
        } else {
            return FALSE;
        }
    }

    public function get_user_info($user_id) {
        $res_object = $this->base_query(array('id' => $user_id), 'id, email, role');
        $res_array  = $res_object->result_array();
        if ($this->result_rows($res_array) == 1) {
            return $res_array[0];
        } else {
            return FALSE;
        }
    }

    /**
     * 根据用户名查询密码
     */
    public function get_hash_password($email) {
        $res_object = $this->base_query(
            array('email' => $email),'password'
        );
        $res_array = $res_object->result_array();
        if($this->result_rows($res_array) == 1) {
            return $res_array[0];
        } else {
            return FALSE;
        }
    }

    /**
     * 根据用户ID查询密码
     */
    public function get_password($user_id) {
        $res_object = $this->base_query(
            array('id' => $user_id),'password'
        );
        $res_array = $res_object->result_array();
        if($this->result_rows($res_array) == 1) {
            return $res_array[0];
        } else {
            return FALSE;
        }
    }
    
    /**
     * 验证用户名是否可用
     */
    public function email_is_available($email) {
        $res_object = $this->base_query(
            array('email' => $email), 'id'
        );
        $res_array = $res_object->result_array();
        if($this->result_rows($res_array) == 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function register_new_user(array $info) {
        $info['password'] = md5($info['password']);
        $this->base_write($info);
        if($this->affected_rows() == 1) {
            return $this->get_user_id($info['email']);
        } else {
            return FALSE;
        }
    }

}
?>

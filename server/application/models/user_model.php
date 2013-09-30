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

    public function get_user_id($username) {
        $res_object = $this->base_query(
            array('username' => $username), 'id'
        );
        $res_array = $res_object->result_array();
        if($this->result_rows($res_array) == 1) {
            return $res_array[0]['id'];
        } else {
            return FALSE;
        }
    }

    public function get_hash_password($username) {
        $res_object = $this->base_query(
            array('username' => $username),'password'
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
    public function username_is_available($username) {
        $res_object = $this->base_query(
            array('username' => $username), 'id'
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
            if(!$this->get_user_id($info['username'])) {
                throw new Exception('Register new user just now, but cannot find it');
            }
            return $this->get_user_id($info['username']);
        } else {
            return FALSE;
        }
    }

}
?>
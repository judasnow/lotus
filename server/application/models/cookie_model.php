<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Admin model
 *
 * @author: odirus@163.com
 */
require_once('base_model.php');

class Cookie_model extends Base_model {
    
    private $_CI;
    private $_table = 'cookie';
    
    public function __construct() {
        parent::__construct($this->_table);
        $this->_CI =& get_instance();
    }

    public function write_user_cookie($user_id, $session_id, $user_agent) {
        //避免重复写入cookie
        if ($this->get_user_cookie($session_id)) {
            return FALSE;
        }
        $this->base_write(array('user_id' => $user_id, 'session_id' => $session_id, 'user_agent' => $user_agent));
        if ($this->affected_rows() == 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    //根据 session_id 获取用户代理信息
    public function get_user_cookie($session_id) {
        $res_object = $this->base_query(array('session_id' => $session_id), 'user_id, user_agent');
        $res_array = $res_object->result_array();
        if ($this->result_rows($res_array) == 1) {
            return $res_array[0];
        } else {
            return FALSE;
        }
    }

    //删除用户登录 cookie
    public function delete_user_cookie($session_id) {
        $this->base_delete(array('session_id' => $session_id));
    }
}
?>
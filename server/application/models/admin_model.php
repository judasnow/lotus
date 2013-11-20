<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Admin model
 *
 * @author: odirus@163.com
 */
require_once('base_model.php');

class Admin_model extends Base_model {

    private $_CI;
    private $_table = 'admin';

    private $_id;
    private $_username; 
    private $_password;

    public function __construct() {
        parent::__construct($this->_table);
        $this->_CI =& get_instance();
    }

    public function get_hash_password($username) {
        $res_object = $this->base_query(array('username' => $username), 'password');
        $res_array  = $res_object->result_array();
        if ($this->result_rows($res_array) == 1) {
            return $res_array[0];
        } else {
            return FALSE;
        }
    }

    public function get_admin_id($username) {
        $res_object = $this->base_query(array('username' => $username), 'id');
        $res_array  = $res_object->result_array();
        if ($this->result_rows($res_array) == 1) {
            return $res_array[0];
        } else {
            return FALSE;
        }
    }
}


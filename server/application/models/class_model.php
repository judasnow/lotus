<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Admin model
 *
 * @author: odirus@163.com
 */
require_once('base_model.php');

class Class_model extends Base_model {

    private $_CI;
    private $_table = 'class';
    
    public function __construct() {
        parent::__construct($this->_table);
        $this->_CI =& get_instance();
    }

    //获取一级目录
    public function class_a() {
        $sql = 'SELECT class_a, content FROM class WHERE class_b = 0';
        $res_object = $this->_CI->db->query($sql);
        $res_array = $res_object->result_array();
        return $res_array;
    }

    //获取二级目录
    public function class_b($class_a_id) {
        $sql = "SELECT class_b, content FROM class WHERE class_a = $class_a_id AND class_b <> 0";
        $res_object = $this->_CI->db->query($sql);
        $res_array  = $res_object->result_array();
        return $res_array;
    }
}
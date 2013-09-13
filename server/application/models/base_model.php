<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Base_model {
    
    private $_CI;
    private $_table;
    
    public function __construct($table) {
        $this->_CI =& get_instance();
        $this->_table = $table;
    }
    
    public function base_query(array $cond, $select_string) {
        $this->_CI->db->select($select_string)
                      ->where($cond);
        return $this->_CI->db->get($this->_table);
    }

    public function base_write(array $content) {
        $this->_CI->db->insert($this->_table, $content);
    }

    public function base_update(array $cond, array $content) {
        
    }

    public function base_delete(array $cond) {
        
    }
    
    protected function affected_rows() {
        return $this->_CI->db->affected_rows();
    }

    protected function result_rows($res_object) {
        return count($res_object);
    }
}
?>
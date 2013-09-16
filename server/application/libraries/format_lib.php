<?php
/**
 * @Author: odirus@163.com
 */
class Format_lib {
    
    private $_CI;
    
    public function __construct() {
        $this->_CI =& get_instance();
    }

    public function to_datetime() {
        return date("Y-m-d H:i:s", time());
    }

    public function get_registger_code() {
        return uniqid();
    }
}
?>
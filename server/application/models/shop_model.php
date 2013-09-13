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

    public function creat_shop($info) {
        $this->base_write($info);
        if($this->affected_rows() == 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
?>
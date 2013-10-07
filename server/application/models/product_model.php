<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Admin model
 *
 * @author: odirus@163.com
 */
require_once('base_model.php');

class Product_model() {
    
    private $_CI;
    private $_table = 'product';

    public function __construct() {
        parent::__construct($thid->_table);
        $this->_CI =& get_instance();
    }

    public function new_product($product_info) {
        $this->base_write($product_info);
        if($this->affected_rows() == 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
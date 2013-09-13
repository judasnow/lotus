<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Invite model
 *
 * @Author: odirus@163.com
 */
require_once 'base_model.php';

class Invite_model extends Base_model {
    
    private $_CI;
    private $_table = 'invite';

    public function __construct() {
        parent::__construct($this->_table);
        $this->_CI =& get_instance();
    }

    /**
     * Verify register code
     *
     * @param string register_code 
     *
     * @return boolean
     */
    public function verify_register_code($register_code) {
        $res_object = $this->base_query(
            array('register_code' => $register_code), 'id'
        );
        $res_array = $res_object->result_array();
        if($this->result_rows($res_array) == 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Get shop base info from invite table
     *
     * @param register_code
     * 
     * @return owner_name shop_tel shop_address
     */
    public function get_shop_base_info($register_code) {
        $res_object = $this->base_query(
            array('register_code' => $register_code), 'shop_owner_name, shop_tel, shop_address'
        );
        $res_array = $res_object->result_array();
        if($this->result_rows($res_array) == 1) {
            return $res_array[0];
        } else {
            return FALSE;
        }
    }
}
?>
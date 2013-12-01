<?php 
/**
 * @Author: odirus@163.com
 */
class View_lib {

    private $_CI;

    public function __construct() {
        $this->_CI =& get_instance();
        $this->_CI->load->model('view_model', 'view_m');
    }

    public function add_view($class, $id) {
        if ($this->_CI->view_m->add_view($class, $id)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
<?php
/**
 * 获取可操作权限的用户编号，调用库文件必须是用户已经登录
 *
 * @Author: odirus@163.com
 */
class Privilege_lib {

    private $_CI;

    public function __construct() {
        $this->_CI =& get_instance();
    }

    public  function shop_privilege_user_id($shop_id) {
        
    }

    public function product_privilege_user_id($product_id) {
        $this->_CI->load->model('product_model', 'product_m');
        if ($privilege_user_id = $this->_CI->product_m->saler_id($product_id)) {
            if ($privilege_user_id == $_SESSION['object_user_id']) {
                return TRUE;
            }
        }
        return FALSE;
    }

}
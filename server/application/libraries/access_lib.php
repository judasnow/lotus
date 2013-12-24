<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 验证用户权限的库函数
 */
class Access_lib {

    private $_CI;
    public $error;

    public function __construct() {
        $this->_CI =& get_instance();
        $this->_CI->load->library('privilege_lib');
        $this->error = '';
    }
    
    /**
     * 权限验证
     *
     * @param string $type 需要验证的权限。可选值'shop', 'product',id要用外部样式
     */
    public function validate_privilege($type, $id) {
        
        switch ($type) {
        case 'shop':
            if (!$this->_CI->privilege_lib->shop_privilege_user_id($id)) {
                $this->error = 'Can not access';
            }
            break;
        case 'product':
            $id = substr($id, 10);
            if (!$this->_CI->privilege_lib->product_privilege_user_id($id)) {
                $this->error = 'Can not access';
            }
            break;
        default:
            $this->error = 'Can not access';
        }
    }
}
?>
<?php
/**
 * 店铺信息处理库文件
 *
 * @author: odirus@163.com
 */ 
class Shop_lib {

    private $_CI;

    public function __construct() {
        $this->_CI =& get_instance();
    }

    public function shop_base_info() {
        $this->_CI->load->model('shop_model', 'shop_m');
        if(!empty($_SESSION['object_user_id'])) {
            if($shop_base_info = $this->_CI->shop_m->shop_base_info($_SESSION['object_user_id'])) {
                $shop_base_info['shop_image_name'] = $shop_base_info['shop_image'];
                $shop_base_info['shop_id']         = $shop_base_info['id'];
                unset($shop_base_info['id']);
                unset($shop_base_info['shop_image']);
                return $shop_base_info;
            }
        }
        return FALSE;
    }

    public function update_shop_base_info($base_info) {
        if(!is_array($base_info)) {
            return FALSE;
        }
        $info['shop_image'] = $base_info['shop_image_name'];
        $info['shop_tel']  = $base_info['shop_tel'];
        $info['shop_address']   = $base_info['shop_address'];
        $this->_CI->load->model('shop_model', 'shop_m');
        if(!empty($_SESSION['object_user_id'])) {
            if($shop_id = $this->_CI->shop_m->get_shop_id($_SESSION['object_user_id'])) {
                if($this->_CI->shop_m->update_shop_info($shop_id, $info)) {
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    public function show_shop_tel() {
        $this->_CI->load->model('shop_model', 'shop_m');
        if(!empty($_SESSION['object_user_id'])) {
            if($shop_id = $this->_CI->shop_m->get_shop_id($_SESSION['object_user_id'])) {
                if($this->_CI->shop_m->show_shop_tel($shop_id)) {
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    public function update_show_shop_tel() {
        $this->_CI->load->model('shop_model', 'shop_m');
        if(!empty($_SESSION['object_user_id'])) {
            if($shop_id = $this->_CI->shop_m->get_shop_id($_SESSION['object_user_id'])) {
                if($this->_CI->shop_m->update_show_shop_tel($shop_id)) {
                    return TRUE;
                }
            }
        }
        return FALSE;
    }
}
?>
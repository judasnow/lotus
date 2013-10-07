<?php 
/**
 * @Author: odirus@163.com
 */
class Product_lib {
    
    private $_CI;

    public function __construct() {
        $this->_CI =& get_instance();
    }

    public function new_product($product_info) {
        //格式化产品信息
        $info = array();
        $info['class_a'] = $product_info['product_class_a'];
        $info['class_b'] = $product_info['product_class_b'];
        $info['name']    = $product_info['product_name'];
        $info['describe'] = $product_info['product_describe'];
        $info['original_price'] = $product_info['product_original_price'];
        $info['discount'] = $product_info['product_discount'];
        $info['quantity'] = $product_info['product_quantity'];
        $info['image'] = $product_info['product_image'];
        $info['detail_image'] = $product_info['product_detail_image'];

        $this->_CI->load->model('product_model', 'product_m');
        if ($this->product_m->new_product($info)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
<?php 
/**
 * @Author: odirus@163.com
 */
class Product_lib {
    
    private $_CI;

    public function __construct() {
        $this->_CI =& get_instance();
    }

    public function product($product_id) {
        $product_id_string = (string) $product_id;
        $product = array(
            'class_a' => substr($product_id_string, 0, 5),
            'class_b' => substr($product_id_string, 5, 5),
            'id'      => substr($product_id_string, 10)
        );
        
        $this->_CI->load->model('product_model', 'product_m');
        if($product_info = $this->_CI->product_m->product($product)) {
            //format product_info
            $product_info_format = array();
            $product_info_format['product_id'] = $product_info['id'];
            $product_info_format['product_class_a'] = $product_info['class_a'];
            $product_info_format['product_class_b'] = $product_info['class_b'];
            $product_info_format['product_name']    = $product_info['name'];
            $product_info_format['product_describe']= $product_info['describe'];
            $product_info_format['product_image']   = $product_info['image'];
            $product_info_format['product_detail_image'] = array();
            $product_info_format['product_detail_image'] = explode(',', $product_info['detail_image']);
            $product_info_format['product_original_price'] = $product_info['original_price'];
            $product_info_format['product_discount'] = $product_info['discount'];
            $product_info_format['product_now_price'] = number_format($product_info['original_price'] * $product_info['discount'], 1);
            
            $product_info_format['product_quantity'] = $product_info['quantity'];
                
            return $product_info_format;
        } else {
            return FALSE;
        }
    }

    public function new_product($product_info) {
        //格式化产品信息
        $info = array();
        $info['class_a'] = $product_info['product_class_a'];
        $info['class_b'] = $product_info['product_class_b'];
        $info['name']    = $product_info['product_name'];
        $info['describe'] = $product_info['product_describe'];
        $info['original_price'] = number_format($product_info['product_original_price'], 1);
        $info['discount'] = number_format($product_info['product_discount'], 1);
        $info['quantity'] = $product_info['product_quantity'];
        $info['image'] = $product_info['product_image'];
        $info['detail_image'] = $product_info['product_detail_image'];

        $this->_CI->load->model('product_model', 'product_m');
        if ($this->_CI->product_m->new_product($info)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function product_update($product_info) {
        //格式化产品信息
        $info = array();
        $info['id'] = substr($product_info['product_id'], 10);
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
        if ($this->_CI->product_m->product_update($info)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
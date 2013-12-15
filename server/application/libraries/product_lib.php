<?php 
/**
 * @Author odirus@163.com
 *
 * @TODO 处理商品写入和取出时的折扣信息
 */
class Product_lib {
    
    private $_CI;
    public $err_msg = array();

    public function __construct() {
        $this->_CI =& get_instance();
        $this->_CI->load->library('regulation');
        $this->_CI->load->model('shop_model', 'shop_m');
        $this->_CI->load->model('product_model', 'product_m');
        $this->_CI->load->model('view_model', 'view_m');
        $this->_CI->load->model('class_model', 'class_m');
        $this->_CI->load->library('view_lib');
        $this->_CI->load->library('qiniuyun_lib');
    }

    /**
     * 该函数使用经过处理后的商品编号
     */
    public function product($product_id, $location = 'index') {
        //如果是直接从商品 api 加载的信息
        if ($location == 'product_detail') {
            $location_main = 'product_detail_main';
            $location_detail = 'product_detail_else';
        } else {
            $location_main = $location;
            $location_detail = $location;
        }
        $product_id_string = (string) $product_id;
        $this->_CI->regulation->validate('product_id', $product_id_string);
        if (count($this->_CI->regulation->err_msg) > 0) {
            $this->err_msg = $this->_CI->regulation->err_msg;
            $this->_CI->regulation->err_msg = array();
            return array(
                'code' => 400,
                'msg'  => $this->err_msg,
                'data' => NULL
            );
        }
        $product = array(
            'class_a' => substr($product_id_string, 0, 5),
            'class_b' => substr($product_id_string, 5, 5),
            'id'      => substr($product_id_string, 10)
        );
        
        $this->_CI->load->library('qiniuyun_lib');
        $this->_CI->load->model('product_model', 'product_m');
        
        //统计页面访问两
        $this->_CI->load->library('view_lib');
        $this->_CI->view_lib->add_view('product', $product['id']);
        
        if($product_info = $this->_CI->product_m->product($product)) {
            //format product_info
            $product_info_format = array();
            $product_info_format['product_id'] = $product_id_string;
            $product_info_format['product_class_a'] = $product_info['class_a'];
            $product_info_format['product_class_b'] = $product_info['class_b'];
            
            $product_info_format['product_class_a_name'] = $this->_CI->class_m->class_a_name($product_info['class_a']);
            $product_info_format['product_class_b_name'] = $this->_CI->class_m->class_b_name($product_info['class_b']);

            $product_info_format['product_name']    = $product_info['name'];
            $product_info_format['product_describe']= $product_info['describe'];
         
            $product_info_format['product_image_url'] = $this->_CI->qiniuyun_lib->thumbnail_qiniu_image_url($product_info['image'] . '.jpg', $location_main, 'product');
            if ($product_info['detail_image'] == '') {
                $product_info_format['product_detail_image_url'] = array();
            } else {
                $product_info_format['product_detail_image'] = array();
                $product_info_format['product_detail_image'] = explode(',', $product_info['detail_image']);
                foreach ($product_info_format['product_detail_image']  as $key => $value) {
                    $product_info_format['product_detail_image_url'][$key] = $this->_CI->qiniuyun_lib->thumbnail_qiniu_image_url($value . '.jpg', $location_detail, 'product');
                }
                unset($product_info_format['product_detail_image']);
            }
            
            $product_info_format['product_original_price'] = $product_info['original_price'];
            $product_info_format['product_discount'] = $product_info['discount'];
            $product_info_format['product_now_price'] = number_format($product_info['original_price'] * $product_info['discount'] * 0.1, 1);
            
            $product_info_format['product_quantity'] = $product_info['quantity'];
            return array(
                'code' => 200,
                'msg'  => [],
                'data' => $product_info_format
            );
        } else {
            $this->err_msg[] = 'Get product info failed';
            return array(
                'code' => 404,
                'msg'  => [],
                'data' => NULL
            );
        }
    }
    
    /**
     * 该函数使用数据库中的商品编号
     */
    public function product_info($product_id, $location = 'index') {

        //如果是直接从商品 api 加载的信息
        if ($location == 'product_detail') {
            $location_main = 'product_detail_main';
            $location_detail = 'product_detail_else';
        } else {
            $location_main = $location;
            $location_detail = $location;
        }
        
        //统计页面访问两
        $this->_CI->view_lib->add_view('product', $product_id);
        if($product_info = $this->_CI->product_m->product_info($product_id)) {
            //format product_info
            $product_info_format = array();
            $product_info_format['product_id'] = $product_info['class_a'] . $product_info['class_b'] . $product_id;
            $product_info_format['product_class_a'] = $product_info['class_a'];
            $product_info_format['product_class_b'] = $product_info['class_b'];

            $product_info_format['product_class_a_name'] = $this->_CI->class_m->class_a_name($product_info['class_a']);
            $product_info_format['product_class_b_name'] = $this->_CI->class_m->class_b_name($product_info['class_b']);

            $product_info_format['product_name']    = $product_info['name'];
            $product_info_format['product_describe']= $product_info['describe'];
            
            $product_info_format['product_image_url'] = $this->_CI->qiniuyun_lib->thumbnail_qiniu_image_url($product_info['image'] . '.jpg', $location_main, 'product');
            if ($product_info['detail_image'] == '') {
                $product_info_format['product_detail_image_url'] = array();
            } else {
                $product_info_format['product_detail_image'] = array();
                $product_info_format['product_detail_image'] = explode(',', $product_info['detail_image']);
                foreach ($product_info_format['product_detail_image']  as $key => $value) {
                    $product_info_format['product_detail_image_url'][$key] = $this->_CI->qiniuyun_lib->thumbnail_qiniu_image_url($value . '.jpg', $location_detail , 'product');
                }
                unset($product_info_format['product_detail_image']);
            }
            
            $product_info_format['product_original_price'] = $product_info['original_price'];
            $product_info_format['product_discount'] = $product_info['discount'];
            $product_info_format['product_now_price'] = number_format($product_info['original_price'] * $product_info['discount'] * 0.1, 1);
            
            $product_info_format['product_quantity'] = $product_info['quantity'];
            return array(
                'code' => 200,
                'msg'  => [],
                'data' => $product_info_format
            );
        } else {
            return array(
                'code' => 404,
                'msg'  => [],
                'data' => NULL
            );
        }
    }

    public function new_product($product_info) {
        $product_info_validate = $product_info;
        $product_info_validate['product_price'] = $product_info_validate['product_original_price'];
        unset($product_info_validate['product_original_price']);

        //可选参数处理
        $opt_array = array(
            'product_describe' => $product_info_validate['product_describe'],
            'product_image'    => $product_info_validate['product_image'],
            'product_detail_image' => $product_info_validate['product_detail_image'],
            'product_discout' => $product_info_validate['product_discount']
        );
        foreach ($opt_array as $key => $value) {
            if (empty($value)) {
                unset($product_info_validate[$key]);
            }
        }
        //验证各字段是否符合条件
        foreach ($product_info_validate as $key => $value) {
            $this->_CI->regulation->validate($key, $value);
        }
        if (count($this->_CI->regulation->err_msg) > 0) {
            $this->err_msg = $this->_CI->regulation->err_msg;
            $this->_CI->regulation->err_msg = array();
            return array(
                'code' => 400,
                'msg'  => $this->err_msg,
                'data' => []
            );
        }
        //格式化产品信息
        $info = array();
        $info['shop_id'] = $this->_CI->shop_m->get_shop_id($_SESSION['object_user_id']);
        $info['class_a'] = $product_info['product_class_a'];
        $info['class_b'] = $product_info['product_class_b'];
        $info['name']    = $product_info['product_name'];
        $info['describe'] = $product_info['product_describe'];
        $info['original_price'] = number_format($product_info['product_original_price'], 1);
        $info['discount'] = number_format($product_info['product_discount'], 1);
        $info['quantity'] = $product_info['product_quantity'];
        $info['image'] = $product_info['product_image'];
        $info['detail_image'] = $product_info['product_detail_image'];

        //@todo 此处应该使用事务
        if ($product_sn = $this->_CI->product_m->new_product($info)) {
            if ($this->_CI->view_m->add_product($product_sn)) {
                return array(
                    'code' => 200,
                    'msg'  => [],
                    'data' => []
                );
            }
        }
        $this->err_msg[] = 'Release new product failed';
        return array(
            'code' => 404,
            'msg'  => $this->err_msg,
            'data' => []
        );
    }

    public function product_update($product_info) {
        //格式化产品信息
        $this->_CI->load->library('regulation');
        $product_info_validate = $product_info;
        $product_info_validate['product_price'] = $product_info_validate['product_original_price'];
        unset($product_info_validate['product_original_price']);
        //处理可选参数
        $opt_array = array(
            'product_describe' => $product_info_validate['product_describe'],
            'product_image'    => $product_info_validate['product_image'],
            'product_detail_image' => $product_info_validate['product_detail_image'],
            'product_discount'  => $product_info_validate['product_discount']
        );
        foreach ($opt_array as $key => $value) {
            if (empty($value)) {
                unset($product_info_validate[$key]);
            }
        }
        //验证参数格式
        foreach ($product_info_validate as $key => $value) {
            $this->_CI->regulation->validate($key, $value);
        }
        if (count($this->_CI->regulation->err_msg) > 0) {
            $this->err_msg = $this->_CI->regulation->err_msg;
            $this->_CI->regulation->err_msg = array();
            return array(
                'code' => 400,
                'msg'  => $this->err_msg,
                'data' => []
            );
        }

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
        $res = $this->_CI->product_m->product_update($info);
        if ($res) {
            return array(
                'code' => 200,
                'msg'  => [],
                'data' => []
            );
        } else {
            $this->err_msg[] = 'Nothing has been change';
            return array(
                'code' => 404,
                'msg'  => $this->err_msg,
                'data' => []
            );
        }
    }
}

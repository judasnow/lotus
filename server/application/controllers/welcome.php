<?php

class Welcome extends CI_Controller {
    
    public function test () {
        $this->load->library('cache_lib');
        $this->load->library('home_lib');
        $this->load->model('product_model','product_m');
        //$res = $this->cache_lib->set_cache_product_info(1);
        //$res = $this->cache_lib->get_cache_product_info(1);
        //$res = $this->cache_lib->get_cache_search_product("商品", 4b);
        //$res = $this->cache_lib->set_cache_search_product('商品');

        //$res = $this->cache_lib->set_cache_shop_info(1);
        //$res = $this->cache_lib->get_cache_shop_info(1, array('shop_id', 'show_shop_tel', 'shop_address'));
        //$res = $this->home_lib->class_a();
        //$res = $this->cache_lib->set_cache_class_a_detail();
        //$res = $this->cache_lib->get_cache_class_a_detail();
        //$res = $this->cache_lib->set_cache_class_b_detail(10000);
        //$res = $this->cache_lib->get_cache_class_b_detail(10000);
        
        $res = $this->product_m->class_product(array('class_a' => '10000'));
        
        echo '<pre>';var_dump($res['data']);die;
        echo 'It works!';
    }

}
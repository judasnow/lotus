<?php

class Welcome extends CI_Controller {
    
    public function test () {
        $this->load->library('cache_lib');
        $res = $this->cache_lib->set_cache_product_info(1);
        //$res = $this->cache_lib->get_cache_product_info(1);
        echo '<pre>';var_dump($res);die;
        echo 'It works!';
    }

}
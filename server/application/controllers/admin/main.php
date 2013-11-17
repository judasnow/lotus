<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * admin page for lotus
 *
 * @author <judasnow@gmail.com>
 */
class Main extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('apply_model', 'apply_m');
    }

    public function index() {
        $this->applied();
    }

    public function auth() {
        $this->load->view( 'admin/auth' );
    }

    public function applied() {
        $applies_info = $this->apply_m->applied();
        $this->load->view( 'admin/home' , ['applies_info'=>$applies_info] );
    }

    public function applying() {
        $this->load->view( 'admin/home' );
        $applies_info = $this->apply_m->applying();
        var_dump( $applies_info );
    }
}

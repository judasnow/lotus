<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Apply api, Apply for shop
 *
 * @Author: odirus@163.com
 */
require_once(APPPATH . '/libraries/REST_Controller.php');

class Apply_api extends REST_Controller {
    
    public function __construct() {
        parent::__construct();
    }

    /**
     * Apply for shop
     *
     * @privilege All 
     *
     * @param string shopkeeper_name
     * @param string shopkeeper_tel
     * @param string shop_name
     * @param string shop_address
     *
     * @doc
     */
    public function do_apply_post() {
        $apply_info = array(
            'shopkeeper_name' => $this->input->post('shopkeeper_name', TRUE),
            'shopkeeper_tel'  => $this->input->post('shopkeeper_tel', TRUE),
            'shop_name'       => $this->input->post('shop_name', TRUE),
            'shop_address'    => $this->input->post('shop_address', TRUE),
        );
        $this->load->library('apply_lib');
        if($this->apply_lib->do_apply($apply_info)) {
            $this->response(
                array(
                    'result' => 'ok',
                    'msg'    => 'Apply success',
                    'data'   => NULL
                )
            );
        } else {
            $this->response(
                array(
                    'result' => 'fail',
                    'msg'    => 'Apply failed',
                    'data'   => NULL
                )
            );
        }
    }

    /**
     * Get verifying applying
     *
     * @privilege Admin
     * 
     * @sort      time
     *
     * @doc
     */
    public function apply_verifying_get() {
        //@Todo check privilege
        $this->load->library('apply_lib');
        $this->response(
            array(
                'result' => 'ok',
                'msg'    => 'Get verifying data success',
                'data'   => $this->apply_lib->apply_verifying()
            )
        );
    }

    /**
     * Get verifying applying detail
     *
     * @privilege Admin
     *
     * @param     apply_id
     *
     * @doc
     */
    public function apply_verifying_detail_get() {
        //@todo check privilege
        $apply_id = $this->input->get('apply_id', TRUE);
        $this->load->library('apply_lib');
        if($res = $this->apply_lib->apply_verifying_detail($apply_id)) {
            $this->response(
                array(
                    'resutl' => 'ok',
                    'msg'    => 'Get verifying detail data success',
                    'data'   => $res
                )
            );
        } else {
            $this->response(
                array(
                    'resutl' => 'fail',
                    'msg'    => 'Get verifying detail data failed',
                    'data'   => NULL
                )
            );
        }
        
    }

    /**
     * Verifying pass
     *
     * @privilege Admin
     *
     * @param     apply_id
     *
     * @doc
     */
    public function apply_verifying_pass_post() {
        //@todo Check privilege
        $this->load->library('apply_lib');
        $apply_id = $this->input->post('apply_id', TRUE);
        if($this->apply_lib->apply_verifying_pass($apply_id)) {
            $this->response(
                array(
                    'result' => 'ok',
                    'msg'    => 'Verifying pass success',
                    'data'   => array(
                        'register_code' => $this->apply_lib->register_code($apply_id)
                    )
                )
            );
        } else {
            $this->response(
                array(
                    'result' => 'fail',
                    'msg'    => 'Verifying passed failed',
                    'data'   => NULL
                )
            );
        }
    }

    /**
     * Verifying failed
     *
     * @privilege Admin
     *
     * @param     apply_id
     * @param     message
     *
     * @doc
     */
    public function apply_verifying_failed_post() {
        //@todo Check privilege
        $this->load->library('apply_lib');
        $apply_id = $this->input->post('apply_id', TRUE);
        $message  = $this->input->post('message', TRUE);
        if($this->apply_lib->apply_verifying_failed($apply_id, $message)) {
            $this->response(
                array(
                    'result' => 'ok',
                    'msg'    => 'Verifying success',
                    'data'   => NULL
                )
            );
        } else {
            $this->response(
                array(
                    'result' => 'fail',
                    'msg'    => 'Verifying failed',
                    'data'   => NULL
                )
            );
        }
    }
    
    /**
     * Get verified applying
     * 
     * @privilege Admin
     * 
     * @sort      time
     *
     * @doc
     */
    public function apply_verified_get() {
        //@Check privilege
        $this->load->library('apply_lib');
        $this->response(
            array(
                'result' => 'ok',
                'msg'    => 'Get verified data success',
                'data'   => $this->apply_lib->apply_verified()
            )
        );
    }

}


?>
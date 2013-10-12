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
        session_start();
    }

    /**
     * 申请店铺
     *
     * @param string shopkeeper_name   店主姓名
     * @param string shopkeeper_tel    店主联系方式
     * @param string shop_name         店铺名称
     * @param string shop_address      店铺地址
     *
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
     * 审核中的店铺
     *
     * @param void
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
     * 待审核店铺店铺申请详细信息
     *
     * @param     apply_id      申请编号
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
     * 店铺审核通过
     *
     * @param    apply_id      店铺编号
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
     * 店铺审核未通过
     *
     * @param     apply_id     店铺编号
     * @param     message      店铺审核未通过原因
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
     * 已经被审核店铺信息（通过和未通过）
     * 
     * @param     void
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

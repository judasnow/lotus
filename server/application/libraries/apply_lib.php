<?php 
/**
 * @Author: odirus@163.com
 */
class Apply_lib {
    
    private $_CI;
    public $err_msg = array();

    public function __construct() {
        $this->_CI =& get_instance();
    }

    public function do_apply(array $info) {
        //@todo format
        $this->_CI->load->model('apply_model', 'apply_m');
        if($this->_CI->apply_m->apply($info)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function apply_verifying() {
        //@todo format
        $this->_CI->load->model('apply_model', 'apply_m');
        $res_array = $this->_CI->apply_m->applying();
        //format return info
        foreach ($res_array as $key => $value) {
            //format apply_id
            $res_array[$key]['apply_id'] = $res_array[$key]['id'];
            unset($res_array[$key]['id']);
        }
        return $res_array;
    }
    
    public function apply_verifying_detail($apply_id) {
        $this->_CI->load->library('regulation');
        $this->_CI->regulation->validate('apply_id', $apply_id);
        if (count($this->_CI->regulation->err_msg) > 0) {
            $this->err_msg = $this->_CI->regulation->err_msg;
            $this->_CI->regulation->err_msg = array();
            return array(
                'res' => FALSE,
                'msg' => $this->err_msg
            );
        }
        $this->_CI->load->model('apply_model', 'apply_m');
        $res_array = $this->_CI->apply_m->applying_detail($apply_id);
        if($res_array) {
            //format return info
            $res_array['apply_id'] = $res_array['id'];
            unset($res_array['id']);
            return array(
                'res' => TRUE,
                'data' => $res_array
            );
        } else {
            $this->err_msg[] = 'No more detail';
            return array(
                'res' => FALSE,
                'msg' => $this->err_msg
            );
        }
    }

    public function apply_verifying_pass($apply_id) {
        $this->_CI->load->library('regulation');
        $this->_CI->regulation->validate('apply_id', $apply_id);
        if (count($this->_CI->regulation->err_msg) > 0) {
            $this->err_msg = $this->_CI->regulation->err_msg;
            $this->_CI->regulation->err_msg = array();
            return array(
                'res' => FALSE,
                'msg' => $this->err_msg
            );
        }
        $this->_CI->load->library('format_lib');
        $this->_CI->load->model('apply_model', 'apply_m');
        $this->_CI->db->trans_begin();
        try {
            $content = array(
                'status'         => 'verified',
                'verified_time'  => $this->_CI->format_lib->to_datetime(),
                'decision'       => 'passed',
                'register_code'  => $this->_CI->format_lib->get_registger_code(),
                'code_available' => 'y'
            );
            $this->_CI->apply_m->apply_verifying_pass($apply_id, $content);
            $this->_CI->db->trans_commit();
            //@todo return register code
            return array(
                'res' => TRUE,
                'data' => $this->register_code($apply_id)
            );
        } catch (Exception $e) {
            log_message('info', $e->getMessage() . "\n");
            $this->_CI->db->trans_rollback();
            $this->err_msg[] = 'Apply verifying failed';
            return array(
                'res' => FALSE,
                'msg' => $this->err_msg
            );
        }
        
    }

    public function apply_verifying_failed($apply_id, $message) {
        //@todo format
        $this->_CI->load->library('regulation');
        $this->_CI->regulation->validate('apply_id', $apply_id);
        if (count($this->_CI->regulation->err_msg) > 0) {
            $this->err_msg = $this->_CI->regulation->err_msg;
            $this->_CI->regulation->err_msg = array();
            return array(
                'res' => FALSE,
                'msg' => $this->err_msg
            );
        }
        //@todo avoid repeat invoke it
        $this->_CI->load->library('format_lib');
        $this->_CI->load->model('apply_model', 'apply_m');
        $this->_CI->db->trans_begin();
        try {
            $content = array(
                'status'         => 'verified',
                'verified_time'  => $this->_CI->format_lib->to_datetime(),
                'decision'       => 'failed',
                'failed_message' => $message
            );
            $this->_CI->apply_m->apply_verifying_failed($apply_id, $content);
            $this->_CI->db->trans_commit();
            return array(
                'res' => TRUE,
                'data' => NULL
            );
        } catch (Exception $e) {
            log_message('info', $e->getMessage() . "\n");
            $this->_CI->db->trans_rollback();
            $this->err_msg[] = 'Verifying not passed';
            return array(
                'res' => FALSE,
                'msg' => $this->err_msg
            );
        }
    }

    public function apply_verified() {
        //@todo format
        $this->_CI->load->model('apply_model', 'apply_m');
        $res_array = $this->_CI->apply_m->applied();
        //format return info
        foreach ($res_array as $key => $res) {
            //format apply_id
            $res_array[$key]['apply_id'] = $res_array[$key]['id'];
            unset($res_array[$key]['id']);
            
            switch ($res['decision']) {
            case 'passed':
                $res_array[$key]['decision'] = '审核通过';
                $res_array[$key]['result']   = $res_array[$key]['register_code'];
                unset(
                    $res_array[$key]['register_code'],
                    $res_array[$key]['failed_message']
                );
                break;
            case 'failed':
                $res_array[$key]['decision'] = '审核未通过';
                $res_array[$key]['result']   = $res_array[$key]['failed_message'];
                unset(
                    $res_array[$key]['register_code'],
                    $res_array[$key]['failed_message']
                );
                break;
            default:
                $res_array[$key]['decision'] = '数据异常';
                log_message('info', "There has a problem in table apply, where id is " . $res_array[$key]['id']);
            }
        }
        return $res_array;
    }

    public function register_code($apply_id) {
        $this->_CI->load->model('apply_model', 'apply_m');
        $res_array = $this->_CI->apply_m->register_code($apply_id);
        if($res_array) {
            return $res_array['register_code'];
        } else {
            return FALSE;
        }
    }
}
?>
<?php
/**
 * 处理上传文件
 */
class Upload_lib {
    
    private $_CI;

    public function __construct() {
        $this->_CI =& get_instance();
    }

    /**
     * 格式化上传的图片至指定格式
     */
    private function format_image($file_name) {
        $image_info = $this->_CI->upload->data();
        $file_ext   = $img_info;
    }

    public function do_upload_image($type) {
        
        //上传配置文件
        $config   = array();
        $config['upload_path']   = getcwd() . '/file/image/' . "$type";
        $config['allowed_types'] = 'gif|jpg|png';
        $config['file_name']     = 'hello';
        
        $this->_CI->load->library('upload', $config);
        if ($this->_CI->upload->do_upload()) {
            //处理图片相关信息
            return TRUE;
        } else {
            return FALSE;
        }
 
    }

}

?>
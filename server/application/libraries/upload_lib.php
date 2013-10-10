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
    private function format_image($image_info, $type) {
        $image_name = uniqid('maoejie');
        $input = $image_info['full_path'];
        $output = getcwd() . '/file/image/' . "$type" . "/$image_name" . '.jpg';
        $file_type = $image_info['file_type'];
        switch($file_type) {
        case 'image/jpeg':
            rename($input, $output);
            break;
        case 'image/png':
            $image = imagecreatefrompng($input);
            imagejpeg($image, $output, 100);
            imagedestroy($image);
            unlink($input);
            break;
        case 'image/gif': 
            $image = imagecreatefromgif($input);
            imagejpeg($image, $output, 100);
            imagedestroy($image);
            break;
        case 'image/wbmp':
            $image = imagecreatefromwbmp($input);
            imagejpeg($image, $output, 100);
            imagedestroy($image);
            break;
		}
        return $image_name;
    }

    /**
     * 更新图片名称至数据库，同步图片至云存储
     */
    public function do_upload_image($type) {
        $user_id = $_SESSION['object_user_id'];
        //上传配置文件
        //todo 限制图片的张数
        $upload_path = getcwd() . '/file/image/' . "$type";
        
        $config   = array();
        $config['upload_path']   = $upload_path;
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['file_name']     = uniqid();
        $this->_CI->load->library('upload', $config);
        $this->_CI->load->model('shop_model', 'shop_m');
        if ($this->_CI->upload->do_upload()) {
            $image_name = $this->format_image($this->_CI->upload->data(), $type);
            if($type == 'shop') {
                $this->_CI->shop_m->base_update(
                    array('shopkeeper_id' => $user_id),
                    array('shop_image' => $image_name)
                );
            }
            $file = $upload_path . "/$image_name.jpg";
            $image_full_name = $image_name . '.jpg';
            $this->_CI->load->library('qiniuyun_lib');
            
            if ($this->_CI->qiniuyun_lib->upload($image_full_name, $file)) {
                return $image_name;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
 
    }

}

?>
<?php
/**
 * Qiniuyun的相关操作
 *
 * @athor: odirus@163.com
 */
require_once(getcwd() . '/application/third_party/qiniu/io.php');
require_once(getcwd() . '/application/third_party/qiniu/rs.php');
require_once(getcwd() . '/application/third_party/qiniu/fop.php');

class Qiniuyun_lib {
    
    private $_bucket = 'maoejie';
    private $_accessKey = 'ylMDiB_RbB1C1JRaEWHg8fqLmCXxf7RuBbOLKCZu';
    private $_secretKey = 'bHcsEs2YvYKZDjimcHzQDQEwOaeVKPgPmYENztAT';
    
    private $_CI;
    
    public function __construct() {
        $this->_CI =& get_instance();
    }

    /**
     * 上传图片至云空间
     */
    public function upload($image_name, $file) {
        $key1 = $image_name;
        Qiniu_SetKeys($this->_accessKey, $this->_secretKey);
        $putPolicy = new Qiniu_RS_PutPolicy($this->_bucket);
        $upToken = $putPolicy->Token(null);
        $putExtra = new Qiniu_PutExtra();
        $putExtra->Crc32 = 1;
        list($ret, $err) = Qiniu_PutFile($upToken, $key1, $file, $putExtra);
        if ($err !== null) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * 上传图片（新接口）
     */
    public function upload_to_qiniu($image_name, $file) {
        $image_node = substr($image_name, 7, 5);
        $this->_CI->config->load('variable', TRUE);
        $qiniuyun_config = $this->_CI->config->item('qiniuyun_config');
        $image_node_key = 'maoejie' . $image_node;
        $accessKey = $qiniuyun_config[$image_node_key]['_accessKey'];
        $secretKey = $qiniuyun_config[$image_node_key]['_secretKey'];
        $key1 = $image_name;
        Qiniu_SetKeys($accessKey, $secretKey);
        $putPolicy = new Qiniu_RS_PutPolicy(substr($image_name, 0, 12));
        $upToken = $putPolicy->Token(null);
        $putExtra = new Qiniu_PutExtra();
        $putExtra->Crc32 = 1;
        list($ret, $err) = Qiniu_PutFile($upToken, $key1, $file, $putExtra);
        if ($err !== null) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * 生成预览图片（新接口）
     */
    public function thumbnail_qiniu_image_url($image_full_name, $size, $type) {
        if (strlen($image_full_name) <= 24) {
            $image_node = 'nodea';
            $this->_CI->config->load('variable');
            $qiniuyun_config = $this->_CI->config->item('qiniuyun_config');
            $image_node_key = 'maoejie' . $image_node;
            $accessKey = $qiniuyun_config[$image_node_key]['_accessKey'];
            $secretKey = $qiniuyun_config[$image_node_key]['_secretKey'];
            Qiniu_SetKeys($accessKey, $secretKey);
            $domain = 'maoejienodea' . '.u.qiniudn.com';
            $baseUrl = Qiniu_RS_MakeBaseUrl($domain, 'defaultimage-' . $type . '.jpg');
        } else {
            $image_node = substr($image_full_name, 7, 5);
            $this->_CI->config->load('variable');
            $qiniuyun_config = $this->_CI->config->item('qiniuyun_config');
            $image_node_key = 'maoejie' . $image_node;
            $accessKey = $qiniuyun_config[$image_node_key]['_accessKey'];
            $secretKey = $qiniuyun_config[$image_node_key]['_secretKey'];
            Qiniu_SetKeys($accessKey, $secretKey);
            $domain = $image_node_key . '.u.qiniudn.com';
            $baseUrl = Qiniu_RS_MakeBaseUrl($domain, $image_full_name);
        }
        $imgView = new Qiniu_ImageView;
        switch ($size) {
        case 'small':
            $imgView->Mode = 2;
            $imgView->Width = 60;
            $imgView->Height = 120;
            break;
        case 'middle':
            $imgView->Mode = 2;
            $imgView->Width = 180;
            $imgView->Height = 360;
            break;
        case 'large':
            $imgView->Mode = 2;
            $imgView->Width = 360;
            $imgView->Height = 720;
            break;
        case 'product':
            $imgView->Mode = 2;
            $imgView->Height = 512;
            break;
        case 'search_result_page'://搜索结果页面商品
            $imgView->Mode = 2;
            $imgView->Width = 230;
            $imgView->Height = 148;
            break;
        case 'product_detail_main'://商品主要图片
            $imgView->Mode = 2;
            $imgView->Width = 320;
            $imgView->Height = 320;
            break;
        case 'product_detail_else'://商品细节图片
            $imgView->Mode = 2;
            $imgView->Width = 234;
            $imgView->Height = 234;
            break;
        case 'index'://首页图片
            $imgView->Mode = 2;
            $imgView->Width = 231;
            $imgView->Height = 192;
            break;
        case 'shop_product_list'://店铺商品列表
            $imgView->Mode = 2;
            $imgView->Width = 148;
            $imgView->Height = 148;
            break;
        default:
            $imgView->Mode = 2;
            $imgView->Width = 180;
            $imgView->Height = 360;
        }
        $imgViewUrl = $imgView->MakeRequest($baseUrl);
        $getPolicy = new Qiniu_RS_GetPolicy();
        $imgViewPrivateUrl = $getPolicy->MakeRequest($imgViewUrl, null);
        return $imgViewPrivateUrl;
    }

    /**
     * 返回私有空间资源原图像的url
     */
    public function original_private_url($image_full_name) {
        $key = $image_full_name;
        $domain = 'maoejie.u.qiniudn.com';
        Qiniu_SetKeys($this->_accessKey, $this->_secretKey);  
        $baseUrl = Qiniu_RS_MakeBaseUrl($domain, $key);
        $getPolicy = new Qiniu_RS_GetPolicy();
        $privateUrl = $getPolicy->MakeRequest($baseUrl, null);
        return $privateUrl;
    }

    /**
     * 生成预览图片
     *
     * @param $image_full_name 图片全称，包括后缀名
     * @param $size            缩略图大小，可选值small,middle,large
     * @param $type            图片类型，shop，product
     */
    public function thumbnail_private_url($image_full_name, $size, $type) {

        //如果前端传输存在w参数，则返回相应的宽度
        if (isset($_GET['w']) && is_numeric($_GET['w'])) {
            $size = 'custom';
        }
        Qiniu_SetKeys($this->_accessKey, $this->_secretKey);
        $domain = 'maoejie.u.qiniudn.com';
        if (strlen($image_full_name) < 5) {
            $baseUrl = Qiniu_RS_MakeBaseUrl($domain, 'maoejiedefaultimage-' . $type . '.jpg');
        } else {
            $baseUrl = Qiniu_RS_MakeBaseUrl($domain, $image_full_name);
        }
        $imgView = new Qiniu_ImageView;
        switch ($size) {
        case 'small':
            $imgView->Mode = 2;
            $imgView->Width = 60;
            $imgView->Height = 120;
            break;
        case 'middle':
            $imgView->Mode = 2;
            $imgView->Width = 180;
            $imgView->Height = 360;
            break;
        case 'large':
            $imgView->Mode = 2;
            $imgView->Width = 360;
            $imgView->Height = 720;
            break;
        case 'product':
            $imgView->Mode = 2;
            $imgView->Height = 512;
            break;
        case 'custom':
            $imgView->Mode = 2;
            $imgView->Width = $_GET['w'];
            break;
        default:
            $imgView->Mode = 2;
            $imgView->Width = 180;
            $imgView->Height = 360;
        }
        $imgViewUrl = $imgView->MakeRequest($baseUrl);
        $getPolicy = new Qiniu_RS_GetPolicy();
        $imgViewPrivateUrl = $getPolicy->MakeRequest($imgViewUrl, null);
        return $imgViewPrivateUrl;
    }
}

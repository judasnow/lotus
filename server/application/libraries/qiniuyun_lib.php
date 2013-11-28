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

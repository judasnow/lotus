<?php
/**
 * Qiniuyun的相关操作
 *
 * @athor: odirus@163.com
 */
require_once(getcwd() . '/application/third_party/qiniu/io.php');
require_once(getcwd() . '/application/third_party/qiniu/rs.php');

class Qiniuyun_lib {
    
    public function upload($image_name, $file) {
        $bucket = "maoejie";
        $key1 = $image_name;
        $accessKey = 'ylMDiB_RbB1C1JRaEWHg8fqLmCXxf7RuBbOLKCZu';
        $secretKey = 'bHcsEs2YvYKZDjimcHzQDQEwOaeVKPgPmYENztAT';
        
        Qiniu_SetKeys($accessKey, $secretKey);
        $putPolicy = new Qiniu_RS_PutPolicy($bucket);
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

}

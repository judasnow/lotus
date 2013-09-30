<?php
class Verify {
    
    /**
     * 验证用户名
     * @param  string   $value
     * @param  int      $length
     * @return boolean
     */
    public static function isNames($value, $minLen=2, $maxLen=20, $charset='ALL'){
        if(empty($value))
            return false;
        switch($charset){
        case 'EN': $match = '/^[_\w\d]{'.$minLen.','.$maxLen.'}$/iu';
            break;
        case 'CN':$match = '/^[_\x{4e00}-\x{9fa5}\d]{'.$minLen.','.$maxLen.'}$/iu';
            break;
        default:$match = '/^[_\w\d\x{4e00}-\x{9fa5}]{'.$minLen.','.$maxLen.'}$/iu';
        }
        return preg_match($match,$value);
    }
    
    /**
     * 验证密码
     * @param string $value
     * @param int $length
     * @return boolean
     */
    public static function isPWD($value,$minLen=5,$maxLen=16){
        $match='/^[\\~!@#$%^&*()-_=+|{}\[\],.?\/:;\'\"\d\w]{'.$minLen.','.$maxLen.'}$/';
        $v = trim($value);
        if(empty($v)) 
            return false;
        return preg_match($match,$v);
    }
    
    /**
     * 验证eamil
     * @param  string  $value
     * @param  int     $length
     * @return boolean
     */
    public static function isEmail($value,$match='/^[\w\d]+[\w\d-.]*@[\w\d-.]+\.[\w\d]{2,10}$/i'){
        $v = trim($value);
        if(empty($v)) 
            return false;
        return preg_match($match,$v);
    }
}
?>
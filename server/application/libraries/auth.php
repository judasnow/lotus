<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 验证用户权限的库函数
 */
class Auth {
    
    static public function is_login() {
        //todo session_start函数放在这里是否合适？
        //session_start();
        if(isset($_SESSION['object_user_id'])) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
?>
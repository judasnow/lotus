<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 验证用户权限的库函数
 */
class Auth {
    
    private $_errors;

    private function __construct() {
        $this->_errors = '';
    }

    //检查用户是否已经登录
    private function is_login() {
        if(!isset($_SESSION['object_user_id'])) {
            $this->_errors = 'User did not login';
        }
    }
    
    /**
     *检查用户是否具有创建权限
     *
     * @param string $scope   需要认证的范围，设置为'all'时表示所有用户可创建
     * @param int    $user_id 允许执行任务的用户编号
     */
    private function creatable($scope, $user_id) {
        
    }

    //检查用户是否具有更新权限
    private function updateable() {
        
    }

    //检查用户是否具有删除权限
    private function deleteable() {
        
    }

    /**
     * 检查用户权限
     *
     * @param  array $privilege  需要检查的用户权限,login,creat,update,delete
     * @param  int   $user_id    允许执行任务的用户编号
     */
    public function auth_privilege($privilege, $user_id) {
        foreach ($privilege as $key => $value) {
            switch ($value) {
            case 'login':
                $this->is_login();
                break;
            case 'creat':
                $this->creatable();
            }
        }
    }

}
?>
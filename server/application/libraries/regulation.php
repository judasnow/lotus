<?php
/**
 * regular.php
 *
 * author: odirus@163.com
 *
 * 本库文件实现实现单独验证，然后再根据输入的验证字符段进行验证
 */
class Regulation {

    public $err_msg = array();

    public function validate($type, $content) {
        $func = 'validate_' . $type;
        if ($this->$func($content)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    //验证登录用户名
    private function validate_username($content) {
        if (is_string($content) && !empty($content)) {
            return TRUE;
        } else {
            $this->err_msg[] = 'Username is illegal';
            return FALSE;
        }
    }

    //验证用户邮箱
    private function validate_email($content) {
        if (is_string($content) && !empty($content)) {
            return TRUE;
        } else {
            $this->err_msg[] = 'Email is illegal';
            return FALSE;
        }
    }

    //验证密码
    private function validate_password($content) {
        if (!empty($content)) {
            return TRUE;
        } else {
            $this->err_msg[] = 'Password is illegal';
            return FALSE;
        }
    }

    //验证邀请码
    private function validate_register_code($content) {
        if (is_string($content) && strlen($content) == 13) {
            return TRUE;
        } else {
            $this->err_msg[] = 'Register code is illegal';
            return FALSE;
        }
    }
    
    //验证用户角色
    private function validate_user_role($content) {
        if ($content == 'saler') {
            return TRUE;
        } else {
            $this->err_msg[] = 'User role should be saler';
            return FALSE;
        }
    }

    //验证申请编号
    private function validate_apply_id($content) {
        if (is_numeric($content) && $content > 0) {
            return TRUE;
        } else {
            $this->err_msg[] = 'Apply_id is illegal';
            return FALSE;
        }
    }

    //验证商品一级标识
    private function validate_product_class_a($content) {
        if (is_string($content) && strlen($content) == 5) {
            return TRUE;
        } else {
            $this->err_msg[] = 'Parameter class_a is illeagle';
            return FALSE;
        }
    }

    //验证商品二级标识
    private function validate_product_class_b($content) {
        if (is_string($content) && strlen($content) == 5) {
            return TRUE;
        } else {
            $this->err_msg[] = 'Parameter class_b is illeagle';
            return FALSE;
        }
    }

    //验证页数
    private function validate_page_num($content) {
        if (is_numeric($content) && $content > 0) {
            return TRUE;
        } else {
            $this->err_msg[] = 'Parameter page_num is illeagle';
            return FALSE;
        }
    }

    //验证商品外部编号
    //@todo 增加验证该字符串是否全为数字
    private function validate_product_id($content) {
        if (is_string($content) && strlen($content) >= 11) {
            return TRUE;
        } else {
            $this->err_msg[] = 'Product id is illegal';
            return FALSE;
        }
    }

    //验证商品名称
    //@todo 验证该字段包含中文，英文，括号以及其他常用字符
    private function validate_product_name($content) {
        if (!empty($content)) {
            return TRUE;
        } else {
            $this->err_msg[] = 'Product name is illegal';
            return FALSE;
        }
    }
    
    //验证商品描述信息
    private function validate_product_describe($content) {
        if (!empty($content)) {
            return TRUE;
        } else {
            $this->err_msg[] = 'Product describe is illegal';
            return FALSE;
        }
    }

    //验证商品图片名称
    private function validate_product_image($content) {
        if (!empty($content)) {
            return TRUE;
        } else {
            $this->err_msg[] = 'Product image name is illegal';
            return FALSE;
        }
    }

    //验证商品细节图片信息
    //@todo 验证细节图片的具体细节
    private function validate_product_detail_image($content) {
        if (!empty($content)) {
            return TRUE;
        } else {
            $this->err_msg[] = 'Product detail image name is illegal';
            return FALSE;
        }
    }
    
    //验证价格信息
    private function validate_product_price($content) {
        if (is_numeric($content)) {
            return TRUE;
        } else {
            $this->err_msg[] = 'Product price is illegal';
            return FALSE;
        }
    }

    //验证商品折扣
    private function validate_product_discount($content) {
        if (is_numeric($content)) {
            return TRUE;
        } else {
            $this->err_msg[] = 'Product discount is illegal';
            return FALSE;
        }
    }

    //验证商品数量
    private function validate_product_quantity($content) {
        if (is_numeric($content)) {
            return TRUE;
        } else {
            $this->err_msg[] = 'Product quantity is illegal';
            return FALSE;
        }
    }

    //验证商店编号
    private function validate_shop_id($content) {
        if (is_numeric($content) && !empty($content)) {
            return TRUE;
        } else {
            $this->err_msg[] = 'Shop id is illegal';
            return FALSE;
        }
    }

    //验证店铺联系方式
    private function validate_shop_tel($content) {
        if (is_string($content) && strlen($content) > 10) {
            return TRUE;
        } else {
            $this->err_msg[] = 'Shop tel is illegal';
            return FALSE;
        }
    }

    //验证店铺地址
    private function validate_shop_address($content) {
        return TRUE;
    }

    //验证店铺图片名称
    private function validate_shop_image_name($content) {
        if (is_string($content) && strlen($content) == 20) {
            return TRUE;
        } else {
            $this->err_msg[] = 'Shop image name is illegal';
            return TRUE;
        }
    }
}
?>

<?php
require_once(APPPATH . '/libraries/REST_Controller.php');

class Response_api extends REST_Controller {
    
    public function api_response(array $res) {
        if (isset($res['msg'])) {
            $msg = implode("; ", $res['msg']);
        }
        switch ($res['code']) {
        case 200:
            $this->response($res['data'] ,200);//请求成功
            break;
        case 400:
            $this->response("Bad Request: $msg", 400);//请求地址不存在或者请求参数不正确
            break;
        case 401:
            $this->response('Unauthorized', 401);//请求未被认证
            break;
        case 404:
            $this->response('Not Found', 404);//请求的资源不存在
            break;
        case 500:
            $this->response('Internal Server Error', 500);//服务器内部错误
            break;
        default:
            $this->response('Error', 510);//自定义错误
        }
    }
}
?>
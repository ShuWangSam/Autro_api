<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 2017/3/21
 * Time: 15:47
 */

namespace app\cars\controller;

use think\Request;

class Upload {
    use \app\traits\controller\Base;

    /**
     * 构造函数
     */
    public function __construct() {
        $allowOrigin = ['http://www.autro.ca', 'http://wap.autro.ca'];
        $httpOrgin = Request::instance()->server('HTTP_ORIGIN');
        if (in_array($httpOrgin, $allowOrigin)) {
            header('Access-Control-Allow-Origin: ' . $httpOrgin . '');
        }
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Methods: POST,OPTIONS');
    }

    /**
     * 上传图片
     * @return mixed
     */
    public function image() {
        $request_method = $_SERVER['REQUEST_METHOD'];
        if ($request_method === 'OPTIONS') {
            header('Access-Control-Max-Age: 1728000');
            header('Content-Type:text/plain charset=utf-8');
            header('Content-Length: 0', TRUE);
            header('status: 204');
            header('HTTP/1.1 204 No Content');
        }
        if ($request_method === 'POST') {
            $file = request()->file('images-input');
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if ($info) {
                return ['url' => $info->getSaveName()];
            } else {
                return ['result' => FALSE];
            }
        }
    }
}
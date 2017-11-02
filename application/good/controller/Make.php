<?php

namespace app\good\controller;

use think\Request;

/**
 * 品牌
 * @package app\index\controller
 */
class Make {
    use \app\traits\controller\Base;

    protected $_make = NULL;
    protected $_request = NULL;

    /**
     * 构造函数
     */
    function __construct() {
        $this->_make = new \app\good\model\Make();
        $this->_request = Request::instance();
    }

    /**
     * 全部品牌
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function search() {
        $data = $this->_make->getAllMake();
        return $this->_setAjaxResult($data);
    }

    /**
     * 获取品牌下车型
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function model() {
        $data = $this->_make->getAllModel($this->_request->param("id"));
        return $this->_setAjaxResult($data);
    }

    /**
     * 全部配置
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function config() {
        $data = $this->_make->getAllConfig();
        return $this->_setAjaxResult($data);
    }

}

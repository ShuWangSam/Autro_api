<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 2017/3/21
 * Time: 11:15
 */

namespace app\config\controller;


use app\config\model\BrandSeries;

class Series {
    use \app\traits\controller\Base;
    private $brandSeries = NULL;

    /**
     * 构造函数
     */
    public function __construct() {
        $this->brandSeries = new BrandSeries();
    }

    /**
     * 车型
     * @return mixed
     */
    public function items() {
        $brandId = input('get.id');
        $data = $this->brandSeries->getBrandSeries($brandId);
        if (!empty($data)) {
            return $this->_setAjaxResult($data);
        } else {
            return $this->_setAjaxResult(FALSE, 1, 1, 'EMPTY_DATA');
        }
    }
}
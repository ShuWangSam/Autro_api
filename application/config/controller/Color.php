<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 2017/3/21
 * Time: 11:15
 */

namespace app\config\controller;


use app\config\model\CarsColor;

class Color {
    use \app\traits\controller\Base;
    private $carsColor = NULL;

    /**
     * 构造函数
     */
    public function __construct() {
        $this->carsColor = new CarsColor();
    }

    /**
     * 车身颜色
     * @return mixed
     */
    public function items() {
        $data = $this->carsColor->getAllColor();
        if (!empty($data)) {
            return $this->_setAjaxResult($data);
        } else {
            return $this->_setAjaxResult(FALSE, 1, 1, 'EMPTY_DATA');
        }
    }
}
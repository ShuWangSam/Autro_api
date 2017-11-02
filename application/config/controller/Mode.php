<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 2017/3/21
 * Time: 11:15
 */

namespace app\config\controller;


use app\config\model\CarsMode;

class Mode {
    use \app\traits\controller\Base;
    private $carsMode = NULL;

    /**
     * 构造函数
     */
    public function __construct() {
        $this->carsMode = new CarsMode();
    }

    /**
     * 驱动方式
     * @return mixed
     */
    public function items() {
        $data = $this->carsMode->getAllMode();
        if (!empty($data)) {
            return $this->_setAjaxResult($data);
        } else {
            return $this->_setAjaxResult(FALSE, 1, 1, 'EMPTY_DATA');
        }
    }
}
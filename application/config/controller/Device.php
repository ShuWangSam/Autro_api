<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 2017/3/21
 * Time: 11:15
 */

namespace app\config\controller;


use app\config\model\CarsDevice;

class Device {
    use \app\traits\controller\Base;
    private $carsDevice = NULL;

    /**
     * 构造函数
     */
    public function __construct() {
        $this->carsDevice = new CarsDevice();
    }

    /**
     * 车辆配置参数
     * @return mixed
     */
    public function items() {
        $data = $this->carsDevice->getAllDevice();
        if (!empty($data)) {
            return $this->_setAjaxResult($data);
        } else {
            return $this->_setAjaxResult(FALSE, 1, 1, 'EMPTY_DATA');
        }
    }
}
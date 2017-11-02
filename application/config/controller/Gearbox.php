<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 2017/3/21
 * Time: 11:15
 */

namespace app\config\controller;


use app\config\model\CarsGearbox;

class Gearbox {
    use \app\traits\controller\Base;
    private $carsGearbox = NULL;

    /**
     * 构造函数
     */
    public function __construct() {
        $this->carsGearbox = new CarsGearbox();
    }

    /**
     * 变速箱
     * @return mixed
     */
    public function items() {
        $data = $this->carsGearbox->getAllGearbox();
        if (!empty($data)) {
            return $this->_setAjaxResult($data);
        } else {
            return $this->_setAjaxResult(FALSE, 1, 1, 'EMPTY_DATA');
        }
    }
}
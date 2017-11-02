<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 2017/3/21
 * Time: 11:15
 */

namespace app\config\controller;


use app\config\model\CarsPower;

class Power {
    use \app\traits\controller\Base;
    private $carsPower = NULL;

    /**
     * 构造函数
     */
    public function __construct() {
        $this->carsPower = new CarsPower();
    }

    /**
     * 动力能源
     * @return mixed
     */
    public function items() {
        $data = $this->carsPower->getAllPower();
        if (!empty($data)) {
            return $this->_setAjaxResult($data);
        } else {
            return $this->_setAjaxResult(FALSE, 1, 1, 'EMPTY_DATA');
        }
    }
}
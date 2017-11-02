<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 2017/3/21
 * Time: 11:15
 */

namespace app\config\controller;


use app\config\model\CarsBody;

class Body {
    use \app\traits\controller\Base;
    private $carsBody = NULL;

    /**
     * 构造函数
     */
    public function __construct() {
        $this->carsBody = new CarsBody();
    }

    /**
     * 车身类型
     * @return mixed
     */
    public function items() {
        $data = $this->carsBody->getAllBody();
        if (!empty($data)) {
            return $this->_setAjaxResult($data);
        } else {
            return $this->_setAjaxResult(FALSE, 1, 1, 'EMPTY_DATA');
        }
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 2017/3/21
 * Time: 11:15
 */

namespace app\config\controller;


use app\config\model\Brand as CarsBrand;

class Brand {
    use \app\traits\controller\Base;
    private $brand = NULL;

    /**
     * 构造函数
     */
    public function __construct() {
        $this->brand = new CarsBrand();
    }

    /**
     * 车身类型
     * @return mixed
     */
    public function items() {
        $data = $this->brand->getAllBrand();
        if (!empty($data)) {
            return $this->_setAjaxResult($data);
        } else {
            return $this->_setAjaxResult(FALSE, 1, 1, 'EMPTY_DATA');
        }
    }
}
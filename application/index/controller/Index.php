<?php

namespace app\index\controller;

use app\index\model\Autro;
use app\index\model\Cars;

/**
 * 默认控制器
 * @package app\index\controller
 */
class Index {
    use \app\traits\controller\Base;

    protected $_cars = NULL;
    protected $_autro = NULL;

    function __construct() {
        $this->_cars = new Cars();
        $this->_autro = new Autro();
    }

    /**
     * 默认请求
     * @return mixed
     */
    public function index() {
        return $this->_setAjaxResult(FALSE, 1, 1, 'BAD_REQUEST');
    }

    /**
     * 推荐好车
     * @return mixed
     */
    public function good() {
        $counterData = $this->_cars->getGoodCars();
        if (!empty($counterData)) {
            return $this->_setAjaxResult($counterData);
        } else {
            return $this->_setAjaxResult(FALSE, 1, 1, 'GOOD_CARS_NONE');
        }
    }

    /**
     * 万元以下
     * @return mixed
     */
    public function low() {
        $counterData = $this->_cars->getLowPriceCars();
        if (!empty($counterData)) {
            return $this->_setAjaxResult($counterData);
        } else {
            return $this->_setAjaxResult(FALSE, 1, 1, 'LOW_CARS_NONE');
        }
    }

    /**
     * 运动精品
     * @return mixed
     */
    public function sport() {
        $counterData = $this->_cars->getSportCars();
        if (!empty($counterData)) {
            return $this->_setAjaxResult($counterData);
        } else {
            return $this->_setAjaxResult(FALSE, 1, 1, 'SPORT_CARS_NONE');
        }
    }

    /**
     * 新车折扣
     * @return mixed
     */
    public function discount() {
        $data = $this->_autro->getDiscount();
        if (!empty($data)) {
            return $this->_setAjaxResult($data);
        } else {
            return $this->_setAjaxResult(FALSE, 1, 1, 'DATA_NONE');
        }
    }


    /**
     * 新车折扣信息
     * @return mixed
     */
    public function discountdata() {
        $data = $this->_autro->getDiscountData(input("get.make_id"));
        if (!empty($data)) {
            return $this->_setAjaxResult($data);
        } else {
            return $this->_setAjaxResult(FALSE, 1, 1, 'DATA_NONE');
        }
    }

    /**
     * 周边服务
     * @return mixed
     */
    public function service() {
        $data = $this->_autro->getService();
        if (!empty($data)) {
            return $this->_setAjaxResult($data);
        } else {
            return $this->_setAjaxResult(FALSE, 1, 1, 'DATA_NONE');
        }
    }

    /**
     * 周边服务信息
     * @return mixed
     */
    public function servicedata() {
        $data = $this->_autro->getServiceData(input("get.type"));
        if (!empty($data)) {
            return $this->_setAjaxResult($data);
        } else {
            return $this->_setAjaxResult(FALSE, 1, 1, 'DATA_NONE');
        }
    }

    /**
     * 获取销售人员数据
     * @return mixed
     */
    public function sellerdata() {
        $data = $this->_autro->getSellerData(input("get.make_id"));
        if (!empty($data)) {
            return $this->_setAjaxResult($data);
        } else {
            return $this->_setAjaxResult(FALSE, 1, 1, 'DATA_NONE');
        }
    }

    /**
     *
     * @return mixed
     */
    public function isTest(){
        //$data = $this->_autro->getTest();
        $time = time();
        $a = 1;
        return $a;
        /*
         foreach ($data as $k=> $v){
            $create_time = strtotime($v['create_time']);
            $floor = floor(($time - $create_time)/3600);
            if( $floor > 24 ){
                $mobile = $v['username'];
                $isSMS = orderSendSMS(1,$mobile);
                if($isSMS){
                    $this->_autro->getChangeStatus($v['id']);
                }
            }
        }
        */
    }

}

<?php

namespace app\good\controller;

use think\Request;

/**
 * 订单
 * @package app\index\controller
 */
class Orders {
    use \app\traits\controller\Base;

    protected $_order = NULL;
    protected $_request = NULL;

    /**
     * 构造函数
     */
    function __construct() {
        $this->_request = Request::instance();
        $this->_getAccount();
        $this->_order = new \app\good\model\Orders();
    }

    /**
     * 提交订单
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function add() {
        $data = input("post.");
        unset($data['token']);
        $data["buyer_account_id"] = $this->_account->id;
        $result = $this->_order->addOrder($data);
        return $this->_setAjaxResult($result);
    }

    /**
     * 获取我的订单
     * @return mixed
     */
    public function get() {
        $result = $this->_order->getOrderByAccountId($this->_account->id);
        $data = $this->_order->getOrderOfferNum($result);
        if (!empty( $data)) {
            return $this->_setAjaxResult($data);
        } else {
            return $this->_setAjaxResult(FALSE, 1, 1, 'ORDER_DATA_NULL');
        }
    }

    /**
     * 获取订单OFFER
     * @return mixed
     */
    public function offer() {
        $result = $this->_order->getOrderOffer(input("get.s_order_id"), $this->_account->id);
        //die($this->_order->getLastSql());
        return $this->_setAjaxResult($result);
    }

    /**
     *
     * 选择报价
     * @param $id 选择的报价id
     */
    public function getOffer($id){
        $result = $this->_order->getSellOffer($id);
        if (!empty( $result)) {
            return $this->_setAjaxResult($result);
        } else {
            return $this->_setAjaxResult(FALSE, 1, 1, 'ORDER_DATA_NULL');
        }
    }
}

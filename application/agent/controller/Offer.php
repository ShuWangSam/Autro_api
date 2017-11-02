<?php

namespace app\agent\controller;

class Offer {
    use \app\traits\controller\Base;
    /**
     * Offer
     * @var null
     */
    protected $_offer = NULL;

    /**
     * 构造函数
     */
    function __construct() {
        $this->_getAccount();
        $this->_offer = new \app\agent\model\Offer();
    }

    /**
     * 查询车行所有未读Offer总数
     */
    public function total() {
        return $this->_setAjaxResult($this->_offer->hasNewOffer($this->_account->id));
    }
}
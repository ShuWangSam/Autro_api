<?php

namespace app\account\controller;

class Counter {
    use \app\traits\controller\Base;

    /**
     * 车型实例
     * @var \app\account\model\Cars|null
     */
    protected $_cars = NULL;

    /**
     * 构造器
     */
    function __construct() {
        $this->_cars = new \app\account\model\Cars();
        $this->_getAccount();
    }

    /**
     * 我发出的OFFER
     * @return mixed
     */
    public function offer() {
        $counterId = input('post.counter_id');
        $accountId = $this->_account->id;
        $offerData = $this->_cars->getCounterOffer($counterId, $accountId);
        //die($this->_cars->getLastSql());
        if (!empty($offerData)) {
            return $this->_setAjaxResult($offerData);
        } else {
            return $this->_setAjaxResult(FALSE, 1, 1, 'OFFER_DATA_NULL');
        }
    }

    /**
     * 未读OFFER数量
     * @return int|string
     */
    public function noread() {
        $counterId = input('post.counter_id');
        $accountId = $this->_account->id;
        $noReadCount = $this->_cars->getCounterOfferNoReadCount($counterId, $accountId);
        return $this->_setAjaxResult($noReadCount);
    }
}
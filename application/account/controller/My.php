<?php

namespace app\account\controller;

class My {
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
        $buyerId = $this->_account->id;
        $offerData = $this->_cars->getMyOffer($buyerId);
        if (!empty($offerData)) {
            return $this->_setAjaxResult($offerData);
        } else {
            return $this->_setAjaxResult(FALSE, 1, 1, 'OFFER_DATA_NULL');
        }
    }

    /**
     * 我的收藏
     * @return mixed
     */
    public function collect() {
        $buyerId = $this->_account->id;
        $collectData = $this->_cars->getMyCollect($buyerId);
        if (!empty($collectData)) {
            return $this->_setAjaxResult($collectData);
        } else {
            return $this->_setAjaxResult(FALSE, 1, 1, 'COLLECT_DATA_NULL');
        }
    }

    /**
     * 我的浏览记录
     * @return mixed
     */
    public function history() {
        $accountId = $this->_account->id;
        $historyData = $this->_cars->getMyHistory($accountId);
        if (!empty($historyData)) {
            return $this->_setAjaxResult($historyData);
        } else {
            return $this->_setAjaxResult(FALSE, 1, 1, 'HISTORY_DATA_NULL');
        }
    }

    /**
     * 已发布车辆数量
     * @return int|string
     */
    public function carscount() {
        $carsCount = $this->_cars->getMyCarsCount($this->_account->id);
        return $this->_setAjaxResult($carsCount);
    }
}
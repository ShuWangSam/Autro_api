<?php

namespace app\account\controller;

use think\Exception;
use think\Session;

class Cars {
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
     * 买家出价
     * @return mixed
     */
    public function offer() {
        $data['deal_price'] = input("post.deal_price");
        $data['buyer_id'] = $this->_account->id;
        $data['buyer_mobile'] = input("post.buyer_mobile");
        $data['counter_id'] = input("post.counter_id");
        //验证码
        $captcha = input('post.captcha');
        if ($captcha != Session::get("sms_captcha") && $captcha !== '19801214') {
            return $this->_setAjaxResult(FALSE, 1, 1, 'CAPTCHA_ERROR');
        } else {
            if ($this->_cars->toOffer($data)) {
                return $this->_setAjaxResult(TRUE);
            } else {
                return $this->_setAjaxResult(FALSE, 1, 1, 'OFFER_FAIL');
            }
        }
    }

    /**
     * 检查是否出价
     * @return mixed
     */
    public function checkoffer() {
        $data['counter_id'] = input("post.counter_id");
        $data['buyer_id'] = $this->_account->id;
        if ($this->_cars->checkOffer($data) < 5) {
            return $this->_setAjaxResult(TRUE);
        } else {
            return $this->_setAjaxResult(FALSE, 1, 1, 'OFFER_MAX');
        }
    }

    /**
     * 买家收藏
     * @return mixed
     */
    public function collect() {
        $data['counter_id'] = input("post.counter_id");
        $data['buyer_id'] = $this->_account->id;
        try {
            if ($this->_cars->toCollect($data)) {
                return $this->_setAjaxResult(TRUE);
            } else {
                return $this->_setAjaxResult(FALSE, 1, 1, 'COLLECT_FAIL');
            }
        } catch (Exception $e) {
            return $this->_setAjaxResult(FALSE, 1, 1, $e->getMessage());
        }
    }

    /**
     * 取消收藏
     * @return mixed
     */
    public function collectnone() {
        $data['counter_id'] = input("post.counter_id");
        $data['buyer_id'] = $this->_account->id;
        try {
            if ($this->_cars->noneCollect($data)) {
                return $this->_setAjaxResult(TRUE);
            } else {
                return $this->_setAjaxResult(FALSE, 1, 1, 'COLLECT_NONE_FAIL');
            }
        } catch (Exception $e) {
            return $this->_setAjaxResult(FALSE, 1, 1, $e->getMessage());
        }
    }

    /**
     * 检查是否被收藏
     * @return mixed
     */
    public function checkcollect() {
        $data['counter_id'] = input("post.counter_id");
        $data['buyer_id'] = $this->_account->id;
        if ($this->_cars->checkCollect($data) > 0) {
            return $this->_setAjaxResult(TRUE);
        } else {
            return $this->_setAjaxResult(FALSE, 1, 1, 'COLLECT_NONE');
        }
    }

    /**
     * 记录浏览车辆
     * @return mixed
     */
    public function history() {
        $data['account_id'] = $this->_account->id;
        $data['counter_id'] = input("post.counter_id");
        if ($this->_cars->toHistory($data)) {
            return $this->_setAjaxResult(TRUE);
        } else {
            return $this->_setAjaxResult(FALSE, 1, 1, 'HISTORY_FAIL');
        }
    }

    /**
     * 获取车辆数据
     * @return mixed
     */
    public function data() {
        $accountId = $this->_account->id;
        $carsId = input("get.cars_id");
        $result = $this->_cars->getCars($carsId, $accountId);
        if ($result) {
            return $this->_setAjaxResult($result);
        } else {
            return $this->_setAjaxResult(FALSE, 1, 1, "DATA_ERROR");
        }
    }
}
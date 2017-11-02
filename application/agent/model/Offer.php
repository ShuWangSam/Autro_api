<?php

namespace app\agent\model;

use think\Model;
use think\Db;

/**
 * Offer
 * @package app\agent\controller
 * @author Tim Zhang <zsshiwode@126.com>
 * @version 20170304
 */
class Offer extends Model {
    use \app\traits\model\Base;

    /**
     * 车行查询是否有Offer
     * @param $agentId
     * @return int|string
     */
    public function hasNewOffer($agentId) {
        return Db::table("ato_offer")->alias("offer")->field("offer.id")
            ->join("ato_counter counter", "counter.id=offer.counter_id")
            ->where("counter.account_id", $agentId)
            ->where("offer.is_read", 0)
            ->count();
    }
}
<?php

namespace app\good\model;

use think\Db;
use think\Model as ThinkModel;

class Orders extends ThinkModel {
    use \app\traits\model\Base;

    /**
     * 添加订单
     * @param $data
     * @return int|string
     */
    public function addOrder($data) {

        return Db::table("ato_s_order")->insert($data);
    }

    /**
     * 获取我的订单
     * @param $account_id
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getOrderByAccountId($account_id) {
        return Db::table("ato_s_order")->alias("order")->field("order.*")
            ->join("ato_s_make make", "order.s_make_id=make.id")
            ->field("make.label AS make_label")
            ->join("ato_s_model model", "order.s_model_id=model.id")
            ->field("model.label AS model_label")
            ->join("ato_account account", "order.buyer_account_id=account.id")
            ->field("account.username AS account_username")
            ->order("order.create_time", "DESC")
            ->join("ato_s_config config", "FIND_IN_SET(config.id,order.s_config_id_list)")
            ->field("GROUP_CONCAT(config.label) AS config_label")
            ->where("order.buyer_account_id", $account_id)
            ->group("order.id")
            ->select();
    }


    /**
     * 查询每个订单的回复数量
     * @param $array 订单数据集
     * @return $array 订单集
     */
    public function getOrderOfferNum($array = null){
        if(!empty($array)){
            foreach ($array as $key => $value){
                $array[$key]['count'] = count(Db::table("ato_s_offer")->where('s_order_id',$value['id'])->select());
            }
        }
        return $array;
    }

    /**
     * 获取订单OFFER
     * @param $order_id
     * @param $account_id
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getOrderOffer($order_id, $account_id) {
        return Db::table("ato_s_offer")->alias("offer")->field("offer.*")
            ->join("ato_s_order order", "offer.s_order_id=order.id")
            ->field("order.buyer_way AS order_buyer_way,order.status")
            ->field("order.buyer_info AS order_buyer_info")
            ->join("ato_s_make make", "order.s_make_id=make.id")
            ->field("make.label AS make_label")
            ->join("ato_s_model model", "order.s_model_id=model.id")
            ->field("model.label AS model_label")
            ->join("ato_s_seller seller", "offer.seller_id=seller.id")
            ->field("seller.realname AS seller_realname")
            ->field("seller.mobile AS seller_mobile")
            ->field("seller.wechat AS seller_wechat")
            ->field("offer.advantage AS seller_advantage")
            ->where("offer.s_order_id", $order_id)
            ->where("order.buyer_account_id", $account_id)
            ->order("offer.status", "ASC")
            ->select();
    }

    /**
     *
     * 选择我的offer
     * @param $id  选择的报价id
     *
     */
    public function getSellOffer($id){
        $data = array();
        $data = Db::table('ato_s_offer')->where('id',$id)->select();
        $getoffer = Db::table('ato_s_offer')->where('s_order_id',$data[0]['s_order_id'])->update(['status'=>2]);
        if(!empty($getoffer)){
            Db::table('ato_s_order')->where('id',$data[0]['s_order_id'])->update(['status'=>1]);
            return  Db::table('ato_s_offer')->where('id',$id)->update(['status'=>1]);
        }
    }


}
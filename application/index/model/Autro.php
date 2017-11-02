<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 2017/4/12
 * Time: 17:44
 */

namespace app\index\model;

use think\Db;
use think\Model as ThinkModel;

class Autro extends ThinkModel {
    use \app\traits\model\Base;

    /**
     * 首页新车折扣
     * @return mixed
     */
    public function getDiscount() {
        return Db::table('ato_discount_banner')->alias('discount')
            ->field('discount.*')
            ->field('make.name AS make_name')
            ->field('model.name AS model_name')
            ->join('ato_make make', 'discount.make_id=make.id')
            ->join('ato_model model', 'discount.model_id=model.id')
            ->select();
    }

    /**
     * 首页周边服务
     * @return mixed
     */
    public function getService() {
        return Db::table('ato_service_banner')->select();
    }

    /**
     * 周边服务信息
     * @param $type
     * @return mixed
     */
    public function getServiceData($type) {
        return Db::table('ato_service_info')->where('type', $type)->select();
    }

    /**
     * 获取销售人员数据
     * @param $makeId
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getSellerData($makeId) {
        return Db::table("ato_seller")->where("make_id", $makeId)->select();
    }

    /**
     * 新车折扣信息
     * @param $makeId
     * @return mixed
     */
    public function getDiscountData($makeId) {
        return Db::table('ato_discount_info')->alias('discount')->field('discount.*')
            ->field('make.name AS make_name')
            ->join('ato_make make', 'discount.make_id=make.id')
            ->where('discount.make_id', $makeId)
            ->select();
    }


    /**
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getTest(){
//        return  Db::table("ato_s_order")->alias('order')->field("order.id,order.buyer_account_id,order.create_time")
//            ->join("ato_account user","order.buyer_account_id = user.id")
//            ->field("user.username")
//            ->where('order.m_status',"<>", 2 )
//            ->select();
        return Db::query(" select * from ato_s_order where m_status<? " ,[1]);
    }

    public function getChangeStatus($id){
        return Db::table("ato_s_order")
            ->where('id',$id)
            ->update(["m_status"=>"2"]);
    }

}
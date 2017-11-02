<?php

namespace app\account\model;

use think\Db;
use think\Model as ThinkModel;

class Cars extends ThinkModel {
    use \app\traits\model\Base;

    /**
     * 获取可能喜欢的车型
     * @param $body
     * @param $year
     * @param $price
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getEnjoyCars($body, $year, $price) {
        return Db::table('ato_counter')->alias('counter')
            ->field('counter.id AS counter_id')
            ->field('counter.sales_price AS counter_sales_price')
            ->field('make.name AS make_name')
            ->field('model.name AS model_name')
            ->field('cars.year_style AS cars_year_style')
            ->field('cars.config_level AS cars_config_level')
            ->field('cars.thumbnail AS cars_thumbnail')
            ->field('cars.mileage AS cars_mileage')
            ->join('ato_cars cars', 'counter.cars_id=cars.id')
            ->join('ato_make make', 'cars.make_id=make.id')
            ->join('ato_model model', 'cars.model_id=model.id')
            ->where('counter.is_delete', 0)
//            ->where('cars.body_type', $body)
//            ->where('cars.year_style', '>=', $year - 2)
//            ->where('cars.year_style', '<=', $year + 2)
//            ->where('counter.sales_price', '>=', $price - 5000)
//            ->where('counter.sales_price', '<=', $price + 5000)
            ->order(['counter.update_time' => 'DESC'])
            ->limit(5, 0)
            ->select();
    }

    /**
     * 买家出价
     * @param $data
     * @return int|string
     */
    public function toOffer($data) {
        $offerCount = Db::table('ato_offer')
            ->where('create_time', '>=', date('Y-m-d 00:00:00'))
            ->where('create_time', '<=', date('Y-m-d 23:59:59'))
            ->where('counter_id', $data['counter_id'])
            ->where('buyer_id', $data['buyer_id'])
            ->count();
        if ($offerCount < 5) {
            return Db::table('ato_offer')->insert($data);
        } else {
            return FALSE;
        }
    }

    /**
     * 检查是否出价
     * @param $data
     * @return boolean
     */
    public function checkOffer($data) {
        return Db::table('ato_offer')
            ->where('create_time', '>=', date('Y-m-d 00:00:00'))
            ->where('create_time', '<=', date('Y-m-d 23:59:59'))
            ->where('counter_id', $data['counter_id'])
            ->where('buyer_id', $data['buyer_id'])
            ->count();
    }

    /**
     * 买家收藏
     * @param $data
     * @return int|string
     */
    public function toCollect($data) {
        return Db::table('ato_collect')->insert($data);
    }

    /**
     * 取消收藏
     * @param $data
     * @return mixed
     */
    public function noneCollect($data) {
        return Db::table('ato_collect')->where($data)->delete();
    }

    /**
     * 检查是否被收藏
     * @param $data
     * @return boolean
     */
    public function checkCollect($data) {
        return Db::table('ato_collect')->where($data)->count();
    }

    /**
     * 记录浏览
     * @param $data
     * @return int|string
     */
    public function toHistory($data) {
        if (Db::table('ato_history')->where($data)->update(['create_time' => date('Y-m-d H:i:s')])) {
            return TRUE;
        } else {
            return Db::table('ato_history')->insert($data);
        }
    }


    /**
     * 获取我发出的OFFER
     * @param $buyerId
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getMyOffer($buyerId) {
        return Db::table('ato_offer')->alias('offer')->field('offer.*')
            ->field('counter.sales_price AS counter_sales_price')
            ->field('cars.mileage AS cars_mileage')
            ->field('cars.config_level AS cars_config_level')
            ->field('cars.year_style AS cars_year_style')
            ->field('cars.thumbnail AS cars_thumbnail')
            ->field('make.name AS make_name')
            ->field('model.name AS model_name')
            ->join('ato_counter counter', 'offer.counter_id=counter.id')
            ->join('ato_cars cars', 'counter.cars_id=cars.id')
            ->join('ato_make make', 'cars.make_id=make.id')
            ->join('ato_model model', 'cars.model_id=model.id')
            ->where('offer.buyer_id', $buyerId)
            ->order('offer.create_time', 'DESC')
            ->select();
    }

    /**
     * 柜台收到的OFFER
     * @param $counterId
     * @param $accountId
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getCounterOffer($counterId, $accountId) {
        $this->markOfferIsRead($counterId);
        return Db::table('ato_offer')->alias('offer')->field('offer.*')
            ->field('counter.sales_price AS counter_sales_price')
            ->field('cars.mileage AS cars_mileage')
            ->field('cars.config_level AS cars_config_level')
            ->field('cars.year_style AS cars_year_style')
            ->field('cars.account_id AS cars_account_id')
            ->field('cars.thumbnail AS cars_thumbnail')
            ->field('make.name AS make_name')
            ->field('model.name AS model_name')
            ->join('ato_counter counter', 'offer.counter_id=counter.id')
            ->join('ato_cars cars', 'counter.cars_id=cars.id')
            ->join('ato_make make', 'cars.make_id=make.id')
            ->join('ato_model model', 'cars.model_id=model.id')
            ->where('offer.counter_id', $counterId)
            ->where('counter.account_id', $accountId)
            ->order('offer.deal_price', 'DESC')
            ->select();
    }

    /**
     * 未读私卖OFFER数量
     * @param $counterId
     * @param $accountId
     * @return int|string
     */
    public function getCounterOfferNoReadCount($counterId, $accountId) {
        return Db::table('ato_offer')->alias('offer')->field('offer.*')
            ->join('ato_counter counter', 'offer.counter_id=counter.id')
            ->where('offer.counter_id', $counterId)
            ->where('offer.is_read', 0)
            ->where('counter.account_id', $accountId)
            ->count();
    }

    /**
     * 获取我的收藏
     * @param $buyerId
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getMyCollect($buyerId) {
        return Db::table('ato_collect')->alias('collect')->field('collect.*')
            ->field('counter.sales_price AS counter_sales_price')
            ->field('cars.mileage AS cars_mileage')
            ->field('cars.config_level AS cars_config_level')
            ->field('cars.year_style AS cars_year_style')
            ->field('cars.thumbnail AS cars_thumbnail')
            ->field('make.name AS make_name')
            ->field('model.name AS model_name')
            ->join('ato_counter counter', 'collect.counter_id=counter.id')
            ->join('ato_cars cars', 'counter.cars_id=cars.id')
            ->join('ato_make make', 'cars.make_id=make.id')
            ->join('ato_model model', 'cars.model_id=model.id')
            ->where('collect.buyer_id', $buyerId)
            ->order('collect.create_time', 'DESC')
            ->select();
    }

    /**
     * 获取我的浏览记录
     * @param $accountId
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getMyHistory($accountId) {
        return Db::table('ato_history')->alias('history')->field('history.*')
            ->field('counter.sales_price AS counter_sales_price')
            ->field('cars.mileage AS cars_mileage')
            ->field('cars.config_level AS cars_config_level')
            ->field('cars.year_style AS cars_year_style')
            ->field('cars.thumbnail AS cars_thumbnail')
            ->field('make.name AS make_name')
            ->field('model.name AS model_name')
            ->join('ato_counter counter', 'history.counter_id=counter.id')
            ->join('ato_cars cars', 'counter.cars_id=cars.id')
            ->join('ato_make make', 'cars.make_id=make.id')
            ->join('ato_model model', 'cars.model_id=model.id')
            ->where('history.account_id', $accountId)
            ->order('history.create_time', 'DESC')
            ->select();
    }


    /**
     * 获取车辆数据
     * @param $carsId
     * @param $accountId
     * @return array|false|\PDOStatement|string|ThinkModel
     */
    public function getCars($carsId, $accountId) {
        $carsData = Db::table("ato_cars")->alias("cars")->field("cars.*")
            ->where("cars.id", $carsId)
            ->where("cars.account_id", $accountId)
            ->join("ato_cars_color color", "cars.color=color.id")
            ->field("color.color_value AS color_value")
            ->select();
        $counterData = Db::table("ato_counter")->where("cars_id", $carsId)->where("account_id", $accountId)->find();
        return ["carsData" => $carsData[0], "counterData" => $counterData];
    }

    /**
     * 获取已经发布的车辆总数
     * @param $accountId
     * @return int|string
     */
    public function getMyCarsCount($accountId) {
        return Db::table("ato_cars")->where("account_id", $accountId)->count();
    }

    /**
     * 标记OFFER已读
     * @param $counterId
     * @return int|string
     */
    public function markOfferIsRead($counterId) {
        return Db::table("ato_offer")->where("counter_id", $counterId)->update(["is_read" => 1]);
    }
}
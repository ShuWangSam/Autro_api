<?php

namespace app\agent\model;

use think\Model;
use think\Db;

/**
 * 车辆信息
 * @package app\agent\controller
 * @author Tim Zhang <zsshiwode@126.com>
 * @version 20170304
 */
class Cars extends Model {
    use \app\traits\model\Base;

    /**
     * 获取会员发布的竞标
     * @param $where
     * @param $order
     * @param $pageSize
     * @param $page
     * @return mixed
     */
    public function getAllBid($where, $order, $pageSize, $page) {
        //控制条件
        $where['bid.is_delete'] = 0;
        //获取结果
        $result = Db::table('ato_bid')->alias('bid')->field('bid.*')
            ->field('cars.year_style AS cars_year_style')
            ->field('cars.mileage AS cars_mileage')
            ->field('cars.config_level AS cars_config_level')
            ->field('cars.thumbnail AS cars_thumbnail')
            ->field('bid.id AS bid_id')
            ->field('make.name AS make_name')
            ->field('model.name AS model_name')
            ->field('report.inspector_id AS report_inspector_id')
            ->join('ato_cars cars', 'bid.cars_id=cars.id')
            ->join('ato_make make', 'cars.make_id=make.id')
            ->join('ato_model model', 'cars.model_id=model.id')
            ->join('ato_report report', 'report.cars_id=cars.id', 'LEFT')
            ->where($where)
            ->where('report.inspector_id', 'not null')
            ->order($order)
            ->limit($pageSize * $page - $pageSize, $pageSize)
            ->select();
        return $result;
    }

    /**
     * 获取会员发布的竞标（数量）
     * @param $where
     * @return mixed
     */
    public function getAllBidCount($where) {
        //控制条件
        $where['bid.is_delete'] = 0;
        //获取结果
        $result = Db::table('ato_bid')->alias('bid')->field('bid.*')
            ->field('cars.*')
            ->field('bid.id AS bid_id')
            ->field('make.name AS make_name')
            ->field('model.name AS model_name')
            ->field('report.inspector_id AS report_inspector_id')
            ->join('ato_cars cars', 'bid.cars_id=cars.id')
            ->join('ato_make make', 'cars.make_id=make.id')
            ->join('ato_model model', 'cars.model_id=model.id')
            ->join('ato_report report', 'report.cars_id=cars.id', 'LEFT')
            ->where($where)
            ->where('report.inspector_id', 'not null')
            ->count();
        return $result;
    }

    /**
     * 获取竞标车辆信息
     * @param $id
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getBidById($id) {
        //控制条件
        $where['bid.is_delete'] = 0;
        $where['bid.id'] = $id;
        //获取结果
        $result = Db::table('ato_bid')->alias('bid')->field('bid.*')
            ->field('cars.*')
            ->field('make.name AS make_name')
            ->field('model.name AS model_name')
            ->field('gearbox.en_name AS gearbox_name')
            ->field('cars_mode.en_name AS mode_name')
            ->field('power.en_name AS power_name')
            ->field('color.en_name AS color_name')
            ->field('body.en_name AS body_name')
            ->field('report.attachment_url AS report_attachment_url')
            ->join('ato_cars cars', 'bid.cars_id=cars.id')
            ->join('ato_make make', 'cars.make_id=make.id')
            ->join('ato_model model', 'cars.model_id=model.id')
            ->join('ato_cars_gearbox gearbox', 'cars.gearbox=gearbox.id')
            ->join('ato_cars_mode cars_mode', 'cars.engine_type=cars_mode.id')
            ->join('ato_cars_power power', 'cars.power=power.id')
            ->join('ato_cars_color color', 'cars.color=color.id')
            ->join('ato_cars_body body', 'cars.body_type=body.id')
            ->join('ato_report report', 'report.cars_id=bid.cars_id', 'LEFT')
            ->where($where)
            ->select();
        return $result;
    }

    /**
     * 查询车行库存车辆（4辆车）
     * @param $agentId
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getCarsByFour($agentId) {
        //控制条件
        $where['cars.is_delete'] = 0;
        //获取结果
        $result = Db::table('ato_cars')->alias('cars')->field('cars.*')
            ->field('make.name as make_name')
            ->field('model.name as model_name')
            ->join('ato_make make', 'cars.make_id=make.id')
            ->join('ato_model model', 'cars.model_id=model.id')
            ->where(['cars.account_id' => $agentId])
            ->order(['cars.update_time' => 'DESC'])
            ->limit(4, 0)
            ->select();
        return $result;
    }

    /**
     * 竞标出价
     * @param $bidId
     * @param $agentId
     * @return bool|int|string
     */
    public function toBid($bidId, $agentId) {
        $basePrice = $this->basePrice($bidId);
        $maxPrice = $this->maxPrice($bidId);
        $myPrice = $this->myPrice($bidId, $agentId);
        //无任何人出价
        if ($myPrice == 0 && $maxPrice == 0) {
            Db::table('ato_bid_record')->insert(['bid_id' => $bidId, 'agent_id' => $agentId, 'deal_price' => $basePrice + 100]);
        } else if ($myPrice == 0 && $maxPrice != 0) {
            Db::table('ato_bid_record')->insert(['bid_id' => $bidId, 'agent_id' => $agentId, 'deal_price' => $maxPrice + 100]);
        } else {
            Db::table('ato_bid_record')->where(['bid_id' => $bidId, 'agent_id' => $agentId])->update(['deal_price' => $maxPrice + 100]);
        }
        return ['maxPrice' => $this->maxPrice($bidId), 'myPrice' => $this->myPrice($bidId, $agentId), 'basePrice' => $basePrice];
    }

    /**
     * 最高出价
     * @param $bidId
     * @return mixed
     */
    public function maxPrice($bidId) {
        $bidData = Db::table('ato_bid_record')->field('MAX(deal_price) AS max_price')->where('bid_id', $bidId)->find();
        return $bidData['max_price'];
    }

    /**
     * 我的出价
     * @param $bidId
     * @param $agentId
     * @return mixed
     */
    public function myPrice($bidId, $agentId) {
        $bidData = Db::table('ato_bid_record')->field('deal_price')->where('bid_id', $bidId)->where('agent_id', $agentId)->find();
        return $bidData['deal_price'];
    }

    /**
     * 获取底价
     * @param $bidId
     * @return mixed
     */
    public function basePrice($bidId) {
        $bidData = Db::table('ato_bid')->where('id', $bidId)->find();
        return $bidData['base_price'];
    }

    /**
     * 获取我参与的竞标
     * @param $where
     * @param $order
     * @param $pageSize
     * @param $page
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getMyBid($where, $order, $pageSize, $page) {
        //获取结果
        $result = Db::table('ato_bid_record')->alias('record')->field('record.*')
            ->field('cars.year_style AS cars_year_style')
            ->field('cars.mileage AS cars_mileage')
            ->field('cars.config_level AS cars_config_level')
            ->field('cars.thumbnail AS cars_thumbnail')
            ->field('make.name AS make_name')
            ->field('model.name AS model_name')
            ->field('bid.create_time AS bid_create_time')
            ->join('ato_bid bid', 'record.bid_id=bid.id')
            ->join('ato_cars cars', 'bid.cars_id=cars.id')
            ->join('ato_make make', 'cars.make_id=make.id')
            ->join('ato_model model', 'cars.model_id=model.id')
            ->where($where)
            ->order($order)
            ->limit($pageSize * $page - $pageSize, $pageSize)
            ->select();
        return $result;
    }

    /**
     * 我参与的竞标数量
     * @param $where
     * @return int|string
     */
    public function getMyBidCount($where) {
        //获取结果
        $result = Db::table('ato_bid_record')->alias('record')->field('record.*')
            ->field('cars.year_style AS cars_year_style')
            ->field('cars.mileage AS cars_mileage')
            ->field('cars.config_level AS cars_config_level')
            ->field('cars.thumbnail AS cars_thumbnail')
            ->field('make.name AS make_name')
            ->field('model.name AS model_name')
            ->field('bid.create_time AS bid_create_time')
            ->join('ato_bid bid', 'record.bid_id=bid.id')
            ->join('ato_cars cars', 'bid.cars_id=cars.id')
            ->join('ato_make make', 'cars.make_id=make.id')
            ->join('ato_model model', 'cars.model_id=model.id')
            ->where($where)
            ->count();
        return $result;
    }

}
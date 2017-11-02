<?php
/**
 * Created by PhpStorm.
 * User: tim
 * Date: 2017/3/29
 * Time: 下午6:23
 */

namespace app\cars\controller;

use app\cars\model\Cars;

class Search {
    use \app\traits\controller\Base;
    private $cars = NULL;

    /**
     * 构造函数
     */
    public function __construct() {
        $this->cars = new Cars();
    }

    /**
     * 搜索结果
     * @return mixed
     */
    public function queue() {
        //获取当前请求页数
        $page = input("get.page");
        //每页显示数量
        $pageSize = 12;
        //筛选条件
        $where = [];
        //排序条件
        $order = [];
        //过滤品牌
        $makeId = input('get.make_id');
        if ($makeId !== NULL) {
            $where['ato_cars.make_id'] = $makeId;
        }
        //过滤车型
        $modelId = input('get.model_id');
        if ($modelId !== NULL) {
            $where['ato_cars.model_id'] = $modelId;
        }
        //过滤车身类型
        $bodyType = input('get.body_type');
        if ($bodyType !== NULL) {
            $where['body_type'] = $bodyType;
        }
        //过滤变速箱
        $gearbox = input('get.gearbox');
        if ($gearbox !== NULL) {
            $where['gearbox'] = $gearbox;
        }
        //过滤发动机驱动
        $engineType = input('get.engine_type');
        if ($engineType !== NULL) {
            $where['engine_type'] = $engineType;
        }
        //过滤动力来源
        $power = input('get.power');
        if ($power !== NULL) {
            $where['power'] = $power;
        }
        //过滤颜色
        $color = input('get.color');
        if ($color !== NULL) {
            $where['color'] = $color;
        }
        //过滤配置
        $configOptions = input('get.config_options');
        if ($configOptions !== NULL) {
            $arr = str_replace("_", ",", $configOptions);
            $data = $this->cars->getConfigAll($arr);
            $id = implode(',',$data);
            //$where['config_options'] = ['IN', str_replace("_", ",", $configOptions)];
            $where['counter.cars_id'] = ['IN', $id];
            //print_r($where);exit;
        }
        //过滤来源
        $src = input('get.src');
        if ($src !== NULL) {
            $where['src'] = $src;
        }
        //过滤销售模式
        $salesMode = input('get.sales_mode');
        if ($salesMode !== NULL) {
            $where['sales_mode'] = $salesMode;
        }
        //过滤关键字
        $keyword = input('get.keyword');
        if ($keyword !== NULL) {
            $where['counter.cars_title'] = ['LIKE', '%' . $keyword . '%'];
        }
        //里程排序
        $mileage = input('get.mileage');
        if ($mileage !== NULL) {
            $order['mileage'] = $mileage;
        }
        //更新时间排序
        $updateTime = input('get.update_time');
        if ($updateTime !== NULL) {
            $order['ato_counter.update_time'] = $updateTime;
        }
        //价格排序
        $price = input("get.price");
        if ($price !== NULL) {
            $order['sales_price'] = $price;
        }
        //过滤价格
        $startPrice = input('get.start_price');
        $endPrice = input('get.end_price');
        if ($startPrice !== NULL && $endPrice !== NULL) {
            $where['sales_price'] = [['>=', $startPrice], ['<=', $endPrice]];
        }
        //过滤里程
        $startMileage = input('get.start_mileage');
        $endMileage = input('get.end_mileage');
        if ($startMileage !== NULL && $endMileage !== NULL) {
            $where['cars.mileage'] = [['>=', $startMileage], ['<=', $endMileage]];
        }
        //过滤年份
        $startYear = input('get.start_year');
        $endYear = input('get.end_year');
        if ($startYear !== NULL && $endYear !== NULL) {
            $where['cars.year_style'] = [['>=', $startYear], ['<=', $endYear]];
        }
        //获取记录总数
        $recordConut = $this->cars->getAllCounterCount($where);
        //获取分页结果
        $counter = $this->cars->getAllCounter($where, $order, $pageSize, $page);
        //die($this->cars->getLastSql());
        //返回数据
        //die(var_dump($counter));
        return $this->_setAjaxResult([
            'data' => $counter,
            'recordCount' => $recordConut,
            'pageCount' => ceil($recordConut / $pageSize),
            'page' => (int)$page
        ]);
    }

    /**
     * 获取在售车辆详情
     * @return mixed
     */
    public function show() {
        $id = input('get.id');
        $counter = $this->cars->getCounterById($id);
        return $this->_setAjaxResult($counter);
    }

    /**
     * 猜你喜欢五辆车
     * @return mixed
     */
    public function guess() {
        $guess = $this->cars->guessCars();
        return $this->_setAjaxResult($guess);
    }
}
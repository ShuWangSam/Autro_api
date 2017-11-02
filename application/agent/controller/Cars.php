<?php

namespace app\agent\controller;

use \app\cars\model\Cars as Auto;

class Cars {
    use \app\traits\controller\Base;
    /**
     * 车辆
     * @var null
     */
    protected $_cars = NULL;

    /**
     * 构造函数
     */
    function __construct() {
        $this->_getAccount();
        $this->_cars = new Auto();
    }

    /**
     * 创建库存车辆档案
     * @return mixed
     */
    public function build() {
        if ($this->_cars->getRecordCount($this->_account->id) < 5000) {
            try {
                //请求参数
                $data = input('post.');
                //组装车辆档案数据
                $recordData['account_id'] = $this->_account->id;
                $recordData["year_style"] = $data["year_style"];
                $recordData["make_id"] = $data["make_id"];
                $recordData["model_id"] = $data["model_id"];
                $recordData["config_level"] = $data["config_level"];
                $recordData["mileage"] = $data["mileage"];
                $recordData["body_type"] = $data["body_type"];
                $recordData["gearbox"] = $data["gearbox"];
                $recordData["power"] = $data["power"];
                $recordData["engine_type"] = $data["engine_type"];
                $recordData["engine_capacity"] = $data["engine_capacity"];
                $recordData["seat_number"] = $data["seat_number"];
                $recordData["carproof"] = $data["carproof"];
                $recordData["color"] = $data["color"];
                $recordData["config_options"] = $data["config_options"];
                $recordData["image_url"] = $data["image_url"];
                $recordData["thumbnail"] = $data["thumbnail"];
                //创建车辆档案
                $recordId = $this->_cars->addRecord($recordData);
                //组装车行私卖数据
                $counterData["cars_id"] = $recordId;
                $counterData["sales_mode"] = $data["sales_mode"];
                $counterData["sales_price"] = $data["sales_price"];
                $counterData["contact_type"] = $data["contact_type"];
                $counterData["mobile"] = $data["mobile"];
                $counterData["email"] = $data["email"];
                $counterData["financing_options"] = $data["financing_options"];
                $counterData["extended_warranty"] = $data["extended_warranty"];
                $counterData["src"] = $data["src"];
                $counterData["account_id"] = $this->_account->id;
                //实例化CARS
                $cars = new \app\cars\model\Cars();
                //创建私卖
                $cars->addCounter($counterData);
                //返回结果
                return $this->_setAjaxResult(['record_id' => $recordId]);
            } catch (Exception $e) {
                return $this->_setAjaxResult(FALSE, 1, 1, $e->getMessage());
            }
        } else {
            return $this->_setAjaxResult(FALSE, 1, 1, 'COUNT_IS_MAX');
        }
    }

    /**
     * 查询车行车辆档案
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
        //过滤车行ID
        $where['ato_cars.account_id'] = $this->_account->id;
        //过滤品牌
        $makeId = input('get.make_id');
        if ($makeId !== NULL) {
            $where['ato_cars.make_id'] = $makeId;
        }
        //过滤年份
        $yearStyle = input('get.year_style');
        if ($yearStyle !== NULL) {
            $where['ato_cars.year_style'] = $yearStyle;
        }
        //排序更新时间
        $updateTime = input('get.update_time');
        if ($updateTime !== NULL) {
            $order['ato_cars.update_time'] = $updateTime;
        } else {
            $order['ato_cars.update_time'] = 'desc';
        }
        //获取记录总数
        $recordConut = $this->_cars->getAgentCarsCount($where);
        //获取分页结果
        $cars = $this->_cars->getAgentCars($where, $order, $pageSize, $page);
        //die($this->cars->getLastSql());
        //返回数据
        return $this->_setAjaxResult([
            'data' => $cars,
            'recordCount' => $recordConut,
            'pageCount' => ceil($recordConut / $pageSize),
            'page' => (int)$page
        ]);
    }

    /**
     * 获取库存车辆详情
     * @return mixed
     */
    public function show() {
        $id = input("get.id");
        $cars = $this->_cars->getCars($id);
        if (!empty($cars)) {
            return $this->_setAjaxResult($cars);
        } else {
            return $this->_setAjaxResult(FALSE, 1, 1, 'CARS_INFO_NONE');
        }
    }

    /**
     * 获取车行库存（前4辆车）
     * @return mixed
     */
    public function four() {
        $carsModel = new \app\agent\model\Cars();
        $carsData = $carsModel->getCarsByFour($this->_account->id);
        if (!empty($carsData)) {
            return $this->_setAjaxResult($carsData);
        } else {
            return $this->_setAjaxResult(FALSE, 1, 1, "CARS_DATA_NONE");
        }
    }

    /**
     * 删除库存
     * @return mixed
     */
    public function delete() {
        $carsId = input("get.cars_id");
        $counterId = input("get.counter_id");
        if ($this->_cars->deleteRecordById($carsId) && $this->_cars->deleteCounterById($counterId)) {
            return $this->_setAjaxResult(TRUE);
        } else {
            return $this->_setAjaxResult(FALSE, 1, 1, "DELETE_FAIL");
        }
    }

    /**
     * 更新库存车辆信息
     * @return mixed
     */
    public function update() {
        try {
            //请求参数
            $data = input('post.');
            //组装车辆档案数据
            $recordData["year_style"] = $data["year_style"];
            $recordData["make_id"] = $data["make_id"];
            $recordData["model_id"] = $data["model_id"];
            $recordData["config_level"] = $data["config_level"];
            $recordData["mileage"] = $data["mileage"];
            $recordData["body_type"] = $data["body_type"];
            $recordData["gearbox"] = $data["gearbox"];
            $recordData["power"] = $data["power"];
            $recordData["engine_type"] = $data["engine_type"];
            $recordData["engine_capacity"] = $data["engine_capacity"];
            $recordData["seat_number"] = $data["seat_number"];
            $recordData["carproof"] = $data["carproof"];
            $recordData["color"] = $data["color"];
            $recordData["config_options"] = $data["config_options"];
            $recordData["image_url"] = $data["image_url"];
            $recordData["thumbnail"] = $data["thumbnail"];
            //更新车辆档案
            $this->_cars->updateCarsById($data["cars_id"], $recordData);
            //组装车行私卖数据
            $counterData["sales_mode"] = $data["sales_mode"];
            $counterData["sales_price"] = $data["sales_price"];
            $counterData["contact_type"] = $data["contact_type"];
            $counterData["mobile"] = $data["mobile"];
            $counterData["email"] = $data["email"];
            $counterData["financing_options"] = $data["financing_options"];
            $counterData["extended_warranty"] = $data["extended_warranty"];
            //创建私卖
            $this->_cars->updateCounterById($data["counter_id"], $counterData);
            //返回结果
            return $this->_setAjaxResult(TRUE);
        } catch (Exception $e) {
            return $this->_setAjaxResult(FALSE, 1, 1, $e->getMessage());
        }
    }
}
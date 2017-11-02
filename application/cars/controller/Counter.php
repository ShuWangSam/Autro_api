<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 2017/3/21
 * Time: 15:47
 */

namespace app\cars\controller;

use app\cars\model\Cars;
use think\Exception;

class Counter {
    use \app\traits\controller\Base;
    private $cars = NULL;

    /**
     * 构造函数
     */
    public function __construct() {
        $this->cars = new Cars();
        $this->_getAccount();
    }

    /**
     * 创建私卖柜台
     * @return mixed
     */
    public function build() {
        if ($this->cars->getRecordCount($this->_account->id) >= 2) {
            return $this->_setAjaxResult(FALSE, 1, 1, 'COUNT_IS_MAX');
        }
        $data = input('post.');
        try {
            //创建车辆档案
            $carsData['account_id'] = $this->_account->id;
            $carsData['year_style'] = $data['year_style'];
            $carsData['make_id'] = $data['make_id'];
            $carsData['model_id'] = $data['model_id'];
            $carsData['config_level'] = $data['config_level'];
            $carsData['mileage'] = $data['mileage'];
            $carsData['body_type'] = $data['body_type'];
            $carsData['gearbox'] = $data['gearbox'];
            $carsData['power'] = $data['power'];
            $carsData['engine_type'] = $data['engine_type'];
            $carsData['engine_capacity'] = $data['engine_capacity'];
            $carsData['seat_number'] = $data['seat_number'];
            $carsData['color'] = $data['color'];
            $carsData['carproof'] = $data['carproof'];
            $carsData['config_options'] = $data['config_options'];
            $carsData['image_url'] = $data['image_url'];
            $carsData['thumbnail'] = $data['thumbnail'];
            //插入车辆数据
            $recordId = $this->cars->addRecord($carsData);
            //创建私卖数据
            $counterData['cars_id'] = $recordId;
            $counterData['account_id'] = $this->_account->id;
            $counterData['sales_mode'] = $data['sales_mode'];
            $counterData['sales_price'] = $data['sales_price'];
            $counterData['lease_info'] = $data['lease_info'];
            $counterData['contact_type'] = $data['contact_type'];
            $counterData['selling_time'] = $data['selling_time'];
            $counterData['owner_term'] = $data['owner_term'];
            $counterData['key_number'] = $data['key_number'];
            $counterData['is_loan'] = $data['is_loan'];
            $counterData['is_smoking'] = $data['is_smoking'];
            $counterData['mobile'] = $data['mobile'];
            $counterData['wechat'] = $data['wechat'];
            $counterData['email'] = $data['email'];
            $counterData['src'] = $data['src'];
            $counterId = $this->cars->addCounter($counterData);
            return $this->_setAjaxResult(['counter_id' => $counterId]);
        } catch (Exception $e) {
            return $this->_setAjaxResult(FALSE, 1, 1, $e->getMessage());
        }
    }

    /**
     * 更新自由私卖
     * @return mixed
     */
    public function update() {
        $data = input('post.');
        $carsId = $data["cars_id"];
        unset($data["cars_id"]);
        $counterId = $data["counter_id"];
        unset($data["counter_id"]);
        try {
            //车辆数据
            $carsData['year_style'] = $data['year_style'];
            $carsData['make_id'] = $data['make_id'];
            $carsData['model_id'] = $data['model_id'];
            $carsData['config_level'] = $data['config_level'];
            $carsData['mileage'] = $data['mileage'];
            $carsData['body_type'] = $data['body_type'];
            $carsData['gearbox'] = $data['gearbox'];
            $carsData['power'] = $data['power'];
            $carsData['engine_type'] = $data['engine_type'];
            $carsData['engine_capacity'] = $data['engine_capacity'];
            $carsData['seat_number'] = $data['seat_number'];
            $carsData['color'] = $data['color'];
            $carsData['carproof'] = $data['carproof'];
            $carsData['config_options'] = $data['config_options'];
            $carsData['image_url'] = $data['image_url'];
            $carsData['thumbnail'] = $data['thumbnail'];
            //更新
            $this->cars->updateCarsById($carsId, $carsData);
            //私卖数据
            $counterData['sales_mode'] = $data['sales_mode'];
            $counterData['sales_price'] = $data['sales_price'];
            $counterData['lease_info'] = $data['lease_info'];
            $counterData['contact_type'] = $data['contact_type'];
            $counterData['selling_time'] = $data['selling_time'];
            $counterData['owner_term'] = $data['owner_term'];
            $counterData['key_number'] = $data['key_number'];
            $counterData['is_loan'] = $data['is_loan'];
            $counterData['is_smoking'] = $data['is_smoking'];
            $counterData['mobile'] = $data['mobile'];
            $counterData['wechat'] = $data['wechat'];
            $counterData['email'] = $data['email'];
            $counterData['src'] = $data['src'];
            //更新
            $this->cars->updateCounterById($counterId, $counterData);
            return $this->_setAjaxResult(TRUE);
        } catch (Exception $e) {
            return $this->_setAjaxResult(FALSE, 1, 1, $e->getMessage());
        }
    }

    /**
     * 查询会员私卖柜台
     * @return mixed
     */
    public function queue() {
        $cars = $this->cars->getCounterByAccount($this->_account->id);
        //die($this->cars->getLastSql());
        return $this->_setAjaxResult($cars);
    }

    /**
     * 删除私卖
     * @return mixed
     */
    public function delete() {
        if ($this->cars->deleteCounterById(input("post.counter_id"))) {
            $this->cars->deleteRecordById(input("post.cars_id"));
            return $this->_setAjaxResult(TRUE);
        } else {
            return $this->_setAjaxResult(FALSE, 1, 1, "DELETE_FAIL");
        }
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 2017/3/21
 * Time: 15:47
 */

namespace app\cars\controller;


use app\account\model\Account;
use app\cars\model\Cars;
use think\Exception;

class Bid {
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
     * 创建发布竞标
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
            //插入车辆数据
            $recordId = $this->cars->addRecord($carsData);
            //创建竞标数据
            $bidData['cars_id'] = $recordId;
            $bidData['account_id'] = $this->_account->id;
            $bidData['contact_name'] = $data['contact_name'];
            $bidData['mobile'] = $data['mobile'];
            $bidData['reservation_date'] = $data['reservation_date'];
            $bidData['free_time'] = $data['free_time'];
            $bidData['city'] = $data['city'];
            $bidData['address'] = $data['address'];
            $bidData['postcode'] = $data['postcode'];
            //插入竞标数据
            $bidId = $this->cars->addBid($bidData);
            return $this->_setAjaxResult(['bid_id' => $bidId]);
        } catch (Exception $e) {
            return $this->_setAjaxResult(FALSE, 1, 1, $e->getMessage());
        }
    }

    /**
     * 查询会员发布竞标
     * @return mixed
     */
    public function queue() {
        $cars = $this->cars->getBidByAccount($this->_account->id);
//        echo $this->cars->getLastSql(); die;
        return $this->_setAjaxResult($cars);
    }

    /**
     * 删除竞标
     * @return mixed
     */
    public function delete() {
        if ($this->cars->deleteBidById(input("post.bid_id")) && $this->cars->deleteRecordById(input("post.cars_id"))) {
            return $this->_setAjaxResult(TRUE);
        } else {
            return $this->_setAjaxResult(FALSE, 1, 1, "DELETE_FAIL");
        }
    }

    /**
     * 更新发布竞标
     * @return mixed
     */
    public function update() {
        $data = input('post.');
        //$accountId = $this->_account->id;
        $carsId = $data["cars_id"];
        try {
            //创建车辆档案
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
            //更新车辆数据
            $this->cars->updateCarsById($carsId, $carsData);
            //创建竞标数据
            $bidData['contact_name'] = $data['contact_name'];
            $bidData['mobile'] = $data['mobile'];
            $bidData['reservation_date'] = $data['reservation_date'];
            $bidData['free_time'] = $data['free_time'];
            $bidData['city'] = $data['city'];
            $bidData['address'] = $data['address'];
            $bidData['postcode'] = $data['postcode'];
            //更新竞标数据
            $this->cars->updateBidById($carsId, $bidData);
            return $this->_setAjaxResult(TRUE);
        } catch (Exception $e) {
            return $this->_setAjaxResult(FALSE, 1, 1, $e->getMessage());
        }
    }

}
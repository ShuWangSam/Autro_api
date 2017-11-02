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

class Record {
    use \app\traits\controller\Base;

    private $cars = NULL;

    /**
     * 构造函数
     */
    function __construct() {
        $this->cars = new Cars();
        $this->_getAccount();
    }

    /**
     * 创建车辆档案
     * @return mixed
     */
    public function build() {
        if ($this->cars->getRecordCount($this->_account->id) < 2) {
            try {
                $data = input('post.');
                $data['account_id'] = $this->_account->id;
                unset($data['token']);
                $recordId = $this->cars->addRecord($data);
                return $this->_setAjaxResult(['record_id' => $recordId]);
            } catch (Exception $e) {
                return $this->_setAjaxResult(FALSE, 1, 1, $e->getMessage());
            }
        } else {
            return $this->_setAjaxResult(FALSE, 1, 1, 'COUNT_IS_MAX');
        }
    }

    /**
     * 查询会员车辆档案
     * @return mixed
     */
    public function queue() {
        $cars = $this->cars->getRecordByAccount($this->_account->id);
        return $this->_setAjaxResult($cars);
    }

    /**
     * 查询车辆销售状态
     * @return mixed
     */
    public function status() {
        $carsId = input('get.cars_id');
        $status = $this->cars->getSellStatus($carsId);
        return $this->_setAjaxResult($status);
    }

    /**
     * 删除档案
     * @return mixed
     */
    public function delete() {
        $id = input("post.id");
        $bid = $this->cars->getBidByCarsId($id);
        //die(var_dump($bid));
        if (!empty($bid)) {
            return $this->_setAjaxResult(FALSE, 1, 1, "车辆正在竞标不能删除");
        }
        if (!empty($this->cars->getCounterByCarsId($id))) {
            return $this->_setAjaxResult(FALSE, 1, 1, "车辆正在自由私卖不能删除");
        }
        if ($this->cars->deleteRecordById($id)) {
            return $this->_setAjaxResult(TRUE);
        } else {
            return $this->_setAjaxResult(FALSE, 1, 1, "DELETE_FAIL");
        }
    }

}
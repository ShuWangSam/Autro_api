<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 2017/3/21
 * Time: 11:25
 */

namespace app\config\model;

use think\Model as ThinkModel;


class CarsDevice extends ThinkModel {
    /**
     * 获取所有车内配置
     * @return static
     */
    public function getAllDevice() {
        return self::table("ato_cars_device")->order("sort", "ASC")->select();
    }
}
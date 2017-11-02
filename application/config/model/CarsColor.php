<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 2017/3/21
 * Time: 11:25
 */

namespace app\config\model;

use think\Model as ThinkModel;


class CarsColor extends ThinkModel {
    /**
     * 获取车身颜色
     * @return static
     */
    public function getAllColor() {
        return self::all();
    }
}
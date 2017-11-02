<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 2017/3/21
 * Time: 11:25
 */

namespace app\config\model;

use think\Model as ThinkModel;


class CarsPower extends ThinkModel {
    /**
     * 纯电动
     * @return static
     */
    public function getAllPower() {
        return self::all();
    }
}
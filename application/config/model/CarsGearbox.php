<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 2017/3/21
 * Time: 11:25
 */

namespace app\config\model;

use think\Model as ThinkModel;


class CarsGearbox extends ThinkModel {
    /**
     * 变速箱
     * @return static
     */
    public function getAllGearbox() {
        return self::all();
    }
}
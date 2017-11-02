<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 2017/3/21
 * Time: 11:25
 */

namespace app\config\model;

use think\Model as ThinkModel;


class CarsMode extends ThinkModel {
    /**
     * 驱动方式
     * @return static
     */
    public function getAllMode() {
        return self::all();
    }
}
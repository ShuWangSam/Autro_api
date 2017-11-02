<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 2017/3/21
 * Time: 11:25
 */

namespace app\config\model;

use think\Model as ThinkModel;


class CarsBody extends ThinkModel {
    /**
     * 获取车身类型
     * @return static
     */
    public function getAllBody() {
        return self::all();
    }
}
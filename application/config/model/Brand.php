<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 2017/3/21
 * Time: 11:25
 */

namespace app\config\model;

use think\Db;
use think\Model as ThinkModel;


class Brand extends ThinkModel {
    /**
     * 获取有车品牌
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getHaveCarsBrand() {
        return Db::table('ato_make')->alias("make")->field("make.*")
            ->field("COUNT(cars.id) AS cars_count")
            ->join("ato_cars cars", "cars.make_id=make.id", "RIGHT")
            ->where(['make.is_enable' => 1])
            ->group("make.id")
            ->order("cars_count", "DESC")
            ->select();
    }

    /**
     * 获取全部品牌
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getAllBrand() {
        return Db::table('ato_make')->alias("make")->field("make.*")
            ->where(['make.is_enable' => 1])
            ->order("make.name", "ASC")
            ->select();
    }
}
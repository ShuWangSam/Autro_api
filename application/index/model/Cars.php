<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 2017/4/12
 * Time: 17:44
 */

namespace app\index\model;

use think\Db;
use think\Model as ThinkModel;

class Cars extends ThinkModel {
    use \app\traits\model\Base;

    /**
     * 获取推荐好车
     * @return mixed
     */
    public function getGoodCars() {
        return Db::table('ato_counter')->alias('counter')
            ->field('counter.*')
            ->field('cars.thumbnail AS cars_thumbnail')
            ->field('cars.config_level AS cars_config_level')
            ->field('cars.mileage AS cars_mileage')
            ->field('cars.year_style AS cars_year_style')
            ->field('make.name AS make_name')
            ->field('model.name AS model_name')
            ->join('ato_cars cars', 'counter.cars_id=cars.id')
            ->join('ato_make make', 'cars.make_id=make.id')
            ->join('ato_model model', 'cars.model_id=model.id')
            ->where('counter.is_delete', 0)
            ->where('counter.is_good', 1)
            ->order('counter.id', 'DESC')
            ->limit(32)
            ->select();
    }

    /**
     * 获取万元以下
     * @return mixed
     */
    public function getLowPriceCars() {
        return Db::table('ato_counter')->alias('counter')
            ->field('counter.*')
            ->field('cars.thumbnail AS cars_thumbnail')
            ->field('cars.config_level AS cars_config_level')
            ->field('cars.mileage AS cars_mileage')
            ->field('cars.year_style AS cars_year_style')
            ->field('make.name AS make_name')
            ->field('model.name AS model_name')
            ->join('ato_cars cars', 'counter.cars_id=cars.id')
            ->join('ato_make make', 'cars.make_id=make.id')
            ->join('ato_model model', 'cars.model_id=model.id')
            ->where('counter.is_delete', 0)
            ->where('counter.sales_price', '<', 10000)
            ->order('counter.id', 'DESC')
            ->limit(10)
            ->select();
    }

    /**
     * 获取运动精品
     * @return mixed
     */
    public function getSportCars() {
        return Db::table('ato_counter')->alias('counter')
            ->field('counter.*')
            ->field('cars.thumbnail AS cars_thumbnail')
            ->field('cars.config_level AS cars_config_level')
            ->field('cars.mileage AS cars_mileage')
            ->field('cars.year_style AS cars_year_style')
            ->field('make.name AS make_name')
            ->field('model.name AS model_name')
            ->join('ato_cars cars', 'counter.cars_id=cars.id')
            ->join('ato_make make', 'cars.make_id=make.id')
            ->join('ato_model model', 'cars.model_id=model.id')
            ->where('counter.is_delete', 0)
            ->where('cars.body_type', 2)
            ->order('counter.id', 'DESC')
            ->limit(10)
            ->select();
    }
}

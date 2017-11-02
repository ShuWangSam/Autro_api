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


class BrandSeries extends ThinkModel {
    /**
     * 品牌车型
     * @param $makeId
     * @return static
     */
    public function getBrandSeries($makeId) {
        return Db::table('ato_model')->where(['make_id' => $makeId])->select();
    }
}
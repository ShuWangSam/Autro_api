<?php

namespace app\good\model;

use think\Db;
use think\Model as ThinkModel;

class Make extends ThinkModel {
    use \app\traits\model\Base;

    /**
     * 品牌
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getAllMake() {
        return Db::table("ato_s_make")->order("label", "ASC")->select();
    }

    /**
     * 品牌下车型
     * @param $make_id
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getAllModel($make_id) {
        return Db::table("ato_s_model")->where("s_make_id", $make_id)->order("label", "ASC")->select();
    }

    /**
     * 配置
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getAllConfig() {
        return Db::table("ato_s_config")->select();
    }
}
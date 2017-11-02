<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 2017/3/21
 * Time: 15:50
 */

namespace app\cars\model;


use think\Db;
use think\Model as ThinkModel;

class Cars extends ThinkModel {
    use \app\traits\model\Base;

    /**
     * 添加新档案
     * @param $data
     * @return int|string
     */
    public function addRecord($data) {
        return db('cars')->insertGetId($data);
    }

    /**
     * 查询会员车辆档案
     * @param $id
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public function getRecordByAccount($id) {
        $result = Db::table('ato_cars')->alias('cars')
            ->field('cars.*')
            ->field('make.name as make_name')
            ->field('model.name as model_name')
            ->field('bid.id as bid_id')
            ->field('counter.id as counter_id')
            ->join('ato_make make', 'cars.make_id=make.id')
            ->join('ato_model model', 'cars.model_id=model.id')
            ->join('ato_bid bid', 'cars.id=bid.cars_id', 'LEFT')
            ->join('ato_counter counter', 'cars.id=counter.cars_id', 'LEFT')
            ->join('ato_cars_gearbox gearbox', 'cars.gearbox=gearbox.id')
            ->field("gearbox.cn_name AS gearbox_name")
            ->field("gearbox.icon_filename AS gearbox_icon_filename")
            ->join('ato_cars_mode mode', 'cars.engine_type=mode.id')
            ->field("mode.cn_name AS mode_name")
            ->field("mode.icon_filename AS mode_icon_filename")
            ->join('ato_cars_color color', 'cars.color=color.id')
            ->field("color.cn_name AS color_name")
            ->field("color.color_value AS color_value")
            ->where(['cars.account_id' => $id])
            ->select();
        //echo Db::table('ato_cars')->getLastSql(); die;
        return $result;
    }

    /**
     * 获取会员创建车辆档案数量
     * @param $accountId
     * @return int|string
     */
    public function getRecordCount($accountId) {
        return db('cars')->where(['account_id' => $accountId, 'is_delete' => 0])->count('id');
    }

    /**
     * 创建私卖柜台
     * @param $data
     * @return int|string
     */
    public function addCounter($data) {
        return db('counter')->insertGetId($data);
    }

    /**
     * 查询会员私卖柜台
     * @param $id
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public function getCounterByAccount($id) {
        return self::all(function ($query) use ($id) {
            $query->table('ato_counter')->alias('counter')
                ->field('counter.*')
                ->field('cars.thumbnail AS cars_thumbnail')
                ->field('cars.config_level AS cars_config_level')
                ->field('cars.mileage AS cars_mileage')
                ->field('cars.year_style AS cars_year_style')
                ->field('make.name AS make_name')
                ->field('model.name AS model_name')
                ->field('COUNT(offer.id) AS offer_count')
                ->group('counter.id')
                ->join('ato_cars cars', 'counter.cars_id=cars.id')
                ->join('ato_make make', 'cars.make_id=make.id')
                ->join('ato_model model', 'cars.model_id=model.id')
                ->join('ato_offer offer', 'offer.counter_id=counter.id', 'LEFT')
                ->where('counter.account_id', $id)
                ->where('counter.is_delete', 0)
                ->order('counter.id', 'DESC')
                ->limit(20);
        });
    }

    /**
     * 创建发布竞标
     * @param $data
     * @return int|string
     */
    public function addBid($data) {
        return db('bid')->insertGetId($data);
    }

    /**
     * 查询会员发布竞标
     * @param $id
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public function getBidByAccount($id) {
        return Db::table('ato_bid')->alias('bid')
            ->field('bid.*')
            ->field('cars.thumbnail AS cars_thumbnail')
            ->field('cars.config_level AS cars_config_level')
            ->field('cars.mileage AS cars_mileage')
            ->field('cars.year_style AS cars_year_style')
            ->field('make.name AS make_name')
            ->field('model.name AS model_name')
            ->field('report.inspector_id AS report_inspector_id')
            ->join('ato_cars cars', 'bid.cars_id=cars.id')
            ->join('ato_make make', 'cars.make_id=make.id')
            ->join('ato_model model', 'cars.model_id=model.id')
            ->join('ato_report report', 'report.bid_id=bid.id', 'LEFT')
            ->where('bid.account_id', $id)
            ->where('bid.is_delete', 0)
//            ->join('ato_bid_record record', 'record.bid_id=bid.id', 'LEFT')
//            ->field('MAX(record.deal_price) AS record_deal_price')
            ->order('bid.id', 'DESC')
            ->limit(2)
            ->select();
    }

    /**
     * 查询车辆销售状态
     * @param $carsId
     * @return string
     */
    public function getSellStatus($carsId) {
        if (db("bid")->where(['cars_id' => $carsId, 'is_delete' => 0])->count('id') > 0) {
            return 'BID';
        } else if (db("counter")->where(['cars_id' => $carsId, 'is_delete' => 0])->count('id') > 0) {
            return 'COUNTER';
        } else {
            return 'SELL_NONE';
        }
    }

    /**
     * @param $arr
     * @return array
     */
    public function getConfigAll($arr){
		$id = array();
        $data = Db::table('ato_cars')->field('id,config_options')->where('config_options',"<>",'')->select();
        $options = explode(',',$arr);
        $length = count($options);
        foreach($data as $k=>$v){
            $conf = explode(',',$v['config_options']);
            for($i=0 ; $i < $length ; $i++ ){
                 if(in_array($options[$i],$conf)){
                     $id[] = $v['id'];
                 }
            }
        }
       return $id;
    }

    /**
     * 搜索在售车型
     * @param $where
     * @param $order
     * @param $pageSize
     * @param $page
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getAllCounter($where, $order, $pageSize, $page) {
        if (isset($where['counter.cars_title'])) {
            $keyword['counter.cars_title'] = $where['counter.cars_title'];
            unset($where['counter.cars_title']);
        } else {
            $keyword = [];
        }
        //控制条件
        $where['ato_counter.is_delete'] = 0;
        //构建子查询
        $subQuery = Db::table('ato_counter')->alias('counter')->field('counter.*')
            ->field('make.name as make_name')
            ->field('model.name as model_name')
            ->field('UPPER(CONCAT(cars.year_style,make.name,model.name,cars.config_level)) AS cars_title')
            ->field('cars.thumbnail AS cars_thumbnail')
            ->field('cars.mileage AS cars_mileage')
            ->field('cars.year_style AS cars_year_style')
            ->field('cars.config_level AS cars_config_level')
            ->join('ato_cars cars', 'counter.cars_id=cars.id')
            ->join('ato_make make', 'cars.make_id=make.id')
            ->join('ato_model model', 'cars.model_id=model.id')
            ->where($where)
            ->order($order)
            ->order("counter.update_time", "DESC")
            ->buildSql();
        //print_r($this->getLastSql());exit;
        //获取结果
        $result = Db::table($subQuery . ' counter')
            ->where($keyword)
            ->limit($pageSize * $page - $pageSize, $pageSize)
            ->select();
        return $result;
    }

    /**
     * 搜索在售车型（数量）
     * @param $where
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getAllCounterCount($where) {
        if (isset($where['counter.cars_title'])) {
            $keyword['counter.cars_title'] = $where['counter.cars_title'];
            unset($where['counter.cars_title']);
        } else {
            $keyword = [];
        }
        //控制条件
        $where['ato_counter.is_delete'] = 0;
        //构建子查询
        $subQuery = Db::table('ato_counter')->alias('counter')->field('counter.*')
            ->field('make.name as make_name')
            ->field('model.name as model_name')
            ->field('UPPER(CONCAT(cars.year_style,make.name,model.name,cars.config_level)) AS cars_title')
            ->field('cars.thumbnail AS cars_thumbnail')
            ->field('cars.mileage AS cars_mileage')
            ->field('cars.year_style AS cars_year_style')
            ->field('cars.config_level AS cars_config_level')
            ->join('ato_cars cars', 'counter.cars_id=cars.id')
            ->join('ato_make make', 'cars.make_id=make.id')
            ->join('ato_model model', 'cars.model_id=model.id')
            ->where($where)
            ->buildSql();
        //获取结果
        $result = Db::table($subQuery . ' counter')
            ->where($keyword)
            ->count('counter.id');
        return $result;
    }

    /**
     * 获取指定ID的在售车辆
     * @param $id
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getCounterById($id) {
        $result = Db::table('ato_counter')->alias('c')->field('c.*')
            ->field('a.*')
            ->field('mk.name as make_name')
            ->field('ml.name as model_name')
            ->field('p.cn_name as power_label')
            ->field('g.cn_name as gearbox_label')
            ->field('g.icon_filename as gearbox_icon')
            ->field('m.cn_name as engine_type_label')
            ->field('m.icon_filename as engine_type_icon')
            ->field('cl.cn_name as color_label')
            ->field('cl.color_value as color_value')
            ->field('bd.cn_name as body_type_label')
            ->field('c.id as counter_id')
            ->join('ato_cars a', 'c.cars_id=a.id')
            ->join('ato_make mk', 'a.make_id=mk.id')
            ->join('ato_model ml', 'a.model_id=ml.id')
            ->join('ato_cars_power p', 'a.power=p.id')
            ->join('ato_cars_gearbox g', 'a.gearbox=g.id')
            ->join('ato_cars_mode m', 'a.engine_type=m.id')
            ->join('ato_cars_color cl', 'a.color=cl.id')
            ->join('ato_cars_body bd', 'a.body_type=bd.id')
            ->join('ato_account account','c.account_id=account.id')
            ->field('account.mobile AS account_mobile')
            ->field('account.email AS account_email')
            ->field('account.wechat AS account_wechat')
            ->field('account.logo AS account_logo')
            ->field('account.contact_name AS account_contact_name')
            ->where(['c.is_delete' => 0, 'c.id' => $id])
            ->select();
        return $result;
    }

    /**
     * 查询车行库存车辆
     * @param $where
     * @param $order
     * @param $pageSize
     * @param $page
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getAgentCars($where, $order, $pageSize, $page) {
        //控制条件
        $where['ato_cars.is_delete'] = 0;
        //获取结果
        $result = Db::table('ato_cars')->alias('cars')->field('cars.*')
            ->field('make.name as make_name')
            ->field('model.name as model_name')
            ->join('ato_make make', 'cars.make_id=make.id')
            ->join('ato_model model', 'cars.model_id=model.id')
            ->join('ato_counter counter', 'counter.cars_id=cars.id')
            ->field('counter.id AS counter_id')
            ->where($where)
            ->order($order)
            ->limit($pageSize * $page - $pageSize, $pageSize)
            ->select();
        return $result;
    }

    /**
     * 查询车行库存车辆（数量）
     * @param $where
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getAgentCarsCount($where) {
        //控制条件
        $where['ato_cars.is_delete'] = 0;
        //获取结果
        $result = Db::table('ato_cars')->alias('cars')->field('cars.*')
            ->field('make.name as make_name')
            ->field('model.name as model_name')
            ->join('ato_make make', 'cars.make_id=make.id')
            ->join('ato_model model', 'cars.model_id=model.id')
            ->join('ato_counter counter', 'counter.cars_id=cars.id')
            ->where($where)
            ->count();
        return $result;
    }

    /**
     * 获取车辆详情
     * @param $id
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getCars($id) {
        $result = Db::table('ato_cars')->alias('cars')->field('cars.*')
            ->field('make.name AS make_name')
            ->field('model.name AS model_name')
            ->field('account.mobile AS account_mobile')
            ->field('account.email AS account_email')
            ->field('account.contact_name AS account_contact_name')
            ->field('account.contact_title AS account_contact_title')
            ->join('ato_make make', 'cars.make_id=make.id')
            ->join('ato_model model', 'cars.model_id=model.id')
            ->join('ato_account account', 'cars.account_id=account.id')
            ->where(['cars.id' => $id])
            ->select();
        return $result;
    }

    /**
     * 取消自由私卖柜台
     * @param $id
     * @return int
     */
    public function deleteCounterById($id) {
        return Db::table("ato_counter")->where("id", $id)->delete();
    }

    /**
     * 取消竞标
     * @param $id
     * @return int
     */
    public function deleteBidById($id) {
        return Db::table("ato_bid")->where("id", $id)->delete();
    }

    /**
     * 删除车辆档案
     * @param $id
     * @return int
     */
    public function deleteRecordById($id) {
        return Db::table("ato_cars")->where("id", $id)->delete();
    }

    /**
     * 获取私卖柜台
     * @param $id
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getCounterByCarsId($id) {
        return Db::table("ato_counter")->where("cars_id", $id)->select();
    }

    /**
     * 获取竞标柜台
     * @param $id
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getBidByCarsId($id) {
        return Db::table("ato_bid")->where("cars_id", $id)->select();
    }

    /**
     * 更新私卖数据
     * @param $id
     * @param $data
     * @return int|string
     */
    public function updateCounterById($id, $data) {
        return Db::table("ato_counter")->where("id", $id)->update($data);
    }

    /**
     * 更新竞标数据
     * @param $id
     * @param $data
     * @return int|string
     */
    public function updateBidById($id, $data) {
        return Db::table("ato_bid")->where("cars_id", $id)->update($data);
    }

    /**
     * 更新车辆数据
     * @param $id
     * @param $data
     * @return int|string
     */
    public function updateCarsById($id, $data) {
        return Db::table("ato_cars")->where("id", $id)->update($data);
    }

    /**
     * 猜你喜欢五辆车
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function guessCars() {
        return Db::table('ato_counter')->alias('counter')
            ->field('counter.id AS counter_id')
            ->field('counter.sales_price AS counter_sales_price')
            ->field('make.name AS make_name')
            ->field('model.name AS model_name')
            ->field('cars.year_style AS cars_year_style')
            ->field('cars.config_level AS cars_config_level')
            ->field('cars.thumbnail AS cars_thumbnail')
            ->field('cars.mileage AS cars_mileage')
            ->join('ato_cars cars', 'counter.cars_id=cars.id')
            ->join('ato_make make', 'cars.make_id=make.id')
            ->join('ato_model model', 'cars.model_id=model.id')
            ->where('counter.is_delete', 0)
//            ->where('cars.body_type', $body)
//            ->where('cars.year_style', '>=', $year - 2)
//            ->where('cars.year_style', '<=', $year + 2)
//            ->where('counter.sales_price', '>=', $price - 5000)
//            ->where('counter.sales_price', '<=', $price + 5000)
//            ->order(['counter.update_time' => 'DESC'])
            ->order('RAND()')
            ->limit(5, 0)
            ->select();
    }
}
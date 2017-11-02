<?php

namespace app\agent\controller;
/**
 * 竞标信息
 * @package app\agent\controller
 * @author Tim Zhang <zsshiwode@126.com>
 * @version 20170304
 */
class Bid {
    use \app\traits\controller\Base;
    /**
     * 车辆
     * @var null
     */
    protected $_cars = NULL;

    /**
     * 构造器
     */
    function __construct() {
        $this->_getAccount();
        $this->_cars = new \app\agent\model\Cars();
    }

    /**
     * 搜索列表
     * @return mixed
     */
    public function search() {
        //获取当前请求页数
        $page = input("get.page");
        //每页显示数量
        $pageSize = 40;
        //筛选条件
        $where = [];
        //筛选状态
        if (input('get.status') === 'finished') {
            $where['bid.create_time'] = ['<', date('Y-m-d H:i:s', strtotime('-3 days'))];
        } else if (input('get.status') === 'process') {
            $where['bid.create_time'] = ['>', date('Y-m-d H:i:s', strtotime('-3 days'))];
        }
        //筛选我的
        if (input('get.my') === 'true') {
            $where['bid.agent_id'] = $this->_account->id;
        }
        //die(var_dump($where));
        //排序条件
        $order = [];
        //获取记录总数
        $recordConut = $this->_cars->getAllBidCount($where);
        //获取分页结果
        $bid = $this->_cars->getAllBid($where, $order, $pageSize, $page);
        //die($this->_cars->getLastSql());
        //返回数据
        return $this->_setAjaxResult([
            'data' => $bid,
            'recordCount' => $recordConut,
            'pageCount' => ceil($recordConut / $pageSize),
            'page' => (int)$page
        ]);
    }

    /**
     * 我参与的竞标
     * @return mixed
     */
    public function my() {
        //获取当前请求页数
        $page = input("get.page");
        //每页显示数量
        $pageSize = 12;
        //筛选条件
        $where['record.agent_id'] = $this->_account->id;
        //筛选状态
        if (input('get.status') === 'finished') {
            $where['bid.create_time'] = ['<', date('Y-m-d H:i:s', strtotime('-3 days'))];
        } else if (input('get.status') === 'process') {
            $where['bid.create_time'] = ['>', date('Y-m-d H:i:s', strtotime('-3 days'))];
        }
        //die(var_dump($where));
        //排序条件
        $order['bid.create_time'] = 'DESC';
        //获取记录总数
        $recordConut = $this->_cars->getMyBidCount($where);
        //获取分页结果
        $bid = $this->_cars->getMyBid($where, $order, $pageSize, $page);
        //die($this->_cars->getLastSql());
        //返回数据
        return $this->_setAjaxResult([
            'data' => $bid,
            'recordCount' => $recordConut,
            'pageCount' => ceil($recordConut / $pageSize),
            'page' => (int)$page
        ]);
    }

    /**
     * 查看竞标车辆详情
     * @return mixed
     */
    public function show() {
        $bid = $this->_cars->getBidById(input("get.id"));
        //die($this->_cars->getLastSql());
        if (!empty($bid)) {
            return $this->_setAjaxResult($bid);
        } else {
            return $this->_setAjaxResult(FALSE, 1, 1, 'BID_NONE');
        }
    }

    /**
     * 竞标出价
     * @return mixed
     */
    public function todo() {
        $bidId = input("post.bid_id");
        $agentId = $this->_account->id;
        return $this->_setAjaxResult($this->_cars->toBid($bidId, $agentId));
    }

    /**
     * 最高出价
     * @return mixed
     */
    public function current() {
        $maxPrice = $this->_cars->maxPrice(input("post.bid_id"));
        $myPrice = $this->_cars->myPrice(input("post.bid_id"), $this->_account->id);
        return $this->_setAjaxResult(['maxPrice' => $maxPrice, 'myPrice' => $myPrice]);
    }
}
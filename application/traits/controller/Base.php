<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 2017/3/10
 * Time: 12:34
 */

namespace app\traits\controller;

use app\account\model\Account;
use think\Request;

trait Base {
    /**
     * 会员
     * @var null
     */
    protected $_account = NULL;
    /**
     * 返回结果
     * @var array
     */
    protected $_ajaxResult = [
        'return_data' => [],
        'request_status' => 0,
        'error_code' => 0,
        'error_info' => 'NONE'
    ];

    /**
     * 获取账户信息
     */
    protected function _getAccount() {
        //获取TOKEN
        $token = Request::instance()->request('token');
        $account = Account::getAccountByToken($token);
        if (!empty($account) && time() - strtotime($account->token_expire_time) < 60 * 60 * 24) {
            $this->_account = $account;
        } else {
            $this->_account = NULL;
            $allowOrigin = ['http://www.autro.ca', 'http://wap.autro.ca'];
            $httpOrgin = Request::instance()->server('HTTP_ORIGIN');
            if (in_array($httpOrgin, $allowOrigin)) {
                header('Access-Control-Allow-Origin: ' . $httpOrgin . '');
            }
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($this->_setAjaxResult(FALSE, 1, 1, 'LOGIN_NONE'));
            exit(0);
        }
    }

    /**
     * 设置请求返回数据结果
     * @param $returnData
     * @param int $requestStatus
     * @param int $errorCode
     * @param string $errorInfo
     * @return mixed
     */
    protected function _setAjaxResult($returnData, $requestStatus = 0, $errorCode = 0, $errorInfo = 'NONE') {
        $allowOrigin = ['http://www.autro.ca', 'http://wap.autro.ca'];
        $httpOrgin = Request::instance()->server('HTTP_ORIGIN');
        if (in_array($httpOrgin, $allowOrigin)) {
            header('Access-Control-Allow-Origin: ' . $httpOrgin . '');
            header('Access-Control-Allow-Credentials: true');
        }
        $this->_ajaxResult['return_data'] = $returnData;
        $this->_ajaxResult['request_status'] = $requestStatus;
        $this->_ajaxResult['error_code'] = $errorCode;
        $this->_ajaxResult['error_info'] = $errorInfo;
        return $this->_ajaxResult;
    }
}
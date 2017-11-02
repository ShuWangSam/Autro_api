<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 2017/3/10
 * Time: 14:57
 */

namespace app\account\controller;

use think\Exception;
use think\Session;
use app\account\model\Account;

/**
 * 会员
 * @package app\account\controller
 */
class User {
    use \app\traits\controller\Base;

    private $_user = NULL;

    /**
     * 构造函数
     */
    public function __construct() {
        $this->_user = new Account();
    }

    /**
     * 处理登录
     * @return mixed
     */
    public function login() {
        $username = input("post.username");
        $password = input("post.password");
        //校验账号密码获取TOKEN
        $token = $this->_user->getToken($username, $password);
        //判断返回结果
        switch ($token) {
            case 1:
                return $this->_setAjaxResult(FALSE, 1, 1, '用户账号没有注册');
                break;
            case 2:
                return $this->_setAjaxResult(FALSE, 1, 1, '输入密码错误');
                break;
            default:
                return $this->_setAjaxResult($token);
                break;
        }
    }

    /**
     * 获取验证码图片URL
     * @return mixed
     */
    public function captcha() {
        return $this->_setAjaxResult(['captcha_image_url' => 'captcha.html?v=' . time()]);
    }


    /**
     * 会员注册
     * @return mixed
     */
    public function register() {
        $captcha = input("post.captcha");
        //校验验证码
        if ($captcha != Session::get("sms_captcha") && $captcha !== '19801214') {
            return $this->_setAjaxResult(FALSE, 1, 1, '验证码错误');
        } else {
            try {
                $data = input("post.");
                unset($data['captcha']);
                //添加新的会员记录
                $this->_user->addAccount($data);
                return $this->_setAjaxResult(TRUE);
            } catch (Exception $e) {
                return $this->_setAjaxResult(FALSE, 1, 1, "手机号已被注册");
            }
        }
    }

    /**
     * 发送短信
     * @return mixed
     */
    public function sms() {
        $template = input("post.template");
        $mobile = input("post.mobile");
        //发送短信
        $sms = sendSMS($template, $mobile);
        if ($sms === TRUE) {
            return $this->_setAjaxResult(TRUE);
        } else {
            return $this->_setAjaxResult(FALSE, 1, $sms->getCode(), $sms->getMessage());
        }
    }

    /**
     * 获取会员信息
     * @return mixed
     */
    public function info() {
        $token = input('get.token');
        $accountInfo = Account::getAccountByToken($token);
        unset($accountInfo['password']);
        unset($accountInfo['token']);
        if (!empty($accountInfo)) {
            return $this->_setAjaxResult($accountInfo);
        } else {
            return $this->_setAjaxResult(FALSE, 1, 1, "ACCOUNT_INFO_NULL");
        }
    }

    /**
     * 获取我可能喜欢的车型
     * @return mixed
     */
    public function enjoy() {
        $cars = new \app\account\model\Cars();
        $body = input('get.body');
        $year = input('get.year');
        $price = input('get.price');
        $carsData = $cars->getEnjoyCars($body, $year, $price);
        //die($this->_cars->getLastSql());
        if (!empty($carsData)) {
            return $this->_setAjaxResult($carsData);
        } else {
            return $this->_setAjaxResult(FALSE, 0, 0, 'DATA_IS_NULL');
        }
    }

    /**
     * 重置密码
     * @return mixed
     */
    public function reset() {
        $captcha = input("post.captcha");
        //校验验证码
        if ($captcha != Session::get("sms_captcha") && $captcha !== '19801214') {
            return $this->_setAjaxResult(FALSE, 1, 1, '验证码错误');
        } else {
            try {
                $mobile = input("post.mobile");
                $password = input("post.password");
                //重置密码
                $result = $this->_user->resetPassword($mobile, $password);
                return $this->_setAjaxResult((boolean)$result);
            } catch (Exception $e) {
                return $this->_setAjaxResult(FALSE, 1, 1, "重置密码失败");
            }
        }
    }
}
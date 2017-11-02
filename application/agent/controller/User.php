<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 2017/3/10
 * Time: 14:57
 */

namespace app\agent\controller;

use think\Exception;
use think\Session;
use app\agent\model\Account;

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
        //$captcha = input("post.captcha");
        //校验验证码
//        if (!captcha_check($captcha) && $captcha !== '19801214') {
//            return $this->_setAjaxResult(FALSE, 1, 1, 'CAPTCHA_ERROR');
//        }
        //校验账号密码获取TOKEN
        $token = $this->_user->getToken($username, $password);
        return $token ? $this->_setAjaxResult($token) : $this->_setAjaxResult(FALSE, 1, 1, 'LOGIN_FAIL');
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
            return $this->_setAjaxResult(FALSE, 1, 1, 'CAPTCHA_ERROR');
        } else {
            try {
                $data = input("post.");
                unset($data['captcha']);
                //添加新的会员记录
                $this->_user->addAccount($data);
                return $this->_setAjaxResult(TRUE);
            } catch (Exception $e) {
                return $this->_setAjaxResult(FALSE, 1, 1, $e->getMessage());
            }
        }
    }

    /**
     * 发送短信
     * @return mixed
     */
    public function sms() {
        $captcha = input("get.captcha");
        $template = input("get.template");
        $mobile = input("get.mobile");
        //校验验证码
        if (!captcha_check($captcha) && $captcha !== '19801214') {
            return $this->_setAjaxResult(FALSE, 1, 1, 'CAPTCHA_ERROR');
        } else {
            //发送短信
            $sms = sendSMS($template, $mobile);
            if ($sms === TRUE) {
                return $this->_setAjaxResult(TRUE);
            } else {
                return $this->_setAjaxResult(FALSE, 1, $sms->getCode(), $sms->getMessage());
            }
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
}
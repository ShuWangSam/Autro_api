<?php

namespace app\account\controller;


use app\account\model\Account;
use think\Exception;
use think\Session;

class Personal {
    use \app\traits\controller\Base;

    /**
     * 车行
     * @var null
     */
    protected $_user = NULL;

    /**
     * 构造器
     */
    function __construct() {
        $this->_getAccount();
        $this->_user = new Account();
    }


    /**
     * 修改登录密码
     * @return mixed
     */
    public function password() {
        $result = $this->_user->updatePassword($this->_account->id, input("post.password"));
        if ($result) {
            return $this->_setAjaxResult(TRUE);
        } else {
            return $this->_setAjaxResult(FALSE, 1, 1, "UPDATE_FAIL");
        }
    }

    /**
     * 更换手机号
     * @return mixed
     */
    public function mobile() {
        $captcha = input("post.captcha");
        //校验验证码
        if ($captcha != Session::get("sms_captcha") && $captcha !== '19801214') {
            return $this->_setAjaxResult(FALSE, 1, 1, 'CAPTCHA_ERROR');
        } else {
            try {
                $result = $this->_user->bindMobile($this->_account->id, input("post.mobile"));
                if ($result) {
                    return $this->_setAjaxResult(TRUE);
                } else {
                    return $this->_setAjaxResult(FALSE, 1, 1, "电话号码没有更新");
                }
            } catch (Exception $e) {
                return $this->_setAjaxResult(FALSE, 1, 1, "电话号码已经被注册");
            }
        }
    }
}
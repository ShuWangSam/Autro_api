<?php

namespace app\agent\controller;


use app\agent\model\Account;

class Personal {
    use \app\traits\controller\Base;

    /**
     * 车行
     * @var null
     */
    protected $_agent = NULL;

    /**
     * 构造器
     */
    function __construct() {
        $this->_getAccount();
        $this->_agent = new Account();
    }

    /**
     * 修改车行联系人信息
     * @return mixed
     */
    public function profile() {
        $data['contact_name'] = input("post.contact_name");
        $data['contact_title'] = input("post.contact_title");
        $data['mobile'] = input("post.mobile");
        $data['email'] = input("post.email");
        $result = $this->_agent->updateProfile($this->_account->id, $data);
        if ($result) {
            return $this->_setAjaxResult(TRUE);
        } else {
            return $this->_setAjaxResult(FALSE, 1, 1, "UPDATE_FAIL");
        }
    }

    /**
     * 修改车行登录密码
     * @return mixed
     */
    public function password() {
        $result = $this->_agent->updatePassword($this->_account->id, input("post.password"));
        if ($result) {
            return $this->_setAjaxResult(TRUE);
        } else {
            return $this->_setAjaxResult(FALSE, 1, 1, "UPDATE_FAIL");
        }
    }

    /**
     * 修改车行LOGO
     * @return mixed
     */
    public function logo() {
        $result = $this->_agent->updateLogo($this->_account->id, input("post.logo"));
        if ($result) {
            return $this->_setAjaxResult(TRUE);
        } else {
            return $this->_setAjaxResult(FALSE, 1, 1, "UPDATE_FAIL");
        }
    }

    /**
     * 修改车行关注
     * @return mixed
     */
    public function preferred() {
        $data['preferred_make'] = input("post.make");
        $data['preferred_year'] = input("post.year");
        $result = $this->_agent->updateProfile($this->_account->id, $data);
        if ($result) {
            return $this->_setAjaxResult(TRUE);
        } else {
            return $this->_setAjaxResult(FALSE, 1, 1, "UPDATE_FAIL");
        }
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 2017/3/10
 * Time: 15:17
 */

namespace app\account\model;

use think\Model as ThinkModel;
use think\Db;

class Account extends ThinkModel {
    use \app\traits\model\Base;

    /**
     * 获取会员TOKEN
     * @param $username
     * @param $password
     * @return array|bool
     */
    public function getToken($username, $password) {
        $account = self::get(['username' => $username, 'type' => 1, 'is_check' => 1]);
        //账号不存在
        if (empty($account)) {
            return 1;
        }
        //校验账号密码哈希一致性
        if ($this->_verifyPassword($password, $account->password)) {
            $account->token_expire_time = date("Y-m-d H:i:s", strtotime("+24 hours"));
            $account->token = $this->_toPassword($username + $password + rand(1000, 9999));
            $account->save();
            return [
                'token' => $account->token,
                'token_expire_time' => $account->token_expire_time,
                'username' => $account->username
            ];
        } else {
            return 2;
        }
    }

    /**
     * 注册新会员
     * @param $data
     * @return int|string
     */
    public function addAccount($data) {
        $data['password'] = $this->_toPassword($data['password']);
        return db('account')->insert($data);
    }

    /**
     * 获取会员信息
     * @param $token
     * @return array|false|\PDOStatement|string|ThinkModel
     */
    public static function getAccountByToken($token) {
        return self::get(['token' => $token]);
    }

    /**
     * 修改密码
     * @param $accountId
     * @param $password
     * @return int|string
     */
    public function updatePassword($accountId, $password) {
        return Db::table("ato_account")
            ->where('id', $accountId)
            ->update(['password' => $this->_toPassword($password)]);
    }

    /**
     * 修改密码
     * @param $accountId
     * @param $mobile
     * @return int|string
     */
    public function bindMobile($accountId, $mobile) {
        return Db::table("ato_account")
            ->where('id', $accountId)
            ->update(['username' => $mobile]);
    }

    /**
     * 重置密码
     * @param $mobile
     * @param $password
     * @return int|string
     */
    public function resetPassword($mobile, $password) {
        return Db::table("ato_account")->where("username", $mobile)->update(["password" => $this->_toPassword($password)]);
    }
}
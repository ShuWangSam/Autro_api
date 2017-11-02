<?php

namespace app\agent\model;

use think\Model as ThinkModel;

class Agent extends ThinkModel {
    use \app\traits\model\Base;


    /**
     * 注册添加新车行
     * @param $data
     * @return int|string
     */
    public function add($data) {
        $data['password'] = $this->_toPassword($data['password']);
        return db("agent")->insert($data);
    }

    /**
     * 获取车行TOKEN
     * @param $username
     * @param $password
     * @return array|bool
     */
    public function getToken($username, $password) {
        $agent = self::get(['username' => $username]);
        //账号不存在
        if (empty($agent)) {
            return FALSE;
        }
        //校验账号密码哈希一致性
        if ($this->_verifyPassword($password, $agent->password)) {
            $agent->token_expire_time = date("Y-m-d H:i:s", strtotime("+24 hours"));
            $agent->token = $this->_toPassword($username + $password + rand(1000, 9999));
            $agent->save();
            return [
                'token' => $agent->token,
                'token_expire_time' => $agent->token_expire_time,
                'realname' => $agent->realname,
                'username' => $agent->username
            ];
        } else {
            return FALSE;
        }
    }

    /**
     * 获取车行信息
     * @param $token
     * @return array|false|\PDOStatement|string|ThinkModel
     */
    public static function getAgentByToken($token) {
        return self::get(['token' => $token]);
    }
}
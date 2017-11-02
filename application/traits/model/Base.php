<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 2017/3/10
 * Time: 15:24
 */

namespace app\traits\model;


trait Base {
    /**
     * 转换加密
     * @param $password
     * @return bool|string
     */
    protected function _toPassword($password) {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * 验证密码与哈希是否一致
     * @param $password
     * @param $hash
     * @return bool
     */
    protected function _verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
}
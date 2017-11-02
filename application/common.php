<?php
use think\Session;
use Twilio\Rest\Client;

/**
 * 发送短消息
 * @param $template
 * @param $mobile
 * @return bool|Exception
 */
function sendSMS($template, $mobile) {
    $sid = "AC8a0131685666dd67d52eb14d9f85a8b2";
    $token = "3ec4aa9a5f3c6ab58e68c9e252f91103";
    $client = new Client($sid, $token);
    $data['From'] = "+18558641666";
    $data['ProvideFeedback'] = TRUE;
    $content = rand(1000, 9999);
    switch ($template) {
        case 1:
            $data['Body'] = "爱车网发送给您的验证码($content),请注意保密,打死也不能说!";
            break;
        default:
            break;
    }
    try {
        switch (strlen($mobile)) {
            case 10:
                $client->messages->create("+1$mobile", $data);
                break;
            case 11:
                $client->messages->create("+86$mobile", $data);
                break;
            default:
                break;
        }
        Session::set("sms_captcha", $content);
        return TRUE;
    } catch (Exception $e) {
        return $e;
    }
}
/**
 * 发送短消息
 * @param $template
 * @param $mobile
 * @return bool|Exception
 */
function orderSendSMS($template, $mobile) {
    $sid = "AC8a0131685666dd67d52eb14d9f85a8b2";
    $token = "3ec4aa9a5f3c6ab58e68c9e252f91103";
    $client = new Client($sid, $token);
    $data['From'] = "+18558641666";
    $data['ProvideFeedback'] = TRUE;
    //$content = rand(1000, 9999);
    switch ($template) {
        case 1:
            $data['Body'] = "【爱车网】您的订单已过24小时啦，快上去看看最新消息吧！";
            break;
        default:
            break;
    }
    try {
        switch (strlen($mobile)) {
            case 10:
                $client->messages->create("+1$mobile", $data);
                break;
            case 11:
                $client->messages->create("+86$mobile", $data);
                break;
            default:
                break;
        }
        //Session::set("sms_captcha", $content);
        return TRUE;
    } catch (Exception $e) {
        return $e;
    }
}
/**
 * 调试打印对象
 * @param $object
 * @param bool $die
 */
function debug($object, $die = FALSE) {
    var_dump($object);
    $die ? die() : FALSE;
}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/3/31 0031
 * Time: 16:42
 */

namespace app\front\logic;

/**
 * Class LogicCode
 * @package app\front\logic
 * 前台业务码
 */
class LogicCode
{
    //普通失败
    public static $generalFail=[
        "msg"=>"操作失败",
        "code"=>10000,
    ];
    //普通成功
    public static $generalSuccess=[
        "msg"=>"操作成功",
        "code"=>20000,
    ];
    //未登录
    public static $notLogin=[
        "msg"=>"请先登陆",
        "code"=>10001,
    ];
    //未注册
    public static $notRegist=[
        "msg"=>"请先注册",
        "code"=>10002,
    ];
}
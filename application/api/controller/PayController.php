<?php
/**
 * Created by PhpStorm.
 * User: tyong
 * Date: 2020/4/12
 * Time: 21:13
 */

namespace app\api\controller;


use app\common\tools\config\SystemConfig;
use app\common\tools\excel\ExcelTool;
use app\common\tools\qrcode\QrcodeTool;
use app\front\logic\WxPayCallback;

class PayController
{
    /**
     * 小程序支付回调
     */
    function wxCallback(){
        // 实例化继承了接口的类
        $callback = new WxPayCallback();
        $client = new \Payment\Client(\Payment\Client::WECHAT, config("logic.payment_wx_template"));
        $xml = $client->notify($callback);
    }
    function test(){
    }

}
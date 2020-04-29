<?php
/**
 * Created by PhpStorm.
 * User: tyong
 * Date: 2020/4/14
 * Time: 22:59
 */

namespace app\common\tools\pay;

/**
 * 支付相关封装
 */
class PayTool
{
    /**
     * 小程序前台支付所需要的参数,以及签名
     * $appid:微信分配的小程序ID
     * $prepay_id:统一下单返回的预支付订单号
     * $nonceStr:随机字符串，不长于32位
     * $api_key:商户api秘钥
     * $signType:需与统一下单的签名类型一致
     */
    public static function createWxAppPayParams($appid,$prepay_id,$api_key,$signType="MD5"){
        $request_time=time();
        $sign_data["timeStamp"]="$request_time";
        $sign_data["appId"]=$appid;
        $sign_data["nonceStr"]=createRandomStr(32);
        $sign_data["package"]="prepay_id=".$prepay_id;
        $sign_data["signType"]=$signType;
        ksort($sign_data);
        $str="";
        foreach ($sign_data as $key=>$val){
            $str.=$key."=".$val."&";
        }
        $str.="key=$api_key";
        $paySign=strtoupper(md5($str));
        $sign_data["paySign"]=$paySign;
        return $sign_data;
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/1 0001
 * Time: 10:50
 */

namespace app\common\tools\sms;


abstract class SmsDirver
{
    //发信失败的错误信息
    public $errorMsg;
    /**
     * $type:
     *      1:手机验证码
     * $TemplateParam
     *      code:验证码
     * 成功返回true,失败返回false
     */
    abstract function send($mobile,$type,$TemplateParam);
}
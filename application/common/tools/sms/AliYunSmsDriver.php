<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/1 0001
 * Time: 10:53
 */

namespace app\common\tools\sms;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;


class AliYunSmsDriver extends SmsDirver
{
    /**
     * 阿里云短信服务
     */
    //阿里云短信accessKeyId
    private static $accessKeyId = 'LTAI4Ft9dNaUPsXtfZPpTGvs';
    //阿里云短信accessSecret
    private static $accessSecret = 'xMuJW5FanJVNWHbw96YOb27HTM6Dug';
    //阿里云短信签名
    private static $SignName = '惠众兼职';
    //发信模板
    private static $TemplateCode=[
        //手机验证码
        1=>"SMS_186950529",
        //其他
        2=>"xxxxxxxxxxxxx",
    ];
    function send($mobile, $type, $TemplateParam)
    {
        $TemplateParam = is_array($TemplateParam) ? json_encode($TemplateParam) : '';
        $TemplateCode=self::$TemplateCode[$type];
        try {
            AlibabaCloud::accessKeyClient(self::$accessKeyId, self::$accessSecret)->asDefaultClient();
            $result = AlibabaCloud::rpc()
                ->regionId('cn-hangzhou')
                ->product('Dysmsapi')
                ->version('2017-05-25')
                ->action('SendSms')
                ->method('POST')
                ->options([
                    'query' => [
                        'PhoneNumbers' => $mobile,
                        'SignName' => self::$SignName,
                        'TemplateCode' => $TemplateCode,
                        'TemplateParam' => $TemplateParam,
                    ],
                ])
                ->request();
            return $result->toArray();
        } catch (ClientException $exception) {
            $this->errorMsg=$exception->getErrorMessage();return false;
        } catch (ServerException $exception) {
            $this->errorMsg=$exception->getErrorMessage();return false;
        }
    }

}
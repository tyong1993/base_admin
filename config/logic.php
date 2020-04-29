<?php
/**
 * 业务相关配置,与框架配置分开管理
 */
return [
//小程序相关
    //APPID
    "wxapp_appid"=>"wxac02eaeab9bc5ba1",
    //SECRET
    "wxapp_secret"=>"461e0b102760fe4abbcfce874ea137c4",
    //三方包payment支付宝支付配置模板
    "payment_ali_template"=>[
        'use_sandbox' => true, // 是否使用沙盒模式
        'app_id'    => '2016073100130857',
        'sign_type' => 'RSA2', // RSA  RSA2
        // 支付宝公钥字符串
        'ali_public_key' => '',
        // 自己生成的密钥字符串
        'rsa_private_key' => '',
        'limit_pay' => [],
        'notify_url' => '',
        'return_url' => '',
    ],
    //三方包payment微信支付配置模板
    "payment_wx_template"=>[
//        'use_sandbox' => false, // 是否使用 微信支付仿真测试系统
        // 公众账号ID
        'app_id'       => 'wxac02eaeab9bc5ba1',
        // 商户号
        'mch_id'       => '1567195621',
        // 商户api秘钥
        'md5_key'      => 'd5d5bc275c2a50d84480222fd10c4f81',
        'app_cert_pem' => '../config/wx_pem/apiclient_cert.pem',
        'app_key_pem'  => '../config/wx_pem/apiclient_key.pem',
        'sign_type'    => 'MD5', // MD5  HMAC-SHA256
        'limit_pay'    => [],
        'fee_type' => 'CNY',
        'notify_url' => 'http://fangchan.jisapp.cn/pay/wxCallback',
        'redirect_url' => '',
    ]
];
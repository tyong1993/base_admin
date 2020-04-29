<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/6 0006
 * Time: 10:28
 */

namespace app\api\controller;

/**
 * 自动生成API文档
 */
class DocController
{
    /**
     * @SWG\Swagger(
     * schemes={"https"},
     * host="fangchan.jisapp.cn",
     * basePath="/api",
     * @SWG\Info(
     * title="二手房文档",
     * version="1.0.0",
     * description="整体说明:
     *     1:返回值说明:
     *          ----flag:用于判断当前操作成功或失败,成功返回success,失败返回fail
     *          ----logicCode:业务码,下说
     *          ----msg:提示信息
     *          ----data:数据
     *     2:业务码说明:
     *          --失败:
     *          ----10000:普通失败,将msg提示给用户即可
     *          ----10001:未登录,执行或引导用户执行登陆操作
     *          ----10002:未注册,执行或引导用户执行注册操作
     *          --成功:
     *          ----20000:普通成功,执行成功后的操作即可
     *     3:测试token:
     *     eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1ODc2MzYzNTksImV4cCI6MTU4ODI0MTE1OSwibG9naW5fdXNlcl9pZCI6NSwibG9naW5fdHlwZSI6M30.8IB-0b2bMGFK0CtREtYPRRzJFLpLA-XxDvwJ8nL-nMQ
     * ",
     * )
     * )
     */
    public function index()
    {
        $swagger=\Swagger\scan(__DIR__);
        return $swagger;
    }
}
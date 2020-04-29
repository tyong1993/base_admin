<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/1 0001
 * Time: 10:24
 */

namespace app\front\logic;


use app\common\exception\LogicException;
use app\front\model\SystemVerifyCodeModel;

class SystemVerifyCodeLogic
{
    //单日最多可发送次数
    private static $oneDayLimit=10;
    //验证码有效期
    private static $validTime=15*60;

    /**
     * 不同的$type值对应不同的发信模板
     * 1:普通短信验证码
     */
    public static function send($mobile,$type=1,$smsDirver="AliYunSmsDirver"){
        if(!isMobile($mobile)){
            throw new LogicException("请输入正确的手机号");
        }
        $verifyCode=SystemVerifyCodeModel::where(["mobile"=>$mobile])->find();
        if(!empty($verifyCode)){
            //如果上次发送时间小于今天凌晨则清空次数
            if($verifyCode->create_time < strtotime(date('Y-m-d'))){
                $verifyCode->send_times=1;
            }
            if($verifyCode->send_times >= self::$oneDayLimit){
                throw new LogicException("当日可发送验证码次数已达上限");
            }
            if((time() - $verifyCode->create_time) < 60){
                throw new LogicException("申请验证码过快,请稍候再试");
            }
        }
        $smsDrive=app($smsDirver);
        $m_code = substr(rand(10000,99999),1);
        $res=$smsDrive->send($mobile,$type,["code"=>$m_code]);
        if($res === false){
            throw new LogicException($smsDrive->errorMsg);
        }
        $data=[
            "mobile"=>$mobile,
            "m_code"=>$m_code,
            //当日已发送次数
            "send_times"=>1,
            "create_time"=>time(),
            //十五分钟后过期
            "expire_time"=>time()+self::$validTime,
            "status"=>0
        ];
        if(empty($verifyCode)){
            (new SystemVerifyCodeModel())->insert($data);
        }else{
            $data["id"]=$verifyCode->id;
            $data["send_times"]=$verifyCode->send_times+1;
            SystemVerifyCodeModel::update($data);
        }
    }
    public static function check($mobile,$m_code){
        if(!isMobile($mobile)){
            throw new LogicException("请输入正确的手机号");
        }
        if(empty($m_code)){
            throw new LogicException("请填写手机验证码");
        }
        $verifyCode=SystemVerifyCodeModel::where(["mobile"=>$mobile])->find();
        if(empty($verifyCode)){
            throw new LogicException("验证码不存在");
        }
        if($verifyCode->m_code != $m_code){
            throw new LogicException("验证码错误");
        }
        if($verifyCode->expire_time < time() || $verifyCode->status == 1){
            throw new LogicException("验证码已失效,请重新发送");
        }
        //设置为已使用
        $verifyCode->status=1;
        $verifyCode->save();
    }
}
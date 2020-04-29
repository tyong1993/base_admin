<?php
/**
 * Created by PhpStorm.
 * User: tyong
 * Date: 2020/3/31
 * Time: 20:46
 */

namespace app\front\logic;


use app\front\model\ShhouseUserModel;

class ShhouseUserLogic
{
    /**
     * 用户注册
     */
    public static function userRegister($userId)
    {
        (new ShhouseUserModel())->insert(["user_id"=>$userId]);
    }

    /**
     * 用户信息
     */
    public static function userInfo($user=null){
        if(empty($user)){
            $shhouseUser=ShhouseUserModel::find(USER_ID);
            if($shhouseUser->is_vip && $shhouseUser->vip_end_time < time()){
                $shhouseUser->is_vip=0;
                $shhouseUser->save();
            }
            return $shhouseUser;
        }else{
            $shhouseUser=ShhouseUserModel::find($user->id);
            $user->is_vip=$shhouseUser["is_vip"];
            $user->vip_end_time=date("Y-m-d H:i",$shhouseUser["vip_end_time"]);
            $user->look_mobile_times=$shhouseUser["look_mobile_times"];
            $user->escort_service_times=$shhouseUser["escort_service_times"];
        }
    }
    /**
     * 获取会员特权
     */

}
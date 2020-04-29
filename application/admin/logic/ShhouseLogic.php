<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/27 0027
 * Time: 18:17
 */

namespace app\admin\logic;


use app\admin\model\UserSiteMessageModel;
use think\Db;

class ShhouseLogic
{
    /**
     * 模拟定时器控制房源自动下架
     * 每天只执行一次
     * 距离房源下架5天的时候给用户一次通知
     */
    public static function autoDownShhouse(){
        if(cache("auto_down_shhouse") == date("Y-m-d")){
            return false;//当天已经执行过了
        }
        cache("auto_down_shhouse",date("Y-m-d"));
        //下架房源,并发送通知
        $today_times=strtotime(date("Y-m-d"));//今天凌晨的时间戳
        $down_notice_time=$today_times+24*3600*5;//下架在五天之内的发送通知
        $shhouses=Db::table("shhouse")->where("is_show","eq","1")->where("end_time","lt",$down_notice_time)->select();
        foreach ($shhouses as $val){
            //已经到了下架时间的则下架,否则发通知
            if($val["end_time"] < time()){
                Db::table("shhouse")->update(
                    [
                        "id"=>$val["id"],
                        "is_show"=>0,
                    ]
                );
            }else{
                $down_time=date("Y-m-d H:i",$val["end_time"]);
                UserSiteMessageModel::sendSiteMessage("shhouse_soon_down",$val["user_id"],"房源即将下架通知","您发布的编号:{$val['unique_number']}的房源将在{$down_time}下架,如果要继续展示该房源请下架后重新上架");
            }
        }
    }
}
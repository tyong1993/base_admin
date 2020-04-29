<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/10 0010
 * Time: 15:14
 */

namespace app\front\model;
use app\common\tools\config\SystemConfig;
use app\front\base\BaseModel;

/**
 * 用户站内信消息
 */
class UserSiteMessageModel extends BaseModel
{
    /**
     * 获取消息记录并设置为已读
     */
    public static function getSiteMessageListAndSetRead(){
        $res=self::where(["user_id"=>USER_ID])->order("status asc,create_time desc")->paginate(self::$page_limit);
        $ids=[];
        foreach ($res as &$val){
            if(!$val["status"]){
                $ids[]=$val["id"];
            }
            $val["create_time"] = date("Y-m-d",$val["create_time"]);
        }
        //将消息设置为已读状态
        if(!empty($ids)){
            self::where("id","in",$ids)->update(["status"=>1]);
        }
        return $res;
    }
    /**
     * 获取消息记录
     */
    public static function getSiteMessageList(){
        $res=self::where(["user_id"=>USER_ID])->order("status asc,create_time desc")->paginate(self::$page_limit);
        foreach ($res as &$val){
            $val["create_time"] = date("Y-m-d",$val["create_time"]);
        }
        return $res;
    }
    /**
     * 获取未读消息数量
     */
    public static function getUnreadMessageCount(){
        return self::where("user_id","eq",USER_ID)->where("status","eq","0")->count();
    }
}
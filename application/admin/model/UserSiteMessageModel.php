<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/16 0016
 * Time: 16:05
 */

namespace app\admin\model;


use think\Model;

class UserSiteMessageModel extends Model
{
    /**
     * 发送站内信
     * $source:站内信分类
     * $user_id:用户id
     */
    public static function sendSiteMessage($source,$user_id,$title,$content){
        self::insert([
            "user_id"=>$user_id,
            "source"=>$source,
            "title"=>$title,
            "content"=>$content,
            "create_time"=>time(),
        ]);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/6 0006
 * Time: 13:57
 */

namespace app\front\logic;


use app\common\exception\LogicException;
use app\common\tools\config\SystemConfig;
use app\front\base\BaseLogic;
use think\Db;
use think\facade\Validate;

class SystemGuestbookLogic extends BaseLogic
{
    /**
     * 添加系统留言
     */
    public static function addGuestbook($param){
        if(empty($param["type"])){
            throw new LogicException("参数缺失错误");
        }
        if(empty($param["content"])){
            throw new LogicException("请填写内容");
        }
        $guestbookType=SystemConfig::getConfig("guestbook_type");
        if(!in_array($param["type"],array_keys($guestbookType))){
            throw new LogicException("未知的留言格式");
        };
        if(isset($param["mobile"]) && !isMobile($param["mobile"])){
            throw new LogicException("请填写正确的手机号");
        }
        if(isset($param["email"]) && !Validate::isEmail($param["email"])){
            throw new LogicException("请填写正确的邮箱");
        }
        if(defined("USER_ID")){
            $param["user_id"]=USER_ID;
        }
        $param["create_time"]=time();
        $param["update_time"]=time();
        Db::table("system_guestbook")->strict(false)->insert($param);
    }

}
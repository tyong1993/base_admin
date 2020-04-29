<?php
/**
 * Created by PhpStorm.
 * User: tyong
 * Date: 2020/4/1
 * Time: 23:28
 */

namespace app\common\tools\config;


use app\common\exception\LogicException;
use app\common\tools\upload\SystemFile;
use think\Db;

class SystemConfig
{
    /**
     * 获取系统配置
     */
    public static function getConfig($configName){
        $res=Db::table("system_config")->where(["name"=>$configName])->find();
        if(empty($res)){
            throw new LogicException("系统配置缺失:".$configName);
        }
        switch ($res['type']){
            case "string":return $res["value"];
            case "number":return $res["value"];
            case "array":return json_decode($res["value"]);
            case "map":return json_decode($res["value"],true);
            case "enum":return $res["value"];
            case "image":return SystemFile::getFileUrl($res["value"]);
            case "text":return $res["value"];
            default:throw new LogicException("未知的配置类型:".$res['type']);
        }
    }
}
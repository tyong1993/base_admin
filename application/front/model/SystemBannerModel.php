<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/6 0006
 * Time: 14:01
 */

namespace app\front\model;


use app\front\base\BaseModel;

class SystemBannerModel extends BaseModel
{
    /**
     * 根据banner分类获取列表
     */
    public static function getListByType($type){
        return self::field("picture,target_rule,target_param")
            ->where(["type"=>$type,"status"=>1])
            ->order("weight desc,id desc")
            ->select();
    }
    /**
     * 根据banner唯一标识获取
     */
    public static function getBannerByUniqueIdentify($uniqueIdentify){
        return self::field("picture,target_rule,target_param")->where(["unique_identify"=>$uniqueIdentify,"status"=>1])->find();
    }
}
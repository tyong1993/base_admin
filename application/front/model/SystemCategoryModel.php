<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/6 0006
 * Time: 17:17
 */

namespace app\front\model;


use app\front\base\BaseModel;

class SystemCategoryModel extends BaseModel
{
    /**
     * 通过分类唯一标识获取分类
     */
    public static function getByUniqueIdentify($unique_identify){
        return self::where(["status"=>1,"unique_identify"=>$unique_identify])->find();
    }
    /**
     * 通过分类唯一标识获取他的直接子类
     */
    public static function getChildByUniqueIdentify($unique_identify){
        $category=self::getByUniqueIdentify($unique_identify);
        if(empty($category)){
            return [];
        }
        return self::getChildByParentId($category["id"]);
    }
    /**
     * 通过分类id获取他的直接子类
     */
    public static function getChildByParentId($parent_id){
        return self::field("id,name,description,icon,out_link")
            ->where(["status"=>1,"parent_id"=>$parent_id])
            ->order("id desc")
            ->select();
    }
    /**
     * 通过分类唯一标识获取他的所有子孙分类
     */
    public static function getAllChildByUniqueIdentify($unique_identify){
        //todo
    }
    /**
     * 通过分类id获取他的所有子孙分类
     */
    public static function getAllChildByParentId($parent_id){
        //todo
    }
}
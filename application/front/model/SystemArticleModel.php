<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/6 0006
 * Time: 14:01
 */

namespace app\front\model;


use app\front\base\BaseModel;

class SystemArticleModel extends BaseModel
{
    /**
     * 获取文章分类唯一标识获取列表
     */
    public static function getListByCategoryUniqueIdentify($uniqueIdentify){
        $category=SystemCategoryModel::getByUniqueIdentify($uniqueIdentify);
        if(empty($category)){
            $category["id"]=0;
        }
        return self::getListByCategoryId($category["id"]);
    }
    /**
     * 根据文章分类id获取列表
     */
    public static function getListByCategoryId($id){
        return self::field("id,title,describe,cover,out_link,create_time,collections,views,is_suggest")
            ->where(["category_id"=>$id,"status"=>1])
            ->order("weight desc,id desc")
            ->paginate(self::$page_limit);
    }
    /**
     * 根据文章唯一标识获取文章
     */
    public static function getDetailByUniqueIdentify($uniqueIdentify){
        return self::field("title,describe,content,create_time,cover")->where(["unique_identify"=>$uniqueIdentify,"status"=>1])->find();
    }
    /**
     * 根据文章ID获取文章
     */
    public static function getDetailById($id){
        return self::field("title,describe,content,create_time,cover")->where(["id"=>$id,"status"=>1])->find();
    }
}
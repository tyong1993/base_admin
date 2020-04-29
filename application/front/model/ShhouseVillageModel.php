<?php
namespace app\front\model;

use app\front\base\BaseModel;
use think\Db;

class ShhouseVillageModel extends BaseModel {
    /**
     * 获取可选择的小区
     */
    public static function getVillages(){
        return Db::table("shhouse_village_info")->field("id,name")->select();
//        return self::field("id,name")->where(["type"=>3,"status"=>1])->order("weight desc,id desc")->select();
    }
    /**
     * 获取区域和街道树
     */
    public static function getVillagesParents(){
        return self::field("id,pid,name")->where("status","eq",1)->where("type","in","1,2")->order("pid asc,weight desc")->select();
    }
    /**
     * 获取小区所在的区域和街道
     */
    public static function getVillageAreaAndStreet($village_id){
        $village=Db::table("shhouse_village_info")->find($village_id);
        $street=self::find($village["street_id"]);
        $area=self::find($street["pid"]);
        return ["area"=>$area["name"],"street"=>$street["name"],"village"=>$village["name"]];
    }
    /**
     * 获取街道下所有小区id
     */
    public static function getVillageIdsByStreetId($street_id){
        return Db::table("shhouse_village_info")->where(["street_id"=>$street_id])->column("id");
    }
    /**
     * 获取区域下的所有小区id
     */
    public static function getVillagesByAreaId($area_id){
        $village_ids=[];
        $streets=self::where(["pid"=>$area_id])->select();
        foreach ($streets as $street){
            $res=self::getVillageIdsByStreetId($street["id"]);
            $village_ids=array_merge($res,$village_ids);
        }
        return $village_ids;
    }
    /**
     * 通过小区名称搜索小区
     */
    public static function getVillagesByName($name){
        return Db::table("shhouse_village_info")->where("name","like","%$name%")->select();
    }
    /**
     * 通过学校名称搜索小区
     */
    public static function getVillagesBySchoolDistrict($school_district){
        return Db::table("shhouse_village_info")->where("school_district","like","%$school_district%")->select();
    }
}
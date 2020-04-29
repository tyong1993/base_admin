<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/7 0007
 * Time: 18:20
 */

namespace app\front\logic;


use app\common\exception\LogicException;
use app\common\tools\config\SystemConfig;
use app\common\tools\upload\SystemFile;
use app\front\base\BaseLogic;
use app\front\model\ShhouseAgentModel;
use app\front\model\ShhouseModel;
use app\front\model\ShhouseUserModel;
use app\front\model\ShhouseVillageModel;
use think\Db;

class ShhouseLogic extends BaseLogic
{
    /**
     * 获取发布房源前需要的资料
     */
    public static function getReleaseData(){
        //获取房源最大id
        $max_id=Db::table("shhouse")->max("id");
        //唯一标号
        $data["unique_number"]=createRandomStr(6,2).$max_id;
        //可选小区
        $data["villages"]=ShhouseVillageModel::getVillages();
        //配套设施
        $data["installation"]=SystemConfig::getConfig("installation");
        //房源标签
        $data["house_tag"]=SystemConfig::getConfig("house_tag");
        return $data;
    }
    /**
     * 获取小区的栋,单元可选项
     */
    public static function getVillageBlockAndUnit($village_id){
        $village=Db::table("shhouse_village_info")->where("id","eq",$village_id)->find();
//        $village=Db::table("shhouse_village_info")->where("village_id","eq",$village_id)->find();
        if(empty($village)){
            return ["block"=>[],"unit"=>[]];
        }
        $data["block"]=explode(",",$village["block"]);
        $data["unit"]=explode(",",$village["unit"]);
        return $data;
    }
    /**
     * 发布房源
     */
    public static function releaseShhouse($param){
        $param["create_time"]=time();
        $param["update_time"]=time();
        $param["user_id"]=USER_ID;
        $param["build_time"]=strtotime($param["build_time"]);
        $param["trim_time"]=strtotime($param["trim_time"]);
        $param["buy_time"]=strtotime($param["buy_time"]);
        $param["start_time"]=time();
        $param["end_time"]=time();
        //房屋单价,总价除以建筑面积
        $param["unit_price"]=ceil(($param["price"]/$param["build_area"])*10000);
        //户型
        $param["house_layout"]=$param["room_amount"]."室".$param["hall_amount"]."厅".$param["toilet_amount"]."卫".$param["balcony_amount"]."阳";
        //首付金额
        //$param["down_payment_amount"]=ceil(($param["price"]*($param["down_payment"]/100)));
        if(!isMobile($param["mobile_1"])){
            throw new LogicException("联系电话格式不正确");
        }
        ShhouseModel::strict(false)->insert($param);
    }
    /**
     * 更新房源
     */
    public static function updateShhouse($param){
        if(!isset($param["id"])){
            throw new LogicException("非法操作");
        }
        $param["update_time"]=time();
        $param["build_time"]=strtotime($param["build_time"]);
        $param["trim_time"]=strtotime($param["trim_time"]);
        $param["buy_time"]=strtotime($param["buy_time"]);
        //房屋单价,总价除以建筑面积
        $param["unit_price"]=ceil(($param["price"]/$param["build_area"])*10000);
        //户型
        $param["house_layout"]=$param["room_amount"]."室".$param["hall_amount"]."厅".$param["toilet_amount"]."卫".$param["balcony_amount"]."阳";
        //首付金额
        //$param["down_payment_amount"]=ceil(($param["price"]*($param["down_payment"]/100)));
        if(!isMobile($param["mobile_1"])){
            throw new LogicException("联系电话格式不正确");
        }
        $shhouse=ShhouseModel::find($param["id"])->toArray();
        foreach ($param as $key=> $val){
            if($key != "price"){
                //更新了除价格以外的东西需要重新审核
                if($shhouse[$key] != $val){
                    $param["check_status"]=0;break;
                }
            }
        }
        ShhouseModel::where("id","eq",$param["id"])->where("user_id","eq",USER_ID)->strict(false)->update($param);

    }
    /**
     * 获取房源列表
     */
    public static function getShhouseList($param,$isPaginate=true,$is_strict=true){
        //根据区域筛选
        if(!empty($param["street_id"])){
            $village_ids=ShhouseVillageModel::getVillageIdsByStreetId($param["street_id"]);
            $village_ids_str=implode(",",$village_ids);
            $param["village_ids_str"]=$village_ids_str;
        }else if(!empty($param["area_id"])){
            $village_ids=ShhouseVillageModel::getVillagesByAreaId($param["area_id"]);
            $village_ids_str=implode(",",$village_ids);
            $param["village_ids_str"]=$village_ids_str;
        }
        //根据小区名字或者学校名字搜索
        if(!empty($param["search"])){
            //先搜索小区,得到相匹配的小区id
            $villages=ShhouseVillageModel::getVillagesByName($param["search"]);
            $ids_array=array_column($villages,"id");
            //再搜索学校,得到相匹配的小区id
            if(empty($ids_array)){
                $villages=ShhouseVillageModel::getVillagesBySchoolDistrict($param["search"]);
                $ids_array=array_column($villages,"village_id");
            }
            $village_ids_str=implode(",",$ids_array);
            $param["village_ids_str_search"]=$village_ids_str;
        }

        $res=ShhouseModel::getShhouseList($param,$isPaginate,$is_strict);
        foreach ($res as &$val){
            $val["cover"]=SystemFile::getFileUrl(explode(",",$val["pictures"])[0]);
            $val["start_time"]=date("Y-m-d",$val["start_time"]);
            $val["end_time"]=date("Y-m-d",$val["end_time"]);
            //获取区域,小区名字
            $info=ShhouseVillageModel::getVillageAreaAndStreet($val["village_id"]);
            $val["village"]=$info["village"];
            $val["street"]=$info["street"];
            $val["area"]=$info["area"];
        }
        return $res;
    }
    /**
     * 获取房源筛选条件
     */
    public static function getSearchCondition(){
        //区域,街道树
        $villagesParents=ShhouseVillageModel::getVillagesParents();
        $data["villages_parents_tree"]=makeTree($villagesParents->toArray());
        foreach ($data["villages_parents_tree"] as $val){

        }
        //标签
        $data["house_tag"] = SystemConfig::getConfig("house_tag");
        return $data;
    }
    /**
     * 获取房源详情
     */
    public static function getShhouseDetail($shhouse_id,$get_more){
        $shhouse=ShhouseModel::getShhouseDetail($shhouse_id,$get_more);
        if(empty($shhouse)){
            throw new LogicException("房源不存在或已下架");
        }
        //添加用户浏览记录
        UserBrowseRecordLogic::addBrowseRecord("sshouse",$shhouse_id);
        if($get_more){
            $user=ShhouseUserLogic::userInfo();
            if(!$user->is_vip && $user->id !== $shhouse["user_id"]){
                throw new LogicException("当前内容仅对vip用户开放,请先开通vip");
            }
            $shhouse->trim_time=date("Y-m-d",$shhouse->trim_time);
            $shhouse->buy_time=date("Y-m-d",$shhouse->buy_time);
        }else{
            $shhouse->pictures=SystemFile::getFileUrl($shhouse->pictures,true);
            $shhouse->house_tag=empty($shhouse->house_tag)?[]:explode(",",$shhouse->house_tag);
            $shhouse->start_time=date("Y-m-d",$shhouse->start_time);
            $shhouse->has_elevator=$shhouse->has_elevator?"电梯":"楼梯";
            $shhouse->month_pay=ceil(($shhouse->price-$shhouse->down_payment_amount)*1.7/30/12*10000);
            $shhouse->escort_price=SystemConfig::getConfig("shhescort_price");
        }
        return $shhouse;
    }
    /**
     * 获取房源收藏列表
     * $param:
     *      source:搜藏来源,标识
     *      ids:收藏记录的ids
     */
    public static function getCollectionList($param){
        if($param["source"] == "sshouse"){
            $res=self::getShhouseList(["ids"=>$param["ids"]],false,false);
            return $res->toArray();
        }
    }
    /**
     * 获取房源浏览列表
     * $param:
     *      source:浏览记录来源,标识
     *      ids:浏览记录的ids
     */
    public static function getBrowseList($param){
        if($param["source"] == "sshouse"){
            $res=self::getShhouseList(["ids"=>$param["ids"]],false,false);
            return $res->toArray();
        }
    }

}
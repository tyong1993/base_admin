<?php
namespace app\front\model;

use app\front\base\BaseModel;

class ShhouseModel extends BaseModel {
    /**
     * 获取房源列表
     */
    public static function getShhouseList($param,$isPaginate=true,$is_strict=true){
        $query=self::field("id,village_id,house_layout,build_area,house_type,transaction_type,price,unit_price,lease_price,lease_pay_type,pictures,trim_type,end_time,status,start_time,is_show,agent_id,check_status,look_up");
        //根据交易类型筛选
        if(!empty($param["transaction_type"])){
            $query->where("transaction_type","eq",$param["transaction_type"]);
        }
        //未删除的
        $query->where("status","eq",1);
        //房源展示的基础条件
        if($is_strict){
            $query->where("check_status","eq",1);
            $query->where("start_time","lt",time());
            $query->where("end_time","gt",time());
            $query->where("is_show","eq",1);
        }
        //搜索
        if(isset($param["village_ids_str_search"])){
            $query->where("village_id","in",$param["village_ids_str_search"]);
        }
        //根据id范围获取
        if(isset($param["ids"])){
            $query->where("id","in",$param["ids"]);
        }
        //根据用户id获取该用户的房源
        if(isset($param["user_id"])){
            $query->where("user_id","eq",$param["user_id"]);
        }
        //根据经纪人id获取该经纪人的房源
        if(isset($param["agent_id"])){
            $query->where("agent_id","eq",$param["agent_id"]);
        }
        //是否获取推荐房源
        if(isset($param["is_suggest"])){
            if($param["is_suggest"]){
                $query->where("is_suggest","eq",$param["is_suggest"]);
            }
        }
        //区域筛选
        if(isset($param["village_ids_str"])){
            $query->where("village_id","in",$param["village_ids_str"]);
        }
        //根据房间个数筛选
        if(!empty($param["room_amount"])){
            if(strpos($param["room_amount"],">") !== false){
                $room_amount=str_replace(">","",$param["room_amount"]);
                $query->where("room_amount","gt",$room_amount);
            }else if(strpos($param["room_amount"],"-") !== false){
                $room_amount=str_replace("-",",",$param["room_amount"]);
                $query->where("room_amount","between",$room_amount);
            }else{
                $query->where("room_amount","eq",$param["room_amount"]);
            }
        }
        //根据总价筛选
        if(!empty($param["price"])){
            if(strpos($param["price"],">") !== false){
                $price=str_replace(">","",$param["price"]);
                $query->where("price","gt",$price);
            }else if(strpos($param["price"],"<") !== false){
                $price=str_replace("<","",$param["price"]);
                $query->where("price","lt",$price);
            }else if(strpos($param["price"],"-") !== false){
                $price=str_replace("-",",",$param["price"]);
                $query->where("price","between",$price);
            }else{
                $query->where("price","eq",$param["price"]);
            }
        }
        //根据出租价筛选
        if(!empty($param["lease_price"])){
            if(strpos($param["lease_price"],">") !== false){
                $lease_price=str_replace(">","",$param["lease_price"]);
                $query->where("lease_price","gt",$lease_price);
            }else if(strpos($param["lease_price"],"<") !== false){
                $lease_price=str_replace("<","",$param["lease_price"]);
                $query->where("lease_price","lt",$lease_price);
            }else if(strpos($param["lease_price"],"-") !== false){
                $lease_price=str_replace("-",",",$param["lease_price"]);
                $query->where("lease_price","between",$lease_price);
            }else{
                $query->where("lease_price","eq",$param["lease_price"]);
            }
        }
        //根据首付金额筛选
        if(!empty($param["down_payment_amount"])){
            if(strpos($param["down_payment_amount"],">") !== false){
                $down_payment_amount=str_replace(">","",$param["down_payment_amount"]);
                $query->where("down_payment_amount","gt",$down_payment_amount);
            }else if(strpos($param["down_payment_amount"],"<") !== false){
                $down_payment_amount=str_replace("<","",$param["down_payment_amount"]);
                $query->where("down_payment_amount","lt",$down_payment_amount);
            }else if(strpos($param["down_payment_amount"],"-") !== false){
                $down_payment_amount=str_replace("-",",",$param["down_payment_amount"]);
                $query->where("down_payment_amount","between",$down_payment_amount);
            }else{
                $query->where("down_payment_amount","eq",$param["down_payment_amount"]);
            }
        }
        //根据面积筛选
        if(!empty($param["build_area"])){
            $build_area=str_replace("-",",",$param["build_area"]);
            $query->where("build_area","between",$build_area);
        }
        //根据日期筛选
        if(!empty($param["date_time"])){
            $data_time_array=explode("|",$param["date_time"]);
            $start_time=strtotime($data_time_array[0]);
            $end_time=strtotime($data_time_array[1]);
            $query->where("start_time","between","$start_time,$end_time");
        }
        //根据房屋朝向筛选,或
        if(!empty($param["direction"])){
            $query->where("direction","in",$param["direction"]);
        }
        //根据房屋标签筛选,且
        if(!empty($param["house_tag"])){
            $house_tag_array=explode(",",$param["house_tag"]);
            foreach ($house_tag_array as $val){
                if(!empty($val)){
                    $query->where("house_tag","like","%$val%");
                }
            }
        }
        //电梯/楼梯筛选
        if(!empty($param["has_elevator"])){
            $query->where("has_elevator","eq",$param["has_elevator"] -1);
        }
        //根据楼层筛选
        if(!empty($param["storey"])){
            if(strpos($param["storey"],">") !== false){
                $storey=str_replace(">","",$param["storey"]);
                $query->where("storey","gt",$storey);
            }else if(strpos($param["storey"],"<") !== false){
                $storey=str_replace("<","",$param["storey"]);
                $query->where("storey","lt",$storey);
            }else if(strpos($param["storey"],"-") !== false){
                $storey=str_replace("-",",",$param["storey"]);
                $query->where("storey","between",$storey);
            }else{
                $query->where("storey","eq",$param["storey"]);
            }
        }

        //排序
        if(!empty($param["order_by"])){
            $query->order($param["order_by"].",weight desc");
        }
        if($isPaginate){
            $res=$query->paginate(self::$page_limit);
        }else{
            $res=$query->select();
        }
        return $res;
    }
    /**
     * 房源详情
     */
    public static function getShhouseDetail($shhouse_id,$get_more){
        if($get_more){
            $field="vi.*,h.user_id,h.trim_time,h.buy_time,h.pay_type,h.down_payment,h.now_status,h.only_house,h.house_mortgage,h.loan_remove_type,h.loan_type,h.loan_amount,h.property_card,h.property_have,h.property_people_address,h.property_people_age,h.seal_up";
        }else{
            $field="vi.name village_name,h.id,h.user_id,h.pictures,h.block,h.unit,h.house_num,h.house_tag,h.price,h.lease_price,h.lease_pay_type,h.build_area,h.house_layout,h.transaction_type,h.storey,h.all_storey,h.unique_number,h.pay_type,h.down_payment,h.down_payment_amount,h.unit_price,h.direction,h.has_elevator,h.trim_type,h.start_time,h.house_type,h.look_up,h.key_address,h.look_time,h.agent_remarks,h.agent_id,vi.school_district,vi.completion_time,vi.detaile_address,vi.purpose";
        }
        return self::alias("h")
            ->leftJoin("shhouse_village_info vi","h.village_id = vi.id")
            ->where("h.check_status","eq",1)
            ->where("h.status","eq",1)
            ->where("h.start_time","lt",time())
            ->where("h.end_time","gt",time())
            ->where("h.is_show","eq",1)
            ->field($field)->find($shhouse_id);
    }

    /**
     * 我的经纪人
     */
    public static function myAgentsList(){
        $agent_ids=self::where("user_id","eq",USER_ID)->where("check_status","eq","1")->column("agent_id");
        $agent_ids[]=0;
        $agent_ids=implode(",",$agent_ids);
        $agents=ShhouseAgentModel::getAgentList(["ids"=>$agent_ids]);
        return $agents;
    }

}
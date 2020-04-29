<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/10 0010
 * Time: 17:18
 */

namespace app\front\model;


use app\common\exception\LogicException;
use app\common\tools\config\SystemConfig;
use app\common\tools\upload\SystemFile;
use app\front\base\BaseModel;

class ShhouseEscortOrderModel extends BaseModel
{
    /**
     * 下单
     */
    public static function submitOrder($shhouse_id){
        //获取房源经纪人
        $agentId=ShhouseModel::where(["id"=>$shhouse_id])->value("agent_id");
        $shhouseAgent=ShhouseAgentModel::find($agentId);
        if(empty($shhouseAgent)){
            throw new LogicException("经纪人不存在");
        }
        $data=[
            "order_num"=>getOrderNum(),
            "user_id"=>USER_ID,
            "agent_id"=>$agentId,
            "shhouse_id"=>$shhouse_id,
            "amount"=>SystemConfig::getConfig("shhescort_price"),
            "pay_amount"=>SystemConfig::getConfig("shhescort_price"),
            "create_time"=>time(),
        ];

        return self::insertGetId($data);
    }
    /**
     * 我的下单记录
     */
    public static function getEscortOrderList($status){
        $query=self::alias("o");
        $query->leftJoin("shhouse h","o.shhouse_id = h.id");
        $query->leftJoin("shhouse_agent a","o.agent_id = a.id");
        $query->field("o.id,o.status,o.create_time,h.pictures,h.village_id,h.price,h.unit_price,a.avatar,a.mobile,a.name");
        $query->where("o.user_id","eq",USER_ID);
        $query->where("o.status","neq",0);
        if(!empty($status)){
            $query->where("o.status","in",$status);
        }
        $res=$query->order("id desc")->select();
        foreach ($res as &$val){
            $val->area=ShhouseVillageModel::where(["id"=>$val->village_id])->value("name");
            $val->avatar=SystemFile::getFileUrl($val->avatar);
            $val->cover=SystemFile::getFileUrl(explode(",",$val->pictures)[0]);
            $val->create_time=date("Y-m-d H:i",$val->create_time);
            switch ($val->status){
                case 1:$val->status_name="待同意";break;
                case 2:$val->status_name="已预约";break;
                case 3:$val->status_name="已完成";break;
                case 4:$val->status_name="忙线";break;
                case 5:$val->status_name="退款中";break;
                case 6:$val->status_name="已退款";break;
            }
        }
        return $res;
    }
}
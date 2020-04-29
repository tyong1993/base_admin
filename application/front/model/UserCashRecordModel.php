<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/10 0010
 * Time: 15:14
 */

namespace app\front\model;
use app\common\tools\config\SystemConfig;
use app\front\base\BaseModel;

/**
 * 用户现金记录
 */
class UserCashRecordModel extends BaseModel
{
    /**
     * 添加用户现金流水记录
     * $pay_type:1微信,2支付宝
     * $trend:金额动向,1收入,0支出
     */
    public static function addCashRecord($user_id,$source,$amount,$pay_type,$trend,$order_id=0,$title="",$describe=""){
        $consume_type=SystemConfig::getConfig("consume_type");
        $consume_name=$consume_type[$source];
        $status=$trend?"失败":"成功";
        $action=$trend?"退款":"付款";
        $title=!empty($title)?$title:"购买{$consume_name}{$status},{$action}{$amount}元";
        $data=[
            "user_id"=>$user_id,
            "source"=>$source,
            "order_id"=>$order_id,
            "amount"=>$amount,
            "pay_type"=>$pay_type,
            "trend"=>$trend,
            "title"=>$title,
            "describe"=>$describe,
            "create_time"=>time(),
            "update_time"=>time(),
        ];
        self::insert($data);
    }
    /**
     * 获取现金记录
     */
    public static function getCashRecordList(){
        $res=self::where(["status"=>1,"user_id"=>USER_ID])->order("update_time desc")->paginate(self::$page_limit);
        foreach ($res as &$val){
            $val["create_time"] = date("Y-m-d",$val["create_time"]);
        }
        return $res;
    }
    /**
     * 删除现金记录
     */
    public static function deleteCashRecord($id){
        if(empty($id)){
            throw new LogicException("请选择要操作的记录");
        }
        $date=[
            "status"=>0
        ];
        self::where("id","in",$id)->where("user_id","eq",USER_ID)->update($date);
    }
}
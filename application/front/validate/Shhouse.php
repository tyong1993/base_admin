<?php
/**
 * Created by PhpStorm.
 * User: tyong
 * Date: 2020/4/7
 * Time: 23:09
 */

namespace app\front\validate;


use app\common\exception\LogicException;
use app\front\model\ShhouseModel;
use think\Validate;

class Shhouse extends Validate
{
    protected $rule =   [
        'unique_number'  => 'require',
//        'pictures|房屋图片'   => 'require',
        'village_id|小区' => 'require',
        'block' => 'require|uniqueShhouse',
        'house_num|门牌号' => 'require',
        'build_area|建筑面积' => 'require|integer',
//        'carpet_area|室内面积' => 'require|integer',
        'storey|楼层' => 'require|integer',
        'all_storey|总楼层' => 'require|integer',
//        'house_layout|户型' => 'require',
        'room_amount|房间个数' => 'require|integer',
        'hall_amount|厅个数' => 'require|integer',
        'toilet_amount|卫生间个数' => 'require|integer',
        'balcony_amount|阳台个数' => 'require|integer',
        'transaction_type|交易类型' => 'require|transactionType',
        'price|出售价' => 'integer',
        'lease_price|出租价' => 'integer',
        'house_master_name|房主姓名' => 'require',
        'mobile_1|联系电话1' => 'require',
        'house_type|房屋类型' => 'require',
        'property_nature|产权性质' => 'require',
        'build_time|建房时间' => 'require',
        'trim_time|装修时间' => 'require',
        'installation|配套设施' => 'require',
        'direction|房屋朝向' => 'require',
        'trim_type|装修情况' => 'require',
        'has_elevator|电梯情况' => 'require',
        'down_payment|首付要求' => 'require',
        'look_up|看房方式' => 'require',
        'certificate|证件信息' => 'require',
        'now_status|房屋现状' => 'require',
//        'pay_type|出售付款方式' => 'require',
//        'lease_pay_type|出租付款方式' => 'require',
        'has_loan|贷款情况' => 'require',
        'can_remove_by_youself|自解情况' => 'require',
        'house_tag|房屋标签' => 'require',
        'buy_time|购房时间' => 'require',
//        'master_remarks' => 'require',
    ];
    /**
     * 保证房源唯一性
     */
    protected function uniqueShhouse($value,$rule,$param){
        if(isset($param["id"])){
            if(
                ShhouseModel::where(
                    [
                        "village_id"=>$param["village_id"],
                        "block"=>$param["block"],
                        "unit"=>$param["unit"],
                        "house_num"=>$param["house_num"],
                        "status"=>1
                    ]
                )->where("id","neq",$param["id"])->count()
            ){
                throw new LogicException("该房源已存在");
            }
        }else{
            if(
                ShhouseModel::where(
                    [
                        "village_id"=>$param["village_id"],
                        "block"=>$param["block"],
                        "unit"=>$param["unit"],
                        "house_num"=>$param["house_num"],
                        "status"=>1
                    ]
                )->count()
            ){
                throw new LogicException("该房源已存在");
            }
        }
        return true;
    }
    /**
     * 交易类型
     */
    protected function transactionType($value,$rule,$param){
        if(!in_array($value,["出租","出售"])){
            throw new LogicException("交易类型错误");
        }
        if($value == "出租"){
            if(empty($param["lease_price"])){
                throw new LogicException("出租价格不能为空");
            }
            if(empty($param["lease_pay_type"])){
                throw new LogicException("出租付款方式不能为空");
            }
        }
        if($value == "出售"){
            if(empty($param["price"])){
                throw new LogicException("出售价格不能为空");
            }
            if(empty($param["pay_type"])){
                throw new LogicException("出售付款方式不能为空");
            }
        }
        return true;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: tyong
 * Date: 2020/4/12
 * Time: 22:21
 */

namespace app\front\logic;


use app\common\exception\LogicException;
use app\front\model\ShhouseEscortOrderModel;
use app\front\model\ShhouseUserModel;
use app\front\model\UserCashRecordModel;
use think\Db;

class WxPayCallback implements \Payment\Contracts\IPayNotify
{
    /**
     * 处理自己的业务逻辑，如更新交易状态、保存通知数据等等
     * @param string $channel 通知的渠道，如：支付宝、微信、招商
     * @param string $notifyType 通知的类型，如：支付、退款
     * @param string $notifyWay 通知的方式，如：异步 async，同步 sync
     * @param array $notifyData 通知的数据
     * @return bool
     */
    public function handle(string $channel,string $notifyType,string $notifyWay,array $notifyData){
        try{
            //购买vip特权回调处理
            if($notifyData["attach"]=="vip_privilege"){
                $tradeNo=$notifyData["out_trade_no"];
                //获取订单数据
                $tradeData=cache($tradeNo);
                if($tradeData){
                    $get_vip_time=$tradeData["get_vip_time"];
                    $privilege=$tradeData["privilege"];
                    $vip_price=$tradeData["vip_price"];
                    $user_id=$tradeData["user_id"];
                    //获取用户信息
                    $shhouse_user=ShhouseUserModel::find($user_id);
                    if($shhouse_user->is_vip){
                        $shhouse_user->vip_end_time=$shhouse_user->vip_end_time+$get_vip_time;
                        $shhouse_user->look_mobile_times=$shhouse_user->look_mobile_times+$privilege["look_mobile_times"];
                        $shhouse_user->escort_service_times=$shhouse_user->escort_service_times+$privilege["escort_service_times"];
                    }else{
                        $shhouse_user->is_vip=1;
                        $shhouse_user->vip_end_time=time()+$get_vip_time;
                        $shhouse_user->look_mobile_times=$privilege["look_mobile_times"];
                        $shhouse_user->escort_service_times=$privilege["escort_service_times"];
                    }
                    Db::startTrans();
                    $shhouse_user->save();
                    //添加交易记录
                    UserCashRecordModel::addCashRecord($user_id,"vip_privilege",$vip_price,1,0);
                    Db::commit();
                    cache($tradeNo,NULL);
                    return true;
                }
                return false;
            }
            //购买陪同回调处理
            elseif ($notifyData["attach"]=="escort"){
                $order=ShhouseEscortOrderModel::where(["order_num"=>$notifyData["out_trade_no"]])->find();
                if($order->status != 0){
                    throw new LogicException("订单状态异常");
                }
                $order->pay_time=time();
                $order->pay_type=1;
                $order->status=1;
                Db::startTrans();
                $order->save();
                //添加交易记录
                UserCashRecordModel::addCashRecord($order->user_id,"agent_escort",$order->pay_amount,1,0,$order->id);
                Db::commit();
                return true;
            }
        }catch (\Exception $exception){
            cache("handle_exception",$exception->getCode().$exception->getFile().$exception->getMessage());
        }





    }
}
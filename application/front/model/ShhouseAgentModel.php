<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/10 0010
 * Time: 17:12
 */

namespace app\front\model;


use app\common\exception\LogicException;
use app\common\tools\upload\SystemFile;
use app\front\base\BaseModel;
use app\front\logic\UserLogic;
use think\Db;

class ShhouseAgentModel extends BaseModel
{
    /**
     * 购买陪同看房服务
     */
    public static function getEscortService($shhouse_id){

        //本系统下订单
        $order_id=ShhouseEscortOrderModel::submitOrder($shhouse_id);
        $order=ShhouseEscortOrderModel::find($order_id);
        if(empty($order)){
            throw new LogicException("订单不存在");
        }
        //调用微信下单接口,返回订单信息,前台完成支付
        $payData = [
            'body'         => '',//对应统一下单接口的detail,单品优惠字段(暂未上线)
            'subject'      => '经纪人陪同服务',//对应统一下单接口的body,商品描述
            'trade_no'     => $order->order_num,
            'time_expire'  => time() + 600, // 表示必须 600s 内付款
            'amount'       => $order->pay_amount, // 微信沙箱模式，需要金额固定为3.01
            'return_param' => 'escort',
            'client_ip'    => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1', // 客户地址
            'openid'       => UserLogic::getWXAppOpenid(),
        ];
        $client = new \Payment\Client(\Payment\Client::WECHAT, config("logic.payment_wx_template"));
        $res=$client->pay(\Payment\Client::WX_CHANNEL_LITE,$payData);
        return $res;
    }
    /**
     * 经纪人列表
     */
    public static function getAgentList($param=[]){
        $query=self::where("id","gt",0);
        if(isset($param["ids"])){
            $query=$query->where("id","in",$param["ids"]);
        }
        //根据服务地址搜索经纪人
        if(isset($param["search"])){
            $query=$query->where("working_position","like","%".$param["search"]."%");
        }
        $res=$query->select();
        foreach ($res as &$val){
            $val->avatar=SystemFile::getFileUrl($val->avatar);
            unset($val->status);
        }
        return $res;
    }
    /**
     * 经纪人详情
     */
    public static function getAgentDetail($agent_id){
        return self::find($agent_id);
    }


}
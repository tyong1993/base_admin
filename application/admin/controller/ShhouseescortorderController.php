<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/3 0003
 * Time: 13:53
 */

namespace app\admin\controller;


use app\admin\logic\LogLogic;
use app\admin\model\SystemCategoryModel;
use app\admin\model\UserSiteMessageModel;
use app\common\exception\LogicException;
use app\common\tools\upload\SystemFile;
use think\Db;

class ShhouseescortorderController extends BaseController
{

    public function index()
    {
        //房源审核状态分类
        $status_type=[
            "1"=>"待同意",
            "2"=>"待服务",
            "3"=>"服务完成",
            "4"=>"忙线",
            "5"=>"申请退款",
            "6"=>"退款完成",
        ];
        if(request()->isAjax()) {
            $limit = input('param.limit');
            $search = input('param.search');
            $status = input('param.status');
            $db=Db::table("shhouse_escort_order")
                ->alias("seo")
                ->leftJoin("user u","seo.user_id = u.id")
                ->leftJoin("shhouse s","seo.shhouse_id = s.id")
                ->leftJoin("shhouse_village_info svi","svi.id = s.village_id")
                ->field("seo.*,u.wx_nickname,u.mobile,svi.detaile_address")
                ->where("seo.status","gt","0")
            ;
            //如果是经纪人则只展示他的订单
            if($this->agent_id){
                $db->where("seo.agent_id","eq",$this->agent_id);
            }
            //根据订单状态筛选
            if(!empty($status)){
                $db->where("seo.status","eq",$status);
            }
            $list = $db->paginate($limit)->toArray();
            foreach ($list["data"] as &$val){
                $val["status_name"]=$status_type[$val["status"]];
                $val["create_time"]=date("Y-m-d H:i",$val["create_time"]);
            }
            return json(['code' => 0, 'msg' => 'ok', 'count' => $list["total"], 'data' => $list["data"]]);
        }

        $this->assign("status_type",$status_type);
        return $this->fetch();
    }


    public function edit(){
        if(request()->isAjax()) {
            $param =  request()->post('', null, 'trim');
            if(empty($order["status"])){
                return jsonSuccess();
            }
            //订单通知
            $order=Db::table("shhouse_escort_order")->find($param["id"]);
            //订单状态发生改变
            if($order["status"] != $param["status"]){
                //预约同意
                if($param["status"] == 2){
                    UserSiteMessageModel::sendSiteMessage("escort_order_pass",$order["user_id"],"您的预约订单经纪人已同意","您的预约订单经纪人已同意");
                }
                //服务完成
                else if($param["status"] == 3){
                    UserSiteMessageModel::sendSiteMessage("escort_order_complate",$order["user_id"],"您的预约订单已完成","您的预约订单已完成");
                }
                //忙线
                else if($param["status"] == 4){
                    UserSiteMessageModel::sendSiteMessage("escort_order_notime",$order["user_id"],"您的预约订单由于经纪人忙线,暂时无法处理","您的预约订单由于经纪人忙线,暂时无法处理");
                }
                //同意退款
                else if($param["status"] == 6){
                    Db::startTrans();
                    $refundData=[
                        "trade_no"=>$order["order_num"],
                        "refund_no"=>"escort_refund_no_".$order["id"],
                        "total_fee"=>$order["pay_amount"],
                        "refund_fee"=>$order["pay_amount"],
                        "refund_desc"=>"纪元平台预约订单退款",
                    ];
                    $client = new \Payment\Client(\Payment\Client::WECHAT, config("logic.payment_wx_template"));
                    $res=$client->refund($refundData);
                    if(!isset($res["result_code"]) || $res["result_code"] != "SUCCESS"){
                        return jsonFail("退款失败,请稍后再试");
                    }
                    UserSiteMessageModel::sendSiteMessage("escort_order_refund",$order["user_id"],"您的预约订单退款已完成","您的预约订单退款已完成");
                    Db::commit();
                }
            }
            Db::table("shhouse_escort_order")->strict(false)->update($param);
            LogLogic::write("处理预约订单：" . $param['id']);

            return jsonSuccess();
        }


        $row=Db::table("shhouse_escort_order")
            ->find(input("id"));

        $this->assign([
            "row"=>$row,
        ]);
        return $this->fetch();
    }

}
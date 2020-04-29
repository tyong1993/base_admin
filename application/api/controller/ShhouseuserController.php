<?php
namespace app\api\controller;

use app\admin\validate\SystemArticle;
use app\common\tools\config\SystemConfig;
use app\common\tools\pay\PayTool;
use app\common\tools\upload\SystemFile;
use app\front\base\BaseController;
use app\front\logic\ShhouseLogic;
use app\front\logic\ShhouseUserLogic;
use app\front\logic\SystemArticleLogic;
use app\front\logic\SystemVerifyCodeLogic;
use app\front\logic\UserBrowseRecordLogic;
use app\front\logic\UserCollectionRecordLogic;
use app\front\logic\UserLogic;
use app\front\model\ShhouseAgentModel;
use app\front\model\ShhouseEscortOrderModel;
use app\front\model\ShhouseModel;
use app\front\model\UserBrowseRecordModel;
use app\front\model\UserCashRecordModel;
use app\front\model\UserCollectionRecordModel;
use think\Db;
use think\Request;

class ShhouseuserController extends BaseController
{
    /**
     *购买vip特权
     * @SWG\Get(path="/shhouseuser/getVipPrivilegeGet",
     * tags={"二手房用户"},
     * summary="获取vip特权",
     * produces={"application/json"},
     * description="",
     * @SWG\Parameter(in="header",name="token",type="string",description="登陆token",required=true),
     * @SWG\Response(response="200", description="请求成功")
     * )
     */
    function getVipPrivilegeGet(){
        $data["vip_introduce"]=SystemArticleLogic::getArticleDetail(2,"vip_introduce");
        $data["vip_price"]=SystemConfig::getConfig("user_vip_price");
        foreach ($data["vip_price"] as $key=>&$val){
            switch ($key){
                case "month":$val="￥".$val."/月";break;
                case "quarter":$val="￥".$val."/季度";break;
                case "year":$val="￥".$val."/年";break;
            }
        }
        return jsonSuccess($data);
    }
    /**
     *购买vip特权
     * @SWG\Post(path="/shhouseuser/getVipPrivilegePost",
     * tags={"二手房用户"},
     * summary="获取vip特权",
     * produces={"application/json"},
     * description="",
     * @SWG\Parameter(in="formData",name="vip_type",type="string",description="vip类型",required=true),
     * @SWG\Parameter(in="header",name="token",type="string",description="登陆token",required=true),
     * @SWG\Response(response="200", description="请求成功")
     * )
     */
    function getVipPrivilegePost(){
        $vip_type=$this->request->param("vip_type");
        //查看vip价格和特权
        $data["vip_price_config"]=SystemConfig::getConfig("user_vip_price");
        if(!in_array($vip_type,["month","quarter","year"])){
            return jsonFail("未定义的vip类型");
        }
        $vip_price=$data["vip_price_config"][$vip_type];
        //调用微信下单接口,返回订单信息,前台完成支付
        $tradeNo = time() . rand(1000, 9999).USER_ID;
        $expire=600;
        $payData = [
            'body'         => '',//对应统一下单接口的detail,单品优惠字段(暂未上线)
            'subject'      => 'vip会员',//对应统一下单接口的body,商品描述
            'trade_no'     => $tradeNo,
            'time_expire'  => time() + $expire, // 表示必须 600s 内付款
            'amount'       => $vip_price, // 微信沙箱模式，需要金额固定为3.01
            'return_param' => 'vip_privilege',
            'client_ip'    => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1', // 客户地址
            'openid'       => UserLogic::getWXAppOpenid(),
        ];
        $client = new \Payment\Client(\Payment\Client::WECHAT, config("logic.payment_wx_template"));
        $order=$client->pay(\Payment\Client::WX_CHANNEL_LITE,$payData);
        //缓存订单信息,有效期和订单有效期一致
        $get_vip_time=$vip_type=="month"?30*24*3600:($vip_type=="quarter"?3*30*24*3600:12*30*24*3600);
        $privilege=SystemConfig::getConfig($vip_type."_vip_privilege");
        cache($tradeNo,["user_id"=>USER_ID,"vip_price"=>$vip_price,"get_vip_time"=>$get_vip_time,"privilege"=>$privilege],$expire);
        $pay_data=PayTool::createWxAppPayParams($order["appid"],$order["prepay_id"],config("logic.payment_wx_template.md5_key"));
        return jsonSuccess($pay_data);
    }
    /**
     * 购买陪同服务
     * @SWG\Post(path="/shhouseuser/getEscortService",
     * tags={"二手房用户"},
     * summary="购买陪同服务",
     * produces={"application/json"},
     * description="",
     * @SWG\Parameter(in="formData",name="shhouse_id",type="string",description="房屋id",required=true),
     * @SWG\Parameter(in="header",name="token",type="string",description="登陆token",required=true),
     * @SWG\Response(response="200", description="请求成功")
     * )
     */
    function getEscortService(){
        $shhouse_id=$this->request->param("shhouse_id");
        $order=ShhouseAgentModel::getEscortService($shhouse_id);
        $pay_data=PayTool::createWxAppPayParams($order["appid"],$order["prepay_id"],config("logic.payment_wx_template.md5_key"));
        return jsonSuccess($pay_data);
    }
    /**
     *我的预约列表
     * @SWG\Get(path="/shhouseuser/getEscortOrderList",
     * tags={"二手房用户"},
     * summary="我的预约列表",
     * produces={"application/json"},
     * description="订单状态：1已支付，待同意，2已同意(已预约)，待服务完成，3服务完成，4经纪人忙，暂时无法提供服务，5退款中(只有1,4状态下支持退款)，6退款完成",
     * @SWG\Parameter(in="query",name="status",type="string",description="0:全部,1:待同意,2:已预约,3:已完成,4:忙线,5,6:退款",required=true),
     * @SWG\Parameter(in="header",name="token",type="string",description="登陆token",required=true),
     * @SWG\Response(response="200", description="请求成功")
     * )
     */
    function getEscortOrderList(){
        $status=$this->request->param("status");
        $res=ShhouseEscortOrderModel::getEscortOrderList($status);
        return jsonSuccess($res);
    }
    /**
     * 申请退款
     * @SWG\Post(path="/shhouseuser/refundEscortService",
     * tags={"二手房用户"},
     * summary="申请退款",
     * produces={"application/json"},
     * @SWG\Parameter(in="formData",name="id",type="string",description="预约记录的id",required=true),
     * @SWG\Parameter(in="header",name="token",type="string",description="登陆token",required=true),
     * @SWG\Response(response="200", description="请求成功")
     * )
     */
    function refundEscortService(){
        $id=$this->request->param("id");
        $order=ShhouseEscortOrderModel::where("user_id","eq",USER_ID)->find($id);
        if(empty($order)){
            return jsonFail("记录不存在");
        }
        if(!in_array($order->status,[1,4])){
            return jsonFail("当前状态下不支持退款");
        }
        $order->status=5;
        $order->save();
        return jsonSuccess();
    }

    /**
     * 帮助中心经纪人列表
     * @SWG\Get(path="/shhouseuser/agentsList",
     * tags={"二手房用户"},
     * summary="帮助中心经纪人列表",
     * produces={"application/json"},
     * @SWG\Parameter(in="header",name="token",type="string",description="登陆token",required=true),
     * @SWG\Response(response="200", description="请求成功")
     * )
     */
    function agentsList(){
        $res=ShhouseAgentModel::getAgentList();
        return jsonSuccess($res);
    }
    /**
     * 帮助中心经纪人房源
     * @SWG\Get(path="/shhouseuser/agentsShhouseList",
     * tags={"二手房用户"},
     * summary="经纪人房源列表",
     * produces={"application/json"},
     * @SWG\Parameter(in="query",name="agent_id",type="string",description="经纪人id",required=true),
     * @SWG\Parameter(in="query",name="transaction_type",type="string",description="房源类型,出租或者出售",required=true),
     * @SWG\Parameter(in="header",name="token",type="string",description="登陆token",required=true),
     * @SWG\Response(response="200", description="请求成功")
     * )
     */
    function agentsShhouseList(){
        $param["agent_id"]=$this->request->param("agent_id");
        $param["transaction_type"]=$this->request->param("transaction_type");
        //经纪人信息
        $agent=ShhouseAgentModel::getAgentDetail($param["agent_id"]);
        $agent->avatar=SystemFile::getFileUrl($agent->avatar);
        //经纪人房源信息
        $agent_shhouses=ShhouseLogic::getShhouseList($param,false);
        return jsonSuccess(["agent"=>$agent,"agent_shhouses"=>$agent_shhouses]);
    }
    /**
     * 我的置业顾问
     * @SWG\Get(path="/shhouseuser/myAgentsList",
     * tags={"二手房用户"},
     * summary="我的置业顾问",
     * produces={"application/json"},
     * @SWG\Parameter(in="header",name="token",type="string",description="登陆token",required=true),
     * @SWG\Response(response="200", description="请求成功")
     * )
     */
    function myAgentsList(){
        $res=ShhouseModel::myAgentsList();
        return jsonSuccess($res);
    }
    /**
     * 我的房源列表
     * @SWG\Get(path="/shhouseuser/myShhouseList",
     * tags={"二手房用户"},
     * summary="我的房源",
     * produces={"application/json"},
     * description="check_status:房源状态,0:待审核,1:审核通过,2:审核未通过,is_show:上下架状态,1上架中,0已下架",
     * @SWG\Parameter(in="header",name="token",type="string",description="登陆token",required=true),
     * @SWG\Response(response="200", description="请求成功")
     * )
     */
    function myShhouseList(){
        $res=ShhouseLogic::getShhouseList(["user_id"=>USER_ID],false,false);
        foreach ($res as &$val){
            if($val["check_status"] == 0){
                $val["status_name"]="审核中";
                $val["end_time"]="---";
            }
            if($val["check_status"] == 2){
                $val["status_name"]="已拒绝";
                $val["end_time"]="---";
            }
            if($val["check_status"] == 1){
                if(strtotime($val["end_time"]) < time()){
                    $val["is_show"]=0;
                }
                if($val["is_show"]){
                    $val["status_name"]="上架中";
                }else{
                    $val["status_name"]="已下架";
                }
            }
        }
        return jsonSuccess($res);
    }
    /**
     * 删除我的房源
     * @SWG\Post(path="/shhouseuser/deleteMyShhouse",
     * tags={"二手房用户"},
     * summary="删除我的房源",
     * produces={"application/json"},
     * @SWG\Parameter(in="formData",name="id",type="string",description="房源id",required=true),
     * @SWG\Parameter(in="header",name="token",type="string",description="登陆token",required=true),
     * @SWG\Response(response="200", description="请求成功")
     * )
     */
    function deleteMyShhouse(){
        $id=$this->request->param("id");
        ShhouseModel::where("id","eq",$id)->where("user_id","eq",USER_ID)->update(["status"=>0]);
        return jsonSuccess();
    }
    /**
     * 上下架我的房源
     * @SWG\Post(path="/shhouseuser/isShowMyShhouse",
     * tags={"二手房用户"},
     * summary="上下架我的房源",
     * produces={"application/json"},
     * @SWG\Parameter(in="formData",name="id",type="string",description="房源id",required=true),
     * @SWG\Parameter(in="formData",name="is_show",type="string",description="上下架:1上架,0下架",required=true),
     * @SWG\Parameter(in="header",name="token",type="string",description="登陆token",required=true),
     * @SWG\Response(response="200", description="请求成功")
     * )
     */
    function isShowMyShhouse(){
        $id=$this->request->param("id");
        $is_show=$this->request->param("is_show");
        $shhouse=ShhouseModel::where("id","eq",$id)->where("user_id","eq",USER_ID)->find();
        if($shhouse->check_status ==0){
            return jsonFail("该房源正在审核中");
        }
        if($shhouse->check_status ==2){
            return jsonFail("该房源未通过审核");
        }
        $data=[
            "is_show"=>$is_show,
            "start_time"=>time(),
            "end_time"=>time()+SystemConfig::getConfig("shhouse_show_days")*3600*24,
        ];
        ShhouseModel::where("id","eq",$id)->where("user_id","eq",USER_ID)->update($data);
        return jsonSuccess();
    }
    /**
     * 修改我的房源
     * @SWG\Get(path="/shhouseuser/updateShhouseGet",
     * tags={"二手房用户"},
     * summary="修改房源",
     * produces={"application/json"},
     * @SWG\Parameter(in="query",name="id",type="string",description="房源id",required=true),
     * @SWG\Parameter(in="header",name="token",type="string",description="登陆token",required=true),
     * @SWG\Response(response="200", description="请求成功")
     * )
     */
    function updateShhouseGet(){
        $id=request()->param("id");
        $shhouse=ShhouseModel::field("unique_number,pictures,village_id,block,unit,house_num,build_area,carpet_area,storey,all_storey,house_layout,room_amount,hall_amount,toilet_amount,balcony_amount,transaction_type,price,lease_price,house_master_name,mobile_1,mobile_2,house_type,property_nature,build_time,trim_time,installation,direction,trim_type,has_elevator,down_payment,look_up,key_address,look_time,certificate,now_status,pay_type,lease_pay_type,has_loan,can_remove_by_youself,house_tag,buy_time,master_remarks")->find($id);
        $shhouse["build_time"]=date("Y-m-d",$shhouse["build_time"]);
        $shhouse["trim_time"]=date("Y-m-d",$shhouse["trim_time"]);
        $shhouse["buy_time"]=date("Y-m-d",$shhouse["buy_time"]);
        $shhouse["pictures"]=SystemFile::getFileForEdit($shhouse["pictures"],true);
        $shhouse["installation"]=!empty($shhouse["installation"])?explode(",",$shhouse["installation"]):[];
        $shhouse["house_tag"]=!empty($shhouse["house_tag"])?explode(",",$shhouse["house_tag"]):[];
        return jsonSuccess($shhouse);
    }
    /**
     * 修改我的房源
     * @SWG\Post(path="/shhouseuser/updateShhousePost",
     * tags={"二手房用户"},
     * summary="修改房源",
     * produces={"application/json"},
     * description="提交房源信息
     *     时间采用:2020-04-08这种格式
     * ",
     * @SWG\Parameter(in="formData",name="id",type="string",description="房源id",required=true),
     * @SWG\Parameter(in="formData",name="unique_number",type="string",description="唯一编号",required=true),
     * @SWG\Parameter(in="formData",name="pictures",type="string",description="图片id,逗号分隔",required=true),
     * @SWG\Parameter(in="formData",name="village_id",type="number",description="小区id",required=true),
     * @SWG\Parameter(in="formData",name="block",type="string",description="栋",required=true),
     * @SWG\Parameter(in="formData",name="unit",type="string",description="单元,没有传空字符串",required=true),
     * @SWG\Parameter(in="formData",name="house_num",type="string",description="门牌号",required=true),
     * @SWG\Parameter(in="formData",name="build_area",type="number",description="建筑面积",required=true),
     * @SWG\Parameter(in="formData",name="carpet_area",type="number",description="室内面积",required=true),
     * @SWG\Parameter(in="formData",name="storey",type="number",description="楼层",required=true),
     * @SWG\Parameter(in="formData",name="house_layout",type="string",description="户型",required=true),
     * @SWG\Parameter(in="formData",name="room_amount",type="number",description="房间个数",required=true),
     * @SWG\Parameter(in="formData",name="transaction_type",type="string",description="交易类型",required=true),
     * @SWG\Parameter(in="formData",name="price",type="number",description="房屋价格",required=true),
     * @SWG\Parameter(in="formData",name="house_master_name",type="string",description="房主姓名",required=true),
     * @SWG\Parameter(in="formData",name="mobile_1",type="string",description="联系电话1",required=true),
     * @SWG\Parameter(in="formData",name="mobile_2",type="string",description="联系电话2"),
     * @SWG\Parameter(in="formData",name="house_type",type="string",description="房屋类型"),
     * @SWG\Parameter(in="formData",name="property_nature",type="string",description="产权性质"),
     * @SWG\Parameter(in="formData",name="build_time",type="string",description="建房时间"),
     * @SWG\Parameter(in="formData",name="trim_time",type="string",description="装修时间"),
     * @SWG\Parameter(in="formData",name="installation",type="string",description="配套设施,逗号隔开"),
     * @SWG\Parameter(in="formData",name="direction",type="string",description="房屋朝向"),
     * @SWG\Parameter(in="formData",name="trim_type",type="string",description="装修情况"),
     * @SWG\Parameter(in="formData",name="has_elevator",type="number",description="是否有电梯:1是0否"),
     * @SWG\Parameter(in="formData",name="down_payment",type="number",description="首付比例"),
     * @SWG\Parameter(in="formData",name="look_up",type="string",description="看房方式"),
     * @SWG\Parameter(in="formData",name="certificate",type="string",description="证件"),
     * @SWG\Parameter(in="formData",name="now_status",type="string",description="房屋现状"),
     * @SWG\Parameter(in="formData",name="pay_type",type="string",description="付款方式"),
     * @SWG\Parameter(in="formData",name="has_loan",type="number",description="是否有贷款:1是0否"),
     * @SWG\Parameter(in="formData",name="can_remove_by_youself",type="number",description="是否可以自行解除贷款:1是0否"),
     * @SWG\Parameter(in="formData",name="house_tag",type="string",description="房源标签,逗号隔开"),
     * @SWG\Parameter(in="formData",name="buy_time",type="string",description="购买时间"),
     * @SWG\Parameter(in="formData",name="master_remarks",type="string",description="房主备注信息"),
     * @SWG\Parameter(in="header",name="token",type="string",description="登陆token",required=true),
     * @SWG\Response(response="200", description="请求成功")
     * )
     */
    function updateShhousePost(){
        $param =  request()->post('', null, 'trim');
        $this->validate($param,"app\\front\\validate\Shhouse");
        ShhouseLogic::updateShhouse($param);
        return jsonSuccess();
    }
}

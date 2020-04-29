<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/7 0007
 * Time: 17:12
 */

namespace app\api\controller;
use app\front\base\BaseController;
use app\front\logic\ShhouseLogic;
use app\front\logic\ShhouseUserLogic;
use app\front\logic\UserCollectionRecordLogic;
use app\front\logic\UserLogic;
use app\front\model\ShhouseModel;
use app\front\model\ShhouseUserModel;
use think\Db;

/**
 * 二手房住宅控制器
 */
class ShhouseController extends BaseController
{
    protected $notNeedLoginFun=["getlist","getsearchcondition"];
    /**
     * 发布房源
     * @SWG\Get(path="/shhouse/releaseGet",
     * tags={"二手房房源"},
     * summary="发布房源",
     * produces={"application/json"},
     * description="获取发布房源前需要的一些信息,有village_id参数的情况下是获取小区的可选栋,单元数据,否则获取其他信息",
     * @SWG\Parameter(in="query",name="village_id",type="string",description="小区id"),
     * @SWG\Parameter(in="header",name="token",type="string",description="登陆token",required=true),
     * @SWG\Response(response="200", description="请求成功")
     * )
     */
    function releaseGet(){
        $villageId=$this->request->param("village_id");
        if(!empty($villageId)){
            //获取小区的栋,单元信息
            $res=ShhouseLogic::getVillageBlockAndUnit($villageId);
            return jsonSuccess($res);
        }else{
            //获取初始信息
            $data=ShhouseLogic::getReleaseData();
            return jsonSuccess($data);
        }
    }
    /**
     * 发布房源
     * @SWG\Post(path="/shhouse/releasePost",
     * tags={"二手房房源"},
     * summary="发布房源",
     * produces={"application/json"},
     * description="提交房源信息
     *     时间采用:2020-04-08这种格式
     * ",
     * @SWG\Parameter(in="formData",name="unique_number",type="string",description="唯一编号",required=true),
     * @SWG\Parameter(in="formData",name="pictures",type="string",description="图片id,逗号分隔",required=true),
     * @SWG\Parameter(in="formData",name="village_id",type="number",description="小区id",required=true),
     * @SWG\Parameter(in="formData",name="block",type="string",description="栋",required=true),
     * @SWG\Parameter(in="formData",name="unit",type="string",description="单元,没有传空字符串",required=true),
     * @SWG\Parameter(in="formData",name="house_num",type="string",description="门牌号",required=true),
     * @SWG\Parameter(in="formData",name="build_area",type="number",description="建筑面积",required=true),
     * @SWG\Parameter(in="formData",name="carpet_area",type="number",description="室内面积",required=true),
     * @SWG\Parameter(in="formData",name="storey",type="number",description="楼层",required=true),
     * @SWG\Parameter(in="formData",name="all_storey",type="number",description="总楼层",required=true),
     * @SWG\Parameter(in="formData",name="room_amount",type="number",description="房间个数",required=true),
     * @SWG\Parameter(in="formData",name="hall_amount",type="string",description="厅个数",required=true),
     * @SWG\Parameter(in="formData",name="toilet_amount",type="string",description="卫生间个数",required=true),
     * @SWG\Parameter(in="formData",name="balcony_amount",type="string",description="阳台个数",required=true),
     * @SWG\Parameter(in="formData",name="transaction_type",type="string",description="交易类型",required=true),
     * @SWG\Parameter(in="formData",name="price",type="number",description="出售价格"),
     * @SWG\Parameter(in="formData",name="lease_price",type="number",description="出租价格"),
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
     * @SWG\Parameter(in="formData",name="look_up",type="number",description="看房方式,0预约,1有钥匙"),
     * @SWG\Parameter(in="formData",name="key_address",type="string",description="钥匙所在地"),
     * @SWG\Parameter(in="formData",name="look_time",type="string",description="看房时间"),
     * @SWG\Parameter(in="formData",name="certificate",type="string",description="证件"),
     * @SWG\Parameter(in="formData",name="now_status",type="string",description="房屋现状"),
     * @SWG\Parameter(in="formData",name="pay_type",type="string",description="出售付款方式"),
     * @SWG\Parameter(in="formData",name="lease_pay_type",type="string",description="出租付款方式"),
     * @SWG\Parameter(in="formData",name="down_payment",type="string",description="首付要求"),
     * @SWG\Parameter(in="formData",name="has_loan",type="number",description="是否有贷款:1是0否"),
     * @SWG\Parameter(in="formData",name="can_remove_by_youself",type="number",description="是否可以自行解除贷款:1是0否"),
     * @SWG\Parameter(in="formData",name="house_tag",type="string",description="房源标签,逗号隔开"),
     * @SWG\Parameter(in="formData",name="buy_time",type="string",description="购买时间"),
     * @SWG\Parameter(in="formData",name="master_remarks",type="string",description="房主备注信息"),
     * @SWG\Parameter(in="header",name="token",type="string",description="登陆token",required=true),
     * @SWG\Response(response="200", description="请求成功")
     * )
     */
    function releasePost(){
        $param =  request()->post('', null, 'trim');
        $this->validate($param,"app\\front\\validate\Shhouse");
        ShhouseLogic::releaseShhouse($param);
        return jsonSuccess();
    }
    /**
     * 获取房源列表
     * @SWG\Get(path="/shhouse/getList",
     * tags={"二手房房源"},
     * summary="房源列表",
     * produces={"application/json"},
     * description="",
     * @SWG\Parameter(in="query",name="is_suggest",type="number",description="仅获取推荐房源:1是0否,房源详情页会用到",default="0"),
     * @SWG\Parameter(in="query",name="transaction_type",type="string",description="交易类型,出租或者出售"),
     * @SWG\Parameter(in="query",name="search",type="string",description="搜索,小区名字或者学校名字",default=""),
     * @SWG\Parameter(in="query",name="area_id",type="number",description="区域id"),
     * @SWG\Parameter(in="query",name="street_id",type="number",description="街道id"),
     * @SWG\Parameter(in="query",name="room_amount",type="string",description="房间个数:0:不限,>5:五房以上,2-4:两房到四房之间"),
     * @SWG\Parameter(in="query",name="price",type="string",description="出售价格:0:不限,<20:20万以下,>200:200万以上,20-40:20万到40万之间"),
     * @SWG\Parameter(in="query",name="lease_price",type="string",description="出租价格:同上"),
     * @SWG\Parameter(in="query",name="down_payment_amount",type="string",description="首付金额:同上"),
     * @SWG\Parameter(in="query",name="storey",type="string",description="楼层:同上"),
     * @SWG\Parameter(in="query",name="has_elevator",type="string",description="是否有电梯,0不限,1没有,2有"),
     * @SWG\Parameter(in="query",name="house_tag",type="string",description="房源标签,不限传空字符串,多个用逗号分隔"),
     * @SWG\Parameter(in="query",name="direction",type="string",description="房屋朝向,不限传空字符串,多个用逗号分隔"),
     * @SWG\Parameter(in="query",name="date_time",type="string",description="委托时间区间:2019-05-04|2020-06-01"),
     * @SWG\Parameter(in="query",name="build_area",type="string",description="面积:50-80"),
     * @SWG\Parameter(in="query",name="order_by",type="string",description="排序方式:
     *          出售价由高到低:price desc
     *          出售价由低到高:price asc
     *          出租价由高到低:lease_price desc
     *          出租价由低到高:lease_price asc
     *          单价由高到低:unit_price desc
     *          单价由低到高:unit_price asc
     *          面积由高到低:build_area desc
     *          面积由低到高:build_area asc
     * "),
     * @SWG\Parameter(in="query",name="page",type="string",description="分页",default="1"),
     * @SWG\Parameter(in="header",name="token",type="string",description="登陆token",required=true),
     * @SWG\Response(response="200", description="请求成功")
     * )
     */
    function getList(){
        $param =  request()->get('', null, 'trim');
        $list=ShhouseLogic::getShhouseList($param);
        return jsonSuccess($list->all());
    }
    /**
     * 获取房源筛选条件
     * @SWG\Get(path="/shhouse/getSearchCondition",
     * tags={"二手房房源"},
     * summary="获取筛选条件",
     * produces={"application/json"},
     * description="",
     * @SWG\Parameter(in="header",name="token",type="string",description="登陆token",required=true),
     * @SWG\Response(response="200", description="请求成功")
     * )
     */
    function getSearchCondition(){
        return jsonSuccess(ShhouseLogic::getSearchCondition());
    }
    /**
     * 房源详情
     * @SWG\Get(path="/shhouse/getShhouseDetail",
     * tags={"二手房房源"},
     * summary="房源详情",
     * produces={"application/json"},
     * description="",
     * @SWG\Parameter(in="query",name="shhouse_id",type="string",description="房源id",required=true),
     * @SWG\Parameter(in="query",name="get_more",type="string",description="查看更多:1是0否",required=true),
     * @SWG\Parameter(in="header",name="token",type="string",description="登陆token",required=true),
     * @SWG\Response(response="200", description="请求成功")
     * )
     */
    function getShhouseDetail(){
        $shhouse_id=$this->request->param("shhouse_id");
        $get_more=$this->request->param("get_more");
        $shhouse=ShhouseLogic::getShhouseDetail($shhouse_id,$get_more);
        //是否vip用户
        $shhouse->is_vip_user=UserLogic::userInfo()->is_vip;
        //如果是业主就当他是vip
        if($shhouse->user_id == USER_ID){
            $shhouse->is_vip_user=1;
        }
        //是否收藏了该房源
        $shhouse->is_collection=UserCollectionRecordLogic::isCollection("sshouse",!empty($shhouse->id)?$shhouse->id:0);
        return jsonSuccess($shhouse);
    }
    /**
     * 获取房源联系电话
     * @SWG\Get(path="/shhouse/getShhouseMobile",
     * tags={"二手房房源"},
     * summary="获取业主电话",
     * produces={"application/json"},
     * description="",
     * @SWG\Parameter(in="query",name="shhouse_id",type="string",description="房源id",required=true),
     * @SWG\Parameter(in="header",name="token",type="string",description="登陆token",required=true),
     * @SWG\Response(response="200", description="请求成功")
     * )
     */
    function getShhouseMobile(){
        $shhouse_id=$this->request->param("shhouse_id");
        $shhouseUser=ShhouseUserLogic::userInfo();
        if(!$shhouseUser->is_vip){
            return jsonFail("房源联系电话仅对vip用户开放");
        }
        $mobile=ShhouseModel::where("id","eq",$shhouse_id)->value("mobile_1");
        return jsonSuccess($mobile);
    }

}
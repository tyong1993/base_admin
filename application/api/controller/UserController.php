<?php
namespace app\api\controller;

use app\admin\validate\SystemArticle;
use app\common\tools\config\SystemConfig;
use app\front\base\BaseController;
use app\front\logic\ShhouseUserLogic;
use app\front\logic\SystemArticleLogic;
use app\front\logic\SystemVerifyCodeLogic;
use app\front\logic\UserBrowseRecordLogic;
use app\front\logic\UserCollectionRecordLogic;
use app\front\logic\UserLogic;
use app\front\model\ShhouseModel;
use app\front\model\UserBrowseRecordModel;
use app\front\model\UserCashRecordModel;
use app\front\model\UserCollectionRecordModel;
use app\front\model\UserSiteMessageModel;
use think\Db;
use think\Request;

class UserController extends BaseController
{
    protected $notNeedLoginFun=["wxapplogin","getverifycode"];

    /**
     * 小程序登陆
     * @SWG\Post(path="/user/wxappLogin",
     * tags={"用户模块"},
     * summary="小程序登陆",
     * produces={"application/json"},
     * description="平常登陆只需要传code,如果返回业务码10002,表示是第一次登陆,此时需要携带wx_nickname和wx_avatar参数以及手机号与验证码再次请求登陆",
     * @SWG\Parameter(in="formData",name="code",type="string",description="小程序code",required=true),
     * @SWG\Parameter(in="formData",name="mobile",type="string",description="手机号"),
     * @SWG\Parameter(in="formData",name="m_code",type="string",description="手机验证码"),
     * @SWG\Parameter(in="formData",name="wx_nickname",type="string",description="微信昵称"),
     * @SWG\Parameter(in="formData",name="wx_avatar",type="string",description="微信头像"),
     * @SWG\Response(response="200", description="请求成功")
     * )
     */
    function wxappLogin(Request $request){
        $param=$request->post();
        if(empty($param["code"])){
            return jsonFail("参数错误");
        }
        //确保注册的时候是有绑定手机的
        if(isset($param["wx_nickname"]) && isset($param["wx_avatar"])){
            if(!isset($param["mobile"]) || !isset($param["m_code"])){
                return jsonFail("非法操作");
            }
        }
        $param["wxapp_openid"]=UserLogic::getWXAppOpenid($param["code"]);
        unset($param["code"]);
        return jsonSuccess(["token"=>UserLogic::loginByToken(3,$param)]);
    }
    /**
     * 个人中心
     * @SWG\Get(path="/user/userCenter",
     * tags={"用户模块"},
     * summary="个人中心",
     * produces={"application/json"},
     * description="",
     * @SWG\Parameter(in="header",name="token",type="string",description="登陆token",required=true),
     * @SWG\Response(response="200", description="请求成功")
     * )
     */
    public function userCenter(){
        $data=[];
        $user=UserLogic::userInfo();
        $data["user_info"]=$user;
        //我的收藏
        $data["my_collections"]=UserCollectionRecordModel::where(["user_id"=>USER_ID,"source"=>"sshouse","status"=>1])->count();
        //浏览记录
        $data["my_browses"]=UserBrowseRecordModel::where(["user_id"=>USER_ID,"source"=>"sshouse","status"=>1])->count();
        //我的房源
        $data["my_shhouse"]=ShhouseModel::where(["user_id"=>USER_ID,"status"=>1])->count();
        return jsonSuccess($data);
    }
    /**
     * 添加收藏记录
     * @SWG\Post(path="/user/addCollectionRecord",
     * tags={"用户模块"},
     * summary="添加收藏记录",
     * produces={"application/json"},
     * description="",
     * @SWG\Parameter(in="formData",name="source",type="string",description="要收藏的记录的分类
     *      已知分类:
     *     sshouse:二手房
     * ",required=true),
     * @SWG\Parameter(in="formData",name="record_id",type="string",description="要收藏的记录的id",required=true),
     * @SWG\Parameter(in="header",name="token",type="string",description="登陆token",required=true),
     * @SWG\Response(response="200", description="请求成功")
     * )
     */
    function addCollectionRecord(){
        $source=$this->request->param("source");
        $record_id=$this->request->param("record_id");
        UserCollectionRecordLogic::addCollectionRecord($source,$record_id);
        return jsonSuccess("收藏成功");
    }
    /**
     * 获取收藏列表
     * @SWG\Get(path="/user/getCollectionList",
     * tags={"用户模块"},
     * summary="获取收藏列表",
     * produces={"application/json"},
     * description="",
     * @SWG\Parameter(in="query",name="source",type="string",description="收藏的记录的分类
     *      已知分类:
     *     sshouse:二手房
     * ",required=true),
     * @SWG\Parameter(in="query",name="page",type="string",description="分页",default="1"),
     * @SWG\Parameter(in="header",name="token",type="string",description="登陆token",required=true),
     * @SWG\Response(response="200", description="请求成功")
     * )
     */
    function getCollectionList(){
        $source=$this->request->param("source");
        $list=UserCollectionRecordLogic::getCollectionList($source);
        return jsonSuccess($list);
    }
    /**
     * 取消收藏
     * @SWG\Post(path="/user/cancelCollection",
     * tags={"用户模块"},
     * summary="取消收藏",
     * produces={"application/json"},
     * description="",
     * @SWG\Parameter(in="formData",name="source",type="string",description="要取消的记录的分类
     *      已知分类:
     *     sshouse:二手房
     * ",required=true),
     * @SWG\Parameter(in="formData",name="record_id",type="string",description="要取消收藏的记录的ids,多个用逗号拼接",required=true),
     * @SWG\Parameter(in="header",name="token",type="string",description="登陆token",required=true),
     * @SWG\Response(response="200", description="请求成功")
     * )
     */
    function cancelCollection(){
        $source=$this->request->param("source");
        $record_id=$this->request->param("record_id");
        UserCollectionRecordLogic::cancelCollection($source,$record_id);
        return jsonSuccess("收藏成功");
    }


    /**
     * 获取浏览记录列表
     * @SWG\Get(path="/user/getBrowseList",
     * tags={"用户模块"},
     * summary="获取浏览记录列表",
     * produces={"application/json"},
     * description="",
     * @SWG\Parameter(in="query",name="source",type="string",description="要获取的记录的分类
     *      已知分类:
     *     sshouse:二手房
     * ",required=true),
     * @SWG\Parameter(in="query",name="page",type="string",description="分页",default="1"),
     * @SWG\Parameter(in="header",name="token",type="string",description="登陆token",required=true),
     * @SWG\Response(response="200", description="请求成功")
     * )
     */
    function getBrowseList(){
        $source=$this->request->param("source");
        $record_id=$this->request->param("record_id");
        $list=UserBrowseRecordLogic::getBrowseList($source);
        return jsonSuccess($list);
    }
    /**
     * 删除浏览记录
     * @SWG\Post(path="/user/deleteBrowseRecord",
     * tags={"用户模块"},
     * summary="删除浏览记录",
     * produces={"application/json"},
     * description="",
     * @SWG\Parameter(in="formData",name="source",type="string",description="要删除的记录的分类
     *      已知分类:
     *     sshouse:二手房
     * ",required=true),
     * @SWG\Parameter(in="formData",name="record_id",type="string",description="要删除的记录的ids,多个用逗号拼接",required=true),
     * @SWG\Parameter(in="header",name="token",type="string",description="登陆token",required=true),
     * @SWG\Response(response="200", description="请求成功")
     * )
     */
    function deleteBrowseRecord(){
        $source=$this->request->param("source");
        $record_id=$this->request->param("record_id");
        UserBrowseRecordLogic::deleteBrowseRecord($source,$record_id);
        return jsonSuccess();
    }
    /**
     * 获取消费记录
     * @SWG\Get(path="/user/getCashRecordList",
     * tags={"用户模块"},
     * summary="获取消费记录列表",
     * produces={"application/json"},
     * description="",
     * @SWG\Parameter(in="query",name="page",type="string",description="分页",default="1"),
     * @SWG\Parameter(in="header",name="token",type="string",description="登陆token",required=true),
     * @SWG\Response(response="200", description="请求成功")
     * )
     */
    function getCashRecordList(){
        $res=UserCashRecordModel::getCashRecordList();
        return jsonSuccess($res->all());
    }
    /**
     * 删除消费记录
     * @SWG\Post(path="/user/deleteCashRecord",
     * tags={"用户模块"},
     * summary="删除消费记录",
     * produces={"application/json"},
     * description="",
     * @SWG\Parameter(in="formData",name="id",type="string",description="要删除的记录的ids,多个用逗号拼接",required=true),
     * @SWG\Parameter(in="header",name="token",type="string",description="登陆token",required=true),
     * @SWG\Response(response="200", description="请求成功")
     * )
     */
    function deleteCashRecord(){
        $id=$this->request->param("id");
        UserCashRecordModel::deleteCashRecord($id);
        return jsonSuccess();
    }
    /**
     * 获取未读站内信数量
     * @SWG\Get(path="/user/getUnreadMessageCount",
     * tags={"用户模块"},
     * summary="获取未读站内信数量",
     * produces={"application/json"},
     * description="",
     * @SWG\Parameter(in="header",name="token",type="string",description="登陆token",required=true),
     * @SWG\Response(response="200", description="请求成功")
     * )
     */
    function getUnreadMessageCount(){
        $res=UserSiteMessageModel::getUnreadMessageCount();
        return jsonSuccess($res);
    }
    /**
     * 获取站内信列表
     * @SWG\Get(path="/user/getUserSiteMessage",
     * tags={"用户模块"},
     * summary="获取站内信列表",
     * produces={"application/json"},
     * description="",
     * @SWG\Parameter(in="query",name="page",type="string",description="分页",default="1"),
     * @SWG\Parameter(in="header",name="token",type="string",description="登陆token",required=true),
     * @SWG\Response(response="200", description="请求成功")
     * )
     */
    function getUserSiteMessage(){
        $res=UserSiteMessageModel::getSiteMessageListAndSetRead();
        return jsonSuccess($res->all());
    }


}

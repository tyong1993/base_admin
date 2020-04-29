<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/7 0007
 * Time: 16:53
 */

namespace app\api\controller;


use app\front\base\BaseController;
use app\front\logic\ShhouseLogic;
use app\front\logic\SystemBannerLogic;
use app\front\model\ShhouseAgentModel;

class IndexController extends BaseController
{
    protected $isNeedLogin = false;
    /**
     * 首页
     * @SWG\Get(path="/index/index",
     * tags={"二手房首页"},
     * summary="首页",
     * produces={"application/json"},
     * description="这里是接口说明信息",
     * @SWG\Parameter(in="header",name="token",type="string",description="登陆token",required=true),
     * @SWG\Response(response="200", description="请求成功")
     * )
     */
    function Index(){
        $index_banner=SystemBannerLogic::getBannerByUniqueIdentify("index_top_banner");
        $data=[
            "top_banenr"=>$index_banner,
        ];
        return jsonSuccess($data);
    }
    /**
     * 首页
     * @SWG\Get(path="/index/IndexSearch",
     * tags={"二手房首页"},
     * summary="搜索",
     * produces={"application/json"},
     * description="这里是接口说明信息",
     * @SWG\Parameter(in="query",name="search",type="string",description="搜索",required=true),
     * @SWG\Parameter(in="query",name="transaction_type",type="string",description="交易类型,出租或者出售"),
     * @SWG\Parameter(in="header",name="token",type="string",description="登陆token",required=true),
     * @SWG\Response(response="200", description="请求成功")
     * )
     */
    function IndexSearch(){
        $search =  request()->get("search");
        $transaction_type =  request()->get("transaction_type");
        $shhouse=ShhouseLogic::getShhouseList(["search"=>$search,"transaction_type"=>$transaction_type],false);
        $agent_ids=array_column($shhouse->toArray(),"agent_id");
        $agents=ShhouseAgentModel::getAgentList(["ids"=>$agent_ids]);
        $data=[
            "shhouse"=>$shhouse,
            "agents"=>$agents,
        ];
        return jsonSuccess($data);
    }

}
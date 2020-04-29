<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/6 0006
 * Time: 14:13
 */

namespace app\api\controller;
use app\front\base\BaseController;
use app\front\logic\SystemArticleLogic;
use think\Request;

/**
 * 文章控制器
 */
class ArticleController extends BaseController
{
    protected $isNeedLogin = false;
    /**
     * 获取分类下的文章列表
     *      type:1根据id获取,2根据唯一标识获取
     *      unique_identify:id或者唯一标识码
     * @SWG\Get(path="/article/articleList",
     * tags={"文章"},
     * summary="文章列表",
     * produces={"application/json"},
     * description="这里是接口说明信息",
     * @SWG\Parameter(in="query",name="type",type="string",description="获取方式:1通过分类id获取,2通过分类唯一码获取",required=true),
     * @SWG\Parameter(in="query",name="unique_identify",type="string",description="分类id或分类唯一码",required=true),
     * @SWG\Parameter(in="query",name="page",type="string",description="分页",default="1"),
     * @SWG\Response(response="200", description="请求成功")
     * )
     */
    function articleList(){
        $type=$this->request->param("type");
        $unique_identify=$this->request->param("unique_identify");
        $article=SystemArticleLogic::getArticleList($type,$unique_identify);
        return jsonSuccess($article->all());
    }

    /**
     * 获取文章详情
     *      type:1根据id获取,2根据唯一标识获取
     *      unique_identify:id或者唯一标识码
     * @SWG\Get(path="/article/articleDetail",
     * tags={"文章"},
     * summary="文章详情",
     * produces={"application/json"},
     * description="
     * ---data
     * ------title:标题
     * ------describe:表述
     * ------content:内容
     * ------cover:封面图
     * ",
     * @SWG\Parameter(in="query",name="type",type="string",description="获取方式:1通过id获取,2通过唯一码获取",required=true),
     * @SWG\Parameter(in="query",name="unique_identify",type="string",description="id或唯一码
     * 已知唯一码:
     * user_protocol:用户协议
     * jy_privacy_protocol:纪元隐私协议
     * jy_upload_house_protocol:上录房源协议
     * system_welcome:欢迎使用纪元平台
     * about_us:联系我们
     * house_butler:房管家
     * new_house_introduce:新房板块介绍
     * domestic_service_introduce:家政板块介绍
     * ",
     * required=true),
     * @SWG\Response(response="200", description="请求成功")
     * )
     */
    function articleDetail(){
        $type=$this->request->param("type");
        $unique_identify=$this->request->param("unique_identify");
        $article=SystemArticleLogic::getArticleDetail($type,$unique_identify);
        return jsonSuccess($article);
    }
}
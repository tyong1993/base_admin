<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/6 0006
 * Time: 18:13
 */

namespace app\api\controller;


use app\front\base\BaseController;
use app\front\logic\SystemCategoryLogic;

/**
 * 分类控制器
 */
class CategoryController extends BaseController
{
    /**
     * 获取分类
     *      type:1根据id获取,2根据唯一标识获取
     *      unique_identify:id或者唯一标识码
     * @SWG\Get(path="/category/categorys",
     * tags={"分类"},
     * summary="分类",
     * produces={"application/json"},
     * description="这里是接口说明信息",
     * @SWG\Parameter(in="query",name="type",type="string",description="获取方式:1通过id获取,2通过唯一码获取",required=true),
     * @SWG\Parameter(in="query",name="unique_identify",type="string",description="id或唯一码
     *     已知唯一码:
     *     platform_help:平台帮助
     * ",required=true),
     * @SWG\Parameter(in="header",name="token",type="string",description="登陆token",required=true),
     * @SWG\Response(response="200", description="请求成功")
     * )
     */
    function categorys(){
        $type=$this->request->param("type");
        $unique_identify=$this->request->param("unique_identify");
        $article=SystemCategoryLogic::getCategorys($type,$unique_identify);
        return jsonSuccess($article->all());
    }
}
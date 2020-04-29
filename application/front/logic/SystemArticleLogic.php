<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/6 0006
 * Time: 13:57
 */

namespace app\front\logic;


use app\common\exception\LogicException;
use app\common\tools\config\SystemConfig;
use app\common\tools\upload\SystemFile;
use app\front\base\BaseLogic;
use app\front\model\SystemArticleModel;

class SystemArticleLogic extends BaseLogic
{
    /**
     * 获取文章列表
     *      type:1根据分类id获取,2根据分类唯一标识获取
     *      unique_identify:id或者唯一标识码
     */
    public static function getArticleList($type,$unique_identify){
        if(empty($type) || empty($unique_identify)){
            throw new LogicException("参数错误");
        }
        switch ($type){
            case 1:
                $articles=SystemArticleModel::getListByCategoryId($unique_identify);break;
            case 2:
                $articles=SystemArticleModel::getListByCategoryUniqueIdentify($unique_identify);break;
            default:
                throw new LogicException("不可识别的获取方式");
        }
        //数据处理
        foreach ($articles as &$article){
            $article->cover=SystemFile::getFileUrl($article->cover);
            $article->create_time=date("Y-m-d",$article->create_time);
        }
        return $articles;
    }

    /**
     * 获取文章详情
     *      type:1根据id获取,2根据唯一标识获取
     *      unique_identify:id或者唯一标识码
     */
    public static function getArticleDetail($type,$unique_identify){
        if(empty($type) || empty($unique_identify)){
            throw new LogicException("参数错误");
        }
        switch ($type){
            case 1:
                $article=SystemArticleModel::getDetailById($unique_identify);break;
            case 2:
                $article=SystemArticleModel::getDetailByUniqueIdentify($unique_identify);break;
            default:
                throw new LogicException("不可识别的获取方式");
        }
        if(empty($article)){
            throw new LogicException("文章不存在");
        }else{
            //获取文章封面图
            $article->cover=SystemFile::getFileUrl($article->cover);
            $article->create_time=date("Y-m-d H:i",$article->create_time);
        }
        //将富文本内的src标签替换成全路径
        $article->content=str_replace("<img src=\"","<img src=\"".SystemConfig::getConfig("localUploadDirverHost")."",$article->content);
        return $article;
    }
}
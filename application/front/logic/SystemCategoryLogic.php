<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/6 0006
 * Time: 18:06
 */

namespace app\front\logic;


use app\common\exception\LogicException;
use app\common\tools\upload\SystemFile;
use app\front\base\BaseLogic;
use app\front\model\SystemCategoryModel;

class SystemCategoryLogic extends BaseLogic
{
    /**
     * 获取分类
     *      type:1根据分类id获取,2根据分类唯一标识获取
     *      unique_identify:id或者唯一标识码
     */
    public static function getCategorys($type,$unique_identify){
        if(empty($type) || empty($unique_identify)){
            throw new LogicException("参数错误");
        }
        switch ($type){
            case 1:
                $categorys=SystemCategoryModel::getChildByParentId($unique_identify);break;
            case 2:
                $categorys=SystemCategoryModel::getChildByUniqueIdentify($unique_identify);break;
            default:
                throw new LogicException("不可识别的获取方式");
        }
        //数据处理
        foreach ($categorys as &$category){
            $category->icon=SystemFile::getFileUrl($category->icon);
        }
        return $categorys;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/6 0006
 * Time: 13:57
 */

namespace app\front\logic;


use app\common\exception\LogicException;
use app\common\tools\upload\SystemFile;
use app\front\base\BaseLogic;
use app\front\model\SystemBannerModel;

class SystemBannerLogic extends BaseLogic
{
    /**
     * 获取banner列表
     *      $type:banner分类标识
     */
    public static function getBannerList($type){
        if(empty($type)){
            throw new LogicException("参数错误");
        }
        $banners=SystemBannerModel::getListByType($type);
        //数据处理
        foreach ($banners as &$banner){
            $banner->picture=SystemFile::getFileUrl($banner->picture);
        }
        return $banner;
    }

    /**
     * 获取一个banner
     *      unique_identify:唯一标识码
     */
    public static function getBannerByUniqueIdentify($unique_identify){
        if(empty($unique_identify)){
            throw new LogicException("参数错误");
        }
        $banner=SystemBannerModel::getBannerByUniqueIdentify($unique_identify);
        if(empty($banner)){
            $banner=new \stdClass();
        }else{
            $banner->picture=SystemFile::getFileUrl($banner->picture);
        }
        return $banner;
    }
}
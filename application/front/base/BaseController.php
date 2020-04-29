<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/3/30 0030
 * Time: 15:51
 */

namespace app\front\base;


use app\admin\logic\ShhouseLogic;
use think\Controller;

class BaseController extends Controller
{
    /**
     * 当前控制器是否需要登陆检测
     */
    protected $isNeedLogin=true;

    /**
     * 当前控制器中不需要检测登陆的方法
     */
    protected $notNeedLoginFun=[];
    // 验证失败是否抛出异常
    protected $failException = true;

    /**
     * @throws \app\common\exception\LogicException
     * 初始化
     */
    protected function initialize(){
        //房源下架
        ShhouseLogic::autoDownShhouse();
        //登陆检测
        if($this->isNeedLogin === true){
            BaseLogic::checkLogin($this->request,$this->notNeedLoginFun);

        }
    }
}
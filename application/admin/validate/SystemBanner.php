<?php
/**
 * Created by PhpStorm.
 * User: NickBai
 * Email: 876337011@qq.com
 * Date: 2019/10/8
 * Time:  15:54
 */
namespace app\admin\validate;

use app\common\exception\LogicException;
use think\Validate;

class SystemBanner extends Validate
{
    protected $rule =   [
        "start_time"=>"checkSETime",
//        'type'  => 'require',
    ];

    protected $message  =   [
//        'type.require' => '请选择分类',
    ];

    /**
     * 检查开始时间与结束时间是否符合逻辑
     */
    protected function checkSETime($value,$rule,$param){
        if(isset($param["start_time"]) && isset($param["end_time"])){
            if($param["start_time"] > $param["end_time"]){
                throw new LogicException("开始时间不能大于结束时间");
            }
        }
        return true;
    }
}
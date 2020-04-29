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

class SystemConfig extends Validate
{
    protected $rule =   [
        'type'  => 'require|checkJson',
        'group'  => 'require',
    ];

    protected $message  =   [
        'type.require' => '请选择分类',
        'group.require' => '请选择分组',
    ];

    /**
     * 检测json格式
     */
    protected function checkJson($value,$rule,$param)
    {
        if(in_array($value,["array","map"])){
            $msg=$value=="array"?"数组":"对象";
            try{
                $res=json_decode($param['value']);
            }catch (\Exception $e){
                throw new LogicException('配置值格式错误,请使用标准json'.$msg.'格式');
            }
            if(!is_array($res) && $value=="array"){
                throw new LogicException('配置值格式错误,请使用标准json'.$msg.'格式');
            }
            if(!is_object($res) && $value=="map"){
                throw new LogicException('配置值格式错误,请使用标准json'.$msg.'格式');
            }
        }
        if ($value == "enum") {
            if(empty($param['enum_config'])){
                throw new LogicException("请填写枚举配置项");
            }
            try{
                $res=json_decode($param['enum_config']);
            }catch (\Exception $e){
                throw new LogicException("枚举配置项格式错误,请使用标准json对象格式");
            }
            if(!is_object($res)){
                throw new LogicException("枚举配置项格式错误,请使用标准json对象格式");
            }
        }
        return true;
    }
}
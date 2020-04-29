<?php
/**
 * Created by PhpStorm.
 * User: NickBai
 * Email: 876337011@qq.com
 * Date: 2019/10/8
 * Time:  15:54
 */
namespace app\admin\validate;

use think\Validate;

class ShhouseVillage extends Validate
{
    protected $rule =   [
        'name'  => 'require',
    ];

    protected $message  =   [
        'name.require' => '配置名称不能为空',
    ];
}
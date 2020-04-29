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

class SystemArticle extends Validate
{
    protected $rule =   [
        'category_id'  => 'require',
    ];

    protected $message  =   [
        'category_id.require' => '请选择分类',
    ];
}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/3/27 0027
 * Time: 10:52
 */

namespace app\front\base;


use think\Model;

class BaseModel extends Model
{
    /**
     * 默认分页数
     */
    public static $page_limit=10;


}
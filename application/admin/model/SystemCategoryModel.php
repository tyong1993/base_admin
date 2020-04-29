<?php
/**
 * Created by PhpStorm.
 * User: NickBai
 * Email: 876337011@qq.com
 * Date: 2019/3/17
 * Time: 4:48 PM
 */
namespace app\admin\model;

use app\common\exception\LogicException;
use think\Db;
use think\Model;

class SystemCategoryModel extends Model
{
    /**
     * 获取树原始数据
     */
    public static function getTreeData(){
        return self::order("parent_id asc,weight desc")->select()->toArray();
    }
}
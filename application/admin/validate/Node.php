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
use think\Db;
use think\Validate;

class Node extends Validate
{
    protected $rule =   [
        'node_name'  => 'require',
        'node_path'   => 'require',
        'is_menu'   => 'checkPC'
    ];

    protected $message  =   [
        'node_name.require' => '节点名称不能为空',
        'node_path.require'   => '节点路径不能为空'
    ];
    /**
     * 设置菜单时检测父子关系
     */
    protected function checkPC($value,$rules,$param){
        //子为菜单必须得父是菜单才行
        if($param["is_menu"] == 2 && $param["node_pid"] != 0){
            $parent=Db::table("system_admin_power_node")->where(["node_id"=>$param["node_pid"]])->find();
            if($parent["is_menu"] != 2){
                throw new LogicException("该项不能设置为菜单,请先将它的父级设置为菜单");
            }
        }
        //父不为菜单子不为菜单
        if($param["is_menu"] == 1 && isset($param["node_id"])){
            $parent=Db::table("system_admin_power_node")->where(["node_pid"=>$param["node_id"],"is_menu"=>2])->find();
            if(!empty($parent)){
                throw new LogicException("该项存在子项为菜单,不能取消菜单");
            }
        }
        return true;
    }
}
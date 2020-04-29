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
use think\facade\Cache;
use think\facade\Log;
use think\Model;

class NodeModel extends Model
{
    protected $table = 'system_admin_power_node';

    /**
     * 获取节点数据
     */
    public function getNodesList()
    {
        return $res = $this->field('node_id as id,node_name as title,node_pid as pid,node_path,node_icon,add_time,is_menu')->order("node_pid asc,weight desc")->select()->toArray();
    }

    /**
     * 获取节点数据
     */
    public function getNodesTree($roleId)
    {
        $result = $this->field('node_id,node_name,node_pid')->select();
        $str = '';

        $roleModel = new RoleModel();
        $rule = $roleModel->getRoleInfoById($roleId);

        $nodeArr = [];
        if(!empty($rule)){
            $nodeArr = explode(',', $rule['role_node']);
        }

        foreach($result as $key => $vo){
            $str .= '{ "id": "' . $vo['node_id'] . '", "pId":"' . $vo['node_pid'] . '", "name":"' . $vo['node_name'].'"';

            if(!empty($nodeArr) && in_array($vo['node_id'], $nodeArr)){
                $str .= ' ,"checked":1';
            }

            $str .= '},';

        }

        $res = '[' . rtrim($str, ',') . ']';
        return $res;
    }

    /**
     * 根据节点id获取节点信息
     */
    public function getNodeInfoByIds($ids)
    {
        return $res = $this->whereIn('node_id', $ids)->select()->toArray();
    }

    /**
     * 添加节点
     */
    public function addNode($param)
    {
        // 检测唯一
        $has = $this->field('node_id')->where('node_name', $param['node_name'])
            ->where('node_pid', $param['node_pid'])->find();

        if (!empty($has)) {
            throw new LogicException("该节点名称已经存在");
        }

        $param['add_time'] = date('Y-m-d H:i:s');

        $this->insert($param);
    }

    /**
     * 编辑节点
     */
    public function editNode($param)
    {
        // 检测唯一
        $has = $this->field('node_id')->where('node_name', $param['node_name'])
            ->where('node_pid', $param['node_pid'])->where('node_id', '<>', $param['node_id'])->find();

        if (!empty($has)) {
            throw new LogicException("该节点名称已经存在");
        }

        $this->where('node_id', $param['node_id'])->update($param);
    }

    /**
     * 根据id 获取节点信息
     */
    public function getNodeInfoById($id)
    {
        return $res = $this->where('node_id', $id)->find();
    }

    /**
     * 根据id 删除节点
     */
    public function deleteNodeById($id)
    {
        // 检测节点下是否有其他的节点
        $has = $this->where('node_pid', $id)->count();
        if ($has > 0) {
            throw new LogicException("该节点下尚有其他节点，不可删除");
        }

        $this->where('node_id', $id)->delete();
    }

    /**
     * 获取节点的id
     */
    public function getNodeIdByPath($path)
    {
        return $res = $this->field('node_id')->where('node_path', $path)->find();
    }

    /**
     * 获取角色菜单集合
     */
    public function getRoleMenuMap($roleId)
    {
        $res = [];

        if (1 == $roleId) {

            $res = $this->field('node_id as id,node_name as title,node_pid as pid,node_path,node_icon')
                ->where('is_menu', 2)->order("node_pid asc,weight desc")->select()->toArray();
        } else {

            $roleModel = new RoleModel();
            $roleInfo = $roleModel->getRoleInfoById($roleId);

            if (!empty($roleInfo)) {

                $res = $this->field('node_id as id,node_name as title,node_pid as pid,node_path,node_icon')
                    ->whereIn('node_id', $roleInfo['role_node'])->where('is_menu', 2)->order("node_pid asc,weight desc")->select()->toArray();
            }
        }
        return $res;
    }
}
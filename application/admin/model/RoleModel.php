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
use think\Model;

class RoleModel extends Model
{
    protected $table = 'system_admin_role';

    /**
     * 获取角色列表
     */
    public function getRolesList($limit, $where)
    {
        return $res = $this->where($where)->order('role_id', 'desc')->paginate($limit);
    }

    /**
     * 增加角色
     */
    public function addRole($param)
    {
        $has = $this->where('role_name', $param['role_name'])->find();
        if(!empty($has)) {
            throw new LogicException("角色名称已经存在");
        }

        $param['role_node'] = '1,2,3'; // 默认权限

        $this->insert($param);
    }

    /**
     * 获取角色信息
     */
    public function getRoleInfoById($id)
    {
        return $info = $this->where('role_id', $id)->findOrEmpty()->toArray();
    }

    /**
     * 编辑角色
     */
    public function editRole($param)
    {
        $has = $this->where('role_name', $param['role_name'])->where('role_id', '<>', $param['role_id'])
            ->findOrEmpty()->toArray();
        if(!empty($has)) {
            throw new LogicException("角色名已经存在");
        }

        $this->save($param, ['role_id' => $param['role_id']]);
    }

    /**
     * 删除角色
     */
    public function delRoleById($id)
    {
        if (1 == $id) {
            throw new LogicException("超级管理员不可删除");
        }

        // 检测角色下是否有管理员
        $adminModel = new AdminModel();
        $has = $adminModel->getAdminInfoByRoleId($id);

        if (!empty($has)) {
            throw new LogicException("该角色下有管理员，不可删除");
        }

        $this->where('role_id', $id)->delete();
    }

    /**
     * 获取所有的角色
     */
    public function getAllRoles()
    {
        return $res = $this->where('role_status', 1)->select()->toArray();
    }

    /**
     * 通过id更新角色信息
     */
    public function updateRoleInfoById($roleId, $param)
    {
        return $res = $this->where('role_id', $roleId)->update($param);
    }

    /**
     * 获取角色的权限节点数组
     */
    public function getRoleAuthNodeMap($roleId)
    {
        $map = Cache::get("role_" . $roleId . "_map");

        if (empty($map)) {
            $res = $this->where('role_id', $roleId)->find();
            if (!empty($res)) {
                $map = $this->cacheRoleNodeMap($res['role_node'], $roleId);
            }
        }
        return $map;
    }

    /**
     * 缓存角色节点信息
     */
    public function cacheRoleNodeMap($roleNode, $roleId)
    {
        $nodeModel = new NodeModel();
        $nodeInfo = $nodeModel->getNodeInfoByIds($roleNode);

        $map = [];
        if (!empty($nodeInfo)) {

            foreach ($nodeInfo as $vo) {
                if (empty($vo['node_path']) || '#' == $vo['node_path']) continue;

                $map[$vo['node_path']] = $vo['node_id'];
            }

            Cache::set("role_" . $roleId . "_map", $map);
        }

        return $map;
    }
}
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
use think\Model;

class AdminModel extends Model
{
    protected $table = 'system_admin';

    /**
     * 获取管理员
     */
    public function getAdmins($limit, $where)
    {
        $prefix = config('database.prefix');

        $res = $this->field($prefix . 'system_admin.*,' . $prefix . 'system_admin_role.role_name')->where($where)
            ->leftJoin($prefix . 'system_admin_role', $prefix . 'system_admin.role_id = ' . $prefix . 'system_admin_role.role_id')
            ->order('admin_id', 'desc')->paginate($limit);

        return $res;
    }

    /**
     * 增加管理员
     */
    public function addAdmin($admin)
    {
        $has = $this->where('admin_name', $admin['admin_name'])->findOrEmpty()->toArray();
        if(!empty($has)) {
            throw new LogicException("管理员名已经存在");
        }
        $this->insert($admin);
    }

    /**
     * 获取管理员信息
     */
    public function getAdminById($adminId)
    {
        return $info = $this->where('admin_id', $adminId)->findOrEmpty()->toArray();
    }

    /**
     * 编辑管理员
     */
    public function editAdmin($admin)
    {
        $has = $this->where('admin_name', $admin['admin_name'])->where('admin_id', '<>', $admin['admin_id'])
            ->findOrEmpty()->toArray();
        if(!empty($has)) {
            throw new LogicException("管理名已经存在");
        }

        $this->save($admin, ['admin_id' => $admin['admin_id']]);
    }

    /**
     * 删除管理员
     */
    public function delAdmin($adminId)
    {
        if (1 == $adminId) {
            throw new LogicException("admin管理员不可删除");
        }

        $this->where('admin_id', $adminId)->delete();
    }

    /**
     * 获取管理员信息
     */
    public function getAdminByName($name)
    {
       return $info = $this->where('admin_name', $name)->findOrEmpty()->toArray();
    }

    /**
     * 获取管理员信息
     */
    public function getAdminInfo($id)
    {
        return $info = $this->where('admin_id', $id)->findOrEmpty()->toArray();
    }

    /**
     * 更新登录时间
     */
    public function updateAdminInfoById($id, $param)
    {
        $this->where('admin_id', $id)->update($param);
    }

    /**
     * 根据角色id 获取管理员信息
     */
    public function getAdminInfoByRoleId($roleId)
    {
        return $info = $this->where('role_id', $roleId)->select()->toArray();
    }
}
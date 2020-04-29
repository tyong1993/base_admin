<?php
/**
 * Created by PhpStorm.
 * User: NickBai
 * Email: 876337011@qq.com
 * Date: 2019/9/3
 * Time:  14:30
 */
namespace app\admin\controller;

use app\admin\model\RoleModel as RoleModel;
use app\admin\logic\LogLogic;

class RoleController extends BaseController
{
    // 角色列表
    public function index()
    {
        if(request()->isAjax()) {

            $limit = input('param.limit');
            $roleName = input('param.role_name');

            $where = [];
            if (!empty($roleName)) {
                $where[] = ['role_name', 'like', $roleName . '%'];
            }

            $roleModel = new RoleModel();
            $list = $roleModel->getRolesList($limit, $where);

            return json(['code' => 0, 'msg' => 'ok', 'count' => $list->total(), 'data' => $list->all()]);
        }

        return $this->fetch();
    }

    // 添加角色
    public function add()
    {
        if (request()->isAjax()) {

            $param = input('post.');

            if (empty($param['role_name'])) {
                return jsonFail("请输入角色名称");
            }

            $roleModel = new RoleModel();
            $res = $roleModel->addRole($param);

            LogLogic::write("添加角色：" . $param['role_name']);

            return jsonSuccess();
        }

        $this->assign([
            'pname' => input('param.pname'),
            'pid' => input('param.pid')
        ]);

        return $this->fetch();
    }

    // 编辑角色
    public function edit()
    {
        if (request()->isAjax()) {

            $param = input('post.');

            if (empty($param['role_name'])) {
                return jsonFail("请输入角色名称");
            }

            $roleModel = new RoleModel();
            $res = $roleModel->editRole($param);

            LogLogic::write("编辑角色：" . $param['role_name']);

            return jsonSuccess();
        }

        $id = input('param.id');

        $roleModel = new RoleModel();

        $this->assign([
            'role_info' => $roleModel->getRoleInfoById($id),
        ]);

        return $this->fetch();
    }

    // 删除角色
    public function delete()
    {
        if (request()->isAjax()) {

            $id = input('param.id');

            $roleModel = new RoleModel();
            $res = $roleModel->delRoleById($id);

            LogLogic::write("删除角色：" . $id);

            return jsonSuccess();
        }
    }

    // 分配权限
    public function assignAuthority()
    {
        if (request()->isPost()) {

            $param = input('post.');

            $roleModel = new RoleModel();
            $res = $roleModel->updateRoleInfoById($param['role_id'], [
                'role_node' => rtrim($param['node'], ',')
            ]);

            // 刷新节点缓存
            $roleModel->cacheRoleNodeMap(rtrim($param['node'], ','), $param['role_id']);

            LogLogic::write("分配权限：" . $param['role_id']);

            return jsonSuccess();
        }

        $roleId = input('param.id');
        $roleInfo = (new RoleModel())->getRoleInfoById($roleId);

        $tree = (new \app\admin\model\NodeModel())->getNodesTree($roleId);

        $this->assign([
            'tree' => $tree,
            'role_info' => $roleInfo
        ]);

        return $this->fetch('allot');
    }
}
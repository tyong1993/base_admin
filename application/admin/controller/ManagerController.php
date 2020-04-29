<?php
/**
 * Created by PhpStorm.
 * User: NickBai
 * Email: 876337011@qq.com
 * Date: 2019/2/28
 * Time: 8:23 PM
 */
namespace app\admin\controller;

use app\admin\model\AdminModel;
use app\admin\logic\LogLogic;

class ManagerController extends BaseController
{
    // 管理员列表
    public function index()
    {
        if(request()->isAjax()) {

            $limit = input('param.limit');
            $adminName = input('param.admin_name');

            $where = [];
            if (!empty($adminName)) {
                $where[] = ['admin_name', 'like', $adminName . '%'];
            }

            $admin = new AdminModel();
            $list = $admin->getAdmins($limit, $where);

            return json(['code' => 0, 'msg' => 'ok', 'count' => $list->total(), 'data' => $list->all()]);
        }

        return $this->fetch();
    }

    // 添加管理员
    public function addAdmin()
    {
        if(request()->isPost()) {

            $param = input('post.');
            $this->validate($param,"app\admin\\validate\Admin");
            $param['admin_password'] = makePassword($param['admin_password']);
            $admin = new AdminModel();
            $admin->addAdmin($param);
            LogLogic::write("添加管理员：" . $param['admin_name']);
            return jsonSuccess();
        }

        $this->assign([
            'roles' => (new \app\admin\model\RoleModel())->getAllRoles()
        ]);

        return $this->fetch('add');
    }

    // 编辑管理员
    public function editAdmin()
    {
        if(request()->isPost()) {

            $param = input('post.');
            $this->validate($param,"app\admin\\validate\Admin.edit");

            if(isset($param['admin_password'])) {
                $param['admin_password'] = makePassword($param['admin_password']);
            }

            $admin = new AdminModel();
            $admin->editAdmin($param);

            LogLogic::write("编辑管理员：" . $param['admin_name']);

            return jsonSuccess();
        }

        $adminId = input('param.admin_id');
        $admin = new AdminModel();

        $this->assign([
            'admin' => $admin->getAdminById($adminId),
            'roles' => (new \app\admin\model\RoleModel())->getAllRoles()
        ]);

        return $this->fetch('edit');
    }

    /**
     * 删除管理员
     */
    public function delAdmin()
    {
        if(request()->isAjax()) {

            $adminId = input('param.id');

            $admin = new AdminModel();
            $admin->delAdmin($adminId);
            LogLogic::write("删除管理员：" . $adminId);

            return jsonSuccess();
        }
    }
}
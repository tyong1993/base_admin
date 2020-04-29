<?php
/**
 * Created by PhpStorm.
 * User: NickBai
 * Email: 876337011@qq.com
 * Date: 2019/2/17
 * Time: 11:33 AM
 */
namespace app\admin\controller;

use app\admin\model\AdminModel;
use app\common\exception\LogicException;
use think\App;
use app\admin\logic\AuthLogic;
use think\Db;

class IndexController extends BaseController
{
    public function index()
    {
        $authModel = new AuthLogic();
        $menu = $authModel->getAuthMenu(session('admin_role_id'));

        $this->assign([
            'menu' => $menu,
            'admin' => session('admin_user_name')
        ]);

        return $this->fetch();
    }

    public function home()
    {
        //用户总数
        $user_amount=Db::table("user")->where(["status"=>1])->count();
        //上架中的二手房数量
        $show_shhouse_amount=Db::table("shhouse")->where(["status"=>1,"is_show"=>1])->count();
        $this->assign([
            'tp_version' => App::VERSION,
            'user_amount'=>$user_amount,
            'show_shhouse_amount'=>$show_shhouse_amount,
        ]);

        return $this->fetch();
    }

    // 修改密码
    public function editPwd()
    {
        if (request()->isPost()) {

            $param = input('post.');

            if ($param['new_password'] != $param['rep_password']) {
                return jsonFail("两次密码输入不一致");
            }

            // 检测旧密码
            $admin = new AdminModel();
            $adminInfo = $admin->getAdminInfo(session('admin_user_id'));

            if(empty($adminInfo)){
                return jsonFail("管理员不存在");
            }

            if(!checkPassword($param['password'], $adminInfo['admin_password'])){
                return jsonFail("旧密码错误");
            }

            $admin->updateAdminInfoById(session('admin_user_id'), [
                'admin_password' => makePassword($param['new_password'])
            ]);

            return jsonSuccess(null,"修改密码成功");
        }

        return $this->fetch('pwd');
    }
}

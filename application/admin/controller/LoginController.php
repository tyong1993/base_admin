<?php
/**
 * Created by PhpStorm.
 * User: NickBai
 * Email: 876337011@qq.com
 * Date: 2019/3/17
 * Time: 4:24 PM
 */
namespace app\admin\controller;

use app\admin\model\AdminModel;
use app\admin\model\LoginLogModel;
use app\common\exception\LogicException;
use think\Controller;

class LoginController extends Controller
{
    // 登录页面
    public function index()
    {
        return $this->fetch();
    }

    // 处理登录
    public function doLogin()
    {
        if(request()->isPost()) {

            $param = input('post.');

            if(!captcha_check($param['vercode'])){
                return jsonFail("验证码错误");
            }

            $log = new LoginLogModel();
            $admin = new AdminModel();
            $adminInfo = $admin->getAdminByName($param['username']);

            if(empty($adminInfo)){
                $log->writeLoginLog($param['username'], 2);
                return jsonFail("用户名密码错误");
            }

            if(!checkPassword($param['password'], $adminInfo['admin_password'])){
                $log->writeLoginLog($param['username'], 2);
                return jsonFail("用户名密码错误");
            }

            // 设置session标识状态
            session('admin_user_name', $adminInfo['admin_name']);
            session('admin_user_id', $adminInfo['admin_id']);
            session('admin_role_id', $adminInfo['role_id']);

            // 维护上次登录时间
            $admin->updateAdminInfoById($adminInfo['admin_id'], ['last_login_time' => date('Y-m-d H:i:s')]);

            $log->writeLoginLog($param['username'], 1);

            return jsonSuccess(["jump_url"=>url('index/index')], '登录成功');
        }
    }

    public function loginOut()
    {
        session('admin_user_name', null);
        session('admin_user_id', null);

        $this->redirect(url('login/index'));
    }
}

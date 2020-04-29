<?php
/**
 * Created by PhpStorm.
 * User: NickBai
 * Email: 876337011@qq.com
 * Date: 2019/2/28
 * Time: 8:24 PM
 */
namespace app\admin\controller;

use app\admin\logic\ShhouseLogic;
use think\Controller;
use app\admin\logic\AuthLogic;
use think\Db;

class BaseController extends Controller
{
    // 验证失败是否抛出异常
    protected $failException = true;
    public $agent_id;
    public function initialize()
    {
        if(empty(session('admin_user_name'))){

            $this->redirect(url('login/index'));
        }

        $controller = lcfirst(request()->controller());
        $action = request()->action();
        $checkInput = $controller . '/' . $action;

        $authModel = AuthLogic::instance();
        $skipMap = $authModel->getSkipAuthMap();

        if (!isset($skipMap[$checkInput])) {

            $flag = $authModel->authCheck($checkInput, session('admin_role_id'));

            if (!$flag) {
                if (request()->isAjax()) {
                    return jsonFail("无操作权限");
                } else {
                    $this->error('无操作权限');
                }
            }
        }

        $this->assign([
            'admin_name' => session('admin_user_name'),
            'admin_id' => session('admin_user_id')
        ]);
        //房源下架
        ShhouseLogic::autoDownShhouse();
        //当前管理员的经纪人id,不是经纪人为0
        $agent=Db::table("shhouse_agent")->where(["admin_id"=>session('admin_user_id')])->find();
        $this->agent_id=!empty($agent)?$agent["id"]:0;
    }
}
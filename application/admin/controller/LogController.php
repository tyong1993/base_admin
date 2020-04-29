<?php
/**
 * Created by PhpStorm.
 * User: NickBai
 * Email: 876337011@qq.com
 * Date: 2019/10/11
 * Time:  14:23
 */
namespace app\admin\controller;

use app\admin\model\OperateModel;
use app\admin\model\LoginLogModel;

class LogController extends BaseController
{
    // 登录日志
    public function login()
    {
        if(request()->isAjax()) {

            $limit = input('param.limit');

            $log = new LoginLogModel();
            $list = $log->loginLogList($limit);

            return json(['code' => 0, 'msg' => 'ok', 'count' => $list->total(), 'data' => $list->all()]);
        }

        return $this->fetch();
    }

    // 操作日志
    public function operate()
    {
        if (request()->isAjax()) {

            $limit = input('param.limit');
            $operateTime = input('param.operate_time');

            $where = [];

            if (!empty($operateTime)) {
                $where[] = ['operate_time', 'between', [$operateTime, $operateTime. ' 23:59:59']];
            }

            $operateModel = new OperateModel();
            $list = $operateModel->getOperateLogList($limit, $where);

            return json(['code' => 0, 'msg' => 'ok', 'count' => $list->total(), 'data' => $list->all()]);
        }

        return $this->fetch();
    }
}
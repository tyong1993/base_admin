<?php
/**
 * Created by PhpStorm.
 * User: NickBai
 * Email: 876337011@qq.com
 * Date: 2019/9/29
 * Time: 10:39 PM
 */
namespace app\admin\model;

use think\facade\Log;
use think\Model;
use think\facade\Request;

class LoginLogModel extends Model
{
    protected $table = 'system_admin_login_log';

    /**
     * 写登录日志
     */
    public function writeLoginLog($user, $status)
    {
        $this->insert([
            'login_user' => $user,
            'login_ip' => request()->ip(),
            'login_area' => getLocationByIp(request()->ip()),
            'login_user_agent' => Request::header('user-agent'),
            'login_time' => date('Y-m-d H:i:s'),
            'login_status' => $status
        ]);
    }

    /**
     * 登录日志明细
     */
    public function loginLogList($limit)
    {
        return $log = $this->order('log_id', 'desc')->paginate($limit);
    }
}
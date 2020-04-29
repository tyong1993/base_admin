<?php
/**
 * Created by PhpStorm.
 * User: NickBai
 * Email: 876337011@qq.com
 * Date: 2019/10/11
 * Time:  14:02
 */
namespace app\admin\model;

use think\Model;

class OperateModel extends Model
{
    protected $table = 'system_admin_operate_log';

    /**
     * 写操作日志
     */
    public function writeLog($param)
    {
        $this->insert($param);
    }

    /**
     * 获取角色列表
     */
    public function getOperateLogList($limit, $where)
    {
        return $res = $this->where($where)->order('log_id', 'desc')->paginate($limit);
    }
}
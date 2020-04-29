<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/9 0009
 * Time: 下午 9:25
 */

namespace app\admin\logic;

use app\admin\model\NodeModel;
use app\admin\model\RoleModel;

class AuthLogic
{
    private static $instance;

    // 跳过权限检测的
    private $skipAuthMap = [
        'login/index' => 1,
        'index/index' => 1,
        'index/home' => 1
    ];

    public static function instance()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * 权限检测
     * @param $input
     * @param $roleId
     * @return bool
     */
    public function authCheck($input, $roleId)
    {
        if (1 == $roleId) {
            return true;
        }

        $roleModel = new RoleModel();
        $roleAuthNodeMap = $roleModel->getRoleAuthNodeMap($roleId);

        if (empty($roleAuthNodeMap)) {
            return false;
        }

        if (!isset($roleAuthNodeMap[$input])) {
            return false;
        }

        return true;
    }

    /**
     * 获取权限菜单
     * @param $roleId
     * @return array
     */
    public function getAuthMenu($roleId)
    {
        $nodeModel = new NodeModel();
        $menu = $nodeModel->getRoleMenuMap($roleId);

        return makeTree($menu);
    }

    /**
     * @return array
     */
    public function getSkipAuthMap()
    {
        return $this->skipAuthMap;
    }

    /**
     * @param array $skipAuthMap
     */
    public function setSkipAuthMap($skipAuthMap)
    {
        $this->skipAuthMap = array_merge($this->getSkipAuthMap(), $skipAuthMap);
    }
}
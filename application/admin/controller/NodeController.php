<?php
/**
 * Created by PhpStorm.
 * User: NickBai
 * Email: 876337011@qq.com
 * Date: 2019/9/3
 * Time:  14:30
 */
namespace app\admin\controller;

use app\admin\model\NodeModel as NodeModel;
use app\admin\logic\LogLogic;

class NodeController extends BaseController
{
    // 节点列表
    public function index()
    {
        $node = new NodeModel();
        $list = $node->getNodesList();
        $this->assign([
            'tree' => makeTree($list)
        ]);

        return $this->fetch();
    }

    // 添加节点
    public function add()
    {
        if (request()->isAjax()) {

            $param = input('post.');
            $this->validate($param,"app\admin\\validate\Node");
            $nodeModel = new NodeModel();
            $res = $nodeModel->addNode($param);

            LogLogic::write("添加节点：" . $param['node_name']);

            return jsonSuccess();
        }

        $this->assign([
            'pname' => input('param.pname'),
            'pid' => input('param.pid')
        ]);

        return $this->fetch();
    }

    // 编辑节点
    public function edit()
    {
        if (request()->isAjax()) {

            $param = input('post.');

            $this->validate($param,"app\admin\\validate\Node");

            $nodeModel = new NodeModel();
            $res = $nodeModel->editNode($param);

            LogLogic::write("编辑节点：" . $param['node_name']);

            return jsonSuccess();
        }

        $id = input('param.id');
        $pid = input('param.pid');

        $nodeModel = new NodeModel();

        if (0 == $pid) {
            $pNode = '顶级节点';
        } else {
            $pNode = $nodeModel->getNodeInfoById($pid)['node_name'];
        }

        $this->assign([
            'node_info' => $nodeModel->getNodeInfoById($id),
            'p_node' => $pNode
        ]);

        return $this->fetch();
    }

    // 删除节点
    public function delete()
    {
        if (request()->isAjax()) {

            $id = input('param.id');

            $nodeModel = new NodeModel();
            $res = $nodeModel->deleteNodeById($id);

            LogLogic::write("删除节点：" . $id);

            return jsonSuccess();
        }
    }
}
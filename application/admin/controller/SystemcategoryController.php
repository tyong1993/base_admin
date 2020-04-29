<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/3 0003
 * Time: 13:52
 */

namespace app\admin\controller;


use app\admin\logic\LogLogic;
use app\admin\model\SystemCategoryModel;
use app\admin\validate\SystemCategory;
use app\common\exception\LogicException;
use app\common\tools\upload\SystemFile;
use think\Db;

class SystemcategoryController extends BaseController
{

    public function index(){
        $list=SystemCategoryModel::getTreeData();
        $tree=makeTree($list,0,"parent_id");
        $this->assign([
            'tree' => $tree
        ]);

        return $this->fetch();
    }

    public function add()
    {
        if (request()->isAjax()) {

            $param = input('post.');
            $this->validate($param,"app\admin\\validate\SystemCategory");
            //防止唯一键冲突
            if(empty($param["unique_identify"])){
                unset($param["unique_identify"]);
            }
            //查看父类的等级,确定自己的等级
            if($param["parent_id"] == 0){
                $param["level"]=1;
            }else{
                $param["level"]=Db::table("system_category")->where(["id"=>$param["parent_id"]])->value("level")+1;
            }
            $id=Db::table("system_category")->strict(false)->insertGetId($param);

            LogLogic::write("添加分类：" . $id);

            return jsonSuccess();
        }
        $this->assign([
            'pname' => input('param.pname'),
            'parent_id' => input('param.parent_id')
        ]);

        return $this->fetch();
    }


    public function edit()
    {
        if (request()->isAjax()) {

            $param = input('post.');

            $this->validate($param,"app\admin\\validate\SystemCategory");
            //防止唯一键冲突
            if(empty($param["unique_identify"])){
                unset($param["unique_identify"]);
            }
            Db::table("system_category")->strict(false)->update($param);

            LogLogic::write("编辑分类：" . $param['id']);

            return jsonSuccess();
        }

        $id = input('param.id');
        $parent_id = input('param.parent_id');

        if (0 == $parent_id) {
            $pname = '顶级分类';
        } else {
            $pname = Db::table("system_category")->where(["id"=>$parent_id])->value("name");
        }
        $row=Db::table("system_category")->find($id);
        $row["file_url"]=SystemFile::getFileUrl($row["icon"]);
        $this->assign([
            'row' => $row,
            'pname' => $pname
        ]);

        return $this->fetch();
    }


    public function delete()
    {
        if (request()->isAjax()) {
            $id = input('id');
            //不可以删除的ids
            $canNotDeleteIds=[];
            if(in_array($id,$canNotDeleteIds)){
                return jsonFail("该分类不可以删除");
            }
            //存在子分类不可删除
            if(Db::table("system_category")->where(["parent_id"=>$id])->count()){
                return jsonFail("该分类存在子分类,不可删除");
            }
            Db::table("system_category")->delete($id);

            LogLogic::write("删除分类：" . $id);

            return jsonSuccess();
        }
    }
}
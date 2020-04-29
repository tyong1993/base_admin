<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/3 0003
 * Time: 13:53
 */

namespace app\admin\controller;


use app\admin\logic\LogLogic;
use app\admin\model\SystemCategoryModel;
use think\Db;

class SystemarticleController extends BaseController
{

    public function index()
    {
        if(request()->isAjax()) {
            $limit = input('param.limit');
            $search = input('param.search');
            $category_id = input('param.category_id');
            $db=Db::table("system_article sa")->leftJoin("system_category sc","sa.category_id = sc.id")->field("sa.*,sc.name category_name");
            if (!empty($search)) {
                $db->where('sa.title', 'like', '%' .$search . '%');
            }
            if (!empty($category_id)) {
                $db->where(['sa.category_id'=>$category_id]);
            }
            $list = $db->paginate($limit)->toArray();
            return json(['code' => 0, 'msg' => 'ok', 'count' => $list["total"], 'data' => $list["data"]]);
        }
        //获取文章分类
        $parentArticleCategory=Db::table("system_category")->where(["unique_identify"=>"article_category"])->find();
        $categorys=SystemCategoryModel::getTreeData();
        $tree=makeTree($categorys,$parentArticleCategory["id"],"parent_id");
        $this->assign('tree' , $tree);
        return $this->fetch();
    }

    public function add(){
        if(request()->isAjax()) {
            $param =  request()->post('', null, 'trim');
            $this->validate($param,"app\admin\\validate\SystemArticle");
            $param['admin_id']=session('admin_user_id');
            $param['create_time']=time();
            $param['update_time']=time();
            //防止唯一键冲突
            if(empty($param["unique_identify"])){
                unset($param["unique_identify"]);
            }
            $id=Db::table("system_article")->strict(false)->insertGetId($param);
            LogLogic::write("添加系统文章：" . $id);

            return jsonSuccess();
        }
        //获取文章分类
        $parentArticleCategory=Db::table("system_category")->where(["unique_identify"=>"article_category"])->find();
        $categorys=SystemCategoryModel::getTreeData();
        $tree=makeTree($categorys,$parentArticleCategory["id"],"parent_id");
        $this->assign('tree' , $tree);
        return $this->fetch();
    }

    public function edit(){
        if(request()->isAjax()) {
            $param =  request()->post('', null, 'trim');
            $this->validate($param,"app\admin\\validate\SystemArticle");
            $param['update_time']=time();
            //防止唯一键冲突
            if(empty($param["unique_identify"])){
                unset($param["unique_identify"]);
            }
            Db::table("system_article")->strict(false)->update($param);
            LogLogic::write("编辑系统文章：" . $param['id']);

            return jsonSuccess();
        }
        //获取文章分类
        $parentArticleCategory=Db::table("system_category")->where(["unique_identify"=>"article_category"])->find();
        $categorys=SystemCategoryModel::getTreeData();
        $tree=makeTree($categorys,$parentArticleCategory["id"],"parent_id");
        $this->assign('tree' , $tree);
        $this->assign([
            "row"=>Db::table("system_article")->find(input("id")),
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
                return jsonFail("该文章不可以删除");
            }

            Db::table("system_article")->delete($id);

            LogLogic::write("删除文章：" . $id);

            return jsonSuccess();
        }
    }
}
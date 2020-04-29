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
use app\common\tools\config\SystemConfig;
use think\Db;

class SystembannerController extends BaseController
{

    public function index()
    {
        if(request()->isAjax()) {
            $limit = input('param.limit');
            $search = input('param.search');
            $category_id = input('param.category_id');
            $db=Db::table("system_banner");
            if (!empty($search)) {
                $db->where('description', 'like', '%' .$search . '%');
            }
            if (!empty($category_id)) {
                $db->where(['category_id'=>$category_id]);
            }
            $list = $db->paginate($limit)->toArray();
            return json(['code' => 0, 'msg' => 'ok', 'count' => $list["total"], 'data' => $list["data"]]);
        }
        //获取banner分类
        $bannerCategorys=SystemConfig::getConfig("banner_type");
        $this->assign("bannerCategorys",$bannerCategorys);
        return $this->fetch();
    }

    public function add(){
        if(request()->isAjax()) {
            $param =  request()->post('', null, 'trim');
            if(isset($param['start_time'])){
                $param['start_time']=strtotime($param['start_time']);
            }
            if(isset($param['end_time'])){
                $param['end_time']=strtotime($param['end_time']);
            }
            $this->validate($param,"app\admin\\validate\SystemBanner");
            $param['create_time']=time();
            $param['update_time']=time();
            //防止唯一键冲突
            if(empty($param["unique_identify"])){
                unset($param["unique_identify"]);
            }
            $id=Db::table("system_banner")->strict(false)->insertGetId($param);
            LogLogic::write("添加系统Banner：" . $id);

            return jsonSuccess();
        }
        //获取banner分类
        $bannerCategorys=SystemConfig::getConfig("banner_type");
        $this->assign("bannerCategorys",$bannerCategorys);
        $this->assign("target_rule",SystemConfig::getConfig("banner_target_rule"));
        return $this->fetch();
    }

    public function edit(){
        if(request()->isAjax()) {
            $param =  request()->post('', null, 'trim');
            if(isset($param['start_time'])){
                $param['start_time']=strtotime($param['start_time']);
            }
            if(isset($param['end_time'])){
                $param['end_time']=strtotime($param['end_time']);
            }
            $this->validate($param,"app\admin\\validate\SystemBanner");
            $param['update_time']=time();
            //防止唯一键冲突
            if(empty($param["unique_identify"])){
                unset($param["unique_identify"]);
            }
            Db::table("system_banner")->strict(false)->update($param);
            LogLogic::write("编辑系统Banner：" . $param['id']);
            return jsonSuccess();
        }
        $res=Db::table("system_banner")->find(input("id"));
        if($res['start_time']){
            $res['start_time']=date("Y-m-d",$res['start_time']);
        }
        if($res['end_time']){
            $res['end_time']=date("Y-m-d",$res['end_time']);
        }
        //获取banner分类
        $bannerCategorys=SystemConfig::getConfig("banner_type");
        $this->assign("bannerCategorys",$bannerCategorys);
        $this->assign("target_rule",SystemConfig::getConfig("banner_target_rule"));
        $this->assign("row",$res);
        return $this->fetch();
    }

    public function delete()
    {
        if (request()->isAjax()) {

            $id = input('id');
            //不可以删除的ids
            $canNotDeleteIds=[];
            if(in_array($id,$canNotDeleteIds)){
                return jsonFail("该Banner不可以删除");
            }

            Db::table("system_banner")->delete($id);

            LogLogic::write("删除Banner：" . $id);

            return jsonSuccess();
        }
    }
}
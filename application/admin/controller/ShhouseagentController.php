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
use app\common\tools\upload\SystemFile;
use think\Db;

/**
 * 经纪人管理
 */
class ShhouseagentController extends BaseController
{

    public function index()
    {
        if(request()->isAjax()) {
            $limit = input('param.limit');
            $search = input('param.search');
            $db=Db::table("shhouse_agent");
            if (!empty($search)) {
                $db->where('name', 'like', '%' .$search . '%');
            }
            $list = $db->paginate($limit)->toArray();
            foreach ($list["data"] as &$val){
                $val["avatar"]=SystemFile::getFileUrl($val["avatar"]);
                //陪同服务次数
                $val["shhouse_escort_times"]=Db::table("shhouse_escort_order")->where([["agent_id","eq",$val["id"]],["status","eq",3]])->count();
            }
            return json(['code' => 0, 'msg' => 'ok', 'count' => $list["total"], 'data' => $list["data"]]);
        }
        return $this->fetch();
    }

    public function add(){
        if(request()->isAjax()) {
            $param =  request()->post('', null, 'trim');
            if(empty($param["admin_id"])){
                return jsonFail("请先关联一名管理员");
            }
            $id=Db::table("shhouse_agent")->strict(false)->insertGetId($param);
            LogLogic::write("添加经纪人：" . $id);
            return jsonSuccess();
        }
        //获取管理员,关联经纪人
        $admins=Db::table("system_admin")->where(["status"=>1])->select();
        //查询存在的经纪人
        $agents_admin_ids=Db::table("shhouse_agent")->column("admin_id");
        $res=[];
        foreach ($admins as $val){
            if($val["admin_id"] == 1){
                continue;
            }
            if(!in_array($val["admin_id"],$agents_admin_ids)){
                $res[$val["admin_id"]]=$val["admin_name"];
            }
        }
        $this->assign('tree' , $res);
        return $this->fetch();
    }

    public function edit(){
        if(request()->isAjax()) {
            $param =  request()->post('', null, 'trim');
            if(empty($param["admin_id"])){
                return jsonFail("请先关联一名管理员");
            }
            Db::table("shhouse_agent")->strict(false)->update($param);
            LogLogic::write("编辑经纪人：" . $param['id']);
            return jsonSuccess();
        }
        $agent=Db::table("shhouse_agent")->find(input("id"));
        //获取管理员,关联经纪人
        $admins=Db::table("system_admin")->where(["status"=>1])->select();
        //查询存在的经纪人
        $agents_admin_ids=Db::table("shhouse_agent")->column("admin_id");
        $res=[];
        foreach ($admins as $val){
            if($val["admin_id"] == 1){
                continue;
            }
            if(!in_array($val["admin_id"],$agents_admin_ids) || $val["admin_id"] == $agent["admin_id"]){
                $res[$val["admin_id"]]=$val["admin_name"];
            }
        }
        $this->assign('tree' , $res);
        $this->assign([
            "row"=>$agent,
        ]);
        return $this->fetch();
    }

    public function delete()
    {
        if (request()->isAjax()) {

            $id = input('id');
            $shhouse=Db::table("shhouse")->where("agent_id","eq",$id)->where("status","eq",1)->count();
            if(!empty($shhouse)){
                return jsonFail("该经纪人下存在房源,不可以删除");
            }
            Db::table("shhouse_agent")->delete($id);

            LogLogic::write("删除经纪人：" . $id);

            return jsonSuccess();
        }
    }
}
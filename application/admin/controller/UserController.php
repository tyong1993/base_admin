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
use app\common\exception\LogicException;
use think\Db;

class UserController extends BaseController
{

    public function index()
    {
        if(request()->isAjax()) {
            $limit = input('param.limit');
            $search = input('param.search');
            $vip = input('param.vip');
            $db=Db::table("user")->leftJoin("shhouse_user","user.id = shhouse_user.user_id");
            if (!empty($search)) {
                $db->where('nickname', 'like', '%' .$search . '%');
            }
            if(!empty($vip)){
                $db->where("is_vip","eq",$vip-1);
            }
            $list = $db->paginate($limit)->toArray();
            foreach ($list["data"] as &$val){
                //是否会员
                $val["is_vip"]=$val["is_vip"]?"是":"否";
                $val["vip_end_time"]=$val["is_vip"]=="否"?"---":date("Y-m-d",$val["vip_end_time"]);
                //房源数量
                $val["shhouse_number"]=Db::table("shhouse")->where([["user_id","eq",$val["id"]],["status","eq",1]])->count();
                //陪同服务次数
                $val["shhouse_escort_times"]=Db::table("shhouse_escort_order")->where([["user_id","eq",$val["id"]],["status","eq",3]])->count();
            }
            return json(['code' => 0, 'msg' => 'ok', 'count' => $list["total"], 'data' => $list["data"]]);
        }
        $vip=[
            "1"=>"不是",
            "2"=>"是",
        ];
        $this->assign("vip",$vip);
        return $this->fetch();
    }

    public function add(){
        if(request()->isAjax()) {
            $param =  request()->post('', null, 'trim');
            if(empty($param["village_id"])){
                throw new LogicException("请选择小区");
            }
            if(Db::table("shhouse_village")->where(["id"=>$param["village_id"]])->value("type") !== 3){
                throw new LogicException("请选择小区,不能选择区域和街道");
            }
            $id=Db::table("shhouse_village_info")->strict(false)->insertGetId($param);
            LogLogic::write("添加物业信息：" . $id);
            return jsonSuccess();
        }
        //获取小区
        $shhouseVillages=Db::table("shhouse_village")->order("pid asc,weight desc")->select();
        $tree=makeTree($shhouseVillages);
        $this->assign('tree' , $tree);
        return $this->fetch();
    }

    public function edit(){
        if(request()->isAjax()) {
            $param =  request()->post('', null, 'trim');
            if(empty($param["village_id"])){
                throw new LogicException("请选择小区");
            }
            if(Db::table("shhouse_village")->where(["id"=>$param["village_id"]])->value("type") !== 3){
                throw new LogicException("请选择小区,不能选择区域和街道");
            }
            Db::table("shhouse_village_info")->strict(false)->update($param);
            LogLogic::write("编辑物业信息：" . $param['id']);

            return jsonSuccess();
        }
        //获取小区
        $shhouseVillages=Db::table("shhouse_village")->order("pid asc,weight desc")->select();
        $tree=makeTree($shhouseVillages);
        $this->assign('tree' , $tree);
        $this->assign([
            "row"=>Db::table("shhouse_village_info")->find(input("id")),
        ]);
        return $this->fetch();
    }

    public function delete()
    {
        if (request()->isAjax()) {
            $id = input('id');
            Db::table("shhouse_village_info")->delete($id);
            LogLogic::write("删除物业信息：" . $id);
            return jsonSuccess();
        }
    }
}
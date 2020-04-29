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

class ShhousevillageinfoController extends BaseController
{

    public function index()
    {
        if(request()->isAjax()) {
            $limit = input('param.limit');
            $search = input('param.search');
            $db=Db::table("shhouse_village_info")
                ->alias("svi")
                //->leftJoin("shhouse_village sv","svi.street_id = sv.id")
                ->field("svi.*")
            ;
            if (!empty($search)) {
                $db->where('svi.name', 'like', '%' .$search . '%');
            }

            $list = $db->paginate($limit)->toArray();
            return json(['code' => 0, 'msg' => 'ok', 'count' => $list["total"], 'data' => $list["data"]]);
        }
        return $this->fetch();
    }

    public function add(){
        if(request()->isAjax()) {
            $param =  request()->post('', null, 'trim');
            if(empty($param["street_id"])){
                throw new LogicException("请选择街道");
            }
            if(Db::table("shhouse_village")->where(["id"=>$param["street_id"]])->value("type") !== 2){
                throw new LogicException("请选择街道,不能选择区域");
            }
            $id=Db::table("shhouse_village_info")->strict(false)->insertGetId($param);
            LogLogic::write("添加物业信息：" . $id);
            return jsonSuccess();
        }
        //获取街道
        $shhouseVillages=Db::table("shhouse_village")->order("pid asc,weight desc")->select();
        $tree=makeTree($shhouseVillages);
        $this->assign('tree' , $tree);
        return $this->fetch();
    }

    public function edit(){
        if(request()->isAjax()) {
            $param =  request()->post('', null, 'trim');
            if(empty($param["street_id"])){
                throw new LogicException("请选择街道");
            }
            if(Db::table("shhouse_village")->where(["id"=>$param["street_id"]])->value("type") !== 2){
                throw new LogicException("请选择街道,不能选择区域");
            }
            Db::table("shhouse_village_info")->strict(false)->update($param);
            LogLogic::write("编辑物业信息：" . $param['id']);

            return jsonSuccess();
        }
        //获取街道
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
            //存在房源不可以删除
            $shhouse=Db::table("shhouse")->where("village_id","eq",$id)->where("status","eq",1)->count();
            if(!empty($shhouse)){
                return jsonFail("该小区存在房源,不可以删除");
            }
            Db::table("shhouse_village_info")->delete($id);
            LogLogic::write("删除物业信息：" . $id);
            return jsonSuccess();
        }
    }
}
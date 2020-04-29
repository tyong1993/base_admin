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
use app\admin\model\UserSiteMessageModel;
use app\common\exception\LogicException;
use app\common\tools\upload\SystemFile;
use think\Db;

class ShhouseController extends BaseController
{

    public function index()
    {
        //房源审核状态分类
        $check_status_tree=[
            "1"=>"待审核",
            "2"=>"审核通过",
            "3"=>"审核未通过",
        ];
        if(request()->isAjax()) {
            $limit = input('param.limit');
            $search = input('param.search');
            $check_status = input('param.check_status');
            $transaction_type = input('param.transaction_type');
            $db=Db::table("shhouse")
                ->alias("s")
                ->leftJoin("shhouse_village_info svi","s.village_id = svi.id")
                ->where("s.status","eq",1)
                ->field("s.*,svi.name village_name")
            ;
            //如果是经纪人则只展示他的房源和待审核的房源
            if($this->agent_id){
                $db->where("agent_id","in",[$this->agent_id,0]);
            }
            if (!empty($search)) {
                $db->where('sv.name', 'like', '%' .$search . '%');
            }
            //根据审核状态筛选
            if(!empty($check_status)){
                $db->where("check_status","eq",$check_status-1);
            }
            //根据交易类型筛选
            if(!empty($transaction_type)){
                $db->where("transaction_type","eq",$transaction_type);
            }
            $list = $db->paginate($limit)->toArray();
            foreach ($list["data"] as &$val){
                $val["check_status_name"]=$check_status_tree[$val["check_status"]+1];
            }
            return json(['code' => 0, 'msg' => 'ok', 'count' => $list["total"], 'data' => $list["data"]]);
        }
        $transaction_type=[
            "出售"=>"出售",
            "出租"=>"出租",
        ];
        $this->assign("transaction_type",$transaction_type);
        $this->assign("check_status",$check_status_tree);
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
            $id=Db::table("shhouse")->strict(false)->insertGetId($param);
            LogLogic::write("添加房源信息：" . $id);
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

            //如果是经纪人则房源属于他自己
            if($this->agent_id){
                $param["agent_id"]=$this->agent_id;
            }else{
                if(empty($param["agent_id"])){
                    return jsonFail("请先为房源分配经纪人");
                }
            }
            //审核通知
            $shhouse=Db::table("shhouse")->find($param["id"]);
            //审核状态发生改变
            if($shhouse["check_status"] != $param["check_status"]){
                //审核通过
                if($param["check_status"] == 1){
                    UserSiteMessageModel::sendSiteMessage("shhouse_check_pass",$shhouse["user_id"],"您发布的编号:{$shhouse['unique_number']}的房源已通过平台审核","您发布的编号:{$shhouse['unique_number']}的房源已通过平台审核,您可以上架您的房源在平台展示了");
                }
                //审核不通过
                else if($param["check_status"] == 2){
                    if(empty($param["refuse_reason"])){
                        return jsonFail("请填写审核不通过的原因,将通知给用户做出调整");
                    }
                    UserSiteMessageModel::sendSiteMessage("shhouse_check_refuse",$shhouse["user_id"],"您发布的编号:{$shhouse['unique_number']}的房源审核未通过,具体原因请查看详情",$param["refuse_reason"]);
                }
            }
            Db::table("shhouse")->strict(false)->update($param);
            LogLogic::write("编辑房源信息：" . $param['id']);

            return jsonSuccess();
        }


        $row=Db::table("shhouse")
            ->alias("s")
            ->leftJoin("shhouse_village_info svi","s.village_id = svi.id")
            ->field("s.*,svi.name village_name")
            ->find(input("id"))
        ;
        $row["build_time"]=date("Y-m-d",$row["build_time"]);
        $row["trim_time"]=date("Y-m-d",$row["trim_time"]);
        $row["buy_time"]=date("Y-m-d",$row["buy_time"]);
        $row["has_elevator"]=$row["has_elevator"]?"有":"无";
        $row["has_loan"]=$row["has_loan"]?"有":"无";
        $row["can_remove_by_youself"]=$row["can_remove_by_youself"]?"是":"否";
        $row["look_up"]=$row["look_up"]?"有钥匙":"预约";
        $row["pictures"]=SystemFile::getFileUrl($row["pictures"],true);
        //获取经纪人
        $agents=Db::table("shhouse_agent")->column("name","id");
        $this->assign([
            "row"=>$row,
            "agent_id"=>$this->agent_id,
            "tree"=>$agents,
        ]);
        return $this->fetch();
    }

    public function delete()
    {
        if (request()->isAjax()) {
            $id = input('id');
            Db::table("shhouse")->where(["id"=>$id])->update(["status"=>0]);
            LogLogic::write("删除房源信息：" . $id);
            return jsonSuccess();
        }
    }
}
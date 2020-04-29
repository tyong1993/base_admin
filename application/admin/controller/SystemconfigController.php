<?php
/**
 * Created by PhpStorm.
 * User: NickBai
 * Email: 876337011@qq.com
 * Date: 2019/2/17
 * Time: 11:33 AM
 */
namespace app\admin\controller;

use app\admin\logic\LogLogic;
use app\common\exception\LogicException;
use app\common\tools\config\SystemConfig;
use app\common\tools\upload\SystemFile;
use think\Db;
use think\Request;

class SystemconfigController extends BaseController
{
    public function index()
    {
        if(request()->isAjax()) {
            $limit = input('param.limit');
            $search = input('param.search');

            $db=Db::table("system_config");
            if (!empty($search)) {
                $db->where('name', 'like', '%' .$search . '%');
                $db->whereOr('title', 'like', '%' .$search . '%');
            }
            $list = $db->paginate($limit)->toArray();
            return json(['code' => 0, 'msg' => 'ok', 'count' => $list["total"], 'data' => $list["data"]]);
        }
        return $this->fetch();
    }

    public function add(){
        if(request()->isAjax()) {
            $param =  request()->post('', null, 'trim');
            $this->validate($param,"app\admin\\validate\SystemConfig");
            //格式化数据
            if(in_array($param["type"],["array","map"])){
                $param['value']=json_encode(json_decode($param['value']),JSON_UNESCAPED_UNICODE);
            }
            if($param["type"]=="enum"){
                $param['enum_config']=json_encode(json_decode($param['enum_config']),JSON_UNESCAPED_UNICODE);
            }
            $param['create_time']=time();
            $param['update_time']=time();
            $id=Db::table("system_config")->insertGetId($param);
            LogLogic::write("添加系统配置：" . $id);
            return jsonSuccess();
        }
        $this->assign([
                "config_type"=>SystemConfig::getConfig("config_type"),
                "config_group"=>SystemConfig::getConfig("config_group")
            ]);
        return $this->fetch();
    }

    public function edit(){
        if(request()->isAjax()) {
            $param =  request()->post('', null, 'trim');
            $this->validate($param,"app\admin\\validate\SystemConfig");
            //格式化数据
            if(in_array($param["type"],["array","map"])){
                $param['value']=json_encode(json_decode($param['value']),JSON_UNESCAPED_UNICODE);
            }
            if($param["type"]=="enum"){
                $param['enum_config']=json_encode(json_decode($param['enum_config']),JSON_UNESCAPED_UNICODE);
            }
            $param['update_time']=time();
            Db::table("system_config")->update($param);
            LogLogic::write("编辑系统配置：" . $param['id']);

            return jsonSuccess();
        }
        $this->assign([
            "row"=>Db::table("system_config")->find(input("id")),
            "config_type"=>SystemConfig::getConfig("config_type"),
            "config_group"=>SystemConfig::getConfig("config_group")
        ]);
        return $this->fetch();
    }

    public function delete(){
        $id=input("id");
        //不可以删除的ids
        $canNotDeleteIds=[1,2];
        if(in_array(input("id"),$canNotDeleteIds)){
            return jsonFail("该配置不可以删除");
        }
        Db::table("system_config")->delete($id);
        LogLogic::write("删除系统配置：" .$id);
        return jsonSuccess();
    }

    public function _empty(Request $request)
    {
        if(strpos($request->action(),"managesystemconfig") !== false){
            $group=str_replace("managesystemconfig","",$request->action());
            return $this->manageSystemConfig($group);
        }
    }
    /**
     * 分组管理系统配置
     * 节点路径中方法名称必须是managesystemconfig+配置分组名称
     */
    private function manageSystemConfig($group){
        if(request()->isPost()) {
            $param =  request()->post('', null, 'trim');
            $data=[];
            foreach ($param as $key =>$val){
                $res=Db::table("system_config")->where(["name"=>$key])->find();
                if(!empty($res)){
                    $res["value"]=$val;
                    $this->validate($res,"app\admin\\validate\SystemConfig");
                    //格式化数据
                    if(in_array($res["type"],["array","map"])){
                        $val=json_encode(json_decode($val),JSON_UNESCAPED_UNICODE);
                    }
                    $data[]=[
                        "id"=>$res["id"],
                        "value"=>$val,
                    ];
                }

            }
            foreach ($data as $val){
                Db::table("system_config")->update($val);
            }
            return jsonSuccess();
        }
        $res=Db::table("system_config")->where(["group"=>$group])->select();
        foreach ($res as $key=>&$val){
            if($val["type"] == "image"){
                $val["file_url"]=SystemFile::getFileUrl($val["value"]);
            }
            if($val["type"] == "enum"){
                $val["enum_config"]=json_decode($val["enum_config"],true);

            }

        }
        $this->assign([
            "rows"=>$res,
            "group"=>$group,
        ]);
        return $this->fetch("systemconfig/managesystemconfig");
    }

}

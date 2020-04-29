<?php
/**
 * Created by PhpStorm.
 * User: NickBai
 * Email: 876337011@qq.com
 * Date: 2019/9/3
 * Time:  14:30
 */
namespace app\admin\controller;


use app\admin\logic\LogLogic;
use app\common\tools\upload\SystemFile;
use think\Db;

class ShhousevillageController extends BaseController
{
    // 配置列表
    public function index()
    {
        $list = Db::table("shhouse_village")->select();
        foreach ($list as &$val){
            $val["type"]=$val["type"]==1?"区域":($val["type"]==2?"街道":"小区");
        }
        $this->assign([
            'tree' => makeTree($list)
        ]);
        return $this->fetch();
    }
    //添加配置
    public function add()
    {
        if (request()->isAjax()) {

            $param = input('post.');
            $this->validate($param,"app\admin\\validate\ShhouseVillage");

            //查看父类的类型,确定自己的类型
            if($param["pid"] == 0){
                $param["type"]=1;
            }else{
                $param["type"]=Db::table("shhouse_village")->where(["id"=>$param["pid"]])->value("type")+1;
            }
            $id=Db::table("shhouse_village")->strict(false)->insertGetId($param);

            LogLogic::write("添加配置：" . $id);

            return jsonSuccess();
        }
        $this->assign([
            'pname' => input('param.pname'),
            'pid' => input('param.pid')
        ]);

        return $this->fetch();
    }


    public function edit()
    {
        if (request()->isAjax()) {

            $param = input('post.');

            $this->validate($param,"app\admin\\validate\\ShhouseVillage");
            Db::table("shhouse_village")->strict(false)->update($param);

            LogLogic::write("编辑配置：" . $param['id']);

            return jsonSuccess();
        }

        $id = input('param.id');
        $pid = input('param.pid');

        if (0 == $pid) {
            $pname = '顶级配置';
        } else {
            $pname = Db::table("shhouse_village")->where(["id"=>$pid])->value("name");
        }
        $row=Db::table("shhouse_village")->find($id);
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
            //存在房源不可删除
            $shhouse=Db::table("shhouse_village_info")->where("street_id","eq",$id)->count();
            if(!empty($shhouse)){
                return jsonFail("该街道下存在小区,不可以删除");
            }
            //存在子配置不可删除
            if(Db::table("shhouse_village")->where(["pid"=>$id])->count()){
                return jsonFail("该配置存在子配置,不可删除");
            }
            Db::table("shhouse_village")->delete($id);

            LogLogic::write("删除配置：" . $id);

            return jsonSuccess();
        }
    }
}
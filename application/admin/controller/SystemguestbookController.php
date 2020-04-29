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

/**
 * 系统留言建议
 */
class SystemguestbookController extends BaseController
{

    public function index()
    {
        if(request()->isAjax()) {
            $limit = input('param.limit');
            $type = input('param.type');
            $db=Db::table("system_guestbook");
            if (!empty($type)) {
                $db->where(['type'=>$type]);
            }
            $list = $db->order("status asc")->paginate($limit)->toArray();
            foreach ($list["data"] as &$val){
                $val["create_time"]=date("Y-m-d H:i",$val["create_time"]);
                $val["type"]=$val["type"]==1?"用户需求":"投诉建议";
                $val["status"]=$val["status"]==1?"已处理":"未处理";
            }
            return json(['code' => 0, 'msg' => 'ok', 'count' => $list["total"], 'data' => $list["data"]]);
        }
        //留言分类
        $types=[
            "1"=>"用户需求",
            "2"=>"投诉建议",
        ];
        $this->assign("types",$types);
        return $this->fetch();
    }

    public function edit(){
        if(request()->isAjax()) {
            $param =  request()->post('', null, 'trim');
            Db::table("system_guestbook")->strict(false)->update($param);
            LogLogic::write("处理系统留言建议：" . $param['id']);
            return jsonSuccess();
        }
        $res=Db::table("system_guestbook")->find(input("id"));
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
                return jsonFail("该记录不可以删除");
            }

            Db::table("system_guestbook")->delete($id);

            LogLogic::write("删除系统留言建议：" . $id);

            return jsonSuccess();
        }
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: tyong
 * Date: 2020/4/9
 * Time: 23:36
 */

namespace app\front\logic;


use app\common\exception\LogicException;
use app\front\base\BaseLogic;
use app\front\model\UserBrowseRecordModel;
use think\facade\Hook;

class UserBrowseRecordLogic extends BaseLogic
{
    /**
     * 添加用户浏览记录
     */
    public static function addBrowseRecord($source,$record_id){
        $record=UserBrowseRecordModel::where(["user_id"=>USER_ID,"source"=>$source,"record_id"=>$record_id])->find();
        if(empty($record)){
            $data=[
                "user_id"=>USER_ID,
                "source"=>$source,
                "record_id"=>$record_id,
                "status"=>1,
                "create_time"=>time(),
                "update_time"=>time(),
            ];
            UserBrowseRecordModel::insert($data);
        }else{
            $record->update_time=time();
            $record->status=1;
            $record->save();
        }
    }
    /**
     * 删除浏览记录
     */
    public static function deleteBrowseRecord($source,$record_id){
        if(empty($record_id)){
            throw new LogicException("请选择要操作的记录");
        }
        UserBrowseRecordModel::where(["user_id"=>USER_ID,"source"=>$source])->where("record_id","in",$record_id)->update(
            [
                "status"=>0,
                "update_time"=>time()
            ]
        );
    }

    /**
     * 浏览列表
     * 根据浏览时间排序分页获得当前页的浏览记录ids,然后交由具体的业务类获得数据
     * 业务类需要注意当某些id失效的时候如何处理,比如后台将该记录设置为不可访问了,或者删除了
     * 建议当设置为不可访问的时候此处依然可以获得数据,只在他前往详情页的时候做限制即可
     * 建议这类数据不能让后台做真删除操作,不然数据完整性得不到保障
     */
    public static function getBrowseList($source){
        $list=UserBrowseRecordModel::where(["user_id"=>USER_ID,"source"=>$source,"status"=>1])->order("create_time desc")->paginate(UserBrowseRecordModel::$page_limit);
        $list=$list->all();
        $ids=[0];
        foreach ($list as $val){
            $ids[]=$val["record_id"];
        }
        $ids=implode(",",$ids);
        //获取具体记录的信息
        $res=Hook::listen('get_browse_list',["ids"=>$ids,"source"=>$source],true);
        return $res;
    }
}
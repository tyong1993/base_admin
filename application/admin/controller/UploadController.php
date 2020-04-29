<?php
/**
 * Created by PhpStorm.
 * User: tyong
 * Date: 2020/4/2
 * Time: 22:18
 */

namespace app\admin\controller;


use app\common\tools\upload\SystemFile;
use think\Request;

class UploadController extends BaseController
{
    public function upload(Request $request){
        $files=$request->file();
        $res=SystemFile::upload($files,["ext"=>"jpg,png,gif,jpeg,xls,xlsx"]);
        return jsonSuccess($res);
    }
}
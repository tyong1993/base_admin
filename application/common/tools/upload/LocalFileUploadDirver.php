<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/1 0001
 * Time: 16:04
 */

namespace app\common\tools\upload;


use app\common\exception\LogicException;
use think\File;

class LocalFileUploadDirver extends FileUploadDirver
{
    private $rootDir="uploads";
    function upload(File $file)
    {
        if(!is_dir($this->rootDir)){
            throw new LogicException("上传根目录不存在,请先添加并设置好目录权限");
        }
        $saveDir=$this->rootDir."/".request()->module()."/".request()->controller()."/".request()->action();
        $res=$file->move( $saveDir);
        if($res === false){
            throw new LogicException($file->getError());
        }
        return [
            "uri"=>"/".$saveDir."/".$res->getSaveName(),
            "file"=>$res
        ];
    }


}
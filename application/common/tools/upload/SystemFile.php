<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/1 0001
 * Time: 15:10
 */

namespace app\common\tools\upload;


use app\common\tools\config\SystemConfig;
use think\Db;
use think\File;

class SystemFile
{
    /**
     * 上传文件
     * 返回文件id
     * $config:
     *      size:限制文件大小(单位字节),默认不限制
     *      ext:限制文件的后缀名(多个用逗号隔开),默认不限制
     */
    public static function upload($files,$config=[],$dirver="localUploadDirver"){
        $result=[];
        if(is_array($files)){
            foreach ($files as $key=>$val){
                $result[$key]=[];
                if(is_array($val)){
                    foreach ($val as $v){
                        $result[$key][]=self::uploadAgent($v,$config,$dirver);
                    }
                }else{
                    $result[$key]=self::uploadAgent($val,$config,$dirver);
                }
            }
        }else{
            $result=self::uploadAgent($files,$config,$dirver);
        }
        return $result;
    }
    /**
     * 上传代理
     */
    private static function uploadAgent(File $file,$config,$dirver){
        $uploader=app("$dirver");
        $res=self::isFileExist($file);
        if($res === false){
            $info=$uploader->upload($file->validate($config));
            $id=self::addFile($info,$dirver);
            $result["id"]=$id;
            $result["url"]=self::getFileUrl($id);
        }else{
            $result["id"]=$res;
            $result["url"]=self::getFileUrl($res);
        }
        return $result;
    }
    /**
     * 文件是否已经存在
     * 存在返回id
     */
    private static function isFileExist(File $file){
        $res=Db::table("system_file")->where(["mime"=>$file->getMime(),"md5"=>$file->md5()])->select();
        if(!empty($res)){
            foreach ($res as $val){
                if($val["sha1"] == $file->sha1() && $val["size"] == $file->getSize()){
                    return $val['id'];
                }
            }
        }
        return false;
    }
    /**
     * 添加文件
     */
    private static function addFile($info,$uploadDriver){
        $file=$info["file"];
        $data=[
            "name"=>$file->getFilename(),
            "extend_name"=>$file->getExtension(),
            "size"=>$file->getSize(),
            "mime"=>$file->getMime(),
            "md5"=>$file->md5(),
            "sha1"=>$file->sha1(),
            "uri"=>str_replace("\\","/",$info['uri']),
            "upload_driver"=>$uploadDriver,
            "carete_time"=>time(),
        ];
        return Db::table("system_file")->insertGetId($data);
    }

    /**
     * 获取文件url
     */
    public static function getFileUrl($ids,$return_array=false){
        if(empty($ids)){
            return $return_array?[]:"";
        }
        $res=Db::table("system_file")->where("id","in",$ids)->select();
        $urls=[];
        foreach ($res as $val){
            $urls[]=SystemConfig::getConfig($val["upload_driver"]."Host").$val["uri"];
        }
        if(!$return_array){
            return isset($urls[0])?$urls[0]:"";
        }
        return $urls;
    }
    /**
     * 在做编辑功能的时候获取文件url和id
     */
    public static function getFileForEdit($ids,$return_array=false){
        if(empty($ids)){
            return $return_array?[]:new \stdClass();
        }
        $res=Db::table("system_file")->where("id","in",$ids)->select();
        $data=[];
        foreach ($res as $val){
            $url=SystemConfig::getConfig($val["upload_driver"]."Host").$val["uri"];
            $data[]=[
                "id"=>$val["id"],
                "url"=>$url,
            ];
        }
        if(!$return_array){
            return isset($data[0])?$data[0]:new \stdClass();;
        }
        return $data;
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/1 0001
 * Time: 15:40
 */

namespace app\common\tools\upload;


use think\File;

abstract class FileUploadDirver
{
    /**
     * 返回文件RUI,File $file
     */
    abstract function upload(File $file);
}
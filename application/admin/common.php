<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
use app\admin\logic\AuthLogic;

/**
 * 获取mysql 版本
 * @return string
 */
function getMysqlVersion() {

    $conn = mysqli_connect(
        config('database.hostname') . ":" . config('database.hostport'),
        config('database.username'),
        config('database.password'),
        config('database.database')
    );

    return mysqli_get_server_info($conn);
}

/**
 * 根据ip定位
 * @param $ip
 * @return string
 * @throws Exception
 */
function getLocationByIp($ip)
{
    $ip2region = new \Ip2Region();
    $info = $ip2region->btreeSearch($ip);

    $info = explode('|', $info['region']);

    $address = '';
    foreach($info as $vo) {
        if('0' !== $vo) {
            $address .= $vo . '-';
        }
    }

    return rtrim($address, '-');
}

/**
 * 按钮检测
 * @param $input
 * @return bool
 */
function buttonAuth($input)
{
    $authModel = AuthLogic::instance();
    return  $authModel->authCheck($input, session('admin_role_id'));
}

/**
 * 文件上传组件
 * $name:数据上传的name属性值
 * $value:做编辑的时候用
 * $is_multiple:是否支持多文件上传
 */
function fileUploadTool($name,$value=0,$is_multiple=false,$is_image=true){
    $is_multiple=$is_multiple?1:0;
    $value=$value?:0;
    $files=\app\common\tools\upload\SystemFile::getFileForEdit($value,true);
    return  view('public/upload',["name"=>$name,"is_multiple"=>$is_multiple,"value"=>$value,"files"=>$files,"is_image"=>$is_image])->getContent();
}
/**
 * 富文本编辑器组件
 *  * $name:数据上传的name属性值
 *  $content做编辑的时候使用
 */
function editorTool($name,$content=""){

    return view('public/editor',["name"=>$name,"content"=>$content])->getContent();
}
/**
 * 下拉框组件
 * $name:数据上传的name属性值
 * $value:选中的值,做编辑的时候使用
 *      普通下拉框:
 *          $data为一个一维数组
 *      层级下拉框:
 *          $data为makeTree()函数生成的数组,层级关系通过level字段体现,顶级为1
 *          $value_key:数据中选中值的键名,默认id
 *          $show_key:数据中用来做option展示的键名,默认name
 */
function selectGradeTool($data,$name,$value="",$value_key="id",$show_key="name"){
    $tree=[];
    if(!isset($data[0]["children"])){
        foreach ($data as $key=>$val){
            $tree[]=[
                "id"=>$key,
                "name"=>$val,
                "level"=>1,
            ];
        }
    }else{
        $tree=pullTree($data);
    }
    return view('public/select_grade',["tree"=>$tree,"name"=>$name,"value"=>$value,"value_key"=>$value_key,"show_key"=>$show_key])->getContent();

}
/**
 * 时间选择组件
 * $name:数据上传的name属性值
 * $value做编辑的时候使用,只支持时间格式的字符串
 * $range左右面板范围选择
 * $format时间格式
 */
function dateTimeTool($name,$value=null,$type="date",$range=false,$format=null){
    return view('public/date_time',["name"=>$name,"value"=>$value,"type"=>$type,"range"=>$range,"format"=>$format,])->getContent();

}
/**
 * 树视图
 * $limit_level:限制分类等级,默认三级
 * $controller:视图里面增删改对应使用的控制器,便于扩展其他分类表
 */
function treeView($tree,$limit_level=3,$controller="systemcategory",$level=1){
    foreach ($tree as $val){
        $level_str="";
        for($i=0;$i<$level;$i++){
            $level_str.="&nbsp;&nbsp;&nbsp;&nbsp;";
        }
        echo view('public/tree_view',["controller"=>$controller,"val"=>$val,"level"=>$level,"level_str"=>$level_str,"limit_level"=>$limit_level])->getContent();
        if(!empty($val["children"])){
            treeView($val["children"],$limit_level,$controller,$level+1);
        }
    }
}
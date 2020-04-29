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
/**
 * 打印调试函数
 * @param $data
 */
function dump($data,$is_exit=true) {
    echo "<pre>";
    print_r($data);
    if($is_exit){
        exit;
    }
}
/**
 * 生成子孙树
 * 父子关系通过$pidField体现
 * $topParentId为顶级节点的$pidField
 * 子节点位于children字段中
 * $data必须是$pidField升序排列的
 */
function makeTree($data,$topParentId=0,$pidField="pid") {
    foreach ($data as $val){
        $val["children"]=[];
        $myself="temp".$val["id"];
        $$myself=$val;
        $parent="temp".$val[$pidField];
        $parent_container=&$$parent;
        $parent_container["children"][]=&$$myself;
    }
    $res="temp".$topParentId;
    if(!isset($$res)){
        return [];
    }
    $res=$$res;
    return $res["children"];

//    $res = [];
//    $tree = [];
//
//    // 整理数组
//    foreach ($data as $key => $vo) {
//        $res[$vo['id']] = $vo;
//        $res[$vo['id']]['children'] = [];
//    }
//    // 查询子孙
//    foreach ($res as $key => $vo) {
//        if($vo[$pidField] != $topParentId){
//            $res[$vo[$pidField]]['children'][] = &$res[$key];
//        }
//    }
//    // 去除杂质
//    foreach ($res as $key => $vo) {
//        if($vo[$pidField] == $topParentId){
//            $tree[] = $vo;
//        }
//    }
//
//    return $tree;
}

/**
 * 将makeTree函数生成的数据按顺序提取出来
 */
function pullTree($tree,$res=[],$level=1){
    foreach ($tree as $val){
        $children=$val["children"];
        unset($val["children"]);
        $val["level"]=$level;
        $res[]=$val;
        if(!empty($children)){
            $res=pullTree($children,$res,$level+1);
        }
    }
    return $res;
}

/**
 * 生成随机字符串
 * $length 随机字符串长度
 * $type :
 *      0:数字字符混合
 *      1:纯字符
 *      2:纯数字
 */
function createRandomStr($length = 16, $type = 0) {
    $arr = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',1,2,3,4,5,6,7,8,9,0);
    $str  = '';
    switch($type) {
        case 0 : $s = 0;  $e = 61; break;
        case 1 : $s = 0;  $e = 51; break;
        case 2 : $s = 52; $e = 61; break;
    }
    for($i = 0; $i < $length; $i++) {
        $str .= $arr[rand($s, $e)];
    }
    return $str;
}
/**
 * 生成订单号
 */
function getOrderNum($tab = 'gl',$length = 8)
{

    $dt = date('YmdHis');

    $str = $dt . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, $length);

    return $tab.$str;

}
/**
 * 生产密码
 * @param $password
 * @return string
 */
function makePassword($password,$salt="") {

    return md5($password.$salt);
}

/**
 * 检测密码
 * @param $dbPassword
 * @param $inPassword
 * @return bool
 */
function checkPassword($inPassword, $dbPassword,$salt="") {
    return (makePassword($inPassword,$salt) == $dbPassword);
}


/**
 * json成功返回
 * logicCode:业务码
 */
function jsonSuccess($data=[],$msg="操作成功",$logicCode="20000"){
    return json(['data' => $data, 'msg' => $msg, 'logicCode' => $logicCode,'flag'=>'success']);
}

/**
 * json失败返回
 * logicCode:业务码
 */
function jsonFail($msg="操作失败",$logicCode="10000",$data=[]){
    return json([ 'msg' => $msg,'logicCode' => $logicCode, 'data' => $data,'flag'=>'fail']);
}

/**
 * 验证是否是有效手机号
 */
function isMobile($mobile=""){
    if(preg_match("/^1\d{10}$/", $mobile)){
        return true;
    }
    return false;
}
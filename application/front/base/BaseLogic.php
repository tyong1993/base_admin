<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/3/30 0030
 * Time: 15:51
 */

namespace app\front\base;


use app\common\exception\LogicException;
use app\front\logic\LogicCode;
use Firebase\JWT\JWT;
use think\Request;

class BaseLogic
{
    protected static $jwtKey="example_key";
    public static function checkLogin(Request $request,$filter=[]){
        $target=strtolower($request->action());
        //存在不需要登陆的方法
        if(!empty($filter)){
            if(in_array($target,$filter)){
                return true;
            }
        }
        //验证登陆,存在login_token则验证,不存在则验证session
        if(isset($_SERVER["HTTP_TOKEN"])){
            $login_token=$_SERVER["HTTP_TOKEN"];
            try{
                $res=JWT::decode($login_token, self::$jwtKey, array('HS256'));
            }catch (\Exception $e){
                throw new LogicException(LogicCode::$notLogin["msg"],LogicCode::$notLogin["code"]);
            }
            define("USER_ID",$res->login_user_id);
            define("LOGIN_TYPE",$res->login_type);
            return true;
        }else{
            if(empty(session("login_user_id"))){
                throw new LogicException(LogicCode::$notLogin["msg"],LogicCode::$notLogin["code"]);
            }
            define("USER_ID",session("login_user_id"));
            define("LOGIN_TYPE",session("login_type"));
            return true;
        }
    }


}
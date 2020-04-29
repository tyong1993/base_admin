<?php
namespace app\index\controller;

use app\common\tools\upload\SystemFile;
use app\front\base\BaseController;
use app\front\logic\SystemVerifyCodeLogic;
use app\front\logic\UserLogic;
use Firebase\JWT\JWT;
use think\Request;


class IndexController extends BaseController
{
    protected $notNeedLoginFun=["index","login","test"];
    public function index()
    {

        return "hello word";
    }
    public function login(){
        $key = "example_key";
        $payload = array(
            //签发者
            "iss" => "http://example.org",
            //接收者
            "aud" => "http://example.com",
            //签发时间
            "iat" => time(),

            //过期时间
            "exp" => time()+100000,
            //数据
            'data' => ["user_id"=>10086]
        );

        $jwt = JWT::encode($payload, $key);
        print_r($jwt);
//        $decoded = JWT::decode($jwt, $key, array('HS256'));
//        print_r($decoded);
    }
    public function center(){
        var_dump("登陆用户的id为:".LOGIN_USER_ID);


    }
    public function testLogin(){
        $token=UserLogic::loginByToken(3,["wxapp_openid"=>123]);
        return jsonSuccess(["login_token"=>$token]);
    }
    public function test(Request $request){
        if($request->isPost()){
            $file = request()->file();
            dump(SystemFile::upload($file));
//            dump(SystemFile::getFileUrl(8));
        }else{
            return $this->fetch();
        }

    }
}

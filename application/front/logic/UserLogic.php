<?php
namespace app\front\logic;

use app\common\exception\LogicException;
use app\common\tools\config\SystemConfig;
use app\common\tools\SmsTool;
use app\common\tools\upload\SystemFile;
use app\front\base\BaseLogic;
use app\front\model\UserModel;
use Firebase\JWT\JWT;
use think\Db;
use think\facade\Hook;

class UserLogic extends BaseLogic
{
    /**
     * session/cookie模式
     */
    static function loginBySession($type,$param){
        $userId=self::login($type,$param);
        session("login_user_id",$userId);
        session("login_type",$type);
    }

    /**
     * token模式
     */
    static function loginByToken($type,$param){
        $userId=self::login($type,$param);
        return self::getToken($userId,$type);
    }

    /**
     * 登陆,返回用户id
     * 调用者需要按照特定的使用场景传递参数
     *      wx_nickname,wx_avatar存在或者qq_nickname,qq_avatar存在会触发自动注册
     * $type:
     *      1:账号密码登陆
     *      2:手机验证码登陆
     *      3:小程序登录,未注册情况下携带wx_nickname和wx_avatar会自动注册
     *      4:微信三方登陆,未注册情况下携带wx_nickname和wx_avatar会自动注册
     *      5:QQ三方登陆,未注册情况下携带qq_nickname和qq_avatar会自动注册
     * $param:
     *      username:用户名
     *      password:密码
     *      mobile:手机号
     *      m_code:手机验证码
     *      wxapp_openid:小程序 openid
     *      wx_openid:微信 openid
     *      qq_openid:QQ openid
     *      wx_nickname:微信昵称
     *      wx_avatar:微信头像
     *      qq_nickname:QQ昵称
     *      qq_avatar:QQ头像
     */
    private static function login($type,$param){
        switch ($type){
            //账号密码登陆
            case 1:
                if(!isset($param["username"]) || !isset($param["password"])){
                    throw new LogicException("缺少参数错误");
                }
                $user=UserModel::where(["username"=>$param["username"]])->find();
                if(empty($user)){
                    throw new LogicException("账号或密码错误");
                }
                if(!checkPassword($param['password'],$user->password,$user->salt)){
                    throw new LogicException("账号或密码错误");
                }
                break;
            //手机验证码登录
            case 2:
                if(!isset($param["mobile"])){
                    throw new LogicException("缺少参数错误:mobile");
                }
                if(!isset($param["m_code"])){
                    throw new LogicException("缺少参数错误:m_code");
                }
                $user=UserModel::where(["mobile"=>$param["mobile"]])->find();
                if(empty($user)){
                    return self::register($type,$param);
                }
                //验证手机验证码
                SystemVerifyCodeLogic::check($param["mobile"],$param["m_code"]);
                break;
            //小程序登陆,未注册的情况下如果携带了用户信息则自动注册,否则返回未注册,携带上openid
            case 3:
                if(!isset($param["wxapp_openid"])){
                    throw new LogicException("缺少参数错误:wxapp_openid");
                }
                $user=UserModel::where(["wxapp_openid"=>$param["wxapp_openid"]])->find();
                break;
            //微信三方登陆,,未注册的情况下如果携带了用户信息则自动注册,否则返回未注册,携带上openid
            case 4:
                if(!isset($param["wx_openid"])){
                    throw new LogicException("缺少参数错误:wx_openid");
                }
                $user=UserModel::where(["wx_openid"=>$param["wx_openid"]])->find();
                break;
            //qq三方登陆,,未注册的情况下如果携带了用户信息则自动注册,否则返回未注册,携带上openid
            case 5:
                if(!isset($param["qq_openid"])){
                    throw new LogicException("缺少参数错误:qq_openid");
                }
                $user=UserModel::where(["qq_openid"=>$param["qq_openid"]])->find();
                break;
        }
        //type为3,4,5的情况下检测是否可以自动注册
        if(empty($user)){
            if(in_array($type,[3,4])){
                if(isset($param["wx_nickname"]) && isset($param["wx_avatar"])){
                    return self::register($type,$param);
                }
            }
            if($type==5){
                if(isset($param["qq_nickname"]) && isset($param["qq_avatar"])){
                    return self::register($type,$param);
                }
            }
            //返回openid让前端获取用户信息先
            throw new LogicException(LogicCode::$notRegist["msg"],LogicCode::$notRegist["code"],$param);
        }
        //检测用户状态
        if($user->status == 0){
            throw new LogicException("该账号已被禁止,如有疑问请联系平台人员");
        }
        //记录登陆信息
        UserModel::update([
            "id"=>$user->id,
            "last_login_time"=>request()->time(),
            "last_login_ip"=>request()->ip(),
            "login_times"=>$user->login_times+1,
        ]);
        return $user->id;
    }
    /**
     * 注册,返回用户id
     * 调用者需要按照特定的使用场景传递参数
     *      存在mobile参数会触发绑定的操作
     *      存在m_code参数会触发验证验证码操作
     * $type:
     *      1:账号密码注册
     *      2:手机注册
     *      3:小程序注册
     *      4:微信三方注册
     *      5:QQ三方注册
     * $param:
     *      username:用户名
     *      password:密码
     *      re_password:重复输入密码
     *      mobile:手机号
     *      m_code:手机验证码
     *      wxapp_openid:小程序 openid
     *      wx_openid:微信 openid
     *      qq_openid:QQ openid
     */
    public static function register($type,$param){
        //存在手机号则验证
        if(isset($param["mobile"])){
            if(!isMobile($param["mobile"])){
                throw new LogicException("请输入正确的手机号");
            }
            if(isset($param["m_code"])){
                //验证手机验证码
                SystemVerifyCodeLogic::check($param["mobile"],$param["m_code"]);
            }
            //查看手机是否注册过,是则执行绑定操作
            $user=UserModel::where(["mobile"=>$param["mobile"]])->find();
            if(!empty($user)){
                $param["id"]=$user->id;
                //各个场景下排除已注册过的情况
                if($type == 1 && !empty($user->username)){
                    throw new LogicException("该手机号已经存在用户");
                }
                if($type == 3 && !empty($user->wxapp_openid)){
                    throw new LogicException("该手机号已经存在用户");
                }
                if($type == 4 && !empty($user->wx_openid)){
                    throw new LogicException("该手机号已经存在用户");
                }
                if($type == 5 && !empty($user->qq_openid)){
                    throw new LogicException("该手机号已经存在用户");
                }
            }
            unset($param["m_code"]);
        }
        switch ($type){
            //账号密码注册
            case 1:
                if(!isset($param["username"]) || !isset($param["password"]) || !isset($param["re_password"])){
                    throw new LogicException("缺少参数错误");
                }
                if($param["password"] != $param["re_password"]){
                    throw new LogicException("两次密码输入不一致");
                }
                $user=UserModel::where(["username"=>$param["username"]])->find();
                if(!empty($user)){
                    throw new LogicException("该用户名已存在");
                }
                //密码加密存储
                $param["salt"]=createRandomStr();
                $param["password"]=makePassword($param["password"],$param["salt"]);
                unset($param["re_password"]);
            //手机注册,在第一次使用手机登陆的时候调用
            case 2:
                break;
            //小程序注册
            case 3:
                if(!isset($param["wxapp_openid"])){
                    throw new LogicException("缺少参数错误:wxapp_openid");
                }
                break;
            //微信三方注册
            case 4:
                if(!isset($param["wx_openid"])){
                    throw new LogicException("缺少参数错误:wx_openid");
                }
                break;
            //qq三方注册
            case 5:
                if(!isset($param["qq_openid"])){
                    throw new LogicException("缺少参数错误:qq_openid");
                }
                break;
        }
        //手机号存在则绑定,不存则新增
        if(isset($param["id"])){
            UserModel::update($param);
            return $param["id"];
        }else{
            Db::startTrans();
            $userId=(new UserModel())->insertGetId(self::initUserInfo($param));
            //注册其他模块的用户信息
            Hook::listen('user_register',$userId);
            Db::commit();
            return $userId;
        }
    }
    /**
     * 获取微信小程序openid
     */
    public static function getWXAppOpenid($code=null){
        if($code === null){
            $openid = UserModel::where("id","eq",USER_ID)->value("wxapp_openid");
            return $openid;
        }else{
            $APPID=config("logic.wxapp_appid");
            $SECRET=config("logic.wxapp_secret");
            $url="https://api.weixin.qq.com/sns/jscode2session?appid={$APPID}&secret={$SECRET}&js_code={$code}&grant_type=authorization_code";
            $res=file_get_contents($url);
            $data=json_decode($res);
            if(isset($data->openid)){
                return $data->openid;
            }else{
                throw new LogicException("服务器繁忙,请稍后再试");
            }
        }
    }
    /**
     * 获取微信三方登陆openid
     */
    public static function getWXOpenid($code){
        return $code;
    }
    /**
     * 获取QQ三方登陆openid
     */
    public static function getQQOpenid($code){
        return $code;
    }
    /**
     * 注册时初始化用户信息
     */
    public static function initUserInfo($param){
        //系统昵称
        if(!isset($param["nickname"])){
            $param["nickname"]="jy_".createRandomStr(6,2);
        }

        $param["register_time"]=time();
        $param["register_ip"]=request()->ip();
        return $param;
    }
    /**
     * 用户个人信息
     */
    public static function userInfo(){
        $user=UserModel::getUserInfo(USER_ID);
        //获取其他模块的用户信息
        Hook::listen('user_info',$user);
        //处理用户头像
        if(!in_array(LOGIN_TYPE,[3,4,5])){
            //如果没有头像使用系统默认头像
            if(empty($user->avatar)){
                $user->avatar=SystemConfig::getConfig("default_head");
            }else{
                $user->avatar=SystemFile::getFileUrl($user->avatar);
            }
        }
        return $user;
    }

    /**
     * 生成用户登陆token
     */
    private static function getToken($userId,$loginType){
        $payload = array(
            //签发时间
            "iat" => time(),
            //过期时间
            "exp" => time()+3600*24*7,
            //用户id
            'login_user_id' => $userId,
            //登陆类型
            'login_type' => $loginType
        );
        $token = JWT::encode($payload, self::$jwtKey);
        return $token;
    }


}

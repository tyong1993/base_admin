<?php
namespace app\front\model;

use app\front\base\BaseModel;

class UserModel extends BaseModel {
    /**
     * 获取用户信息
     */
    public static function getUserInfo($userId){
        $login_user_info=in_array(LOGIN_TYPE,[3,4])?"wx_nickname as nickname,wx_avatar as avatar":(LOGIN_TYPE==5?"qq_nickname as nickname,qq_avatar as avatar":"nickname,avatar");
        return self::field("id,username,$login_user_info,email,mobile,gender,birthday,balance,score")->find($userId);
    }
}
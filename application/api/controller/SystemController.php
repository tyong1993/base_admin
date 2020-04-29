<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/6 0006
 * Time: 14:13
 */

namespace app\api\controller;
use app\common\tools\upload\SystemFile;
use app\front\base\BaseController;
use app\front\logic\SystemArticleLogic;
use app\front\logic\SystemGuestbookLogic;
use app\front\logic\SystemVerifyCodeLogic;
use think\Request;

/**
 * 系统杂项控制器
 */
class SystemController extends BaseController
{
    protected $isNeedLogin = false;
    /**
     * 获取手机验证码
     * @SWG\Get(path="/system/getVerifyCode",
     * tags={"系统杂项"},
     * summary="获取手机验证码",
     * produces={"application/json"},
     * description="这里是接口说明信息",
     * @SWG\Parameter(in="query",name="mobile",type="string",description="手机号",required=true),
     * @SWG\Response(response="200", description="请求成功")
     * )
     */
    function getVerifyCode(){
        $mobile=$this->request->param("mobile");
        SystemVerifyCodeLogic::send($mobile);
        return jsonSuccess("验证码发送成功");
    }
    /**
     * 验证手机验证码
     * @SWG\Post(path="/system/checkVerifyCode",
     * tags={"系统杂项"},
     * summary="验证手机验证码",
     * produces={"application/json"},
     * description="这里是接口说明信息",
     * @SWG\Parameter(in="formData",name="mobile",type="string",description="手机号",required=true),
     * @SWG\Parameter(in="formData",name="m_code",type="string",description="手机验证码",required=true),
     * @SWG\Response(response="200", description="请求成功")
     * )
     */
    function checkVerifyCode(){
        $mobile=$this->request->param("mobile");
        $mCode=$this->request->param("m_code");
        SystemVerifyCodeLogic::check($mobile,$mCode);
        return jsonSuccess();
    }

    /**
     * 添加系统留言
     * @SWG\Post(path="/system/addGuestbook",
     * tags={"系统杂项"},
     * summary="留言",
     * produces={"application/json"},
     * description="这里是接口说明信息",
     * @SWG\Parameter(in="formData",name="type",type="string",description="留言类型
     * 已知类型:
     *     ----1:用户需求
     *     ----2:投诉建议
     * ",
     * required=true),
     * @SWG\Parameter(in="formData",name="title",type="string",description="留言标题"),
     * @SWG\Parameter(in="formData",name="content",type="string",description="留言内容",required=true),
     * @SWG\Parameter(in="formData",name="mobile",type="string",description="手机号"),
     * @SWG\Parameter(in="formData",name="email",type="string",description="邮箱"),
     * @SWG\Response(response="200", description="请求成功")
     * )
     */
    function addGuestbook(){
        $param =  request()->post('', null, 'trim');
        if(!isset($param["mobile"]) && $param["type"] ==1){
            return jsonFail("请填写手机号");
        }
        SystemGuestbookLogic::addGuestbook($param);
        return jsonSuccess();
    }
    /**
     * 文件上传
     * @SWG\Post(path="/system/upload",
     * tags={"系统杂项"},
     * summary="文件上传",
     * produces={"application/json"},
     * description="表单的name值可以自定义,接口会按照同样的key_name返回数据",
     * @SWG\Parameter(in="formData",name="key_name1",type="file",description="文件的二进制数据"),
     * @SWG\Parameter(in="formData",name="key_name2",type="file",description="文件的二进制数据"),
     * @SWG\Response(response="200", description="请求成功")
     * )
     */
    public function upload(Request $request){
        $files=$request->file();
        $res=SystemFile::upload($files,["ext"=>"jpg,png,gif,jpeg"]);
        return jsonSuccess($res);
    }
    /**
     * 经纬度换位置信息
     * @SWG\Get(path="/system/latLng2Address",
     * tags={"系统杂项"},
     * summary="经纬度换位置信息",
     * produces={"application/json"},
     * description="",
     * @SWG\Parameter(in="query",name="lat",type="string",description="维度",required=true),
     * @SWG\Parameter(in="query",name="lng",type="string",description="经度",required=true),
     * @SWG\Response(response="200", description="请求成功")
     * )
     */
    public function latLng2Address(){
        //维度
        $lat=$this->request->param("lat");
        //经度
        $lng=$this->request->param("lng");
        $url="https://apis.map.qq.com/ws/geocoder/v1/?location=$lat,$lng&key=EIVBZ-KVJWP-Q4QDP-LTSEK-MB5DK-HLFJX&get_poi=0";
        $res=file_get_contents($url);
        $res=json_decode($res,true);
        if(!empty($res["result"])){
            return jsonSuccess($res["result"]["address_component"]);
        }
        return jsonFail("获取位置信息失败");
    }

    /**
     * 房贷计算等额本息
     */
    function debx()
    {
        $dkm     = 240; //贷款月数，20年就是240个月
        $dkTotal = 10000; //贷款总额
        $dknl    = 0.0515;  //贷款年利率
        $emTotal = $dkTotal * $dknl / 12 * pow(1 + $dknl / 12, $dkm) / (pow(1 + $dknl / 12, $dkm) - 1); //每月还款金额
        $lxTotal = 0; //总利息
        for ($i = 0; $i < $dkm; $i++) {
            $lx      = $dkTotal * $dknl / 12;   //每月还款利息
            $em      = $emTotal - $lx;  //每月还款本金
            echo "第" . ($i + 1) . "期", " 本金:", $em, " 利息:" . $lx, " 总额:" . $emTotal, "<br />";
            $dkTotal = $dkTotal - $em;
            $lxTotal = $lxTotal + $lx;
        }
        echo "总利息:" . $lxTotal;
    }

    /**
     * 房贷计算等额本金
     */
    function debj()
    {
        $dkm     = 240; //贷款月数，20年就是240个月
        $dkTotal = 10000; //贷款总额
        $dknl    = 0.0515;  //贷款年利率

        $em      = $dkTotal / $dkm; //每个月还款本金
        $lxTotal = 0; //总利息
        for ($i = 0; $i < $dkm; $i++) {
            $lx      = $dkTotal * $dknl / 12; //每月还款利息
            echo "第" . ($i + 1) . "期", " 本金:", $em, " 利息:" . $lx, " 总额:" . ($em + $lx), "<br />";
            $dkTotal -= $em;
            $lxTotal = $lxTotal + $lx;
        }
        echo "总利息:" . $lxTotal;
    }

}
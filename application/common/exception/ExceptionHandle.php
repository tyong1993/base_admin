<?php
namespace app\common\exception;

use app\front\logic\LogicCode;
use Exception;
use think\Container;
use think\exception\Handle;
use think\exception\PDOException;
use think\exception\ValidateException;
use traits\controller\Jump;
use think\Response;

class ExceptionHandle extends Handle
{
    use Jump;

    /**
     * @param Exception $e
     * @return ExceptionHandle|Response|\think\response\Json
     * 异常接管中心
     * 业务异常公共处理，需要特殊处理的可自行捕获处理
     */
    public function render(Exception $e)
    {
        // 记录系统异常日志
        //todo

        // 逻辑层异常处理
        if ($e instanceof LogicException) {
            return $this->LogicExceptionHandler($e);
        }
        // 验证器异常处理
        if ($e instanceof ValidateException) {
            return $this->LogicExceptionHandler($e);
        }
        // 模型层异常统一处理,调试模式下不处理
        if ($e instanceof PDOException) {
            if(!config("app_debug")){
                if($e->getCode()==10501){
                    return $this->LogicExceptionHandler(new LogicException("当前操作不可行,违反数据完整性,一致性原则"));
                }
                return $this->LogicExceptionHandler(new LogicException("数据库异常，当前操作不可行"));
            }
        }
        // 其他错误交给系统处理
        return parent::render($e);
    }

    /**
     * @param Exception $e
     * @return $this|\think\response\Json
     * 业务异常公共处理器
     */
    public function LogicExceptionHandler(Exception $e){
        //ajax返回
        if(request()->isAjax()){
            return jsonFail($e->getMessage(),$e->getCode(),method_exists($e,"getData")?$e->getData():[]);
        }
        //接口返回
        if(request()->module() == "api"){
            return jsonFail($e->getMessage(),$e->getCode(),method_exists($e,"getData")?$e->getData():[]);
        }
        //页面返回,默认返回上一页,可根据具体业务码控制跳转页面
        $result = [
            'code' => 0,
            'msg'  => $e->getMessage(),
            'url'  => 'javascript:history.back(-1);',
            'wait' => 3,
        ];
        //未登录
        if($e->getCode() == LogicCode::$notLogin["code"]){
//            $result["url"] = "index/user/login";
        }
        //未注册
        if($e->getCode() == LogicCode::$notRegist){

        }
        $response = Response::create($result, 'jump')->header([])->options(['jump_template' =>Container::get("app")['config']->get('dispatch_error_tmpl')]);
        return $response;
    }

}
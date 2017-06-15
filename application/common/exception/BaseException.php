<?php
/**
 *  WORKBGM开发框架
 * User: 吴渭明
 * Date: 2017/6/5
 * Time: 下午3:44
 * For:
 */


namespace app\common\exception;


use think\Exception;
use Throwable;

class BaseException extends Exception
{
    // HTTP状态码 404,200
    public $code =400;

    //错误具体信息
    public $msg = '参数错误';

    // 自定义的错误码
    public $errorCode = 10000;

    public function __construct($params = [])
    {
        if(!is_array($params)){
            return;
        }
        if(array_key_exists('code',$params)){
            $this->code=$params['code'];
        }
        if(array_key_exists('msg',$params)){
            $this->msg=$params['msg'];
        }
        if(array_key_exists('errorCode',$params)){
            $this->errorCode=$params['errorCode'];
        }
    }
}
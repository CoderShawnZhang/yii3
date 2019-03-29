<?php
/**
 * Created by PhpStorm.
 * User: zhanghongbo
 * Date: 2019/3/25
 * Time: 上午10:55
 */

namespace yii\web;


use Throwable;
use yii\base\UserException;

class HttpException extends UserException
{
    public $statusCode;

    public function __construct($status,$message = "", $code = 0, Throwable $previous = null)
    {
        $this->statusCode = $status;
        parent::__construct($message, $code, $previous);
    }

    public function getName()
    {
        if(isset(Response::$httpStatuses[$this->statusCode])){
            return Response::$httpStatuses[$this->statusCode];
        } else {
            return 'Error';
        }
    }
}
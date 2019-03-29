<?php
/**
 * Created by PhpStorm.
 * User: zhanghongbo
 * Date: 2019/3/25
 * Time: 上午10:54
 */

namespace yii\web;


use Throwable;

class NotFoundHttpException extends HttpException
{
    public function __construct($status, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct(404, $message, $code, $previous);
    }
}
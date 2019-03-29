<?php
/**
 * Created by PhpStorm.
 * User: zhanghongbo
 * Date: 2019/3/25
 * Time: 上午11:51
 */

namespace yii\di;


use Throwable;
use yii\base\InvalidConfigException;

class NotInstantiableException extends InvalidConfigException
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        if($message === null){
            $message = "Can not instantiate $class.";
        }
        parent::__construct($message, $code, $previous);
    }

    public function getName()
    {
        return 'Not instantiable';
    }
}
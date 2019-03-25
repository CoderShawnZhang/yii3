<?php
/**
 * yii3异常处理--基类
 */

namespace yii\base;


class Exception extends \Exception
{
    public function getName()
    {
        return 'Exception';
    }
}

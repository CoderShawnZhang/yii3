<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/3/23
 * Time: 下午4:51
 */

namespace yii\base;


class InvalidCallException extends \BadMethodCallException
{
    public function getName()
    {
        return '无效的调用';
    }
}

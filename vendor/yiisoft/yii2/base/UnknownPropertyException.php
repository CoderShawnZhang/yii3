<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/3/23
 * Time: 下午4:52
 */

namespace yii\base;


class UnknownPropertyException extends Exception
{
    public function getName()
    {
        return '未知的属性';
    }
}

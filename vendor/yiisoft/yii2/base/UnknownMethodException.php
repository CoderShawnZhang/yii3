<?php
/**
 * Created by PhpStorm.
 * User: zhanghongbo
 * Date: 2019/3/28
 * Time: 下午5:21
 */

namespace yii\base;


class UnknownMethodException extends \BadMethodCallException
{
    public function getName()
    {
        return 'Unknown Method';
    }
}
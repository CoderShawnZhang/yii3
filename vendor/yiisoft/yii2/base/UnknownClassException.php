<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/3/23
 * Time: 下午3:07
 */

namespace yii\base;


class UnknownClassException extends Exception
{
    public function getName()
    {
        return 'Unknown Class';
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/3/23
 * Time: 下午3:36
 */

namespace yii\base;


class InvalidConfigException extends Exception
{
    public function getName()
    {
        return 'Invalid Configuration';
    }
}

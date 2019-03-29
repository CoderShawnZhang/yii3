<?php
/**
 * Created by PhpStorm.
 * User: zhanghongbo
 * Date: 2019/3/25
 * Time: 上午9:51
 */

namespace yii\base;


class InvalidRouteException extends UserException
{
    public function getName()
    {
        return 'Invalid Route';
    }
}
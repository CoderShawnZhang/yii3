<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/3/23
 * Time: 下午3:17
 */

namespace yii\base;


use yii\di\ServiceLocator;

class Module extends ServiceLocator
{
    public function get($id,$throwException = true)
    {
        parent::get($id,$throwException);
    }
}

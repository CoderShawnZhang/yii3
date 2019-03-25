<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/3/23
 * Time: 下午5:49
 */

namespace yii\di;

//容器顾名思义是用来装东西的，DI容器里面的东西是什么呢？
//Yii使用 yii\di\Instance 来表示容器中的东西
//当然Yii中还将这个类用于Service Locator
class Instance
{
    public $id;

    protected function __construct($id)
    {
        $this->id = $id;
    }

    public static function of($id)
    {
        return new static($id);
    }
}

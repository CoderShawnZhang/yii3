<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/3/23
 * Time: 下午3:20
 */

namespace yii\base;


class Component extends BaseObject
{
    public function __construct($config = [])
    {
        if(!empty($config)){
            \Yii::configure($this,$config);
        }
    }

    public function __get($name)
    {
        // TODO: Implement __get() method.
    }


}

<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/3/23
 * Time: 下午2:28
 */

namespace yii\web;

class Application extends \yii\base\Application
{
    public function handleRequest($request)
    {
        var_dump(333333);die;
    }

    //核心组件
    public function coreComponents()
    {
        return array_merge(parent::coreComponents(),[
            'request' => ['class' => 'yii\web\Request']
        ]);
    }
}

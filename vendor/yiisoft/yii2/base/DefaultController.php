<?php
namespace yii\base;

class DefaultController extends \yii\base\Controller
{
    public function actionIndex()
    {
        return '控制器被执行1';
    }
}
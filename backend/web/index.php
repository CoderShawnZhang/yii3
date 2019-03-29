<?php
defined('YII_DEBUG') or define('YII_DEBUG',true);
defined('BACKEND') or define('BACKEND', dirname(dirname(__DIR__)));


require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/../Config/bootstrap.php';

$config = array_merge(
    require __DIR__ . '/../Config/main.php',
    require __DIR__ . '/../Config/main-local.php'
);
(new \yii\web\Application($config))->run();
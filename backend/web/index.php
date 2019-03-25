<?php
echo 22222;
defined('YII_DEBUG') or define('YII_DEBUG',true);

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';

$config = [];
(new \yii\web\Application())->run($config);


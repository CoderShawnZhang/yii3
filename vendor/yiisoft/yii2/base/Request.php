<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/3/23
 * Time: 下午5:59
 */

namespace yii\base;


abstract class Request extends Component
{
    private $_scriptFile;
    private $_isConsoleRequest;

    abstract public function resolve();
}

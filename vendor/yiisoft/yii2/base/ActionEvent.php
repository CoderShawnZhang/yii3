<?php
/**
 * Created by PhpStorm.
 * User: zhanghongbo
 * Date: 2019/3/28
 * Time: 下午4:31
 */

namespace yii\base;


class ActionEvent extends Event
{
    public $action;

    public $result;

    public $isValid = true;

    public function __construct($action,array $config = [])
    {
        $this->action = $action;
        parent::__construct($config);
    }
}
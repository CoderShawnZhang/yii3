<?php
/**
 * Created by PhpStorm.
 * User: zhanghongbo
 * Date: 2019/3/28
 * Time: 下午4:15
 */

namespace yii\base;


class InlineAction extends Action
{
    public $actionMethod;

    public function __construct($id, $controller,$actionMethod, array $config = [])
    {
        $this->actionMethod = $actionMethod;
        parent::__construct($id, $controller, $config);
    }

    public function runWithParams($params)
    {
        $args = $this->controller->bindActionParams($this,$params);
        if(\Yii::$app->requestedParams === null){
            \Yii::$app->requestedParams = $args;
        }
        return call_user_func_array([$this->controller,$this->actionMethod],$args);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: zhanghongbo
 * Date: 2019/3/25
 * Time: 下午8:13
 */

namespace yii\web;


use yii\base\InlineAction;

class Controller extends \yii\base\Controller
{

    public $actionParams = [];

    public function bindActionParams($action,$params)
    {
        if($action instanceof InlineAction){
            $method = new \ReflectionMethod($this,$action->actionMethod);
        } else {
            $method = new \ReflectionMethod($action,'run');
        }

        $args = [];
        $missing = [];
        $actionParams = [];
        foreach($method->getParameters() as $param){
            $name = $param->getName();
            if(array_key_exists($name,$params)){
                if($param->isArray()){
                    $args[] = $actionParams[$name] = (array) $params[$name];
                } elseif (!is_array($params[$name])) {
                    $args[] = $actionParams[$name] = $params[$name];
                } else {
                    //throw new BadRequestHttpException();
                }
                unset($params[$name]);
            } elseif ($param->isDefaultValueAvailable()) {
                $args[] = $actionParams[$name] = $param->getDefaultValue();
            } else {
                $missing[] = $name;
            }
        }
        if(!empty($missing)){

        }

        $this->actionParams = $actionParams;
        return $args;
    }
}
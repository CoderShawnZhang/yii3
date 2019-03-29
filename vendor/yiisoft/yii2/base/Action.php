<?php
/**
 * Created by PhpStorm.
 * User: zhanghongbo
 * Date: 2019/3/28
 * Time: 下午3:11
 */

namespace yii\base;


class Action extends Component
{
    public $id;

    public $controller;

    public function __construct($id,$controller,array $config = [])
    {
        $this->id = $id;
        $this->controller = $controller;
        parent::__construct($config);
    }

    public function getUniqueId()
    {
        return $this->controller->getUniqueId() . '/' . $this->id;
    }

    public function runWithParams($params)
    {
        if(!method_exists($this,'run')){
            throw new InvalidConfigException(get_class($this) . ' must define a "run()" method.');
        }

        $args = $this->controller->bindActionParams($this,$params);
        if(\Yii::$app->requestedParams === null){
            \Yii::$app->requestedPatams = $args;
        }
        if($this->beforeRun()){
            $result = call_user_func_array($this,'run',$args);
            $this->afterRun();
            return $result;
        } else {
            return null;
        }
    }

    public function beforeRun()
    {
        return true;
    }

    public function afterRun()
    {

    }
}
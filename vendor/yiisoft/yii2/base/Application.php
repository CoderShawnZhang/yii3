<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/3/23
 * Time: 下午2:34
 */
namespace yii\base;

use Yii;

abstract class Application extends Module
{
    abstract public function handleRequest($request);

    public $loadedModules = [];

    public $controllerNamespace = 'app\\controllers';

    public $charset = 'UTF-8';

    public $requestedParams;//存放 请求action的参数

    public $requestedAction;

    public $requestedRoute;

    public function __construct($config = [])
    {
        Yii::$app = $this;

        $this->preInit($config);
        parent::__construct($config);
    }

    public function run()
    {

        $response = $this->handleRequest($this->getRequest());
        $response->send();
    }

    public function getRequest()
    {
        return $this->get('request');
    }

    public function getResponse()
    {
        return $this->get('response');
    }

    public function getUrlManager()
    {
        return $this->get('urlManager');
    }


    /**
     * 初始化准备将自定义配置的config组件与yii内部核心定义的组件写入容器
     * @param $config
     */
    public function preInit(&$config)
    {
        /**
         * $config['components'] 这个是必须要写死这个，因为在后面初始化组键 服务提供器setComponents定死了调用这个方法。
         */
        foreach($this->coreComponents() as $id => $component){
            if(!isset($config['components'][$id])){
                $config['components'][$id] = $component;
            } elseif (is_array($config['components'][$id]) && !isset($config['components'][$id]['class'])){
                $config['components'][$id]['class'] = $component['class'];
            }
        }
    }
    //基类核心组件
    public function coreComponents()
    {
        return [
            'urlManager' => ['class' => 'yii\web\UrlManager'],
        ];
    }
}

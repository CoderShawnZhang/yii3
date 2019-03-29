<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/3/23
 * Time: 下午3:17
 */

namespace yii\base;


use yii\di\ServiceLocator;

class Module extends ServiceLocator
{
    const EVENT_BEFORE_ACTION = 'beforeAction';

    const EVENT_AFTER_ACTION = 'beforeAction';

    public $defaultRoute = 'default';

    public $controllerMap = [];//控制器对象缓存映射

    public $controllerNamespace;

    public $module;

    public $id;

    private $_modules = [];

    public function runAction($route,$params = [])
    {
        $parts = $this->createController($route);
        if(is_array($parts)){
            list($controller,$actionID) = $parts;
            $oldController = \Yii::$app->controller;
            \Yii::$app->controller = $controller;
            $result = $controller->runAction($actionID,$params);
            \Yii::$app->controller = $oldController;
            return $result;
        } else {
            $id = $this->getUniqueId();
            throw new InvalidRouteException('Unable to resolve the request "' . ($id === '' ? $route : $id . '/' . $route) . '".');
        }
    }

    public function getUniqueId()
    {
        return $this->module ? ltrim($this->module->getUniqueId() . '/' . $this->id, '/') : $this->id;
    }

    //根据路由地址创建控制器对象
    public function createController($route)
    {
        if($route === ''){
            $route = $this->defaultRoute;
        }

        $route = trim($route,'/');
        if(strpos($route,'//') !== false){
            return false;
        }

        if(strpos($route,'/') !== false){
            list($id,$route) = explode('/',$route,2);
        } else {
            $id = $route;
            $route = '';
        }

        //检查控制器映射表是否已经有了
        //$this->controllerMap[$id] 存的是？
        if(isset($this->controllerMap[$id])){
            $controller = \Yii::createObject($this->controllerMap[$id],[$id,$this]);
            return [$controller,$route];
        }

        $module = $this->getModule($id);
        if($module !== null){
            return $module->createController($route);
        }

        if(($pos = strrpos($route,'/')) !== false){
            $id .= '/' . substr($route,0, $pos);
            $route = substr($route,$pos + 1);
        }

        $controller = $this->createControllerByID($id);
        if($controller === null && $route !== ''){
            $controller = $this->createControllerByID($id . '/' . $route);
            $route = '';
        }
        return $controller === null ? false : [$controller,$route];
    }

    public function createControllerByID($id)
    {
        $pos = strrpos($id,'/');
        if($pos === false){
            $prefix = '';
            $className = $id;
        } else {
            $prefix = substr($id,0,$pos + 1);
            $className = substr($id,$pos + 1);
        }

        if(!preg_match('%^[a-z][a-z0-9\\-_]*$%',$className)){
            return null;
        }
        if($prefix !== '' && !preg_match('%^[a-z0-9_/]+$%i',$prefix)){
            return null;
        }
        $className = str_replace(' ','',ucwords(str_replace('-',' ',$className))) . 'Controller';
        $className = ltrim($this->controllerNamespace . '\\' . str_replace('/', '\\',$prefix) . $className,'\\');


        if(strpos($className,'-') !== false || !class_exists($className)){
            return null;
        }

        if(is_subclass_of($className,'yii\base\Controller')){
            $controller = \Yii::createObject($className,[$id,$this]);
            return get_class($controller) === $className ? $controller : null;
        } elseif(YII_DEBUG){
            throw new InvalidConfigException("Controller class must extend from \\yii\\base\\Controller.");
        } else {
            return null;
        }
    }

    public function getModule($id, $load = true)
    {
        if($pos = strpos($id,'/') !== false){
            $module = $this->getModule(substr($id,0,$pos));
            return $module === null ? null : $module->getModule(substr($id,$pos + 1),$load);
        }

        if(isset($this->_modules[$id])){
            if($this->_modules[$id] instanceof  Module){
                return $this->_modules[$id];
            } elseif ($load) {
                $module = \Yii::createObject($this->_modules[$id],[$id,$this]);
                $module->setInstance($module);
                return $this->_modules[$id] = $module;
            }
        }
        return null;
    }

    public static function setInstance($instance)
    {
        if($instance == null){
            unset(Yii::$app->loadedModules[get_class($instance)]);
        } else {
            \Yii::$app->loadModules[get_class($instance)] = $instance;
        }
    }

    public function beforeAction($action)
    {
        $event = new ActionEvent($action);
        $this->trigger(self::EVENT_BEFORE_ACTION,$event);
        return $event->isValid;
    }

    public function afterAction($action,$result)
    {
        $event = new ActionEvent($action);
        $event->result = $result;
        $this->trigger(self::EVENT_AFTER_ACTION,$event);
        return $event->result;
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/3/23
 * Time: 下午3:54
 */

namespace yii\di;

//这个就是最最🐂的容器了

use yii\base\Component;
use yii\base\InvalidConfigException;

class Container extends Component
{
    private $_singletons = [];
    private $_definitions = [];
    private $_reflections = []; //映射数组，反射数组

    public function get($class, $params = [], $config = [])
    {
        if(isset($this->_singletons[$class])){
            return $this->_singletons[$class];
        } elseif(!isset($this->_definitions[$class])){
            return $this->build($class,$params,$config);
        }
    }

    public function build($class,$params,$config)
    {
        list($reflection,$dependencies) = $this->getDependencies($class);
    }

    //获取依赖
    protected function getDependencies($class)
    {
        if(isset($this->_reflections[$class])){
            return [$this->_reflections[$class],$this->_definitions[$class]];
        }

        $dependencies = [];
        try{
            $reflection = new \ReflectionClass($class); //反射这个类
        } catch (\ReflectionException $e) {
            throw new InvalidConfigException("Failed to instantiate component or class $class",0,$e);
        }

        $constructor = $reflection->getConstructor();
        if($constructor !== null){
            foreach($constructor->getParameters() as $param){
                if(version_compare(PHP_VERSION,'5.6.0','>=') && $param->isVariadic()){ //isVariadic判断是否是可变参数
                    break;
                } elseif($param->isDefaultValueAvailable()){ //判断是否有默认值
                    $dependencies[] = $param->getDefaultValue();
                } else {
                    $c = $param->getClass();
                    $dependencies[] = Instance::of($c === null ? null : $c->getName());
                }
            }
        }

        $this->_reflections[$class] = $reflection;
        $this->_definitions[$class] = $dependencies;

        return [$reflection,$dependencies];
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/3/23
 * Time: 下午3:20
 */
namespace yii\di;

use yii\base\Component;
use Closure;
use yii\base\InvalidConfigException;

class ServiceLocator extends Component
{
    private $_components = [];//服务提供者--组件的对象

    private $_definitions = [];//服务提供者--组件的定义

    /**
     * 根据组键ID获取组键对象
     *
     * @param $id
     * @param bool $throwException
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function get($id,$throwException = true)
    {
        //是否已经有这个组件对象了
        if(isset($this->_components[$id])){
            return $this->_components[$id];
        }
        //是有已经有这个组件的定义了
        if(isset($this->_definitions[$id])){
            $definition = $this->_definitions[$id];
            //是对象，但是不是闭包
            if(is_object($definition) && !$definition instanceof Closure){
                //如果是组件的定义直接就是个对象则放入组件对象数组同时返回。
                return $this->_components[$id] = $definition;
            }
            //如果组件对象数组没有，组件定义数组也没有，那么创建一个吧
            return $this->_components[$id] = \Yii::createObject($definition);
        } elseif ($throwException){
            throw new InvalidConfigException("Unknown component ID: $id");
        }
        return null;
    }

    /**
     * @param $id
     * @param $definition
     * @throws InvalidConfigException
     */
    public function set($id,$definition)
    {
        //删除掉组件对象，为了是重新定义
        unset($this->_components[$id]);
        //如果定义的内容是null,（表示删除组件和组件定义）
        if($definition === null){
            unset($this->_definitions[$id]);
            return;
        }
        if(is_object($definition) || is_callable($definition,true)){
            $this->_definitions[$id] = $definition;
        } elseif(is_array($definition)){
            if(isset($definition['class'])){
                $this->_definitions[$id] = $definition;
            } else {
                throw new InvalidConfigException("The configuration for the \"$id\" component must contain a \"class\" element.");
            }
        } else{
            throw new InvalidConfigException("Unexpected configuration type for the \"$id\" component must contain a \"class\"element.");
        }
    }

    /**
     * 这个方法主要是将组件的定义写入到，组件定义的数组里。
     *
     * @param $components
     * @throws InvalidConfigException
     */
    public function setComponents($components)
    {
        foreach($components as $id => $component){
            $this->set($id,$component);
        }
    }
}

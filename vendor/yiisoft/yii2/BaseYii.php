<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/3/23
 * Time: 下午2:42
 */
namespace yii;

use yii\base\InvalidConfigException;
use yii\base\UnknownClassException;

defined('YII2_PATH') or define('YII2_PATH', __DIR__);

class BaseYii
{
    public static $classMap = [];

    public static $container;//Yii基类定义容器

    /**
     * Yii类自动加载
     *
     * @param $className
     * @throws UnknownClassException
     */
    public static function autoload($className)
    {
       if(isset(static::$classMap[$className])){
           $classFile = static::$classMap[$className];
           if($classFile[0] === '@'){
                $classFile = static::getAlias($classFile);
           }
       } elseif (strpos($className,'\\') !== false){//如果加载的类路径存在双斜线，转换成单斜线后通过别名获取。
           $classFile = static::getAlias('@'. str_replace('\\','/',$className) . '.php',false);
           if($classFile === false || !is_file($classFile)){
                return;
           }
       } else {
            return;
       }
       include $classFile;//载入文件。关键的一步，实现了自动载入（其实就是去设置好的映射表里去找然后require进来）

        if(YII_DEBUG && !class_exists($className,false) && !interface_exists($className,false) && !trait_exists($className,false)){
            throw new UnknownClassException("Unable to find '$className' in file: $classFile . Namespace missing?");
        }
    }

    //获取别名
    public static function getAlias($alias,$throwException = true){

    }

    /**
     * 创建一个组件对象
     * @param $type
     * @param array $params
     * @return mixed
     * @throws InvalidConfigException
     */
    public static function createObject($type, array $params = [])
    {
        if(is_string($type)){
            //这里的static::$container 是获取的 vendor/yiisoft/yii2/Yii.php
            return static::$container->get($type,$params);
        } elseif(is_array($type) && isset($type['class'])){
            $class = $type['class'];
            unset($type['class']);
            return static::$container->get($class,$params,$type);
        } elseif(is_callable($type,true)){
            return static::$container->invoke($type,$params);
        } elseif(is_array($type)){
            throw new InvalidConfigException('Object configuration must be an array containing a "class" element.');
        }
        throw new InvalidConfigException('Unsupported configuration type:' . gettype($type));
    }

    public static function configure($object,$properties)
    {
        //执行(这个$this是最顶级的父类)$this->components是因为没ßßßßß有定义 components
        // 则会调用BaseObject.php 定义的 __set魔术方法
        foreach ($properties as $name => $value) {
            $object->$name = $value;
        }
        return $object;
    }
}

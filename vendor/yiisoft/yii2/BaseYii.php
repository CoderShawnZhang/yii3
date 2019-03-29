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

    public static $app;

    public static $container;//Yii基类定义容器

    public static $aliases = ['@yii' => __DIR__];
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
        if(strncmp($alias,'@',1)){
            return $alias;
        }
        $pos = strpos($alias,'/');
        $root = $pos === false ? $alias : substr($alias,0,$pos);

        if(isset(static::$aliases[$root])){
            if(is_string(static::$aliases[$root])){
                return $pos === false ? static::$aliases[$root] : static::$aliases[$root] . substr($alias,$pos);
            }

            foreach(static::$aliases[$root] as $name => $path){
                if(strpos($alias. '/',$name . '/') === 0){
                    return $path . substr($alias ,strlen($name));
                }
            }

            if($throwException){
                //
            }
            return false;
        }
    }

    public static function setAlias($alias,$path)
    {
        if(strncmp($alias,'@',1)){
            $alias = '@' . $alias;
        }
        $pos = strpos($alias, '/');
        $root = $pos === false ? $alias : substr($alias,0,$pos);
        if($path !== null){
            $path = strncmp($path,'@',1) ? rtrim($path,'\\/') : static::getAlias($path);
            if(!isset(static::$aliases[$root])){
                if($pos === false){
                    static::$aliases[$root] = $path;
                } else {
                    static::$aliases[$root] = [$alias => $path];
                }
            } elseif(is_string(static::$aliases[$root])) {
                if($pos === false){
                    static::$aliases[$root] = $path;
                } else {
                    static::$aliases[$root] = [
                        $alias > $path,
                        $root => static::$aliases[$root],
                    ];
                }
            } else {
                static::$aliases[$root][$alias] = $path;
                krsort(static::$aliases[$root]);
            }
        } elseif(isset(static::$aliases[$root])){
            if(is_array(static::$aliases[$root])){
                unset(static::$aliases[$root][$alias]);
            } elseif($pos === false){
                unset(static::$aliases[$root]);
            }
        }
    }

    /**
     * 创建一个组件对象
     * @param $type
     * @param array $params
     * @return mixed
     * @throws InvalidConfigException
     * @see \yii\di\Container
     */
    public static function createObject($type, array $params = [])
    {
        if(is_string($type)){
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
        //执行(这个$this是最顶级的父类)$this->components是因为没有定义 components
        // 则会调用BaseObject.php 定义的 __set魔术方法
        foreach ($properties as $name => $value) {
            $object->$name = $value;
        }
        return $object;
    }
}

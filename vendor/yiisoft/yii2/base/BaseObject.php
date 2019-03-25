<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/3/23
 * Time: 下午3:20
 */

namespace yii\base;

class BaseObject implements Configurable
{
    public static function className()
    {
        return get_called_class();
    }

    public function __set($name, $value)
    {
        $setter = 'set' . $name;
        if(method_exists($this,$setter)){
            $this->$setter($value);
        } elseif(method_exists($this,'get'.$name)){
            throw new InvalidCallException('Setting read-only property: ' . get_class($this) . '::' . $name);
        } else {
            throw new UnknownPropertyException('Setting unknown property: ' . get_class($this) . '::' . $name);
        }
    }
}

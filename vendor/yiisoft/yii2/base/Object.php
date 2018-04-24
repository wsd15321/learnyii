<?php
/**
 * Created by PhpStorm.
 * User: sc
 * Date: 2018/4/20/020
 * Time: 15:14
 */

namespace yii\base;

use Yii;

class Object
{

    public static function className()
    {
        return get_called_class();
    }

    public function __construct($config = [])
    {
        if (!empty($config)) {
            Yii::configure($this,$config);
        }
        $this->init();
    }


    public function init(){}

    public function __get($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            $this->$getter();
        } elseif (method_exists($this, 'set' . $name)) {
            exit('exists is set' . get_class($this) . ' ' . $name);
        } else {
            exit('Getting unknown property: ' . get_class($this) . '::' . $name);
        }
    }

    public function __set($name, $value)
    {
        $setter = 'set' . $name;
        if (method_exists($this,$setter)) {
            $this->$setter($value);
        } elseif (method_exists($this,'get'.$name)) {
            exit('Setting read-only property: ' . get_class($this) . '::' . $name);
        } else {
            exit('Setting unknown property: ' . get_class($this) . '::' . $name);
        }

    }


    public function __isset($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            return $this->$getter() !== null;
        } else {
            return false;
        }
    }

    public function __unset($name)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            $this->$setter(null);
        } elseif (method_exists($this, 'get' . $name)) {
            exit('Unsetting read-only property: ' . get_class($this) . '::' . $name);
        }
    }


    public function hasMethod($name)
    {
        return method_exists($this,$name);
    }


}
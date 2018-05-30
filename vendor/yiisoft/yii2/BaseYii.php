<?php

namespace yii;

use yii\di\Container;

defined('YII2_PATH') or define('YII2_PATH', __DIR__);

class BaseYii
{

    /** @var \yii\web\Application */
    public static $app;

    /** @var array 别名组合 */
    public static $aliases = ['@yii' => __DIR__];

    public static $classMap;

    /**
     * @var Container
     */
    public static $container;

    /**
     * 框架本身依赖这个自动加载
     */
    public static function autoload($className)
    {
        if (isset(static::$classMap[$className])) {
            $classFile = static::$classMap[$className];
            if (substr($classFile,0,1) === '@') {
                $classFile = static::getAlias($className);
            }
            //命名空间必须有二级以上
        } elseif (strpos($className, '\\') !== false) {
            $classFile = static::getAlias('@' . str_replace('\\', '/', $className) . '.php', false);
            if ($classFile === false || !is_file($classFile)) {
                return;
            }
        } else {
            return;
        }

        include($classFile);

    }

    public static function configure($object,$properties)
    {

        foreach ($properties as $name=>$property) {
            $object->$name = $property;
        }

        return $object;
    }



    /**
     * 别名获取路径
     */
    public static function getAlias($alias, $throwException = true)
    {
        //不是别名就是完整的路径
        if (strncmp($alias, '@', 1)) {
            // not an alias
            return $alias;
        }

        //获取根别名，模块名
        $pos = strpos($alias,'/');
        $root = $pos === false ? $alias : substr($alias,0,$pos);

        if (isset(static::$aliases[$root])) {
            if (is_string(static::$aliases[$root])) {
                //二级以上的命名空间获取根别名后面的路径
                return $pos === false ? static::$aliases[$root] : static::$aliases[$root] . substr($alias,$pos);
            //todo 先学到一级别名
            } else {

            }
        }

        if ($throwException) {
            exit('error '. $alias . ' public static function getAlias');
        } else {
            return false;
        }
    }


    public static function setAlias($alias, $path)
    {
        //别名前如果没@则加上@
        if (strncmp($alias, '@', 1)) {
            $alias = '@' . $alias;
        }
        //查找'/'首次位置
        $pos = strpos($alias, '/');
        //$alias没有'/'则认为@alias全称就是完整的模块名，有则取'/'前的字符串为模块名
        //$root 根别名
        $root = $pos === false ? $alias : substr($alias, 0, $pos);
        //路径有定义就添加路径
        if ($path !== null) {
            //$path是否有@前缀，没有就删除末尾的\/，有则从getAlias获取地址
            $path = strncmp($path, '@', 1) ? rtrim($path, '\\/') : static::getAlias($path);
            //根别名未设置
            if (!isset(static::$aliases[$root])) {
                if ($pos == false) {
                    //$alias就是根别名直接添加$path
                    static::$aliases[$root] = $path;
                } else {
                    //二级别名就作为数组
                    static::$aliases[$root] = [$alias => $path];
                }
                //todo 先到这
            } elseif (false) {

            }
            //$path为null时删除别名
        } elseif (isset(static::$aliases[$root])) {
            if (is_array(static::$aliases[$root])) {
                unset(static::$aliases[$root][$alias]);
            } elseif ($pos === false) {
                unset(static::$aliases[$root]);
            }
        }

    }


    /**
     * yii专用创建对象的方法
     * @param array|string|object $type 要创建的对象
     * @param array $params
     *
     */
    public static function createObject($type,$params = [])
    {
        if (is_string($type)) {
            return static::$container->get($type,$params);
        } elseif (is_array($type) && isset($type['class'])) {
            $class = $type['class'];
            //其余元素作为参数
            unset($type['class']);
            return static::$container->get($class,$params,$type);
        } elseif (is_callable($type)) {
            //todo 未完成
        } else {
            exit('createObject fail');
        }

    }



}
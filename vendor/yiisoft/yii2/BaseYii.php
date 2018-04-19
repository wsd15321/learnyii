<?php

namespace yii;

defined('YII2_PATH') or define('YII2_PATH', __DIR__);

class BaseYii {


    public static $app;

    public static $aliases = ['@yii' => __DIR__];

    public static $classMap;

    /**
     * 框架本身依赖这个自动加载
     */
    public static function autoload($className)
    {
        if (isset(static::$classMap[$className])) {

        } elseif (strpos($className, '\\') !== false) {
            $classFile = static::getAlias('@' . str_replace('\\', '/', $className) . '.php', false);
            var_dump($classFile); exit;
        }

    }

    public static function getAlias($alias, $throwException = true)
    {
        var_dump($alias); exit;
    }



    public static function setAlias($alias,$path)
    {
        //别名前如果没@则加上@
        if (strncmp($alias, '@', 1)) {
            $alias = '@' . $alias;
        }
        //查找'/'首次位置
        $pos = strpos($alias, '/');
        //$alias没有'/'则认为@alias全称就是模块名，有则取'/'前的字符串为模块名
        $root = $pos === false ? $alias : substr($alias, 0, $pos);



    }



}
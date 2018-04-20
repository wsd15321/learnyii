<?php

namespace yii\di;

use yii\base\Object;

class Container extends Object
{
    // 用于保存单例Singleton对象，以对象类型为键
    private $_singletons = [];

    // 用于保存依赖的定义，以对象类型为键
    private $_definitions = [];

    // 用于保存构造函数的参数，以对象类型为键
    private $_params = [];

    // 用于缓存ReflectionClass对象，以类名或接口名为键
    private $_reflections = [];

    // 用于缓存依赖信息，以类名或接口名为键
    private $_dependencies = [];



}
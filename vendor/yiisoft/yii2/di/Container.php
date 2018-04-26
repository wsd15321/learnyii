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



    public function get($class, $params = [], $config = [])
    {
        //是否单例
        if (isset($this->_singletons[$class])) {
            return $this->_singletons[$class];
        } elseif (!isset($this->_definitions[$class])) {
            return $this->build($class,$params,$config);
        }




    }

    /**
     * 创建实例
     * @param string $class 类名
     * @param array|object $params 构造函数参数
     * @param array $config 属性数组
     * @return object
     */
    protected function build($class, $params, $config)
    {
        list ($reflection, $dependencies) = $this->getDependencies($class);
        //传入的参数覆盖掉原有的默认参数 构造函数的参数
        foreach ($params as $index=>$param) {
            $dependencies[$index] = $param;
        }

        //如果有参数是对象则获取该参数转成实例
        $dependencies = $this->resolveDependencies($dependencies,$reflection);
        // 能否实例化
        if (!$reflection->isInstantiable()) {
            exit('not instantiable');
        }

        //没有配置就直接实例化$reflection
        if (empty($config)) {
            // newInstanceArgs 给出的参数赋值给构造方法
            return $reflection->newInstanceArgs($dependencies);
        }
        //$config有内容
        //存在参数且对象继承Object
        if (!empty($dependencies) && $reflection->implementsInterface('yii\base\Configurable')) {
            //Object的构造函数会用到$config
            $dependencies[count($dependencies) - 1] = $config;
            return $reflection->newInstanceArgs($dependencies);
        } else {
            //可能不是Object子类，但仍会给属性逐一赋值
            $object = $reflection->newInstanceArgs($dependencies);
            foreach ($config as $name=>$value) {
                $object->$name = $value;
            }
            return $object;
        }

    }



    public function set($class,$definition, array $params)
    {
        $this->_definitions[$class] = $this->normalizeDefinition($class,$definition);
        $this->_params[$class] = $params;
        unset($this->_singletons[$class]);
        return $this;
    }


    /**
     * 检查符合规范
     * @param string $class class name
     * @param string|array|callable $definition
     * @return array the normalized class definition
     */
    protected function normalizeDefinition($class, $definition)
    {
        if (empty($definition)) {
            return ['class'=>$class];
        } elseif (is_string($definition)) {
            return ['class' => $definition];
        } elseif (is_callable($definition,true) || is_object($definition)) {
            return $definition;
        } elseif (is_array($definition)) {
            if (!isset($definition['class'])) {
                if (strpos($class,'\\') !== false) {
                    $definition[$class] = $class;
                } else {
                    exit("A class definition requires a \"class\" member.");
                }
            }
            return $definition;
        } else {
            exit("Unsupported definition type for \"$class\": " . gettype($definition));
        }

    }

    public function getDependencies($class)
    {
        if (isset($this->_reflections[$class])) {
            return [$this->_reflections[$class],$this->_dependencies[$class]];
        }

        $dependencies = [];
        $reflection = new \ReflectionClass($class);

        $construct = $reflection->getConstructor();
        //看是否有构造函数
        if ($construct !== null) {
            foreach ($construct->getParameters() as $param) {
                if ($param->isDefaultValueAvailable()) {
                    $dependencies[] = $param->getDefaultValue();
                } else {
                    //构造函数参数是否为对象
                    $c = $param->getClass();
                    $dependencies[] = Instance::of($c === null ? null : $c->getName());
                }
            }
        }
        $this->_definitions[$class] = $dependencies;
        $this->_reflections[$class] = $reflection;
        return [$reflection,$dependencies];
    }

    /**
     * @param array $dependencies the dependencies
     * @param ReflectionClass $reflection the class reflection associated with the dependencies
     * @return array the resolved dependencies
     */
    protected function resolveDependencies($dependencies, $reflection = null)
    {
        $id = 'id';
        foreach ($dependencies as $index=>$dependency) {
            if ($dependency instanceof Instance) {
                if ($dependency->$id !== null) {
                    $dependency[$index] = $this->get($dependency->$id);
                }
            } elseif ($reflection !== null) {
                exit('Missing required parameter');
            }
        }
        return $dependencies;
    }


    /**
     * @return array
     */
    protected function mergeParams($class, $params)
    {
        if (empty($this->_params[$class])) {
            return $params;
        } elseif (empty($params)) {
            return $this->_params[$class];
        } else {
            //$params有缓存并也有配置传入就逐一覆盖
            $ps = $this->_params[$class];
            foreach ($params as $index => $value) {
                $ps[$index] = $value;
            }
            return $ps;
        }
    }


}
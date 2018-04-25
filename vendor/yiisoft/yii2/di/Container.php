<?php

namespace yii\di;

class Container
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



    public function get($class)
    {

    }

    protected function build($class, $params, $config)
    {

        list ($reflection, $dependencies) = $this->getDependencies($class);

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
    }


}
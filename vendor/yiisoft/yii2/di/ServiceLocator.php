<?php
/**
 * Created by PhpStorm.
 * User: sc
 * Date: 2018/4/24/024
 * Time: 19:38
 */

namespace yii\di;


use yii\base\Component;

class ServiceLocator extends Component
{


    /**
     * @var array shared component instances indexed by their IDs
     */
    private $_components = [];
    /**
     * @var array component definitions indexed by their IDs
     */
    private $_definitions = [];


    /**
     * @param string $id
     * @return object
     */
    public function get($id,$throwException = true)
    {
        //已实例化过的组件
        if (isset($this->_components[$id])) {
            return $this->_components[$id];
        }

        if (isset($this->_definitions[$id])) {
            $definition = $this->_definitions[$id];
            if (is_object($this->_definitions[$id] && !$definition instanceof \Closure)) {
                return $this->_components[$id] = $definition;
            } else {
                return $this->_components[$id] = \Yii::createObject($definition);
            }
        } else {
            exit("Unknown component ID: $id");
        }

    }


    /**
     * 配置$this->_definition数组
     * @param string $id 组件名称 'db','cache'
     * @param mixed $definition 要注册的服务内容
     */
    public function set($id,$definition)
    {
        //null代表移除组件
        if ($definition === null) {
            unset($this->_components[$id],$this->_definitions[$id]);
        }

        //移除$_components里的指定组件，暂不明
        unset($this->_components[$id]);

        if (is_object($definition) || is_callable($definition)) {
            $this->_definitions[$id] = $definition;
        } elseif (is_array($definition)) {
            if (isset($definition['class'])) {
                $this->_definitions[$id] = $definition;
            } else {
                exit('config error not class');
            }
        } else {
            exit('Unexpected configuration type for the '.$id);
        }

    }


    /**
     *  * ```php
     * [
     *     'db' => [
     *         'class' => 'yii\db\Connection',
     *         'dsn' => 'sqlite:path/to/file.db',
     *     ],
     *     'cache' => [
     *         'class' => 'yii\caching\DbCache',
     *         'db' => 'db',
     *     ],
     * ]
     * 相当于批量调用set()
     * 在框架中一开始就以setter方式调用
     */
    public function setComponents($components)
    {
        foreach ($components as $id=>$component) {
            $this->set($id,$component);
        }
    }


}
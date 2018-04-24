<?php
/**
 * Created by PhpStorm.
 * User: sc
 * Date: 2018/4/24/024
 * Time: 19:38
 */

namespace yii\di;


class ServiceLocator
{


    /**
     * @var array shared component instances indexed by their IDs
     */
    private $_components = [];
    /**
     * @var array component definitions indexed by their IDs
     */
    private $_definitions = [];




    public function get($id,$throwException = true)
    {

    }







    /**
     * 配置$this->_definition数组
     */
    public function set($id,$definition)
    {
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
            exit('Unexpected configuration type for the '.$id.' componen');
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
     */
    public function setComponents($components)
    {
        foreach ($components as $id=>$component) {
            $this->set($id,$component);
        }
    }


}
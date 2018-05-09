<?php
/**
 * Created by PhpStorm.
 * User: sc
 * Date: 2018/5/3/003
 * Time: 14:51
 */

namespace yii\base;


class Component extends Object
{

    private $_event = [];

    /** @var  array */
    private $_behaviors;


    public function __get($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this,$getter)) {
            return $this->$getter;
        } else {
            //从绑定的行为中找相应对象
            $this->ensureBehaviors();
            foreach ($this->_behaviors as $behavior) {
                //$name存在于$behavior对象中
                if ($behavior->canGetProperty($name)) {
                    return $behavior->$name;
                }
            }
        }

        exit('属性不存在');

    }

    public function __call($name, $arguments)
    {

    }


    public function behaviors()
    {
        return [];
    }


    /**
     * Attaches an event handler to an event.
     *
     * The event handler must be a valid PHP callback. The following are
     * some examples:
     *
     * ```
     * function ($event) { ... }         // anonymous function
     * [$object, 'handleClick']          // $object->handleClick()
     * ['Page', 'handleClick']           // Page::handleClick()
     * 'handleClick'                     // global function handleClick()
     * ```
     *
     * The event handler must be defined with the following signature,
     *
     * ```
     * function ($event)
     * ```
     *
     * where `$event` is an [[Event]] object which includes parameters associated with the event.
     *
     * @param string $name the event name
     * @param callable $handler the event handler
     * @param mixed $data the data to be passed to the event handler when the event is triggered.
     * When the event handler is invoked, this data can be accessed via [[Event::data]].
     * @param boolean $append whether to append new event handler to the end of the existing
     * handler list. If false, the new handler will be inserted at the beginning of the existing
     * handler list.
     * @see off()
     * $append 决定是否插入到数组结尾
     */
    public function on($name, $handler, $data = null, $append = true)
    {
        $this->ensureBehaviors();
        if ($append || empty($this->_event[$name])) {
            $this->_event[$name][] = [$handler,$data];
        } else {
            array_unshift($this->_event[$name],[$handler,$data]);
        }

    }

    public function off()
    {
        $this->ensureBehaviors();
        //todo
    }


    /**
     * Attaches a behavior to this component.
     * @param string|integer $name the name of the behavior. If this is an integer, it means the behavior
     * is an anonymous one. Otherwise, the behavior is a named one and any existing behavior with the same name
     * will be detached first.
     * @param string|array|Behavior $behavior the behavior to be attached
     * @return Behavior the attached behavior.
     */
    public function attachesBehaviorInternal($name, $behavior)
    {
        if (!$behavior instanceof Behavior) {
            //直接创建对象
            $behavior = \Yii::createObject($behavior);
        }
        //是否匿名绑定,不填键名就是匿名绑定
        if (is_int($name)) {
            $behavior->attach($this);
            $this->_behaviors[] = $behavior;
        } else {
            if (isset($this->_behaviors[$name])) {
                $this->_behaviors[$name]->detach();
            }
            $this->_behaviors[$name] = $behavior;
        }

        return $behavior;

    }


    public function ensureBehaviors()
    {
        if ($this->_behaviors === null) {
            $this->_behaviors = [];
            $behaviors = $this->behaviors();
            //给$this->_behaviors赋值
            //类名或回调函数
            foreach ($behaviors as $name=>$behavior) {
                $this->attachesBehaviorInternal($name,$behavior);
            }
        }
    }


}
<?php
/**
 * Created by PhpStorm.
 * User: sc
 * Date: 2018/5/3/003
 * Time: 14:03
 */

namespace yii\base;


class Event extends Object
{

    /**
     * @var string the event name. This property is set by [[Component::trigger()]] and [[trigger()]].
     * Event handlers may use this property to check what event it is handling.
     */
    public $name;
    /**
     * @var object the sender of this event. If not set, this property will be
     * set as the object whose `trigger()` method is called.
     * This property may also be a `null` when this event is a
     * class-level event which is triggered in a static context.
     * 使用trigger的对象
     */
    public $sender;
    /**
     * @var boolean whether the event is handled. Defaults to `false`.
     * When a handler sets this to be `true`, the event processing will stop and
     * ignore the rest of the uninvoked event handlers.
     */
    public $handled = false;
    /**
     * @var mixed the data that is passed to [[Component::on()]] when attaching an event handler.
     * Note that this varies according to which event handler is currently executing.
     */
    public $data;

    /**
     * @var array contains all globally registered event handlers.
     */
    private static $_events = [];

    /**
     * @param string $class
     * @param string $name
     * @param callable $handler 回调函数
     * @param mixed $data
     * @param boolean $append 执行的顺序
     */
    public function on($class,$name,$handler, $data = null, $append = true)
    {
        $class = ltrim('\\',$class);
        if ($append || empty(static::$_events[$name][$class])) {
            //添加到尾部
            static::$_events[$name][$class][] = [$handler,$data];
        } else {
            //添加到首部
            array_unshift(static::$_events[$name][$class],[$handler,$data]);
        }

    }


    public static function offAll()
    {
        static::$_events = [];
    }


    public static function trigger($class, $name, $event = null)
    {
        if (empty(self::$_events[$name])) {
            return;
        }
        if ($event === null) {
            $event = new static;
        }
        $event->handled = false;
        $event->name = $name;

        if (is_object($class)) {
            if ($event->sender === null) {
                $event->sender = $class;
            }
            $class = get_class($class);
        } else {
            $class = ltrim($class, '\\');
        }

        //$class string
        //类名及这个类所遇父类和所有实现的接口的数组集合
        $classes = array_merge(
            [$class],
            class_parents($class, true),
            class_implements($class, true)
        );
        //$handler [callable, ...]
        foreach ($classes as $class) {
            if (!empty(self::$_events[$name][$class])) {
                foreach (self::$_events[$name][$class] as $handler) {
                    $event->data = $handler[1];
                    call_user_func($handler[0], $event);
                    if ($event->handled) {
                        return;
                    }
                }
            }
        }
    }


}
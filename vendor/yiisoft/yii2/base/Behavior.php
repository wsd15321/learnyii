<?php
/**
 * Created by PhpStorm.
 * User: sc
 * Date: 2018/5/3/003
 * Time: 15:57
 */

namespace yii\base;


class Behavior extends Object
{

    /** @var  object */
    public $owner;

    public function events()
    {
        return [];
    }

    /**
     * @param Component $owner
     */
    public function attach($owner)
    {
        $this->owner = $owner;
        foreach ($this->events() as $event => $handle) {
            $owner->on($event, is_string($handle) ? [$this , $handle] : $handle);
        }

    }

    /**
     * 解除行为绑定
     */
    public function detach()
    {

    }


}
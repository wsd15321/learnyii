<?php
/**
 * Created by PhpStorm.
 * User: sc
 * Date: 2018/5/3/003
 * Time: 15:57
 */

namespace yii\base;


class Behavior
{

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

    }



}
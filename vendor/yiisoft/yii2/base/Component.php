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

    private $_behaviors;


    public function behaviors()
    {
        return [];
    }





    public function attachesBehaviorInternal($name,$behavior)
    {

    }

}
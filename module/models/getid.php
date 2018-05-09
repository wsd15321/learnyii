<?php
/**
 * Created by PhpStorm.
 * User: sc
 * Date: 2018/5/9/009
 * Time: 14:34
 */

namespace module\models;


use yii\base\Behavior;

class getid extends Behavior
{

    public $abc;

    public function init()
    {
        $this->abc = $this->getAbc();
    }


    public function getAbc()
    {
        $str = 'id' . rand(1000,9999) . $this->owner->extdd();
        return $str ;
    }





}
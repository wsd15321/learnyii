<?php

namespace module\models;
use yii\base\Object;
use yii\di\Container;

class Tpl
{

    public function test()
    {
        $container = new Container();
        $container->set('C',['class'=>'module\models\C']);
        $obj = $container->get('C',['db'=>'objk'],['db'=>'objk']);
        return $obj;

    }


}


class C{
    public $db;
    public function __construct($db = '')
    {
        $this->db = $db;
    }
}


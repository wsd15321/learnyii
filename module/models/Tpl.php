<?php

namespace module\models;
use yii\base\Object;
use yii\di\Container;
use yii\di\ServiceLocator;

class Tpl
{

    public function test()
    {
        $class = 'module\models\TestContainer';

        $db = [
            'class' => $class,
            'a' => 1234567
        ];

        $location = new ServiceLocator();
        $location->setComponents(['db'=>$db]);
        $obj = $location->get('db');
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


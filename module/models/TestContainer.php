<?php

namespace module\models;

use yii\base\Object;


interface UserFinderInterface
{
    function findUser();
}

class Connections {
    public $dbs;
    public $dsn;
    public function __construct($dsn = '')
    {
        $this->dsn = $dsn;
    }

}


class UserFinder extends Object implements UserFinderInterface
{
    public $db;

    public function __construct(Connections $db, $config = [])
    {
        $this->db = $db;
        parent::__construct($config);
    }

    public function findUser()
    {
    }
}

class UserLister extends Object
{
    public $finder;

    public function __construct(UserFinderInterface $finder, $config = [])
    {
        $this->finder = $finder;
        parent::__construct($config);
    }
}

class TestContainer {

    public $a;

    public function __construct($a = 1)
    {
        $this->a = $a;
    }


    public function getUser()
    {
        return 'ok';
    }


}


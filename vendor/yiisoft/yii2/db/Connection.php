<?php
/**
 * Created by PhpStorm.
 * User: sc
 * Date: 2018/6/7/007
 * Time: 16:47
 */

namespace yii\db;


use yii\base\Component;

class Connection extends Component
{


    public $username;

    public $dsn;

    public $pdo;

    public $charset;

    public $tablePrefix = '';

    public $enableSchemaCache = false;

    public $schemaCacheDuration = 3600;

    public $schemaMap = [
        'mysqli' => ''
    ];


}
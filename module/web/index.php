<?php

require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../../common/config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../common/config/main.php'),
    require(__DIR__ . '/../../common/config/params.php')
);


$tpl = new \module\models\triggertest();
$tpl->start();
$tpl->trigger('testE');
var_dump($tpl->getEvent()); exit;





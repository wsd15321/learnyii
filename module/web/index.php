<?php

require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../../common/config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../common/config/main.php'),
    require(__DIR__ . '/../../common/config/params.php')
);




require_once('E:\test\single\module\models\TestContainer.php');

$container = new \yii\di\Container();
$container->set('module\models\Connections', [
    'dsn' => '...',
]);
$container->set('module\models\UserFinderInterface', [
    'class' => 'module\models\UserFinder',
]);
$container->set('userLister', 'module\models\UserLister');


var_dump($container->getMsg()); exit;

$lister = $container->get('userLister');



var_dump($lister); exit;





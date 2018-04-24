<?php
/**
 * Created by PhpStorm.
 * User: sc
 * Date: 2018/4/24/024
 * Time: 10:38
 */

namespace yii\di\learnsl\test;


use yii\di\learnsl\DatabaseService;
use yii\di\learnsl\LogService;
use yii\di\learnsl\ServiceLocator;

class ServiceLocatorTest
{
    /**
     * @var LogService
     */
    private $logService;

    /**
     * @var DatabaseService
     */
    private $databaseService;

    /**
     * @var ServiceLocator
     */
    private $serviceLocator;

    public function __construct()
    {
        $this->serviceLocator = new ServiceLocator();
        $this->logService = new LogService();
        $this->databaseService = new DatabaseService();
    }

    public function testHasServices()
    {
        $this->serviceLocator->add(
            'yii\di\learnsl\LogServiceInterface',
            $this->logService
        );

        $this->serviceLocator->add(
            'yii\di\learnsl\DatabaseServiceInterface',
            $this->databaseService
        );

        $d = $this->serviceLocator->has('yii\di\learnsl\DatabaseServiceInterface');
        return $d;

    }

    public function testServicesWithObject()
    {
        $this->serviceLocator->add(
            'yii\di\learnsl\LogServiceInterface',
            $this->logService
        );

        $this->serviceLocator->add(
            'yii\di\learnsl\DatabaseServiceInterface',
            $this->databaseService
        );

    }

    public function testServicesWithClass()
    {
        $this->serviceLocator->add(
            'yii\di\learnsl\LogServiceInterface',
            get_class($this->logService)
        );

        $this->serviceLocator->add(
            'yii\di\learnsl\DatabaseServiceInterface',
            get_class($this->databaseService)
        );


    }

    public function testServicesNotShared()
    {
        $this->serviceLocator->add(
            'yii\di\learnsl\LogServiceInterface',
            $this->logService,
            false
        );

        $this->serviceLocator->add(
            'yii\di\learnsl\DatabaseServiceInterface',
            $this->databaseService,
            false
        );


    }
}
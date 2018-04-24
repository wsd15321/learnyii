<?php

namespace yii\di\learnsl;

interface  ServiceLocatorInterface
{

    /**
     * @param string $interface
     * @return boolean
     */
    public function has($interface);

    /**
     * @param string $interface
     * @return mixed
     */
    public function get($interface);

}
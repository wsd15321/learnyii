<?php

namespace module\models;

use Yii;
use yii\base\Component;

class triggertest extends Component
{
    public function start()
    {
        $this->on('testE', [new testEvent, 'aa']);
    }
}
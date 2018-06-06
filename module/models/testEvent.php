<?php

namespace module\models;


class testEvent
{

    public function aa()
    {
        $path = 'E:/test/tmp/str/123356.txt';
        file_put_contents($path, 'aa testEvent 01');
    }

}
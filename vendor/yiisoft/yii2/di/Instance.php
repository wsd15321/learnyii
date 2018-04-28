<?php
/**
 * Created by PhpStorm.
 * User: sc
 * Date: 2018/4/25/025
 * Time: 18:22
 */

namespace yii\di;


class Instance
{

    public $id;

    protected function __construct($id)
    {
        $this->id = $id;
    }

    public static function of($id)
    {
        return new static($id);
    }



}
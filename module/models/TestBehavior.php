<?php
/**
 * Created by PhpStorm.
 * User: sc
 * Date: 2018/5/9/009
 * Time: 14:30
 */

namespace module\models;


use yii\base\Component;

class TestBehavior extends Component
{

    public $testBehavior;

    public function behaviors()
    {
        return [
            'tpl' => [
                'class' => 'module\models\getid',
                'owner' => $this
            ],
            'ok' => [

            ],
        ];
    }


    public function extdd()
    {
        return 'ok';
    }


}
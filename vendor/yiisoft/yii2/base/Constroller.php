<?php
/**
 * Created by PhpStorm.
 * User: sc
 * Date: 2018/5/18/018
 * Time: 11:32
 */

namespace yii\base;


class Constroller extends Component
{

    public $id;

    public $module;

    public function __construct($id, $module, array $config = [])
    {
        $this->id = $id;
        $this->module = $module;
        parent::__construct($config);
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: sc
 * Date: 2018/5/16/016
 * Time: 15:42
 */

namespace yii\base;

use Yii;

abstract class Application  extends Module {


    /**
     * @var array list of loaded modules indexed by their class names.
     */
    public $loadedModels = [];


    public function __construct($id, $parent = null, array $config = [])
    {
        Yii::$app = $this;
        parent::__construct($id, $parent, $config);
    }


}
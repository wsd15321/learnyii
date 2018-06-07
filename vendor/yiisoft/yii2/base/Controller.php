<?php
/**
 * Created by PhpStorm.
 * User: sc
 * Date: 2018/5/18/018
 * Time: 11:32
 */

namespace yii\base;

use Yii;

class Controller extends Component
{

    public $id;

    public $module;

    public function __construct($id, $module, array $config = [])
    {
        $this->id = $id;
        $this->module = $module;
        parent::__construct($config);
    }

    public function actions()
    {
        return [];
    }

    /**
     * Constroller action
     */
    public function runAction($id, $params = [])
    {
        //TODO
        return $this->createAction($id);
    }

    public function createAction($id)
    {
        $actionMap = $this->actions();
        if (isset($actionMap[$id])) {
            return Yii::createObject($actionMap[$id], [$id, $this]);
        } else {
            $methodName = 'action' . ucwords($id);
            if (method_exists($this, $methodName)) {
                $ref = new \ReflectionMethod($this, $methodName);
                if ($ref->isPublic() && $ref->getName() === $methodName) {
                    //TODO
                    return $this->$methodName();
                }
            }
        }

    }


}
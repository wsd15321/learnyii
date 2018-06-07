<?php
/**
 * Created by PhpStorm.
 * User: sc
 * Date: 2018/5/16/016
 * Time: 15:59
 */

namespace yii\base;

use module\controllers\Controller;
use yii\di\ServiceLocator;
use Yii;

class Module extends ServiceLocator
{

    public $params = [];

    public $id;

    public $model;

    public $layout = false;


    public $controllerMap = [];

    public $controllerNamespace;
    /**
     * @var string the default route of this module. Defaults to `default`.
     * The route may consist of child module ID, controller ID, and/or action ID.
     * For example, `help`, `post/create`, `admin/post/create`.
     * If action ID is not given, it will take the default value as specified in
     * [[Controller::defaultAction]].
     */
    public $defaultRoute = 'default';

    /**
     * @var string the root directory of the module.
     */
    private $_basePath;
    /**
     * @var string the root directory that contains view files for this module
     */
    private $_viewPath;
    /**
     * @var string the root directory that contains layout view files for this module.
     */
    private $_layoutPath;
    /**
     * @var array child modules of this module
     */
    private $_modules = [];


    public function __construct($id, $parent = null, $config = [])
    {
        $this->id = $id;
        $this->model = $parent;
        parent::__construct($config);
    }

    public static function getInstance()
    {

    }

    public static function setInstance($instance)
    {

    }


    /**
     * 确保constroller命名空间存在
     */
    public function init()
    {
        if ($this->controllerNamespace === null) {
            $class = get_class($this);
            if ($pos = strpos($class, '\\') !== false) {
                $this->controllerNamespace = substr(get_class($this), 0, $pos) . '\\Controller';
            }
        }

    }


    public function getBasePath()
    {
        if ($this->_basePath) {
            $class = new \ReflectionClass($this);
            $this->_basePath = dirname($class->getFileName());
        }

        return $this->_basePath;
    }

    public function setBasePath($path)
    {
        $path = Yii::getAlias($path);
        $p = strncmp($path, 'phar://', 7) === 0 ? $path : realpath($path);
        if ($p !== false && is_dir($p)) {
            $this->_basePath = $p;
        } else {
            exit('The directory does not exist: ' . $path);
        }
    }


    public function getControllerPath()
    {
        return Yii::getAlias('@' . str_replace('\\', '/', $this->controllerNamespace));
    }


    public function setAliases($aliases)
    {
        foreach ($aliases as $name => $alias) {
            Yii::setAlias($name, $alias);
        }
    }


    public function hasModule($id)
    {
        if ($pos = strpos($id, '/') !== false) {
            $module = $this->hasModule(substr($id, 0, $pos));
            //id在$this->_modules存在且值为null,就算作卸载，当作不存在
            //如果不是null那就可能存在或不存在，都会找到最后，确定都存在
            return $module === null ? false : $this->hasModule(substr($id, $pos + 1));
        }
        return isset($this->_modules[$id]);
    }


    /**
     * @param string $id Module Id
     * @param boolean $load
     * @return Module|null
     */
    public function getModule($id, $load = true)
    {
        if ($pos = strpos($id, '/') !== false) {
            $module = $this->getModule(substr($id, 0, $pos));
            return $module === null ? null : $this->getModule(substr($id, $pos + 1));
        }

        if (isset($this->_modules[$id])) {
            if ($this->_modules[$id] instanceof Module) {
                return $this->_modules[$id];
            } elseif ($load) {
                //此处会有log记录 Yii::trace("Loading module: $id", __METHOD__);
                $module = Yii::createObject($this->_modules[$id], [$id, $this]);
                /** @var $module Module */
                $module->setInstance($module);
                return $this->_modules[$id] = $module;
            }
        }
        return null;

    }

    public function setModule($id, $model)
    {
        if ($model === null) {
            unset($this->_modules[$id]);
        } else {
            $this->_modules[$id] = $model;
        }
    }

    public function getModules($loadedOnly = false)
    {
        if ($loadedOnly) {
            $modules = [];
            foreach ($this->_modules as $module) {
                if ($module instanceof Module) {
                    $modules[] = $module;
                }
            }
            return $modules;
        } else {
            return $this->_modules;
        }
    }

    /**
     * Registers sub-modules in the current module.
     *
     * Each sub-module should be specified as a name-value pair, where
     * name refers to the ID of the module and value the module or a configuration
     * array that can be used to create the module. In the latter case, [[Yii::createObject()]]
     * will be used to create the module.
     *
     * If a new sub-module has the same ID as an existing one, the existing one will be overwritten silently.
     *
     * The following is an example for registering two sub-modules:
     *
     * ```php
     * [
     *     'comment' => [
     *         'class' => 'app\modules\comment\CommentModule',
     *         'db' => 'db',
     *     ],
     *     'booking' => ['class' => 'app\modules\booking\BookingModule'],
     * ]
     * ```
     *
     * @param array $modules modules (id => module configuration or instances).
     */
    public function setModules($modules)
    {
        foreach ($modules as $id => $module) {
            $this->_modules[$id] = $module;
        }
    }

    /**
     * 通过$id创建controller实例 $id=>controller name 去掉Controller后的名字
     *
     */
    public function createControllerById($id)
    {
        $pos = strpos('/', $id);
        if ($pos === false) {
            $prefix = '';
            $className = $id;
        } else {
            $prefix = substr($id, 0, $pos);
            $className = substr($id, $pos + 1);
        }

        if (!preg_match('%^[a-z][a-z0-9\\-_]*$%', $className)) {
            return null;
        }
        if ($prefix !== '' && !preg_match('%^[a-z0-9_/]+$%i', $prefix)) {
            return null;
        }
        //简单版
        $className = str_replace(' ', '', ucwords($className)) . 'Controller';
        $className = $this->controllerNamespace . $prefix . $className;

        if (strpos($className, '-') !== false || !class_exists($className)) {
            return null;
        }

        if (is_subclass_of($className, 'yii\base\Controller')) {
            $controller = Yii::createObject($className, [$id, $this]);
            return get_class($controller) === $className ? $controller : null;
        } else {
            exit('Controller class must extend from \\yii\\base\\Controller');
        }

    }

    /**
     * 创建controller控制器对象
     */
    public function createController($route)
    {
        //传入的路由为空就采用默认路径
        if ($route === '') {
            $route = $this->defaultRoute;
        }
        //是否有多级路由
        if ($pos = strpos($route, '//') !== false) {
            return null;
        }
        //拆分路径
        if (strpos($route, '/') !== false) {
            list($id, $route) = explode('/', $route, 2);
        } else {
            $id = $route;
            $route = '';
        }

        if (isset($this->controllerMap[$id])) {
            $controller = Yii::createObject($this->controllerMap[$id], [$id, $this]);
            return [$controller, $route];
        }

        //优先加载module?
        $module = $this->getModule($id);
        if ($module !== null) {
            return $this->createController($module);
        }

        if ($pos = strpos($route, '/') !== false) {
            $id = substr($route, 0, $pos);
            $route = substr($route, $pos + 1);
        }
        //id就是去掉Controller后的类名
        $controller = $this->createControllerById($id);

        if ($controller === null && $route !== '') {
            return $this->createControllerById($id . '/' . $route);
        }
        return $controller !== null ? [$controller, $route] : false;

    }

    public function runAction($route, $params = [])
    {
        $parts = $this->createController($route);
        if (is_array($parts)) {
            list($controller, $actionId) = $parts;
            $oldController = Yii::$app->controller;
            Yii::$app->controller = $controller;
            /** @var \yii\base\Controller $controller */
            $result = $controller->runAction($actionId, $params);
            if (Yii::$app->controller !== null) {
                Yii::$app->controller = $oldController;
            }
            return $result;
        } else {
            exit('error modele runAction');
        }

    }


}
<?php
/**
 * 创建应用Application
 * 单例模式
 */

namespace Lib;

class Application
{
    public $base_dir;
    protected static $instance; //static instance;
    public $config; //应用配置

    protected function __construct($base_dir)
    {
        $this->base_dir = $base_dir;
        $this->config   = new Config($base_dir . '/Configs');
    }

    public static function getInstance($base_dir)
    {
        if (empty(self::$instance)) {
            self::$instance = new self($base_dir);
        }
        return self::$instance;
    }

    public function dispatch()
    {
        $uri             = $_SERVER['PATH_INFO'];
        list($m, $c, $a) = explode('/', trim($uri, '/'));

        $m = ucwords($m); //作为类调用时名称
        $c = ucwords($c);
        $a = strtolower($a);

        define('MODULE_NAME', $m); //被其他类配置时的名称
        define('CONTROLLER_NAME', $c);
        define('ACTION_NAME', $a);

        define('MODULE_PATH', APP_PATH . $m . '/');
        define('CONTROLLER_PATH', MODULE_PATH . $c . '/');

        define('__MODULE__', $_SERVER['SCRIPT_NAME'] . '/' . $m);
        define('__CONTROLLER__', __MODULE__ . '/' . $c);
        define('__ACTION__', __CONTROLLER__ . '/' . $a);

        $class = "App\\{$m}\\Controller\\{$c}Controller";
        $obj   = new $class($m, $c, $a);

        Hook::import($this->config['tags'], true);
        // 加载模块tags文件定义
        if (is_file(MODULE_PATH . 'Conf/tags.php')) {
            Hook::import(include MODULE_PATH . 'Conf/tags.php');
        }

        $controller_config = $this->config['controller'];

        Hook::listen('APP_begin');

        $obj->$a();

        Hook::listen('APP_end');

    }
}

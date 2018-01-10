<?php
/**
 * ZVC.php 框架入口文件
 *
 */

define('BASEDIR', str_replace('\\', '/', __DIR__)); //框架路径
define('ROOT', str_replace('\\', '/', dirname(__DIR__))); //网站路径
define('APP_PATH', ROOT . '/App/'); //应用路径
define('DEBUG', true);

date_default_timezone_set('PRC');

include BASEDIR . '/Lib/Loader.php';
require 'Common/function.php';

spl_autoload_register('\\Lib\\Loader::autoload');

$_GET    = I($_GET);
$_POST   = I($_POST);
$_COOKIE = I($_COOKIE);

session_start();

if (defined('DEBUG')) {
    error_reporting(E_ALL);
} else {
    error_reporting(0);
}

Lib\Application::getInstance(__DIR__)->dispatch();

<?php
/**
 * Loader.php
 * 类自动加载
 */

namespace Lib;

class Loader
{
    public static function autoload($class)
    {
        $zvclist = array('Lib', 'Configs');

        foreach ($zvclist as $value) {
            if (strpos($class, $value) !== false) {
                require BASEDIR . '/' . str_replace('\\', '/', $class) . '.php';
                return;
            }
        }
        if (strpos($class, 'App\\') !== false) {
            require ROOT . '/' . str_replace('\\', '/', $class) . '.php';
        }

    }
}

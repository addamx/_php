<?php
namespace Lib;

/**
 * 系统钩子
 * 标签连接行为插件
 */

class Hook
{
    private static $tags = array();

    //动态设置插件到某标签
    public static function add($tag, $name)
    {
        if (!isset(self::$tags[$tag])) {
            self::$tags[$tag] = array();
        }
        if (is_array($name)) {
            self::$tags[$tag] = array_merge(self::$tags[$tag], $name);
        } else {
            self::$tags[$tag][] = $name;
        }
    }

    //批量导入插件
    public static function import($data, $recursive = true)
    {
        if (!$recursive) {
            //覆盖导入
            self::$tags = array_merge(self::$tags, $data);
        } else {
            //$data = array('view_start'=>array('TestBehavior','_overlay'));
            foreach ($data as $tag => $val) {
                if (!isset(self::$tags[$tag])) {
                    self::$tags[$tag] = array();
                }
                //覆盖
                if (!empty($val['_overlay'])) {
                    unset($val['_overlay']);
                    self::$tags[$tags] = $val;
                } else {
                    //合并
                    self::$tags[$tag] = array_merge(self::$tags[$tag], $val);
                }
            }
        }
    }

    public static function get($tag = '')
    {
        if (empty($tag)) {
            return self::$tags;
        } else {
            return self::$tags[$tag];
        }
    }

    public static function listen($tag, &$params = null)
    {
        if (isset(self::$tags[$tag])) {
            foreach (self::$tags[$tag] as $name) {
                $result = self::exec($name, $tag, $params);
                if ($result === false) {
                    return;
                }
            }
        }
    }

    //执行-行为插件执行run方法
    public static function exec($name, $tag, &$params = null)
    {
        if ('Behavior' == substr($name, -8)) {
            $tag = 'run';
        }
        $addon = new $name();
        return $addon->$tag($params);
    }
}

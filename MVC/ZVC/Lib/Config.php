<?php
/**
 * 配置与设计模式
 * 使用ArrayAccess实现配置文件的加载;
 * (ArrayAccess提供像访问数组一样访问对象的属性的接口。)
 */

namespace Lib;

class Config implements \ArrayAccess
{
    protected $path;
    protected $configs = array();

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function offsetGet($key)
    {
        if (empty($this->configs[$key])) {
            $file_path           = $this->path . '/' . $key . '.php';
            $config              = require $file_path;
            $this->configs[$key] = $config;
        }
        return $this->configs[$key];
    }

    public function offsetSet($key, $value)
    {
        throw \Exception("Can not write config file");
    }

    public function offsetExists($key)
    {
        return isset($this->configs[$key]);
    }

    public function offsetUnset($key)
    {
        unset($this->configs[$key]);
    }

}

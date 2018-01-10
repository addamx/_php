<?php

function C($config)
{
    $configs = require BASEDIR . '/Configs/Convention.php';
    if (isset($configs[$config])) {
        return $configs[$config];
    }
    return '';
}

function I($data)
{
    foreach ($data as $k => $v) {
        if (is_array($v)) {
            $data[$k] = I($v);
        } else if (is_string($v)) {
            $data[$k] = addslashes($v);
        }
    }
    return $data;
}

<?php
/**
 * PSR-0
 * 1. 命名空间必须和绝对路径一致=>全部使用命名空间
 * 2. 类名的首字母必须大写=>所有PHP文件必须自动载入, 不能有include/require
 * 3. 除了入口文件外, 其他.php文件必须只有一个类=>单一入口
 */

define('BASEDIR', __DIR__); //定义项目更目录
require BASEDIR . '/Library/Loader.php';
spl_autoload_register('\\Library\\Loader::autoload');

$stack = new SplStack();
$stack->push('data1');
$stack->push('data2');

echo $stack->pop();
echo $stack->pop();

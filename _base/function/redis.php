<?php
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$redis->select(0);

$redis->set('test', "11111111111");
echo $redis->get('test');
print_r(($redis->keys('*')));

$me = new ReflectionClass('Redis');

var_dump($me->getMethod("set")->getParameters());

var_dump($me->getMethods());

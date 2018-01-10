<?php
class BlogAction
{

    public function detail()
    {
        echo 'detail' . "<br/>";
    }

    public function test($year = 2014, $month = 4, $day = 21)
    {
        echo $year . '--' . $month . '--' . $day . "<br/>";
    }

    public function _before_detail()
    {
        echo __FUNCTION__ . "<br/>";
    }

    public function _after_detail()
    {
        echo __FUNCTION__ . "<br/>";
    }
}
/*
ReflectionClass
->hasMethod
->getMethod = > 相当于ReflectionMethod

ReflectionMethod
->isPublic
->hasMethod
->invoke(ins, parameter)    //执行一个反射的方法

 */

// 执行detail方法
// public ReflectionMethod::__construct ( mixed $class , string $name )
// public ReflectionMethod::__construct ( string $class_method )
$method   = new ReflectionMethod('BlogAction', 'detail');
$instance = new BlogAction();

// 进行权限判断
if ($method->isPublic()) {

    $class = new ReflectionClass('BlogAction');

    // 执行前置方法
    if ($class->hasMethod('_before_detail')) {
        $beforeMethod = $class->getMethod('_before_detail');
        if ($beforeMethod->isPublic()) {
            $beforeMethod->invoke($instance);
        }
    }

    $method->invoke(new BlogAction);

    // 执行后置方法
    if ($class->hasMethod('_after_detail')) {
        $beforeMethod = $class->getMethod('_after_detail');
        if ($beforeMethod->isPublic()) {
            $beforeMethod->invoke($instance);
        }
    }
}

// 执行带参数的方法
$method = new ReflectionMethod('BlogAction', 'test');
$params = $method->getParameters();
foreach ($params as $param) {
    $paramName = $param->getName();
    if (isset($_REQUEST[$paramName])) {
        $args[] = $_REQUEST[$paramName];
    } elseif ($param->isDefaultValueAvailable()) {
        $args[] = $param->getDefaultValue();
    }
}

if (count($args) == $method->getNumberOfParameters()) {
    $method->invokeArgs($instance, $args);
} else {
    echo 'parameters is wrong!';
}

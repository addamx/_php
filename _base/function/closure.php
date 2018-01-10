<?php
//闭包

//基础：一句话实现定义一个函数并立即调用
//大家在JavaScript里经常这么搞，因为变量污染是个不可容忍的事儿。但是PHP里如何实现这样的功能？一个可行方法是：
call_user_func(function () {
    echo "hello,world";
});

//碉堡了的特性：调用那个可调用的东西调用一切可调用的东西，不只是你自定义的函数。
//简单例子：把这个用于实现一个通用控制器。
class Contrller
{

    //调用具体的action，
    public function __act($action)
    {
        call_user_func(
            array($this, $action)
        );
    }

}

class HelloContrller extends Controller
{

    public function index()
    {
    }

    public function hello()
    {
    }

    public function dance()
    {
    }

}

//利用闭包实现和Python类似的装饰器函数

//装饰器
$dec = function ($func) {
    $wrap = function () use ($func) {
        echo "before calling you do sth\r\n";
        $func();
        echo "after calling you can do sth too\r\n ";
    };
    return $wrap;
};

//执行某功能的函数
$hello = function () {
    echo "hello\r\n";
};
//装饰
$hello = $dec($hello);

//在其他地方调用经过装饰的原函数
$hello();

/*output:
before calling you do sth
hello
after calling you can do sth too
 */

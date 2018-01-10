<?php

/**
 * 异常
 * 当异常被触发时，通常会发生：
- 当前代码状态被保存
- 代码执行被切换到预定义的异常处理器函数
- 根据情况，处理器也许会从保存的代码状态重新开始执行代码，终止脚本执行，或从代码中另外的位置继续执行脚本
 * 1. 异常的基本使用
 * 2. 创建一个自定义的 Exception 类
 * 3. 多个异常
 * 4. 重新抛出异常
 * 5. 设置顶层异常处理器
 */

//1. 异常的基本使用 Try, throw 和 catch
function checkNum($number)
{
    if ($number > 1) {
        //1.1
        //显示Fatal error: Uncaught exception 'Exception' with message 'Value must be 1 or below' in ....
        throw new Exception("Value must be 1 or below");
    }
    return true;
}
//checkNum(2);

//1.2在 "try" 代码块中触发异常
//echo "Value must be 1 or below"(一般文本输出)
try
{
    checkNum(2);
    //If the exception is thrown, this text will not be shown
    echo 'If you see this, the number is 1 or below';
} catch (Exception $e) {
    echo 'Message: ' . $e->getMessage() . '<br/>';
}

//2. 创建一个自定义的 Exception 类
class customException extends Exception
{
    public function errorMessage()
    {
        //error message
        $errorMsg = 'Error on line ' . $this->getLine() . ' in ' . $this->getFile()
        . ': <b>' . $this->getMessage() . '</b> is not a valid E-Mail address' . '<br/>';
        return $errorMsg;
    }
}

$email = "someone@example...com";

try
{
    //check if
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        //throw exception if email is not valid
        throw new customException($email);
    }
} catch (customException $e) {
    //display custom message
    echo $e->errorMessage();
}

//3.多个异常. 可以使用多个 if..else 代码块，或一个 switch 代码块，或者嵌套多个异常。这些异常能够使用不同的 exception 类，并返回不同的错误消息.

//4. 重新抛出异常
//用一个try..catch 重新封装, 然后throw给new customException类, 在外catch里输出错误(->errorMessage()).
try
{
    try
    {
        if (strpos($email, "example") !== false) {
            throw new Exception($email);
        }
    } catch (Exception $e) {
        //re-throw exception
        throw new customException($email);
    }
} catch (customException $e) {
    echo $e->errorMessage();
}

//5.设置顶层异常处理器 （Top Level Exception Handler）
//set_exception_handler() 函数可设置处理所有未捕获异常的用户定义函数。
function myException($exception)
{
    echo "<b>Exception:</b> ", $exception->getMessage();
}

set_exception_handler('myException');

throw new Exception('Uncaught Exception occurred');

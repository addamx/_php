<?php
//1. __construct()和__destruct()
//
//对象创建和消亡时被调用
//在继承时可以扩展
class FileRead
{
    protected $handle = null;

    public function __construct()
    {
        $this->handle = fopen('./yellowbook.txt');
    }

    public function __destruct()
    {
        fclose($this->handle);
    }
}

//2. __call()和__callStatic()
//
//一个不可访问方法时会调用这两个方法，后者为静态方法
class MethodTest1
{
    public function __call($name, $arguments)
    {
        echo "Calling object method '$name' " . implode(', ', $arguments) . "\n";
    }

    public static function __callStatic($name, $arguments)
    {
        echo "Calling static method '$name' " . implode(', ', $arguments) . "\n";
    }
}

$obj = new MethodTest1;
$obj->runTest('in object context');
MethodTest1::runTest('in static context');

//3. __get()，__set()，__isset()和__unset()
//
//此时, 储存成员变量的数组应该是 private
class MethodTest
{
    private $data = array();

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }

        return null;
    }

    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    public function __unset($name)
    {
        unset($this->data[$name]);
    }
}

//4. __sleep()和__wakeup()
//
// *serialize() 函数会检查类中是否存在一个魔术方法 __sleep()
// *与之相反，unserialize() 会检查是否存在一个 __wakeup() 方法
// __sleep() 不能返回父类的私有成员的名字。这样做会产生一个 E_NOTICE 级别的错误。可以用 Serializable 接口来替代。
// *__sleep()此功能可以用于清理对象，并返回一个包含对象中所有应被序列化的变量名称的数组。如果该方法未返回任何内容，则 NULL 被序列化，并产生一个 E_NOTICE 级别的错误。
// __wakeup() 经常用在反序列化操作中，例如重新建立数据库连接，或执行其它初始化操作
//例如我们在序列化一个对象时，这个对象有一个数据库链接，想要在反序列化中恢复链接状态，则可以通过重构这两个函数来实现链接的恢复。例子如下：

class Connection
{
    protected $link;
    private $server, $username, $password, $db;

    public function __construct($server, $username, $password, $db)
    {
        $this->server   = $server;
        $this->username = $username;
        $this->password = $password;
        $this->db       = $db;
        $this->connect();
    }

    private function connect()
    {
        $this->link = mysqli_connect($this->server, $this->username, $this->password);
        mysqli_select_db($this->db, $this->link);
    }

    public function __sleep()
    {
        return array('server', 'username', 'password', 'db');
    }

    public function __wakeup()
    {
        $this->connect();
    }
}

//5. __toString()
//
//对象当成字符串时的回应方法。例如使用echo $obj;来输出一个对象

class TestClass
{
    public function __toString()
    {
        return 'TestClass:this is a object';
    }
}

$class = new TestClass();
echo $class . "\n";

//6. __invoke()
//
//把类当做函数那样去调用方式(加括号)(即"$classObject() ")调用一个类对象时的回应方法。

class CallableClass
{
    public function __invoke($x)
    {
        var_dump($x);
    }
}
$obj = new CallableClass;
$obj(5);
var_dump(is_callable($obj)); //此时;类变得callable

//7. __set_state()
//
//自 PHP 5.1.0 起当调用 var_export() 导出类时，此**静态**方法会被调用。
//本方法的唯一参数是一个数组，其中包含按 array('property' => value, ...) 格式排列的类属性
//(var_export)应用: 配合eval.
class A
{
    public $var1;
    public $var2;

    function __set_state($an_array) // As of PHP 5.1.0

    {
        $obj       = new A;
        $obj->var1 = $an_array['var1'];
        $obj->var2 = $an_array['var2'];
        return $obj;
    }
}

$a       = new A;
$a->var1 = 5;
$a->var2 = 'foo';

eval('$b = ' . var_export($a, true) . ';');
// $b = A::__set_state(array(
//    'var1' => 5,
//    'var2' => 'foo',
// ));
var_dump($b);

//输出:
// object(A)#2 (2) {
//   ["var1"]=>
//   int(5)
//   ["var2"]=>
//   string(3) "foo"
// }

//9.__clone()
//
//当对象复制完成时调用。例如在设计模式详解及PHP实现：单例模式一文中提到的单例模式实现方式，利用这个函数来防止对象被克隆。

class Singleton
{
    private static $_instance = null;

    // 私有构造方法
    function __construct()
    {}

    function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new Singleton();
        }
        return self::$_instance;
    }

    // 防止克隆实例
    function __clone()
    {
        die('Clone is not allowed.' . E_USER_ERROR);
    }
}

echo __FILE__ . "\n" . __DIR__ . "\n" . __LINE__ . "\n";
// F:\Dropbox\WorkPlace\WWW\PHP\Magic_methods.php on line 205
// F:\Dropbox\WorkPlace\WWW\PHP\Magic_methods.php
// F:\Dropbox\WorkPlace\WWW\PHP
// 205
//
class NewClass
{
    public static function printx()
    {
        echo __CLASS__ . "\n" . __FUNCTION__ . "\n" . __METHOD__; //___METHOD__
    }
}

NewClass::printx();
//NewClass
// printx
// NewClass::printx

/**
 * 10. __toString*()当我们试图打印(echo)类时执行
 */
class ToString
{
    public function __toString()
    {
        return __CLASS__;
    }
}

$a = new ToString();
echo $a; //执行__toString()方法

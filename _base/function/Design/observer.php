<?php
/*
************[观察者模式]*************
 */


/**
 * 定义一个时间抽象类, 定义观察的 私有的观察者集(数组)属性, 注册方法(限定对象) 和 通知方法
 */
abstract class EventGenerator {
	private $observers = array();
	function addObserver(Observer $observer) {
		$this->observers[] = $observer;
	}
	function notify() {
		foreach($this->observers as $observer) {
			$observer->update();
		}
	}
}

//具体的事件类: 定义监控触发方法(它执行了初始化操作, 同时进行[可以设定条件]通知)
class Event extends EventGenerator {
    
    function trigger() {
    	echo "Event<br/>\n";
    	$this->notify();
    }
}

//观察者接口: 定义观察者共有的方法(由被观察者通知调用)
interface Observer
{
	function update($event_info = null);
}

//具体观察者类, 一定要定义update方法(事件/被观察者 notify() 里调用)
class Observer1 implements Observer
{
	function update($event_info = null) {
		echo "逻辑1<br/>\n";
	}
}

//实例化事件
$event = new Event;

//为事件添加观察者
$event->addObserver(new Observer1);

//点火
$event->trigger();


////////////////////////////////////
 
/**
 * 观察者模式 2010-09-23 sz
 * @author phppan.p#gmail.com  http://www.phppan.com                                             
 * 哥学社成员（http://www.blog-brother.com/）
 * @package design pattern
 */
 
/**
 * 抽象主题角色
 */
interface Subject {
 
    /**
     * 增加一个新的观察者对象
     * @param Observer $observer
     */
    public function attach(Observer $observer);
 
    /**
     * 删除一个已注册过的观察者对象
     * @param Observer $observer
     */
    public function detach(Observer $observer);
 
    /**
     * 通知所有注册过的观察者对象
     */
    public function notifyObservers();
}
 
/**
 * 具体主题角色
 */
class ConcreteSubject implements Subject {
 
    private $_observers;
 
    public function __construct() {
        $this->_observers = array();
    }
 
    /**
     * 增加一个新的观察者对象
     * @param Observer $observer
     */
    public function attach(Observer $observer) {
        return array_push($this->_observers, $observer);
    }
 
    /**
     * 删除一个已注册过的观察者对象
     * @param Observer $observer
     */
    public function detach(Observer $observer) {
        $index = array_search($observer, $this->_observers);
        if ($index === FALSE || ! array_key_exists($index, $this->_observers)) {
            return FALSE;
        }
 
        unset($this->_observers[$index]);
        return TRUE;
    }
 
    /**
     * 通知所有注册过的观察者对象
     */
    public function notifyObservers() {
        if (!is_array($this->_observers)) {
            return FALSE;
        }
 
        foreach ($this->_observers as $observer) {
            $observer->update();
        }
 
        return TRUE;
    }
 
}
 
/**
 * 抽象观察者角色
 */
interface Observer {
 
    /**
     * 更新方法
     */
    public function update();
}
 
class ConcreteObserver implements Observer {
 
    /**
     * 观察者的名称
     * @var <type>
     */
    private $_name;
 
    public function __construct($name) {
        $this->_name = $name;
    }
 
    /**
     * 更新方法
     */
    public function update() {
        echo 'Observer', $this->_name, ' has notified.<br />';
    }
 
}
 
/**
 * 客户端
 */
class Client {
 
    /**
     * Main program.
     */
    public static function main() {
        $subject = new ConcreteSubject();
 
        /* 添加第一个观察者 */
        $observer1 = new ConcreteObserver('Martin');
        $subject->attach($observer1);
 
        echo '<br /> The First notify:<br />';
        $subject->notifyObservers();
 
        /* 添加第二个观察者 */
        $observer2 = new ConcreteObserver('phppan');
        $subject->attach($observer2);
 
        echo '<br /> The Second notify:<br />';
        $subject->notifyObservers();
 
        /* 删除第一个观察者 */
        $subject->detach($observer1);
 
        echo '<br /> The Third notify:<br />';
        $subject->notifyObservers();
    }
 
}
 
Client::main();
?>
<?php
/**
 * Created by PhpStorm.
 * User: addams
 * Date: 2017/1/23
 * Time: 16:13
 */

namespace frontend\components;

use yii\base\Behavior;

class  TestBehavior extends Behavior
{
    //在行为内部可以通过 yii\base\Behavior::owner 属性访问行为已附加的组件。
    public $prop1;
    private $_prop2;

    public function getProp2()
    {
        return $this->_prop2;
    }

    public  function setProp2($value)
    {
        $this->_prop2 = $value;
    }

    public function foo()
    {
        echo __METHOD__;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: addams
 * Date: 2017/1/18
 * Time: 13:19
 */

namespace frontend\components;


class ActionTimeFilter extends \yii\base\ActionFilter
{
    private $_startTime;

    public function beforeAction($action)
    {
        echo '<h1>Time-Start</h1>';
        $this->_startTime = microtime(true);
        return parent::beforeAction($action);
    }

    public function afterAction($action, $result)
    {
        $time = microtime(true) - $this->_startTime;
        \Yii::trace("Action '{$action->uniqueId}' spent $time second.");
        return parent::afterAction($action, $result);
    }
}
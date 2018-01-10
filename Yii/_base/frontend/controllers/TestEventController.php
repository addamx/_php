<?php
/**
 * Created by PhpStorm.
 * User: addams
 * Date: 2017/1/15
 * Time: 23:09
 */
namespace frontend\controllers;

class TestEventController extends \yii\web\Controller
{
    public function actionIndex()
    {
        \yii::$app->on(\yii\base\Application::EVENT_AFTER_REQUEST, function ($event) {
            echo '<h1>EV:after_request</h1>';
            if (true) {
                //$event->isValid = false;    //停止后续动作
                //var_dump($event);
            }
        });
        echo 'index<br/>';
    }

    public function beforeAction($action)
    {
        echo "<h1>beforeAction</h1>";
        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }

    public function afterAction($action, $result)
    {
        echo "<h1>afterAction</h1>";
        //var_dump($action);
        //var_dump($result);
        return parent::afterAction($action, $result); // TODO: Change the autogenerated stub
    }
}
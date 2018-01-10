<?php
/**
 * Created by PhpStorm.
 * User: addams
 * Date: 2017/1/17
 * Time: 22:12
 */
namespace frontend\modules\forum\controllers;

use yii\web\Controller;

class TestController extends Controller
{
    public function actionIndex()
    {
        // 获取ID为 "forum" 的模块
        $module1 = \Yii::$app->getModule('forum');
        // 获取处理当前请求控制器所属的模块
        $module2 = \Yii::$app->controller->module;
        var_dump($module2->id);

        var_dump(\yii::getAlias('@app'));
    }
}
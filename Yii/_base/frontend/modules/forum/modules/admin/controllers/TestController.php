<?php
/**
 * Created by PhpStorm.
 * User: addams
 * Date: 2017/1/17
 * Time: 22:12
 */
namespace frontend\modules\forum\modules\admin\controllers;

use yii\web\Controller;

class TestController extends Controller
{
    public function actionIndex()
    {
        $module2 = \Yii::$app->controller->module;
        var_dump($module2->id);
    }
}
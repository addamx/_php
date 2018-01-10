<?php

/**
 * Created by PhpStorm.
 * User: addams
 * Date: 2017/2/3
 * Time: 11:42
 */
namespace frontend\modules\atheme\controllers;

class TestController extends \yii\web\controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
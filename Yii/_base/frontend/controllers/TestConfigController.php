<?php
namespace frontend\controllers;

use common\models\Test;
use common\models\Posts;
use common\models\Tags;

class TestConfigController extends \yii\web\Controller
{
    public function actionIndex()
    {
        var_dump(\yii::$app->components['db']);         //获取components配置
        var_dump(\yii::$app->getComponents()['db']);    //获取components配置, 同上
        //var_dump(\yii::$app->getComponents(false));     //获取已经加载的component
        echo \yii::$app->name . '<br/>';
        echo \yii::$app->timeZone . '<br/>';
        echo \yii::$app->defaultRoute . '<br/>';
        var_dump(\yii::$app->extensions);
        var_dump(\yii::$app->vendorPath);
    }
}
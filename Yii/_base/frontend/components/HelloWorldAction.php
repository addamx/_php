<?php
/**
 * Created by PhpStorm.
 * User: addams
 * Date: 2017/1/16
 * Time: 8:59
 */
namespace frontend\components;

use yii\base\Action;

class HelloWorldAction extends Action
{
    public function run()
    {
        return "Hello World!";
    }
}
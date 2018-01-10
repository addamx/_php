<?php
/**
 * Created by PhpStorm.
 * User: addams
 * Date: 2017/1/17
 * Time: 10:12
 */
namespace frontend\modules\forum\modules\admin;

class Module extends \yii\base\Module
{
    public function init()
    {
        parent::init();

        $this->params['foo'] = 'bar';

        echo "Module-forum-admin:init";
    }
}
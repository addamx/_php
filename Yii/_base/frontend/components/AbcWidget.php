<?php
/**
 * Created by PhpStorm.
 * User: addams
 * Date: 2017/1/16
 * Time: 18:02
 */
namespace frontend\components;

use yii\base\Widget;
use yii\helpers\Html;

class AbcWidget extends Widget
{
    public $items = ['a', 'b', 'c'];
    public $message;

    public function init()
    {
        parent::init();
        if ($this->message === null) {
            $this->message = 'Hello World';
        }
        $this->registerTranslations();      // 配置widget的翻译功能
        ob_start();     //使用begin(), end()
    }

    public function run()
    {
        // 渲染一个名为 "AbcWidget" 的视图
        //return $this->render('abcwidget', ['items' => $this->items,]);    //3. 将内容放入一个视图文件components/views/abcwidget.php

        //return Html::encode($this->message);  //1. echo AbcWidget::widget(['message' => 'Hello World'])

        $content = ob_get_clean();
        return Html::encode($content);      //2. AbcWidget::begin() ~ AbcWidget::end()
    }

    public function registerTranslations()
    {
        $i18n = \Yii::$app->i18n;
        $i18n->translations['widgets/menu/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@app/widgets/menu/messages',
            'fileMap' => [
                'widgets/menu/messages' => 'messages.php',
            ],
        ];
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        return \Yii::t('widgets/menu/' . $category, $message, $params, $language);
    }

}
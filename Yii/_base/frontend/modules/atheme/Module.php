<?php
/**
 * Created by PhpStorm.
 * User: addams
 * Date: 2017/1/17
 * Time: 10:12
 */
namespace frontend\modules\atheme;

class Module extends \yii\base\Module
{
    public function init()
    {
        parent::init();
        $config = [
            'components' => [
                'view' => [
                    'class' => \yii\web\view::className(),
                    'theme' => [
                        'basePath' => '@app/modules/atheme/themes/basic',  //指定包含主题资源（CSS, JS, images, 等等）的基准目录。
                        'baseUrl' => '@web/theme/basic',    //指定主题资源的基准UR
                        'pathMap' => [
                            '@app/modules/atheme/views' => '@app/modules/atheme/themes/basic', //指定视图文件的替换规则
                            '@app/modules/atheme/widgets' => '@app/modules/atheme/themes/basic/widgets',
                        ]
                    ]
                ]
            ],
        ];

        \Yii::configure(\Yii::$app, $config);   //模块中要更新的config, 有些需要全局地更新, 如view的theme, 直接更新$this是不起效的.
        $this->registerTranslations();  //注册配置翻译组件内的模块部分
    }

    public function registerTranslations()
    {
        \Yii::$app->i18n->translations['modules/users/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            //'basePath' => '@app/modules/users/messages',
            'fileMap' => [
                //'modules/users/validation' => 'validation.php',
                //'modules/users/form' => 'form.php',
            ],
        ];
    }

    // 简化模块内的翻译
    public static function t($category, $message, $params = [], $language = null)
    {
        return \Yii::t('modules/users/' . $category, $message, $params, $language);
    }

}
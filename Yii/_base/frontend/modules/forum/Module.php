<?php
/**
 * Created by PhpStorm.
 * User: addams
 * Date: 2017/1/17
 * Time: 10:12
 */
namespace frontend\modules\forum;

use yii\base\BootstrapInterface;

class Module extends \yii\base\Module implements BootstrapInterface //使用了BootstrapInterface接口才能使bootstrap()有效
{
    public function init()
    {
        parent::init();

        $this->params['foo'] = 'bar';
        //$this->components = [];
        // ...  其他初始化代码 ...
        // 从config.php加载配置来初始化模块
        //\Yii::configure($this, require(__DIR__ . '/config.php'));

        $this->modules = [          //嵌套模块
            'admin' => [
                // 此处应考虑使用一个更短的命名空间
                'class' => 'frontend\modules\forum\modules\admin\Module',
            ],
        ];
    }

    public function bootstrap($app)
    {
        $app->getUrlManager()->addRules([
            [
                'class' => 'yii\web\UrlRule',
                'pattern' => 'forum/tests',
                'route' => 'forum/test/index',
            ],
        ], false);
        //var_dump(\yii\helpers\ArrayHelper::toArray($app->getUrlManager()));
    }
}
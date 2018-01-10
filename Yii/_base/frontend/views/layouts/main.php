<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerMetaTag(['name' => 'keywords', 'content' => 'yii, framework, php']); ?><!--注册meta-->
    <?php $this->registerLinkTag([              //注册链接标签
        'title' => 'Live News for Yii',
        'rel' => 'alternate',
        'type' => 'application/rss+xml',
        'href' => 'http://www.yiiframework.com/rss.xml/',
    ]);?>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?><!--引入css和js-->
    <style type="text/css">
        body > .wrap > .container {padding-top: 60px;}
    </style>
</head>
<body>
<?php $this->beginBody() ?><!--局的开始处调用， 它触发表明页面开始的 yii\base\View::EVENT_BEGIN_PAGE 事件。-->

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'My Company',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse',
        ],
    ]);
    $menuItems = [
        ['label' => '博客', 'url' => ['/post/index']],
        ['label' => '标签', 'url' => ['/tag/index']],
        ['label' => 'Home', 'url' => ['/site/index']],
        ['label' => 'About', 'url' => ['/site/about']],
        ['label' => 'Contact', 'url' => ['/site/contact']],
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Logout (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>

        <?= $content ?><!--这里开始显示视图内容;  在布局中可访问两个预定义变量：$this 和 $content, 其他数据:拉取 或者 共享数据params-->
        <!--嵌套布 -->
        <?php //$this->beginContent('@app/views/layouts/base.php'); ?>
        <!--...child layout content here...-->
        <?php //$this->endContent(); ?>

        <!--数据块block-->
        <div style="border:1px solid red">
        <?php if (isset($this->blocks['block1'])): ?>
            <?= $this->blocks['block1'] ?>
        <?php else: ?>
            ... default content for block1 ...
        <?php endif; ?>
        </div>

        <div style="border:1px solid #1b6d85">
            <?php if (isset($this->blocks['block2'])): ?>
                <?= $this->blocks['block2'] ?>
            <?php else: ?>
                ... default content for block2 ...
            <?php endif; ?>
        </div>

    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>
        <p class="pull-right"><?= Yii::powered() ?></p><!--Returns an HTML hyperlink that can be displayed on your Web page showing "Powered by Yii Framework" information-->
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

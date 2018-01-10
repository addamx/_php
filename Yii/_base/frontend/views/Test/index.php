<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\HtmlPurifier;
use yii\jui\DatePicker;
use frontend\components\AbcWidget;
use frontend\assets\AppAsset;
use yii\helpers\Url;

$this->params['breadcrumbs'][] = 'Test';    //视图间共享数据
$this->title = '<strong>test</strong>';

AppAsset::register($this);  // 注册资源包

//通过[依赖注入]设置默认widget的默认值
\Yii::$container->set('yii\widgets\LinkPager', ['maxButtonCount' => 5]);
?>

<!--Html::encode()-->
<h1>Html::encode ~ <?= Html::encode($this->title) ?></h1>
<!--yii\helpers\HtmlPurifier::process() 显示html代码-->
<p>tmlPurifier::process ~ <?= HtmlPurifier::process($this->title) ?></p>
<!--$this->context可获取到控制器ID， 可让你在report视图中获取控制器的任意属性或方法-->
<p>拉取数据 ~ <?= $this->context->id; ?></p>

<!--$form = ActiveForm::begin()-->
<?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'name') ?>
    <?= $form->field($model, 'email') ?>
    <?= Html::submitButton('Login') ?>
<?php ActiveForm::end(); ?>



<!--数据块block-->
<?php $this->beginBlock('block1'); ?>

<?= DatePicker::widget([
    'name' => 'date',       //name属性
    //'model' => $model,
    'attribute' => 'from_date',
    'language' => 'en',
    'clientOptions' => [
        'dateFormat' => 'yy-mm-dd',
    ],
]) ?>
<?php //echo AbcWidget::widget(['message' => '[This message from AbcWiget.]']) ?>
<?php AbcWidget::begin(); ?>
<p>This content comes from AbcWidget<tag>'s .</p>
<?php AbcWidget::end(); ?>

    <p><a href="<?= url::to(['post/item', 'id' => '10', '#' => 'test'], true, 'https');//ture=>创建绝对链接 ?>">Test url Link</a></p>
    <pre>Url::remember();
    echo Url::previous();</pre>
    <?php
    //url
    // remember the currently requested URL and retrieve it back in later requests
    Url::remember();
    echo Url::previous();

    //request
    $headers = Yii::$app->request->headers;
    if ($headers->has('User-Agent')) { echo '<p>User-Agent:->  '. $headers->get('User-Agent') .'</p>'; }
    print_r(Yii::$app->request->acceptableLanguages);

    //Flash-session
    $session = Yii::$app->session;
    $session->setFlash('alerts', 'a new alert');
        //添加内容, getFlash将生成数组
//    $session->addFlash('alerts', 'You have successfully deleted your post.');
//    $session->addFlash('alerts', 'You have successfully added a new friend.');
//    $session->addFlash('alerts', 'You are promoted.');
    echo \yii\bootstrap\Alert::widget([
        'options' => ['class' => 'alert-info'],
        'body' => Yii::$app->session->getFlash('alerts'),
    ]);

    ?>


<?php $this->endBlock(); ?>



<?php $this->beginBlock('block2'); ?>
<?php
$form = \yii\bootstrap\ActiveForm::begin([
'id' => 'login-form',
'options' => ['class' => 'form-horizontal'],
]) ?>
<?= $form->field($model, 'name') ?>
<?= $form->field($model, 'email')->textInput()->hint('email address')->label('Email') ?>
<?=
$form->field($model, 'name')->dropdownList(
    \common\models\test::find()->select(['name', 'id'])->indexBy('id')->column(),
    ['prompt'=>'Select name']
)->label('Name dropdown list');
?>

<div class="form-group">
    <div class="col-lg-offset-1 col-lg-11">
        <?= Html::submitButton('Login', ['class' => 'btn btn-primary']) ?>
    </div>
</div>
<?php \yii\bootstrap\ActiveForm::end() ?>


<?php
\yii\widgets\Pjax::begin([
    // Pjax options
]);
$form = \yii\widgets\ActiveForm::begin([
    'options' => ['data' => ['pjax' => true]],
    // more ActiveForm options
]);

// ActiveForm content

\yii\widgets\ActiveForm::end();
\yii\widgets\Pjax::end();


?>


<?php $this->endBlock(); ?>A



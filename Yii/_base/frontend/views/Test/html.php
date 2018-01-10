<?php
/**
 * Created by PhpStorm.
 * User: addams
 * Date: 2017/2/8
 * Time: 10:07
 */

use yii\helpers\html;
use yii\helpers\ArrayHelper;

$type = 'success';
$user = ['name' => '<h4>addams</h4>'];
?>
<?= Html::cssFile('@web/css/ie5.css', ['condition' => 'IE 5']) ?>
<?= Html::jsFile('@web/js/main.js') ?>

<!--------------------------------------------------->

<?= Html::tag('p', Html::encode($user['name']), ['class' => 'username']) ?>

<!-------------------添加/移除class-------------------------------->

<?php
$options = ['class' => 'btn btn-default'];

if ($type === 'success') {
Html::removeCssClass($options, 'btn-default');
Html::addCssClass($options, 'btn-success');     //addCssClass() prevents duplicating classes
}

echo Html::tag('div', 'test tag', $options);
?>

<!--------------------添加/移除style------------------------------->

<?php
$options = ['style' => ['width' => '100px', 'height' => '100px']];

// gives style="width: 100px; height: 200px; position: absolute;"
Html::addCssStyle($options, 'height: 200px; position: absolute;');

// gives style="position: absolute;"
Html::removeCssStyle($options, ['width', 'height']);
?>

<!----------------------编译/解码html内容----------------------------->

<?php
$userName = Html::encode($user['name']);
echo '<br/>'.$userName;

$decodedUserName = Html::decode($userName);
echo '<br/>'.$decodedUserName;
?>

<!----------------------表单----------------------------->


<?= Html::beginForm(['order/update', 'id' => 1], 'post', ['enctype' => 'multipart/form-data']) ?>

<?= Html::input('text', 'name', $user['name'], ['class' => 'username'])  //type, input name, input value, options ?>
<?= Html::activeInput('text', $model, 'name', ['class' => 'username']) //type, model, model attribute name, options ?>

<?= Html::radio('name', true, ['label' => 'I agree']); ?>
<?= Html::activeRadio($model, 'name', ['class' => 'agreement']) ?>

<?= Html::checkbox('name', true, ['label' => 'I agree']); ?>
<?= Html::activeCheckbox($model, 'name', ['class' => 'agreement']) ?>


<?= Html::dropDownList('list', '1',/*current ID*/ ArrayHelper::map($array, 'id', 'name')) ?>
<?= Html::activeDropDownList($model, 'id', ArrayHelper::map($array, 'id', 'name')) ?>

<?= Html::listBox('list', '2', ArrayHelper::map($array, 'id', 'name')) ?>
<?= Html::activeListBox($model, 'id', ArrayHelper::map($array, 'id', 'name')) ?>

<?= Html::checkboxList('name', [2, 3], ArrayHelper::map($array, 'id', 'name')) ?>
<?= Html::activeCheckboxList($model, 'name', ArrayHelper::map($array, 'id', 'name')) ?>

<?= Html::radioList('name', 4, ArrayHelper::map($array, 'id', 'name')) ?>
<?= Html::activeRadioList($model, 'name', ArrayHelper::map($array, 'id', 'name')) ?>

<?= Html::label('User name', 'name', ['class' => 'label username', 'style' => 'background:blue']) ?>
<?= Html::activeLabel($model, 'name', ['class' => 'label username', 'style' => 'background:blue']) ?>

<?= Html::errorSummary($model, ['class' => 'errors']) ?>
<?= Html::error($model, 'title', ['class' => 'error']) ?>

<?= Html::button('Press me!', ['class' => 'teaser']) ?>
<?= Html::submitButton('Submit', ['class' => 'submit']) ?>
<?= Html::resetButton('Reset', ['class' => 'reset']) ?>

<?= Html::getAttributeName('dates[0]'); ?>
<?= Html::getAttributeValue($model, '[0]name'); ?>
<?= Html::endForm() ?>


<!--------------------------超链接----------------------------------->
<?= Html::a('Profile', ['user/view', 'id' => '123'], ['class' => 'profile-link']) ?>
<?= Html::mailto('Contact us', 'admin@example.com') ?>
<?= Html::img('@web/images/logo.png', ['alt' => 'My logo']) ?>

<!--------------------------列表----------------------------------------->
<?php /*= Html::ul($array, ['item' => function($item, $index) {
    return Html::tag(
        'li',
        $this->render('post', ['item' => $item]),
        ['class' => 'post']
    );
}]) */ ?>




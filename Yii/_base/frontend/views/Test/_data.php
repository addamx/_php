<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
?>
<div class="<?= $class ?>">
    <h2><?= Html::encode($model->title) ?></h2>

    <?= HtmlPurifier::process($model->name) ?>
</div>
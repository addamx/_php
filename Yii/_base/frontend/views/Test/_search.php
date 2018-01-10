<?php
/**
 * Created by PhpStorm.
 * User: addams
 * Date: 2017/1/26
 * Time: 14:48
 */

use yii\helpers\html;
use yii\widgets\ActiveForm;
?>
<div class="test-search">
    <?php $form = ActiveForm::begin([
    'action' => ['data1'],
    'method' => 'get',
    'id' => 'test-search'
]); ?>

<?= $form->field($model, 'title') ?>
<?= $form->field($model, 'name') ?>

<div class="form-group">
    <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
    <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
</div>
    <script type="text/javascript">
        $('.test-search button[type="reset"]').click(function () {
            var _form = $('#test-search .form-control');
            _form.not(':button, :submit, :reset, :hidden').removeAttr('checked').removeAttr('selected');
            if (_form.val()) {
                _form.val('').attr('value', '');
                $('#test-search').submit();
            } else {
                _form.val('');
            }
        });
    </script>

<?php ActiveForm::end(); ?>
</div>
<?php
/**
 * Created by PhpStorm.
 * User: addams
 * Date: 2017/1/26
 * Time: 12:16
 */

//修改sort, page的get中的变量名.  当有多个网格组建时, 这样可以避免互相影响.
$dataProvider->pagination->pageParam = 'data-page';
$dataProvider->sort->sortParam = 'data-sort';

\yii\widgets\Pjax::begin([
    // PJax options
]);

echo \yii\grid\GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
    ],
]);

\yii\widgets\Pjax::end();
?>


<?= $this->render('_search', ['model' => $searchModel]); ?>

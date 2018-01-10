<?php
/**
 * Created by PhpStorm.
 * User: addams
 * Date: 2017/1/26
 * Time: 16:32
 */

echo \yii\grid\GridView::widget([
    'dataProvider' => $dataProvider,
    //'filterModel' => $searchModel,
    'columns' => [
    ],
]);

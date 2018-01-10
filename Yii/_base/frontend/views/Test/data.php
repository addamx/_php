<?php

echo \yii\widgets\DetailView::widget([
    'model' => $model,
    'attributes' => [
        'title',               // title attribute (in plain text)
        'email:html',    // description attribute formatted as HTML
        [                      // the owner name of the model
            'label' => 'name',
            'value' => $model->name,
        ],
        //'created_at:datetime', // creation date formatted as datetime
    ],
]);


echo \yii\widgets\ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => '_data',  //调用view文件渲染
    'viewParams' => [   //传输实参到view文件
        'class' => 'test',
        // ...
    ],
]);




echo \yii\grid\GridView::widget([
    'dataProvider' => $dataProvider1,
    'filterModel' => $searchModel,
    'columns' => [
        //序号列
        ['class' => 'yii\grid\SerialColumn'],   //显示序号, 不受排序影响

        //DataColumn
        'id',
        'title',
        [
            'class' => 'yii\grid\DataColumn', //由于是默认类型，可以省略
            'attribute' => 'name',  //排序功能
            'value' => function ($data) {
                return 'new'.$data->name; // 如果是数组数据则为 $data['name'] ，例如，使用 SqlDataProvider 的情形。
            },
            'label' =>'name',
        ],
        [
            'attribute' => 'email',
            'format' => 'text',     //[默认text], 值的配置参考数据格式化输出(\yii\i18n\Formatter)
            'label' => 'Email Address',
            //visible' => false,   //是否显示本列内容
            //'header' => 'Email-header',      //显示头部内容 (覆盖了label属性)
            //'footer' => 'Email-footer',//尾部行设置内容。
            'content' => function ($model, $key, $index, $column) {
                return $key . "~" . $index . "~" . $model->email .  "[" . $column->label  . "]";
            },
        ],
        'name:text:Name',       //快捷格式化串, 表示列名是name, 格式是text, 显示标签是Name

        //动作列
        [
            'class' => 'yii\grid\ActionColumn',  //动作列
            'controller' => 'Test',     //执行这些动作的控制器ID, 没设置则使用当前控制器
            'template' => '{view} {update} {delete}', //启用动作和调整位置
            'buttons' => [                              //按钮的html
                'view' => function ($url, $model, $key) {
                    return '<a>' . "abc" . '</a>';
                },
                'update' => function ($url, $model, $key) {
                    return '<a href="'.$url .'">name>2</a>';
                },
            ],
            'urlCreator' => function ($action, $model, $key, $index) {   //url链接
                return "http://example.com/" . $action . "?id=" . $key;
            },

            //可见性控制
            'visibleButtons' => [
                'update' => function ($model, $key, $index) {
                    return mb_strlen($model->name) > 2;
                },
                //or pass a boolean value
                'delete' => true, //\Yii::$app->user->can('delete'),
            ],

        ],

        //复选框列
            //被选择的行可通过以下js获得
            //var keys = $('#grid').yiiGridView('getSelectedRows');
        [
            'class' => 'yii\grid\CheckboxColumn',

        ],

    ],

]);



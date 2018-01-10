<?php
/**
 * Created by PhpStorm.
 * User: addams
 * Date: 2017/2/6
 * Time: 9:42
 */

$dependency = [
    'class' => 'yii\caching\DbDependency',
    'sql' => 'SELECT MAX(updated_at) FROM posts',
];


if ($this->beginCache('temp_id', [
        'duration' => 5,
        //'cacheControlHeader' => 'public, max-age=3600',        //Cache-Control 头
        'dependency' => $dependency,
        'variations' => [\Yii::$app->language],     //variations 选项来指定变化， 该选项的值应该是一个标量，每个标量代表不同的变化系数。 例如设置缓存根据当前语言而变化而缓存多个版本
        'enabled' => \Yii::$app->request->isGet,    // [开关], 例如，一个显示表单的页面，可能只 需要在初次请求时缓存表单（通过 GET 请求）。随后请求所显示（通过 POST 请求） 的表单不该使用缓存
    ])) {

    echo date('s');

    //缓存嵌套
    //内层缓存和外层缓存使用 不同的过期时间。甚至当外层缓存的数据过期失效了，内层缓存仍然 可能提供有效的片段缓存数据。但是，反之则不然。如果外层片段缓存 没有过期而被视为有效，此时即使内层片段缓存已经失效
    if ($this->beginCache('temp_id2')) {
        //......
        $this->endCache();
    }

    //echo $this->renderDynamic('return \Yii::$app->user->identity->name;');   //动态内容, 不该被缓存


    $this->endCache();
}
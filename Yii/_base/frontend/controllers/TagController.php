<?php

namespace frontend\controllers;

use common\models\Tags;

class TagController extends \yii\web\Controller
{
    public function actionIndex()
    {
    	$tags = Tags::find()->all();
        return $this->render('index', [
        	'tags' => $tags
    	]);
    }

}

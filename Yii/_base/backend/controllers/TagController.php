<?php

namespace backend\controllers;

use Yii;
use common\models\Tags;
use backend\models\TagSearch;
use backend\models\TagForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\Url;

/**
 * TagController implements the CRUD actions for Tags model.
 */
class TagController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Tags models.
     * @return mixed
     */

    public function actionIndex()
    {
        $searchModel = new TagSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Tags model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Tags model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Tags();  //新建 Tags 模型 
        $uploadImg =new TagForm(); //新建 TagForm 模型 
        $request = Yii::$app->request;  
        if ($this->save($request,$model,$uploadImg)) {  // 调用自定义函数 save 保存表单信息和上传图片。
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'uploadImg'=>$uploadImg,    // 把 TagForm 模型上传到前台 
            ]);
        }
    }


    /**
     * Updates an existing Tags model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $uploadImg = new TagForm(); //新建 TagForm 模型 
        $request = Yii::$app->request;
        echo 'update';
        if ($this->save($request,$model,$uploadImg)) {  // 调用自定义函数 save 保存表单信息和上传图片。
            return $this->redirect(['view', 'id' => $model->id]);

        }else {
            return $this->render('update', [
                'model' => $model,
                'uploadImg'=>$uploadImg   // 把 TagForm 模型上传到前台 
            ]);
        }
    }

    /**
     * Deletes an existing Tags model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Tags model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Tags the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tags::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function save($request,$model,$uploadImg)    // 自定义函数
    {
        if ($request->isPost) {     
            $tag = $request->post('Tags');
            $model->tag = $tag['tag'];
            $model->meta_description = $tag['meta_description'];
            if ($uploadImg->tag_img= UploadedFile::getInstance($uploadImg, 'tag_img')) {   // 调用  UploadedFile 获得上传文件实例传递给   $uploadImg->tag_img
                if($uploadPath = $uploadImg->upload()) {	   //上传文件，返回文件路径
                	$model->tag_img = $uploadPath;
                }
            }
            if ($model->save()) {
                return true;
            }else{
                return false;
            }
        } else {
        	return false;
        }
    }
}
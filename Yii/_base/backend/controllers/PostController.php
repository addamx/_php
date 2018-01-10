<?php

namespace backend\controllers;

use Yii;
use common\models\Posts;
use common\models\Tags;
use common\models\PostTagPivot;
use backend\models\PostsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PostController implements the CRUD actions for Posts model.
 */
class PostController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actionSeeder()
    {
        $faker = \Faker\Factory::create('zh_CN');
        $a = 0;
        for ($i = 0; $i < 20; $i++) {
            $posts = new Posts();
            $posts->title = $faker->text($maxNbChars = 20);
            $posts->author = $faker->name;
            $posts->content = $faker->text($maxNbChars = 3000);
            if ($posts->insert()) {
                $a+=1;
            } else {

            }
        }
        echo "添加" . $a . "条数据";
    }

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
     * Lists all Posts models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PostsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Posts model.
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
     * Creates a new Posts model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()  //创建post时, 查找所有标签, 提供选择
    {
        $model = new Posts();
        $tags = Tags::find()->all();
        $request = Yii::$app->request;
        $checkTags = "";    //已选择的标签, 由于新建标签所以 $checkTags 为空
        if ($model->load(Yii::$app->request->post()) && $model->save() && $this->saveTag($model->attributes['id'], $request->post('tags'))) {   //新建 post 时获得 post 的id，然后通过自定义方法 saveTag保存 tags
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'tags' => $tags, //传递所有标签到前台
                 'checkTags' => $checkTags, //传递已选择标签到前台
            ]);
        }
    }

    /**
     * Updates an existing Posts model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = Posts::findOne($id);
        $checkTags = $model->tags;   //根据post的id查找已选择的tag
        $tags = Tags::find()->all();
        $request = Yii::$app->request;
        $checkTagList = array();
        foreach ($checkTags as $checkTag) {
            $checkTagList[] = $checkTag->id;   ////把查找到的 $checkTag 提取出已选择 tag 的 id。
        }
        if ($model->load(Yii::$app->request->post()) && $model->save() && $this->saveTag($id, $request->post('tags'))) {
            //修改 post 时获得 post 的id，然后通过自定义方法 saveTag 保存 tags
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'tags' => $tags,
                'checkTags' => $checkTagList,
            ]);
        }
    }

    public function saveTag($id, $checkTag)
    {
        PostTagPivot::deleteAll("post_id = $id");
        if ($checkTag) {
            foreach ($checkTag as $key => $value) {
                $PostTagPivot = new PostTagPivot();
                $PostTagPivot->post_id = $id;
                $PostTagPivot->tag_id = $value;
                $PostTagPivot->insert();
            }
         }
         return true;
    }

    /**
     * Deletes an existing Posts model.
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
     * Finds the Posts model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Posts the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Posts::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

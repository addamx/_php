<?php
namespace frontend\controllers;
use common\models\Posts;
use yii\data\Pagination;
use common\models\Tags;
class PostController extends \yii\web\Controller
{

    public function actionSeeder()
    {
        $faker = \Faker\Factory::create('zh_CN');
        $a=0;
        for ($i=0; $i <20 ; $i++) {
            $posts = new Posts();
            $posts->title = $faker->text($maxNbChars = 20);
            $posts->author = $faker->name;
            $posts->content = $faker->text($maxNbChars = 3000);
            if ($posts->insert()) {
                 $a+=1;
            }else{
            }
        }
        echo "添加".$a."条数据";
    }

    public function actionIndex($id='')
    {
        if (!empty($id)) {
            $tags = Tags::findOne($id);
            $posts = $tags->getPosts()->orderBy(['id' => SORT_DESC]);   //根据标签Id查找所有Post
        } else {
            $posts = Posts::find()->where(['status' => 1])->orderBy(['id' => SORT_DESC]);
        }
    	$countuPosts = clone $posts ;
        $pages = new Pagination(['totalCount' => $countuPosts->count(),'pageSize'=>5]);
        $models = $posts->offset($pages->offset)
        ->limit($pages->limit)
        ->all();
       return $this->render('index', [
         'models' => $models,
         'pages' => $pages,
       ]);
    }

    public function actionItem($id='')
    {
    	$post = Posts::findOne($id);
        $tags = $post->tags;
        return $this->render('item',['post' => $post,
            'tags' => $tags
        ]);
    }
}

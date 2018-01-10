<?php
/**
 * Created by PhpStorm.
 * User: addams
 * Date: 2017/2/6
 * Time: 11:02
 */

namespace frontend\controllers;

use \Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;


/*
yii\rest\Controller 和 yii\rest\ActiveController 提供以下功能， 一些功能在后续章节详细描述：
- HTTP 方法验证;
- 内容协商和数据格式化;
- 认证;
- 频率限制.

yii\rest\ActiveController 额外提供一下功能:

- 一系列常用操作: index, view, create, update, delete, options;
- 对操作和资源进行用户认证.
 * */
class ApiController extends ActiveController
{
    //如果控制器继承yii\rest\ActiveController，应设置 $modelClass
    public $modelClass = 'common\models\Test';
    //将返回的模型数据保函在'items'字段里
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

/*yii\rest\ActiveController 默认提供一下操作:

yii\rest\IndexAction: 按页列出资源;
yii\rest\ViewAction: 返回指定资源的详情;
yii\rest\CreateAction: 创建新的资源;
yii\rest\UpdateAction: 更新一个存在的资源;
yii\rest\DeleteAction: 删除指定的资源;
yii\rest\OptionsAction: 返回支持的HTTP方法.*/
    public function actions()
    {
        $actions = parent::actions();
        //禁用delete
        unset($actions['delete']);
        // 使用"prepareDataProvider()"方法自定义数据provider
        //$actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }

    public function prepareDataProvider()
    {
        // 为"index"操作准备和返回数据provider
        $query = \common\models\Posts::find();
        $dataProvider = new \yii\data\ActiveDataProvider(['query' => $query]);
        return $dataProvider;
    }

//GET /users: 逐页列出所有用户
//HEAD /users: 显示用户列表的概要信息
//POST /users: 创建一个新用户
//GET /users/123: 返回用户 123 的详细信息
//HEAD /users/123: 显示用户 123 的概述信息
//PATCH /users/123 and PUT /users/123: 更新用户123
//DELETE /users/123: 删除用户123
//OPTIONS /users: 显示关于末端 /users 支持的动词
//OPTIONS /users/123: 显示有关末端 /users/123 支持的动词*/


/*
 * 以下过滤器会按顺序执行：

yii\filters\ContentNegotiator: 支持内容协商，
yii\filters\VerbFilter: 支持HTTP 方法验证;
yii\filters\auth\AuthMethod: 支持用户认证，
yii\filters\RateLimiter: 支持频率限制
 * */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        //认证
            //单个;
        /*$behaviors['authenticator'] = [
            'class' => HttpBasicAuth::className(),
        ];*/
            //多种认证方式
        /*$behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                HttpBasicAuth::className(),
                HttpBearerAuth::className(),
                QueryParamAuth::className(),
            ],
        ];*/


        //RESTful APIs 同时支持JSON和XML格式。为了支持新的格式，你应该 在 contentNegotiator 过滤器中配置新的response格式
        $behaviors['contentNegotiator']['formats']['text/html'] = \yii\web\Response::FORMAT_HTML;
        return $behaviors;
    }


    //yii\rest\Controller::serializer 和 yii\web\Response 会处理原始数据到请求格式的转换
    public function actionView($id)
    {
        return \common\models\Test::findOne($id);
    }

    //checkAccess() 方法默认会被yii\rest\ActiveController默认操作所调用
    public function checkAccess($action, $model = null, $params = [])
    {
        // check if the user can access $action and $model
        // throw ForbiddenHttpException if access should be denied
        // 可使用Role-Based Access Control (RBAC) 基于角色权限控制组件实现checkAccess()。
    }


}
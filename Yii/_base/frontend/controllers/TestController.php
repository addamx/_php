<?php
/**
 * Created by PhpStorm.
 * User: addams
 * Date: 2017/1/16
 * Time: 9:03
 */
namespace frontend\controllers;

use Yii;
use common\models\Test;
use yii\helpers\ArrayHelper;

class TestController extends \yii\web\Controller
{

    //public $defaultAction = 'helloworld';   //默认操作('index')
    public $layout = 'main';        //改变布局文件(.php)

    public function actions()   //独立操作
    {
        return [
            'helloworld' => 'frontend\components\HelloWorldAction',
            'page' => [
                'class' => 'yii\web\ViewAction',    //ViewAction渲染静态页面(使用布局), 想要的页面使用GET参数?view=
                //'viewPrefix' => 'static'          //修改存放静态页面的目录, 默认是pages
            ],
            'error' => [
                'class' => 'yii\web\ErrorAction',   //等同下面的"actionError"方法
            ],
        ];
    }
/*
    public function actionError()
    {
        $exception = Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            return $this->render('error', ['exception' => $exception]);
            //视图文件将获得以下变量:
            //name: 错误名称
            //message: 错误信息
            //exception: 更多详细信息的异常对象，如HTTP 状态码，错误码， 错误调用栈等。
        }
    }
*/

    public function behaviors()
    {
        return ArrayHelper::merge([
            [
                //HttpCache利用Last-Modified 和 Etag HTTP头实现客户端缓存
                'class' => 'yii\filters\HttpCache',
                'only' => ['index', 'view'],        //规定使用该方法的action; 默认是应用到所有方法
                'except' => ['rss'],                //排除使用该方法的action
                'lastModified' => function ($action, $params) {     //?
                    $q = new \yii\db\Query();
                    return $q->from('user')->max('updated_at');
                },
            ],
            //自定义的过滤器
//            [
//                'class' => '\frontend\components\ActionTimeFilter',
//            ],
            //[页面缓存]
                //页面缓存和片段缓存极其相似。它们都支持 duration，dependencies， variations 和 enabled 配置选项。它们的主要区别是页面缓存是由过滤器实现， 而片段缓存则是一个小部件。
            [
                'class' => 'yii\filters\PageCache',
                'only' => ['cache'],
                'duration' => 10,
                'variations' => [
                    \Yii::$app->language,
                ],
                'dependency' => [
                    'class' => 'yii\caching\DbDependency',
                    'sql' => 'SELECT COUNT(*) FROM posts',
                ],
            ],
            'access' => [                                   //授权过滤器
                'class' => 'yii\filters\AccessControl',
                'only' => ['create', 'update'],             //已认证用户访问create 和 update 动作， 拒绝其他用户访问这两个动作。
                'rules' => [
                    // 允许认证用户
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    // 默认禁止其他用户
                ],
            ],
            //[HTTP缓存]

            [
                'class' => 'yii\filters\HttpCache',
                'cacheControlHeader' => 'public, max-age=3600', //Cache-Control头
                                        //默认情况下 HttpCache 禁止 自动发送 会话缓存限制器(session.cache_limiter) ('public', 'private_no_expire', 'private', 'nocache')
                'only' => ['cache'],
                'lastModified' => function ($action, $params) {
                    $q = new \yii\db\Query();
                    return $q->from('posts')->max('updated_at');
                },
//                'etagSeed' => function ($action, $params) {
//                    $post = $this->findModel(\Yii::$app->request->get('id'));
//                    return serialize([$post->title, $post->content]);
//                },
            ],
            //认证方法过滤器通过HTTP Basic Auth或OAuth 2 来认证一个用户
            //认证方法过滤器通常在实现RESTful API中使用
//            'basicAuth' => [
//                'class' => 'yii\filters\auth\HttpBasicAuth',
//            ],
            [
                //ContentNegotiator支持响应内容格式处理和语言处理。 通过检查 GET 参数和 Accept HTTP头部来决定响应内容格式和语言
                //在应用主体生命周期过程中检测响应格式和语言简单很多， 因此ContentNegotiator设计可被 [bootstrap] 引导启动组件调用的过滤器
                'class' => 'yii\filters\ContentNegotiator',
                'formats' => [
                    //'application/json' => \yii\web\Response::FORMAT_JSON,
                    //'application/xml' => \yii\web\Response::FORMAT_XML,
                ],
                'languages' => [
                    'zh-CN',            // 这里会修改\Yii::$app->language的值
                    //'de',
                ],
            ],
            //PageCache实现服务器端整个页面的缓存
            'pageCache' => [
                'class' => 'yii\filters\PageCache',
                'only' => ['index'],
                'duration' => 60,           //保存60秒
                'dependency' => [           //或post表的记录数发生变化
                    'class' => 'yii\caching\DbDependency',
                    'sql' => 'SELECT COUNT(*) FROM posts',
                ],
                'variations' => [           //根据不同应用语言保存不同的页面版本
                    \Yii::$app->language,
                ]
            ],
            // 根据 漏桶算法 来实现速率限
            //['class' => 'yii\filters\RateLimiter '],
            //检查请求动作的HTTP请求方式是否允许执行， 如果不允许，会抛出HTTP 405异常
            'verbs' => [
                'class' => 'yii\filters\VerbFilter',
                'actions' => [
                    'index'  => ['get'],
                    'view'   => ['get'],
                    'create' => ['get', 'post'],
                    'update' => ['get', 'put', 'post'],
                    'delete' => ['post', 'delete'],
                ],
            ],
            [
                'class' => 'yii\filters\Cors',
                'cors' => [
                    'Origin' => ['http://www.myserver.net'],    //定义允许来源的数组，可为['*'] (任何用户)
                    'Access-Control-Request-Method' => ['GET', 'HEAD', 'OPTIONS'], //允许动作数组, 默认为 ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS']
                    'Access-Control-Request-Headers' => ['*'],  //可为 ['*'] 所有类型头部 或 ['X-Request-With'] 指定类型头部. 默认为 ['*'].
                    'Access-Control-Allow-Credentials' => null, //定义当前请求是否使用证书，可为 true, false 或 null (不设置). 默认为 null.
                    'Access-Control-Max-Age' => 86400,  //定义请求的有效时间，默认为 86400
                ],
                'actions' => [              //为某些action改变设置
                    'login' => [
                        'Access-Control-Allow-Credentials' => true,
                    ]
                ],
            ]
        ], parent::behaviors());
    }

    public function actionIndex()
    {
        $model = new Test(['scenario' => 'login']);
        $model->name = 'Tom';
        $model->email = 'Tom@123.com';

        \Yii::$app->view->on(\yii\web\View::EVENT_END_BODY, function () {
            echo '<strong>EVENT_END_BODY</strong>';
            //$event->handled = true;   // 停止后面的事件处理器
        }/*, $data 传参, false 放在队列最前*/);

        return $this->render('index', ['model' => $model,]);    //视图名默认使用.php后缀;
                                                                // 以"//"开头就默认是@app/views文件夹下的路径
                                                                //以"/"开头则默认是当前模块下的路径/views, 没模块则是@app/views
        //return $this->renderPartial('index', ['model' => $model,]);     //不使用布局
        //return $this->renderAjax('index', ['model' => $model,]);    //渲染一个 视图名 并且不使用布局， 并注入所有注册的JS/CSS脚本和文件
        //return $this->renderFile('@app/views/test/index.php', ['model' => $model,]);    //指定路径, 不使用布局
        //return $this->renderContent('<h1>Test: renderContent</h1>');    //使用布局, 不转义 (>=2.0.1)


    }

    public function getViewPath()   //覆盖该方法以自定义view的默认目录, 在这里默认应该是 .../frontend/views/test/;
    {
        return parent::getViewPath(); // TODO: Change the autogenerated stub
    }

    public function actionPain()
    {
        \Yii::trace('Yii-trace', __METHOD__);  //记录一条消息去跟踪一段代码是怎样运行的。这主要在开发的时候使用。
        \Yii::info('Yii-info');   //记录一条消息来传达一些有用的信息。
        \Yii::warning('Yii-warning');    //记录一个警告消息用来指示一些已经发生的意外。
        \Yii::error('Yii-error'); //记录一个致命的错误，这个错误应该尽快被检查。
        try {
            10/0;
        } catch (\yii\base\ErrorException $e) {
            //\Yii::warning("Division by zero.");
            throw new \yii\web\NotFoundHttpException();     //转向404页面(使用layout)
        }

    }

    public function actionEvent()
    {
        $eventor = new \frontend\components\TestEvent();
        //实例级别绑定事件
        $eventor->on($eventor::EVENT_MESSAGE_SENT, function($event) {
            var_dump($event->data);
            var_dump($event->message);
        }, 'event-data');
        $eventor->send("trust me");
        //$eventor->off($eventor::EVENT_MESSAGE_SENT);
    }

    public function actionEvent2()
    {
        //[类级别绑定事件]
        \yii\base\Event::on(\frontend\components\TestEvent::className(), \frontend\components\TestEvent::EVENT_MESSAGE_SENT, function ($event) {
            var_dump($event->data);
            //var_dump($event->message);
        }, 'event-data');
        // $eventor = new \frontend\components\TestEvent();
        // $eventor->send("trust me");
        //[类级别触发事件]
        \yii\base\Event::trigger(\frontend\components\TestEvent::className(), \frontend\components\TestEvent::EVENT_MESSAGE_SENT);
        // [类级别移除全部处理器]
        Event::off(Foo::className(), Foo::EVENT_HELLO);

        //全局时间
        //\yii:$app->on(), \yii:$app->trigger()
    }

    public function actionBehavior()
    {
        $this->attachBehavior('myBehavior1', \frontend\components\TestBehavior::className());
        $this->foo();       //执行行为定义的方法
        $behavior1= $this->getBehavior('myBehavior1');  //获取行为
        var_dump($behavior1->owner);   // 在行为内部可以通过 yii\base\Behavior::owner 属性访问行为已附加的组件。
    }

    public function actionConfig()
    {
        $config = [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;dbname=yii2basic',
            'username' => 'root',
            'password' => '123',
            'charset' => 'utf8',
        ];

        $db = \Yii::createObject($config);
        var_dump($db->createCommand('SELECT * FROM test')->queryOne());

        $config1 = [
            'dsn' => 'mysql:host=127.0.0.1;dbname=yii2basic',
            'username' => 'root',
            'password' => '123',
            'charset' => 'utf8',
            'on ' . \yii\db\Connection::EVENT_AFTER_OPEN => function($event) {echo 'event1';},  //[配置]绑定事件
            'as behavior1' => [                                                 // [配置]绑定行为
                'class' => 'frontend\components\TestBehavior'
            ]
        ];
        $db1 = \Yii::createObject('yii\db\Connection');
        \Yii::configure($db1, $config1);
        var_dump($db1->createCommand('SELECT * FROM test')->queryOne());
        $db1->foo();
    }

    public function actionServiceLocator()
    {
        $locator = new \yii\di\ServiceLocator;

        // 通过一个可用于创建该组件的类名，注册 "cache" （缓存）组件。
        $locator->set('cache', 'yii\caching\FileCache');

        // 通过一个可用于创建该组件的配置数组，注册 "db" （数据库）组件。
        $locator->set('db', [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=yii2basic',
            'username' => 'root',
            'password' => '123',
        ]);

        // 通过一个能返回该组件的匿名函数，注册 "test-behavior" 组件。
        $locator->set('test-behavior', function () {
            return new \frontend\components\TestBehavior;
        });

        // 用组件注册 "pageCache" 组件
        $locator->set('pageCache', new \yii\caching\FileCache);


        //一旦组件被注册成功，你可以任选以下两种方式之一，通过它的 ID 访问它：

        $cache1 = $locator->get('cache');
        // 或者
        $cache2 = $locator->cache;

        var_dump($cache1);
    }

    public function actionDi()
    {
        //依赖关系的定义可以是一个类名，一个配置数组，或者一个 PHP 回调。
        $container = new \yii\di\Container;

        // 注册一个同类名一样的依赖关系，这个可以省略。
        $container->set('yii\db\Connection');

        // 注册一个接口
        // 当一个类依赖这个接口时，相应的类会被初始化作为依赖对象。
        $container->set('yii\mail\MailInterface', 'yii\swiftmailer\Mailer');

        // [单例]注册一个别名。
        // 你可以使用 $container->get('foo') 创建一个 Connection 实例
        $container->set('foo', 'yii\db\Connection');

        // 通过配置注册一个类
        // 通过 get() 初始化时，配置将会被使用。
        $container->set('yii\db\Connection', [
            'dsn' => 'mysql:host=127.0.0.1;dbname=demo',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ]);

        // 通过类的配置注册一个别名
        // 这种情况下，需要通过一个 “class” 元素指定这个类
        $container->set('db', [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;dbname=demo',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ]);

        // 注册一个 PHP 回调
        // 每次调用 $container->get('db') 时，回调函数都会被执行。
        $container->set('db', function ($container, $params, $config) {
            return new \yii\db\Connection($config);
        });
        var_dump($container->get('db', [], ['username' => 'root']));


        // [单例]注册一个组件实例
        // $container->get('pageCache') 每次被调用时都会返回同一个实例。
        $container->set('pageCache', new \yii\caching\FileCache);

        //[单例]setSingleton
        $container->setSingleton('yii\db\Connection', [
            'dsn' => 'mysql:host=127.0.0.1;dbname=demo',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ]);

        $db1 = $container->get('yii\db\Connection');
        $db2 = $container->get('yii\db\Connection');
        echo spl_object_hash($db1) == spl_object_hash($db2) ? '同一实例' : '不是同一实例';     //同一实例
    }


    //数据提供器
    public function actionProvider()
    {
        //AR 活动数据提供器
        $query = \common\models\Test::find()->where(['category_id' => 1]);
        $provider1 = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'name' => SORT_DESC,
                    'title' => SORT_ASC,
                ]
            ]
        ]);
        //var_dump($provider1->getModels());
        var_dump($provider1->getCount());       //10
        var_dump($provider1->getTotalCount());       //45

        echo \yii\grid\GridView::widget([
            'dataProvider' => $provider1,
        ]);

        //SQL数据提供者
        $count = \Yii::$app->db->createCommand('SELECT COUNT(*) FROM Test WHERE category_id=:category_id', [':category_id' => 1])->queryScalar();

        $provider2 = new \yii\data\SqlDataProvider([
            'sql' => 'SELECT * FROM Test WHERE category_id=:category_id',
            'params' => [':category_id' => 1],
            'totalCount' => $count,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'attributes' => [
                    'title',
                    'view_count',
                    'created_at',
                ],
            ],
        ]);

        echo \yii\grid\GridView::widget([
            'dataProvider' => $provider2,
        ]);


        //数组数据提供者
        $data= [
            ['id' => 1, 'name' => 'name1'],
            ['id' => 2, 'name' => 'name2'],
            ['id' => 3, 'name' => 'name3'],
            ['id' => 4, 'name' => 'name4'],
            ['id' => 5, 'name' => 'name5'],
            ['id' => 6, 'name' => 'name6'],
            ['id' => 7, 'name' => 'name7'],
        ];
        $provider3 = new \yii\data\ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 3,
            ],
            'sort' => [
                'attributes' => ['id', 'name'],
            ]
        ]);

        echo \yii\grid\GridView::widget([
            'dataProvider' => $provider3,
        ]);


        //数据键的使用
        $provider4 = new \yii\data\ActiveDataProvider([
            'query' => \common\models\Test::find(),
            'key' => 'title',       //设定"title"字段为键值, 默认是"id"
        ]);
        $provider5 = new \yii\data\ActiveDataProvider([
            'query' => \common\models\Test::find(),
            'key' => function ($model) {    //callback返回key
                return md5($model->id);
            }
        ]);
        var_dump($provider4->getKeys());    //获取数据键
        var_dump($provider5->getKeys());


        //自定义数据提供者
        $provider6 = new \frontend\components\TestDataProvider([
            'filename' => realpath(\yii::getAlias('@app').'/../zzz/1.csv'),
            'pagination' => [
                'pageSize' => 2,
            ]
        ]);
        var_dump($provider6->getModels());

        echo \yii\grid\GridView::widget([
            'dataProvider' => $provider6,
        ]);





    }

    public function actionData()
    {
        //DetailView显示单行的数据
        $model = new \common\models\Test;
        $model = $model->findOne(10);

        //ListView 显示多行, 排序,分页,过滤
        //GridView 网格系统, 排序,分页
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $model::find(),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $searchModel = new \common\models\TestSearch;
        $dataProvider1 = $searchModel->search(\Yii::$app->request->get());
        return $this->render('data', ['model' => $model, 'dataProvider' => $dataProvider, 'dataProvider1' => $dataProvider1, 'searchModel' => $searchModel]);

    }

    public function actionData1()
    {
        $searchModel = new \common\models\TestSearch;
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
        return $this->render('data1', ['dataProvider' => $dataProvider, 'searchModel' => $searchModel]);
    }

    public function actionData2()
    {
        $query = \common\models\Posts::find();
        $dataProvider = new \yii\data\ActiveDataProvider(['query' => $query]);
        $model = $query->innerJoinWith('PostTagPivot')->all();

        $dataProvider->sort->attributes['tag.tag'] =[
            'asc' => ['tag.tag' => SORT_ASC],
            'desc' => ['tag.tag' => SORT_DESC],
        ];
        return $this->render('data2', ['model' => $model, 'dataProvider' => $dataProvider]);
    }

    public function actionUser()
    {
        // 当前用户的身份实例。未认证用户则为 Null 。
        $identity = \Yii::$app->user->identity;

        // 当前用户的ID。 未认证用户则为 Null 。
        $id = \Yii::$app->user->id;

        // 判断当前用户是否是游客（未认证的）
        $isGuest = \Yii::$app->user->isGuest;
        var_dump($identity, $id, $isGuest);

        // 使用指定用户名获取用户身份实例。
        // 请注意，如果需要的话您可能要检验密码
        $identity = \common\models\User::findOne(['username' => \yii::$app->request->get('username')]);

        // [登录用户]
        //如果 session 设置为 yii\web\User::enableSession，则使用 session登录。如果开启自动登录 yii\web\User::enableAutoLogin 则基于 cookie 登录
        //为了使用 cookie 登录，你需要在应用配置文件中将 yii\web\User::enableAutoLogin 设为 true。你还需要在 yii\web\User::login() 方法中 传递有效期（记住登录状态的时长）参数。
        \Yii::$app->user->login($identity);

        \Yii::$app->user->logout(); //注销用户;  默认情况下，它还会注销所有的 用户会话数据。如果你希望保留这些会话数据，可以换成 Yii::$app->user->logout(false) 。
    }

    public function actionCache()
    {
        //[查询缓存]
        $dependency = new \yii\caching\ExpressionDependency(['expression' => date('Y') == '2017']);
        //回调函数($db:DAO或AR)
        $result = Test::getDb()->cache(function ($db) {
            $temp = Test::getDb()->createCommand('SELECT * FROM test WHERE id=1')->noCache()->queryOne();  //单独不缓存此查询结果, noCache()仅限DAO可用
            return Test::find()->where(['id' => 1])->one();
        }, 60, $dependency);

        //单行(仅限DAO)
        $result1 = Test::getDb()->createCommand('SELECT * FROM test WHERE id=1')->Cache(60)->queryOne();

        return $this->render('cache', ['result' => $result]);
    }

    public function actionResponse()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $items = ['some', 'array', 'of', 'data' => ['associative', 'array']];
        return $items;
    }

    public function actionTranslate()
    {
        // 名称占位符
        $username = 'Alexander';
        echo \Yii::t('app', 'Hello, {username}!', [
            'username' => $username,
        ]) . '<br/>';

        //位置占位符
        $price = 100;
        $count = 2;
        $subtotal = 200;
        echo \Yii::t('app', 'Price: {0}, Count: {1}, Subtotal: {2}', [$price, $count, $subtotal]) . '<br/>';

        //格式化参数
        $price = 100;
        echo \Yii::t('app', 'Price: {0, number, currency}', $price) . '<br/>';        //参数的格式化需要安装 intl PHP 扩展
        $sum = 42;
        echo \Yii::t('app', 'Balance: {0, number}', $sum) . '<br/>';
        $sum = 42;
        echo \Yii::t('app', 'Balance: {0, number, ,000,000000}', $sum) . '<br/>';
        //日期可选: short ，medium ，long ，或 full
        echo \Yii::t('app', 'Today is {0, date, short}', time()) . '<br/>';
        echo \Yii::t('app', 'Today is {0, date, yyyy-MM-dd}', time()) . '<br/>';
        // 输出："You are here for 47 sec. already!"
        echo \Yii::t('app', 'You are here for {n, duration} already!', ['n' => 47]) . '<br/>';

        // If you need to use special characters such as {, }, ', #, wrap them in ':
        // `#` 代表本身
        echo \Yii::t('app', "Example of string with ''-escaped characters'': '{' '}' '{test}' {count,plural,other{''count'' value is # '#{}'}}", ['count' => 3]) . '<br/>';

        //选择1
        $n = 1;
        echo \Yii::t('app', 'There {n, plural, =0{are no cats} =1{is one cat} other{are # cats}}!', ['n' => $n]) . '<br/>';
        //选择2
        // 输出："Snoopy is a dog and it loves Yii!"
        echo \Yii::t('app', '{name} is a {gender} and {gender, select, female{she} male{he} other{it}} loves Yii!', [
            'name' => 'Snoopy',
            'gender' => 'dog',
        ]) . '<br/>';
    }

    // 配置语言 和 视图文件
    public function actionTranslate1()
    {
        //\Yii::$app->language = 'zh-CN';
        // 如果修改了本控制器的响应格式行为 (yii\filters\ContentNegotiator) 的language, 也会修改\Yii::$app->language
        var_dump(\yii::$app->language);
        return $this->render('translate1');
    }

    // Mail, 基本的发送邮件
    public function actionMail()
    {
        //如果要使用layout, 则 1.使用视图文件, 2. 不要使用setTexBody/setHtmlBody
        \Yii::$app->mailer->compose('test',
                ['imageFileName' => '/path/to/image.jpg']   //嵌入图片(其实就是传入参数, 在视图文件中<img src="/docs/guide/2.0/<?= $message->embed($imageFileName);....)
            )
            ->setFrom('from@domain.com')
            ->setTo('addamx@126.com')
            ->setSubject('Message subject')
            //->setTextBody('Plain text content')
            //->setHtmlBody('<b>HTML content</b>')
            //->attach('/path/to/source/file.pdf')        //附件来自本地文件
            //->attachContent('Attachment content', ['fileName' => 'attach.txt', 'contentType' => 'text/plain'])  //动态创建一个文件附件
            ->send();
    }

    // Mail, 根据访客身份发送邮件
    public function actionMail1()
    {
        $message = Yii::$app->mailer->compose();
        if (Yii::$app->user->isGuest) {
            $message->setFrom('from@domain.com');
        } else {
            $message->setFrom(Yii::$app->user->identity->email);
        }
        $message->setTo(Yii::$app->params['adminEmail'])
            ->setSubject('Message subject')
            ->setTextBody('Plain text content')
            ->send();
    }

    public function actionMail2()
    {
        // 渲染一个视图作为邮件内容
        Yii::$app->mailer->compose('mails')
        ->setFrom('from@domain.com')
            ->setTo('to@domain.com')
            ->setSubject('Message subject')
            ->send();

        //你也可以在 compose() 方法中传递一些视图所需参数，这些参数可以在视图文件中使用：
        Yii::$app->mailer->compose('greetings', [
            'user' => Yii::$app->user->identity,
            'advertisement' => $adContent,
        ]);

        //你可以指定不同的视图文件的 HTML 和纯文本邮件内容：
            //如果指定视图名称为纯字符串，它的渲染结果将被用来作为 HTML Body， 同时纯文本正文将被删除所有 HTML 实体。
        Yii::$app->mailer->compose([
            'html' => 'contact-html',
            'text' => 'contact-text',
        ]);


    }

    public function actionHelper()
    {
        // [ArrayHelper::getValue]: 如果不存在就返回null, 不报错
        $array = [
            'foo' => [
                'bar' => 'cc',
            ]
        ];
        $value = ArrayHelper::getValue($array, 'foo.bar.name'); //第三个可选的参数如果没有给定值，则默认为 null
        @$fullName = ArrayHelper::getValue($array, function ($array, $defaultValue) {     //使用回调函数规定获取值的方式;
            return $array->firstName . ' ' . $array->lastName;
        });

        // [ArrayHelper::remove] 删除指定键/属性, 并返回它
        $array = ['type' => 'A', 'options' => [1, 2]];
        $type = ArrayHelper::remove($array, 'type'); //$array 将包含 ['options' => [1, 2]] 并且 $type 将会是 A
        var_dump($array, $type);


        // [ArrayHelper::keyExists] 工作原理和array_key_exists差不多，除了 它还可支持大小写不敏感的键名比较


        // [ArrayHelper::getColumn] 多行数据或者多个对象构成的数组中获取某列的值
        $data = [
            ['id' => '123', 'data' => 'abc'],
            ['idx' => '345', 'data' => 'def'],
        ];
        $ids = ArrayHelper::getColumn($data, 'ids');    //相当于array_colum(), 但取不到值时返回null
        var_dump($ids); //['123', '345']
        $result = ArrayHelper::getColumn($data, function ($element) {   //如果还需要转换, 可以指定一个匿名函数
            return $element['id'];
        });
        var_dump($result);




        // [ArrayHelper::index]
        /*$array = [
            ['id' => '123', 'data' => 'abc', 'device' => 'laptop'],
            ['id' => '345', 'data' => 'def', 'device' => 'tablet'],
            ['id' => '345', 'data' => 'hgi', 'device' => 'smartphone'],
        ];*/
        $result = ArrayHelper::index($array, 'id'); //仅有两参数时, 同key的将后面的覆盖
        /*[
            '123' => ['id' => '123', 'data' => 'abc', 'device' => 'laptop'],
            '345' => ['id' => '345', 'data' => 'hgi', 'device' => 'smartphone']
            // The second element of an original array is overwritten by the last element because of the same id
        ]*/
        $result = ArrayHelper::index($array, null, 'id');   //当键名作为第3个参数时, 同名的键名将被作为一个group;
        /*
        [
            '123' => [
                ['id' => '123', 'data' => 'abc', 'device' => 'laptop']
            ],
            '345' => [ // all elements with this index are present in the result array
                ['id' => '345', 'data' => 'def', 'device' => 'tablet'],
                ['id' => '345', 'data' => 'hgi', 'device' => 'smartphone'],
            ]
        ]*/


    }

    public function actionArray()
    {
        // 不指定键名的数组
        $indexed = ['Qiang', 'Paul'];
        echo ArrayHelper::isIndexed($indexed);

// 所有键名都是字符串
        $associative = ['framework' => 'Yii', 'version' => '2.0'];
        echo ArrayHelper::isAssociative($associative);
    }

    public function actionHtml()
    {
        $array = \common\models\Test::findAll([1,2,3,4,5]);
        $model = new \common\models\Test;
        return $this->render('html', ['model' => $model, 'array' => $array]);
    }





}
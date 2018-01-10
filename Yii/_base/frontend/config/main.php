<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend', //[必要] 用来区分其他应用的唯一标识ID
    'basePath' => dirname(__DIR__), //[必要] 该应用的根目录。在根目录下可以看到对应MVC设计模式的models, views, controllers等子目录。
                                    //或者使用'@frontend' (在common\bootstrap.php中设置)
    'bootstrap' => [
        //应用组件ID
        'log',
        //类名
        //'app\components\Profiler',
        //配置数组
        /*[
            'class' => 'app\components\Profiler',
            'level' => 3,
        ],*/
        //匿名函数
        function () {
            //return new app\components\Profiler();
        },
        //如果模块ID和应用组件ID同名， 优先使用应用组件ID，如果你想用模块ID， 可以使用如下无名称函数返回模块ID。
        function () {
            //return Yii::$app->getModule('user');      //获取Module
        },
        'forum'       //具体的类名在下面的'module'配置中已定义

    ],
    /*'catchAll' => [     //(全拦截路由)要处理"所有"用户请求的控制器方法; 当该属性存在时连debug panel都会失效; 仅 yii\web\Application 网页应用支持
        'test-config/index',
        'param1' => 'value1',
    ],*/

    'aliases' => [
        '@name1' => 'test',
        '@name2' => '@frontend/test',
    ],

    'controllerNamespace' => 'frontend\controllers',
    'components' => [       //注册多个在其他地方使用的应用组件;
                            //全局访问: 1. \Yii::$app->components['db'], 2. \Yii::$app->getComponents()['db']
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    //'basePath' => '@app/messages',
                    //'sourceLanguage' => 'en-US',
                    'fileMap' => [      //默认情况下， 文件名应该与类别名称相同。但是，你可以配置 yii\i18n\PhpMessageSource::fileMap 来映射一个类别到不同名称的 PHP 文件
                        'app' => 'app.php',             // 可以简单地使用类映射的同名文件而不是使用 fileMap, 此处可以不设置
                        'app/error' => 'error.php',     //[@app/messages/ru-RU/error.php]如果没有此配置， 该类别将被映射到 @app/messages/ru-RU/app/error.php 。
                    ],
                    //处理缺少的翻译 - 事件
                    'on missingTranslation' => ['frontend\components\TranslationEventHandler', 'handleMissingTranslation'],
                ],
            ],
        ],
        'urlManager' => [
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'Api',       //针对Restful控制器; 值可以为数组或者字符串,  ['user', 'post']; 控制器ID会被使用yii\helpers\Inflector::pluralize()复数化, 可以设置 yii\rest\UrlRule::pluralize 为false 来禁用此行为, 或者 使用自定义名字 ['u' => 'user']
                    'pluralize' => false,
                    //'except' => ['delete', 'create', 'update'],  //禁用行为
                    'extraPatterns' => [                //添加行为      "GET /api/search"
                        'GET search' => 'search',
                    ],
                ],
            ]
        ],
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
                //若未按上述配置，API 将仅可以分辨 application/x-www-form-urlencoded 和 multipart/form-data 输入格式
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'keyPrefix' => 'myapp',       // 唯一键前缀; 当同一个缓存存储器被用于多个不同的应用时，应该为每个应用指定一个唯一的缓存键前缀 以避免缓存键冲突
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,  //使用cookie登录
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
            'loginUrl' => ['site/login'],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
            //'class' => 'yii\web\DbSession',
            // 'db' => 'mydb',  // 数据库连接的应用组件ID，默认为'db'.
            // 'sessionTable' => 'my_session', // session 数据表名，默认为'session'.
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'test/error',   //定义错误操作
        ],
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
        'assetManager' => [
            'linkAssets' => true,   //如果操作系统和Web服务器允许可以使用符号链接
            'appendTimestamp' => true,  //给资源链接添加get参数 eg:/assets/5515a87c/yii.js?v=1423448645
            'bundles' => [
                //'yii\web\JqueryAsset' => false, //禁用该资源包, 即使视图文件注册了也无法使用
                'yii\web\JqueryAsset' => [
                    'sourcePath' => null,   // 一定不要发布该资源
                    'js' => [
                        '//cdn.bootcss.com/jquery/3.1.1/jquery.min.js',
                        //YII_ENV_DEV ? 'jquery.js' : 'jquery.min.js'
                    ]
                ],
                'assetMap' => [     //? 资源部署
                    //当视图注册资源包，在yii\web\AssetBundle::css 和 yii\web\AssetBundle::js 数组中每个相关资源文件会和该部署进行对比， 如果数组任何键对比为资源文件的最后文件名
                    //'jquery.js' => '//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js',
                ],

            ],
            'converter' => [        // 预编译/压缩
                'class' => 'yii\web\AssetConverter',
                'commands' => [
                    'less' => ['css', 'lessc {from} {to} --no-color'],
                    'ts' => ['js', 'tsc --out {to} {from}'],
                ],
            ],
        ],

        'response' => [
            'class' => 'yii\web\Response',
            'on beforeSend' => function ($event) {      // 自定义错误响应格式
                $response = $event->sender;
                if ($response->data !== null && !empty(Yii::$app->request->get('new_response_code'))) { //当 suppress_response_code 作为 GET 参数传递时，上面的代码 将重新按照自己定义的格式响应（无论失败还是成功）
                    $response->data = [
                        'success' => $response->isSuccessful,
                        'data' => $response->data,
                    ];
                    $response->statusCode = 200;
                }
                /*结果:
                 {
                    "success": false,
                    "data": {
                        "name": "Not Found Exception",
                        "message": "The requested resource was not found.",
                        "code": 0,
                        "status": 404
                    }
                 }
                 */
            },
        ],
      /*  'view' => [
            'theme' => [    //使用主题
                'basePath' => '@app/themes/basic',  //指定包含主题资源（CSS, JS, images, 等等）的基准目录。
                'baseUrl' => '@web/theme/basic',    //指定主题资源的基准UR
                'pathMap' => [
                    '@app/views' => '@app/themes/basic',
                    '@app/views' => [
                        '@app/themes/christmas',        //主题继承: 当christmas文件夹下的视图文件不存在时才调用basic的视图文件
                        '@app/themes/basic',
                    ],
                    '@app/modules' => '@app/themes/basic/modules', //主体化模块, 这会影响到所有模块; 如需针对单独模块, 请在模块的Module.php中设置
                    '@app/widgets' => '@app/themes/basic/widgets'
                ],
            ]
        ],*/
    ],
    'controllerMap' => [
        'test-map' => 'frontend\controllers\TestConfigController',
        [
            /*'article' => [
                'class' => 'app\controllers\PostController',
                'enableCsrfValidation' => false,
            ]*/
        ],
    ],
    //'controllerNamespace' => 'frontend\api',   //? 该属性指定控制器类默认的命名空间，默认为app\controllers
    'params' => $params,
    'name' => 'frontend',
    'timeZone' => 'Asia/Hong_Kong',       //质上就是调用PHP函数 date_default_timezone_set()
    'version' => '1.0',
    'charset' => 'utf-8',   //默认为'UTF-8'
    'defaultRoute' => 'post/index',             //默认'site/index'
/*    'extensions' => [       //指定应用安装和使用的 扩展
                            //默认使用@vendor/yiisoft/extensions.php文件返回的数组。
                            //当你使用 Composer 安装扩展，extensions.php 会被自动生成和维护更新
                            //所以一般不需要在Composer再设置
        [
            'name' => 'extension name',
            'version' => 'version number',
            'bootstrap' => 'BootstrapClassName',    // 可选配
            'alias' => [    // 可选配
                '@alias1' => 'to/path1',
                '@alias2' => 'to/path2'
            ]
        ]
    ],*/
    'layout' => 'main.php',      //渲染 视图 默认使用的布局名字, 默认使用'main.php'
                                 //可以使用路径别名@app/views/layouts/main.php
                                 //如果不想设置默认布局文件，可以设置该属性为 false
    'layoutPath' => '@app/views/layouts',       //指定查找布局文件的路径
    'runtimePath' => '@app/runtime',
    'viewPath' => '@app/views',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'modules' => [
        'forum' => [        //可以加入Bootstrap中以便每次都被加载
            'class' => 'frontend\modules\forum\Module',
            // ... 模块其他配置 ...
        ],
        'atheme' => [
            'class' => 'frontend\modules\atheme\Module',
        ]
    ],

];

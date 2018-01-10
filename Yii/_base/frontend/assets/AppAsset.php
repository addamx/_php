<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    //public $sourcePath = ''; /指定包包含资源文件的根目录， 当根目录不能被Web访问时该属性应设置，
        //否则，应设置 yii\web\AssetBundle::basePath 属性和yii\web\AssetBundle::baseUrl。 路径别名 可在此处使用；
        //当指定yii\web\AssetBundle::sourcePath 属性， 资源管理器 会发布包的资源到一个可Web访问并覆盖basePath, baseUrl属性
        //如果你的资源文件在一个Web可访问目录下，应设置该属性，这样就不用再发布了。
        //属性不要用@webroot/assets，该路径默认为 yii\web\AssetManager资源管理器将源资源发布后存储资源的路径， 该路径的所有内容会认为是临时文件， 可能会被删除。
        //当设置为@bower/PackageName 或 @npm/PackageName， Composer会安装Bower或NPM包到对应的目录下
    public $basePath = '@webroot';      //指定包含资源包中资源文件并可Web访问的目录
    public $baseUrl = '@web';       //指定对应到yii\web\AssetBundle::basePath目录的URL
    public $css = [
        'css/site.css',
    ];
    public $js = [
        //'js/main.js',
        //http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js,
    ];
    public $depends = [         //依赖的资源包
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
    public $jsOptions = [   //当调用yii\web\View::registerJsFile()注册该包 每个 JavaScript文件时， 指定传递到该方法的选项。
        'position' => \yii\web\View::POS_HEAD,  //使JavaScript文件包含在页面head区域 （JavaScript文件默认包含在body的结束处）
    ];
    public $cssOptions = array(  //当调用yii\web\View::registerCssFile()注册该包 每个 css文件时， 指定传递到该方法的选项。
        'condition' => 'lte IE9',     //只想IE9或更高的浏览器包含一个CSS文件
        'noscript' => true,          //为链接标签包含<noscript>
        'depends' => ['\yii\bootstrap\BootstrapAsset'],
        'media' => 'print',
    );
    public $publishOptions = [   //指定子目录
                                //当调用yii\web\AssetManager::publish()发布该包资源文件到Web目录时 指定传递到该方法的选项，
                                // 仅在指定了yii\web\AssetBundle::sourcePath属性时使用。
        'fonts/',
        'css/',
    ];
}

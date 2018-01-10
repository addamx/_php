<?php
return array(
    'TMPL_ENGINE_TYPE'     => 'ISmarty',
    'TMPL_ENGINE_CONFIG'   => array(
        'plugins_dir' => './Application/Smarty/Plugins/',
    ),
    'DEFAULT_CHARSET'      => 'utf8',
    'Content-Type'         => 'text/html',
    'HTTP_CACHE_CONTROL'   => 'private', // 网页缓存控制

    'TMPL_FILE_DEPR'       => '/', //模板文件CONTROLLER_NAME与ACTION_NAME之间的分割符
    'TMPL_TEMPLATE_SUFFIX' => '.html', // 默认模板文件后缀
    'DEFAULT_V_LAYER'      => 'View', // 默认的视图层名称

    'DB_DSN'               => 'mysql:host=localhost;dbname=zvc;charset=utf8',
    'DB_USER'              => 'root',
    'DB_PASSWORD'          => 'net691029',
    'DB_PREFIX'            => 'zvc_',

    'SALT'                 => '{]$*(.>A_5',

);

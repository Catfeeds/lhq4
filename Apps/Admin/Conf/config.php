<?php
return array(
	//'配置项'=>'配置值'
	'ADMIN_TITLE' => '一元淘宝后台管理',
	'ADMIN_LOGO' => '/web/img/logo.png',
    'URL_MODEL'          => '0', //URL模式
    'DB_PREFIX'             =>  'yytb_',    // 数据库表前缀   // 数据库表前缀'
    'DEFAULT_CONTROLLER'    =>  'Index', // 默认控制器名称
    'DEFAULT_ACTION'        =>  'index', // 默认操作名
	'LOAD_EXT_CONFIG' => 'menu',
    'VIEW_PATH' => APP_PATH .'tpl/default/'. MODULE_NAME .'/',   //重新指定模板路径
    'TMPL_ACTION_ERROR' =>  'Layout/tiaozhuan',
    'TMPL_ACTION_SUCCESS'  => 'Layout/tiaozhuan',
);
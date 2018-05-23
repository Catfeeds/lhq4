<?php
return array(
    
	//'配置项'=>'配置值'
    'LAYOUT_ON'=>true,  //开启模板布局
    'URL_MODEL'          => '0', //URL模式
    'LAYOUT_NAME'=>'Layout/layout',//指定模板布局页面
    'DB_PREFIX'             =>  'yytb_',    // 数据库表前缀
    'DEFAULT_CONTROLLER'    =>  'Index', // 默认控制器名称
    'DEFAULT_ACTION'        =>  'index', // 默认操作名称
  
    'VIEW_PATH' => APP_PATH .'tpl/default/'. MODULE_NAME .'/',   //重新指定模板路径
     'TMPL_ACTION_ERROR' =>  'Layout/tiaozhuan',
    'TMPL_ACTION_SUCCESS'  => 'Layout/tiaozhuan',
);
<?php
return array(
	//'配置项'=>'配置值'
    'SHOW_PAGE_TRACE'=>true,
    'URL_PATHINFO_DEPR' => '/',  // URL地址中使用的分隔符
    'DB_TYPE'               =>  'mysql',     // 数据库类型
    'DB_HOST'               =>  'localhost', // 服务器地址
    'DB_NAME'               =>  'adbe',          // 数据库名
    'DB_USER'               =>  'root',      // 用户名
    'DB_PWD'                =>  '',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    //'DB_PREFIX'             =>  'ad_',    // 数据库表前缀
    'DB_CHARSET'            =>  'utf8',// 数据库编码默认采用utf8

    'MODULE_ALLOW_LIST'    =>    array('Mobile','Backend','Api','Admin','Home','User','Weixin'),
    'DEFAULT_MODULE'       =>    'Home',

    //
   // 'DEFAULT_MODULE'     => 'Weixin', //默认模块
    'DEFAULT_CONTROLLER'    =>  'Index', // 默认控制器名称
     'DEFAULT_ACTION'        =>  'index', // 默认操作名称

    'URL_MODEL'          => '2', //URL模式
    'SESSION_AUTO_START' => true, //是否开启session

    // 'DB_PREFIX' => 'yytb_', //  数据库表前缀
    //  'DB_CHARSET'	=> 'utf8', // 字符集
   // 'DB_FIELDTYPE_CHECK' => false, //  是否进行字段类型检查
   // 'DB_FIELDS_CACHE' => true, //  启用字段缓存
   // 'TMPL_ACTION_ERROR' =>  'Layout/tiaozhuan',
    'TMPL_ACTION_SUCCESS'  => 'Index/message',


    'TMPL_PARSE_STRING' => array(
        // '__LIB_URL__' => 'http://api.asilu.com/cdn',
        '__CDN__' => 'http://api.asilu.com/cdn',
        '__STATIC__' => __ROOT__ . '/web',
        '__WEB__' => __ROOT__ . '/web',
        '__LIB_URL__' => __ROOT__ . '/web/lib',
        '__WXP__' => __ROOT__ . '/web/weixin', //微信端的公共资源（js、css、图片等）
        '__PCP__' => __ROOT__ . '/web/home', //PC端的公共资源（js、css、图片等）


        // '__LIB_CSS__' => 'http://api.asilu.com/cdn',
        // '__LIB_URL__' => 'http://api.asilu.com/cdn',
    ),

    //



    // 'ADMIN_USER_TABLE' => 'admin_user', // 管理员表

    // 'VIEW_PATH' => './View/',
    'DEFAULT_THEME'  		=>	'', // 默认模板主题名称
    'THEME_NAME'  		=>	'default',

    // 模板文件的默认后缀的情况是.html
    //  'TMPL_TEMPLATE_SUFFIX'	=>	'.html',

    'SEND_MSG_SIGN' => 'aeeeyy789oi78j6$Z*&^%$#E#$FGBHUHygy53.5',

    'URL_ROUTER_ON'   => false,

    'URL_ROUTE_RULES'=>array(
        'i' => array('Home/Index', 'echo_log=1'),
        'news/:id'               => 'News/read',
        'news/read/:id'          => '/news/:1',
    ),

    //微信常量设置
    'WX_LIST' => array(
        'token'=>'cndiandian', //填写你设定的key
        'encodingaeskey'=>'KCg2Q3xQZ6m2peNSjNUEUAZIZtMs1b3v3zvBUzF3FwL', //填写加密用的EncodingAESKey，如接口为明文模式可忽略
        'appid'=>'wx44e75a73a7b50d77', //填写高级调用功能的app id
        'appsecret'=>'77756ee72d11921a4f561cc5237e7b63' //填写高级调用功能的密钥
    ),

    //引入类

   // 'page' =>LIB_PATH.'Common/Page.class.php',
);

//require ('./include/lib/Message/TopSdk.php');




/*define ('ACCESS',1);
error_reporting(E_ALL ^ E_NOTICE);
//autoload 使用常量
define ( 'ADMIN_BASE', dirname ( __FILE__ ) . '/../../include' );
define ( 'ADMIN_BASE_LIB', ADMIN_BASE . '/lib/' );
define ( 'ADMIN_BASE_CLASS', ADMIN_BASE . '/class/' );

//Smarty模板使用常量
define ( 'TEMPLATE_DIR', ADMIN_BASE . '/template/' );
define ( 'TEMPLATE_COMPILED', ADMIN_BASE . '/compiled/' );
define ( 'TEMPLATE_PLUGINS', ADMIN_BASE_LIB . 'Smarty/plugins/' );
define ( 'TEMPLATE_SYSPLUGINS', ADMIN_BASE_LIB . 'Smarty/sysplugins/' );
define ( 'TEMPLATE_CONFIGS', ADMIN_BASE . '/config/' );
define ( 'TEMPLATE_CACHE', ADMIN_BASE . '/cache/' );

//OSAdmin常量
define ( 'ADMIN_URL' , 'http://localhost/backend');
//define ( 'ADMIN_URL' , 'http://localhost/dev');
define ( 'ADMIN_TITLE' ,'管理后台');
define ( 'COMPANY_NAME' ,'管理后台');

//微信常量设置
$WX_LIST = array(
    'token'=>'cndiandian', //填写你设定的key
    'encodingaeskey'=>'KCg2Q3xQZ6m2peNSjNUEUAZIZtMs1b3v3zvBUzF3FwL', //填写加密用的EncodingAESKey，如接口为明文模式可忽略
    'appid'=>'wx44e75a73a7b50d77', //填写高级调用功能的app id
    'appsecret'=>'77756ee72d11921a4f561cc5237e7b63' //填写高级调用功能的密钥
);

//OSAdmin数据库设置
define ( 'OSA_DB_ID' ,'adbe');
$DATABASE_LIST[OSA_DB_ID] = array (
    "server"=>'localhost',
    "port"=>'3306',
    "username"=> 'root',
    "password"=>'',
    "db_name"=>'adbe' );

//样例数据库设置
define ( 'SAMPLE_DB_ID' ,'adbe');
$DATABASE_LIST[SAMPLE_DB_ID] = array (
    "server"=>'127.0.0.1',
    "port"=>'3306',
    "username"=> 'root',
    "password"=>'',
    "db_name"=>'adbe' );


//COOKIE加密密钥，建议修改
define( 'OSA_ENCRYPT_KEY','whatafuckingday!');

//prefix不要更改，除非修改osadmin.sql文件中的所有表名
define ( 'OSA_TABLE_PREFIX' ,'osa_');

//页面设置
define ( 'DEBUG' ,false);
define ( 'PAGE_SIZE', 25 );

$OSA_TEMPLATES=array(
    'default'=>"默认模板",
    'schoolpainting'=>'青葱校园',
    'blacktie'=>'黑色领结',
    'wintertide'=>'冰雪冬季',
);

$OSADMIN_COMMAND_FOR_LOG=array(
    'SUCCESS'=>'成功',
    'ERROR'=>'失败',
    'ADD'=>'增加',
    'DELETE'=>'删除',
    'MODIFY'=>'修改',
    'LOGIN'=>'登录',
    'LOGOUT'=>'退出',
    'PAUSE'=>'封停',
    'PLAY'=>'解封',
);

$OSADMIN_CLASS_FOR_LOG=array(
    'ALL' => '全部',
    'User'=>'用户',
    'UserGroup'=>'账号组',
    'Module'=>'菜单模块',
    'MenuUrl'=>'功能',
    'GroupRole'=>'权限',
    'QuickNote'=>'QuickNote',
);*/
<?php 

#define('ADMIN_URL','http://lhq.cndiandian.cn');
define('ADMIN_URL','http://localhost/lhq');
define('Click_URL',ADMIN_URL.'/api/ReportClickApi/index');
define('Active_URL',ADMIN_URL.'/api/ReportActiveApi/index');
define('Callback_URL',ADMIN_URL.'/api/CallbackApi/index');
define('Queryidfa_URL',ADMIN_URL.'/api/CheckIdfa/index');


/**
 * 发送HTTP请求方法
 * @param  string $request_url  请求URL
 * @return array  $data   响应数据
 */
function httpGet($request_url){
	/* 初始化并执行curl请求 */
	$ch = curl_init();
	//设置选项
	curl_setopt($ch, CURLOPT_URL, $request_url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	//执行
	$data = curl_exec($ch);
	$error = curl_error( $ch );
	//关闭curl
	curl_close($ch);  
	if( $error ) throw new Exception( '请求发生错误：' . $error ) ;
	return $data ;
}

?>
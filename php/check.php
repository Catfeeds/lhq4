<?php

session_start();



if(!is_login()) die('请登录后再试');


function is_login(){
	$user = isset($_SESSION['user_auth']) ? $_SESSION['user_auth'] : '';
	$auth_sign = isset($_SESSION['user_auth_sign']) ? $_SESSION['user_auth_sign'] : '';

	if (empty($user)) {
		return 0;
	} else {
		return $auth_sign == data_auth_sign($user) ? $user['uid'] : 0;
	}
}


function data_auth_sign($data){
	//数据类型检测
	if (!is_array($data)) {
		$data = (array)$data;
	}
	ksort($data); //排序
	$code = http_build_query($data); //url编码并生成query字符串
	$sign = sha1($code); //生成签名
	return $sign;
}








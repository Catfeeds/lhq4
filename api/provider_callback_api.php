<?php
require_once('./config.php');
	$idfa = $mac = $appid = $chanid = $ip = $plog_id = $callback_time = $openudid = $missionId=   $body = '';
	extract ( $_GET, EXTR_IF_EXISTS );
	extract ( $_POST, EXTR_IF_EXISTS );
	//请求地址
	$request_url = Callback_URL."?appid=$appid&chanid=$chanid&idfa=$idfa&mac=$mac&ip=$ip&openudid=$openudid&body=$body";
    //执行请求
    $data = httpGet($request_url);
    echo $data;
?>

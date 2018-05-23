<?php
require_once('./config.php');
	$idfa = $mac = $appid =$chanid= $ip = $plog_id = $callback_time = $callback = $openudid = $os =$devicemodel= $kid = '';
	extract ( $_GET, EXTR_IF_EXISTS );
	//请求地址
	$request_url = Click_URL."?appid=$appid&chanid=$chanid&idfa=$idfa&mac=$mac&ip=$ip&openudid=$openudid&os=$os&callback=$callback&devicemodel=$devicemodel&kid=$kid";
    //执行请求
    $data = httpGet($request_url);
    echo $data;
?>


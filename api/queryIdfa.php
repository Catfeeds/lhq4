<?php
require_once('./config.php');
    $idfa = $mac = $app = $ip = $plog_id = $callback_time = $appid = $chanid= $kid ='';
    extract ( $_GET, EXTR_IF_EXISTS );
    extract ( $_POST, EXTR_IF_EXISTS );
    //请求地址
	$request_url = Queryidfa_URL."?appid=$appid&chanid=$chanid&idfa=$idfa&mac=$mac&ip=$ip&app=$app&kid=$kid";
    //执行请求
    $data = httpGet($request_url);
    echo $data;

?>

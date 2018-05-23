<?php
/*function checkDevice()
{
    checkKey();
    return true;
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')) {
        checkKey();
        return true;
    } else {
        $url='welcome/index.html';
        header("Location:".$url);
    }
}
function checkKey()
{
    $member_id =(int) $_SESSION['member_id'];
//    $member_id='0';
//    var_dump($member_id);
    if ($member_id<1||$member_id==''||$member_id ==NULL) {
        Template::assign ( 'noaccess', 0);
    } else {
        Template::assign ( 'noaccess', 1);
    }
}

//获取ip的值
function getIp(){
    if (isset($_SERVER)){
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
            $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            $realip = $_SERVER["HTTP_CLIENT_IP"];
        } else {
            $realip = $_SERVER["REMOTE_ADDR"];
        }
    } else {
        if (getenv("HTTP_X_FORWARDED_FOR")){
            $realip = getenv("HTTP_X_FORWARDED_FOR");
        } else if (getenv("HTTP_CLIENT_IP")) {
            $realip = getenv("HTTP_CLIENT_IP");
        } else {
            $realip = getenv("REMOTE_ADDR");
        }
    }
    return $realip;
}*/
?>
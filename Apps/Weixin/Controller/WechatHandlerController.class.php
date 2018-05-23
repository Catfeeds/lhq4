<?php
namespace Weixin\Controller;

use Think\Controller;

class WechatHandlerController extends Controller{
    //public function index(){
	   // echo $this->valid();
	 //  $this->responseMsg();
	//}
//$wechatObj->responseMsg();
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        //valid signature , option
        if($this->checkSignature()){
			//file_put_contents('C:/weixn.txt',$echoStr,FILE_APPEND);
        	return $echoStr;
        }
    }
	public function responseMsg()
    {
		//get post data, May be due to the different environments
		//$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        $postStr = file_get_contents("php://input"); 
      	//extract post data
		if (!empty($postStr)){
                /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
                   the best way is to check the validity of xml by yourself */
                libxml_disable_entity_loader(true);
              	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
                $keyword = trim($postObj->Content);
                $time = time();
                $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";  					
				if(!empty( $keyword ))
                {
              		$msgType = "text";
                	$contentStr = "Welcome to wechat world!";
                	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                	echo $resultStr;
                }else{
                	echo "Input something...";
                }

        }else {
        	echo "";
        	exit;
        }
    }
		
	public function checkSignature()
	{   
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        		
		file_put_contents('C:/weixn.txt',$_GET["signature"]."   ",FILE_APPEND);
		file_put_contents('C:/weixn.txt',$_GET["timestamp"]."  ",FILE_APPEND);
		file_put_contents('C:/weixn.txt',$_GET["nonce"]."   ",FILE_APPEND);
		$token = "weixin";
		$tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			//file_put_contents('C:/weixn.txt','hao',FILE_APPEND);
			return true;
		}else{
			//file_put_contents('C:/weixn.txt','cuo',FILE_APPEND);
			return false;
		}
	}

               
    public function index(){

	$options = array(
    'token'=>'asdsdwddd', //填写你设定的key
    'appid'=>'wx079fa9562e7dfe17', //填写高级调用功能的app id
    'appsecret'=>'b9db72b554eca444325a1d19bd364c77' //填写高级调用功能的密钥
    );
    Vendor('WeChatphp.wechat','','.class.php');
    $weObj = new \Wechat($options);
    //$weObj->valid();
	$type = $weObj->getRev()->getRevType();
        switch($type) {
        	case  $weObj::MSGTYPE_EVENT:
        	    $eventParam = $weObj->getRevEvent();
        	    if($eventParam['event'] ==  $weObj::EVENT_SCAN || $eventParam['event'] ==  $weObj::EVENT_SUBSCRIBE) {
        	 	    $txt = "你好，欢迎关注我";
        	 	    $weObj->text($txt)->reply();
                    break;
				}	
			case $weObj::MSGTYPE_TEXT;
			    $txt = "http://qinjiwei.ittun.com/yydb/index.php/Weixin/index/index";
        	    $weObj->text($txt)->reply();
			
        	
        }
	//获取菜单操作:
    $menu = $weObj->getMenu();
    //设置菜单
    $newmenu =  array(
    		"button"=>
    			array(
    				array('type'=>'view','name'=>'一元夺宝','url'=>'http://qinjiwei.ittun.com/yydb/index.php/Weixin/index/index'),
    				)
   		);
    $result = $weObj->createMenu($newmenu);

    }
}	

?>
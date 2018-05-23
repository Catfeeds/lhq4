<?php
namespace Common\Common;
//use Common\Model\Menu_urlModel;
class Common {

	//获取OSAdmin的action_url，用于权限验证
	/* public static function getActionUrl(){
		$action_script=$_SERVER['SCRIPT_NAME'];
//		dump(" == ".$_SERVER['SCRIPT_NAME']);
	
		$admin_url = strtolower(ADMIN_URL);
		if($admin_url{strlen($admin_url)-1}=="/"){
			$admin_url = substr($admin_url,0,strlen($admin_url)-1);
		}
		
		$http_pos = strpos($admin_url,'http://');
		
		if($http_pos !== false){
			$admin_url_no_http = substr($admin_url,7);			
		}else{
			$admin_url_no_http=$admin_url;
		}
		$slash = 0;
		$slash=strpos($admin_url_no_http,'/');
		
		if($slash){
			$sub_dir = substr($admin_url_no_http,$slash);
			$action_url = substr($action_script,strlen($sub_dir));
		}else{
			$action_url =$action_script;
		}
//		dump($action_url);
		return str_replace('//','/',$action_url);
	} */
	/**
	 * 获取当前页面完整URL地址
	*/
	public static function getActionUrl() {
		$admin_url = strtolower(ADMIN_URL);
		$sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
		$php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
		$path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
		$relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : $path_info);
		
		$admin_url = strtolower(ADMIN_URL);
		if($admin_url{strlen($admin_url)-1}=="/"){
			$admin_url = substr($admin_url,0,strlen($admin_url)-1);
		}
		
		$http_pos = strpos($admin_url,'http://');
		
		if($http_pos !== false){
			$admin_url_no_http = substr($admin_url,7);
		}else{
			$admin_url_no_http=$admin_url;
		}
		$slash = 0;
		$slash=strpos($admin_url_no_http,'/');

		if($slash){
			$sub_dir = substr($admin_url_no_http,$slash);
			
			$action_url = substr($relate_url,strlen($sub_dir));
		}else{
			$action_url =$relate_url;
		}
		$slash1 = 0;
		$slash1=strpos($action_url,'?');
		if($slash1) {
			$action_url = substr($action_url, 0,$slash1);
		}
		return $action_url;
	}

	

	
	public static function checkParam($param,$to_url=null){
		
		if($to_url == null ){
			if(array_key_exists('HTTP_REFERER',$_SERVER)){
				$referer = $_SERVER['HTTP_REFERER'];
			}
			if(!empty($referer)){
				$start = strpos($referer,ADMIN_URL);
				$to_url = substr($referer,$start+strlen(ADMIN_URL));
			}else{
				$to_url = 'index.php';
			}
		}
		
		if (empty ( $param )) {
			Common::exitWithError ( '缺少必要的参数', $to_url ,3,"error" );
		}
	}
	
	public static function jumpUrl($url) {
		
		Header ( "Location: ".__ROOT__."/backend/$url" );
		//return true;
	}
	
	public static function isPost() {
		return $_SERVER ['REQUEST_METHOD'] === 'POST' ? TRUE : FALSE;
	}
	
	public static function isGet() {
		return $_SERVER ['REQUEST_METHOD'] === 'GET' ? TRUE : FALSE;
	}
	
	public static function getIp() {
		if (getenv ( "HTTP_CLIENT_IP" ) && strcasecmp ( getenv ( "HTTP_CLIENT_IP" ), "unknown" )) {
			$ip = getenv ( "HTTP_CLIENT_IP" );
		} elseif (getenv ( "HTTP_X_FORWARDED_FOR" ) && strcasecmp ( getenv ( "HTTP_X_FORWARDED_FOR" ), "unknown" )) {
			$ip = getenv ( "HTTP_X_FORWARDED_FOR" );
		} elseif (getenv ( "REMOTE_ADDR" ) && strcasecmp ( getenv ( "REMOTE_ADDR" ), "unknown" )) {
			$ip = getenv ( "REMOTE_ADDR" );
		} elseif (isset ( $_SERVER ['REMOTE_ADDR'] ) && $_SERVER ['REMOTE_ADDR'] && strcasecmp ( $_SERVER ['REMOTE_ADDR'], "unknown" )) {
			$ip = $_SERVER ['REMOTE_ADDR'];
		} else {
			$ip = "unknown";
		}
		return ($ip);
	}
	
	public static function getDateTime($time = null) {
		
		return (!is_numeric($time)) ? date ( 'Y-m-d H:i:s' ) : date( 'Y-m-d H:i:s', $time);
	}
	
	public static function getTime() {
		return strtotime(date( 'Y-m-d H:i:s' ));
	}
	
	public static function getSysInfo() {
		$sys_info_array = array ();
		$sys_info_array ['gmt_time'] = gmdate ( "Y年m月d日 H:i:s", time () );
		$sys_info_array ['bj_time'] = gmdate ( "Y年m月d日 H:i:s", time () + 8 * 3600 );
		$sys_info_array ['server_ip'] = gethostbyname ( $_SERVER ["SERVER_NAME"] );
		$sys_info_array ['software'] = $_SERVER ["SERVER_SOFTWARE"];
		$sys_info_array ['port'] = $_SERVER ["SERVER_PORT"];
		$sys_info_array ['admin'] = $_SERVER ["SERVER_ADMIN"];
		$sys_info_array ['diskfree'] = intval ( diskfreespace ( "." ) / (1024 * 1024) ) . 'Mb';
		$sys_info_array ['current_user'] = @get_current_user ();
		$sys_info_array ['timezone'] = date_default_timezone_get();
//		$db=new Medoo(OSA_DB_ID);
		$mysql_version = (new \Think\Model())->query("select version()");
		$sys_info_array ['mysql_version'] = $mysql_version[0]['version()'];
		return $sys_info_array;
	}
	
	public static function transact($options) {
		$temp_array = array ();
		foreach ( $options as $k => $v ) {
			if (is_null ( $v ) || (is_string ( $v ) && trim ( $v ) == ''))
				$v = '';
			else
				is_array ( $v ) ? $v = self::transact ( $v ) : $v = ( string ) $v;
			$temp_array [$k] = $v;
		}
		return $temp_array;
	}
	
	public static function getRandomIp() {
		$ip = '';
		for($i = 0; $i < 4; $i ++) {
			$ip_str = rand ( 1, 255 );
			$ip .= ".$ip_str";
		}
		$ip = substr($ip, 1);
		
		return $ip;
	}
	
	public static function filterText($text){
		if(ini_get('magic_quotes_gpc')){
			$text=stripslashes($text);
		}
		return $text;
	}
}
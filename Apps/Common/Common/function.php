<?php

/**
 * 发送HTTP请求方法
 * @param  string $url    请求URL
 * @param  array  $params 请求参数
 * @param  string $method 请求方法GET/POST
 * @return array  $data   响应数据
 */
function http( $url , $params = null , $method = 'POST' , $header = array() ) {

	$opts = array(
		CURLOPT_TIMEOUT => 30,CURLOPT_RETURNTRANSFER => 1,CURLOPT_SSL_VERIFYPEER => false,CURLOPT_SSL_VERIFYHOST => false,CURLOPT_HTTPHEADER => $header
	) ;
	/* 根据请求类型设置特定参数 */
	switch( strtoupper( $method ) ){
		case 'GET' :
			if( $params ){
				$opts[CURLOPT_URL] = $url . '?' . http_build_query( $params ) ;
			}else{
				$opts[CURLOPT_URL] = $url ;
			}
			$opts[CURLOPT_URL] = trim( $opts[CURLOPT_URL] ) ;
			
			break ;
		case 'POST' :
			$opts[CURLOPT_URL] = $url ;
			$opts[CURLOPT_POST] = 1 ;
			$opts[CURLOPT_POSTFIELDS] = $params ;
			break ;
		default:
			throw new Exception( '不支持的请求方式！' ) ;
	}
	
	/* 初始化并执行curl请求 */
	$ch = curl_init( ) ;
	curl_setopt_array( $ch , $opts ) ;
	$data = curl_exec( $ch ) ;
	$error = curl_error( $ch ) ;
	curl_close( $ch ) ;
	if( $error ) throw new Exception( '请求发生错误：' . $error ) ;
	return $data ;

}





function checkHostByIlanguo(){
}





/**
 * 获取最后一期时时彩信息
 */
function getLastSsc( ) {

	static $info = false ;
	if( $info ){
		return $info ;
	}
	if( ! $info ){
		$info = S( 'last_ssc_info' ) ;
		if( ! $info ){
			$json = json_decode( file_get_contents( "http://f.apiplus.cn/cqssc.json" ) , true ) ;
			if( isset( $json['data'][0] ) && isset( $json['data'][0]['expect'] ) && isset( $json['data'][0]['opencode'] ) ){
				$info = array(
					'expect' => $json['data'][0]['expect'],'code' => preg_replace( '/[^\d]/' , '' , $json['data'][0]['opencode'] )
				) ;
				S( 'last_ssc_info' , $info , 90 ) ;
				return $info ;
			}
		}
	}
	return $info ;

}

//强制浏览器进行缓存
function browserCache( $expires = 20 , $hash = 'xxx' , $time = false ) {

	$time = $time ? $time : time( ) ;
	header( "Last-Modified: " . date( 'r' , $time ) ) ;
	header( "Expires: " . date( "r" , ( $time + $expires ) ) ) ;
	header( "Cache-Control: max-age=$expires" ) ;
	header( "Etag: $hash" ) ;
	
	if( isset( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) && ! empty( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) && ( strtotime( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) + $expires > time( ) ) ){
		header( "Etag: $hash" , true , 304 ) ;
		exit( ) ;
	}

}

/**
 * 根据开奖码倒退时间和
 * @date 2016年1月5日 上午3:54:21
 * @author 王崇全
 * @param string $winCode 作弊抽奖码
 * @param string $amount 总需参与数
 * @param string $timeSum 正常时间和
 * @return string 作弊时间和
 */
function getTimeSum( $winCode , $amount , $timeSum ) {

	return ( $winCode - ( $timeSum % $amount + 10000001 ) ) + $timeSum ;

}

/**
 * 无限分级数据 生成 <option> HTML
 * @param list array 数据
 * @return string 返回 <option> HTML
 */
function getOptionsHtml( $list , $_pid = 0 , $lv = '' ) {

	$html = "" ;
	foreach( $list as $v ){
		if( $v['pid'] == $_pid ){
			$html .= '<option value="' . $v['id'] . '">' . $lv . $v['name'] . "</option>\r\n" ;
			$html .= getOptionsHtml( $list , $v['id'] , "--$lv" ) ;
		}
	}
	return $html ;

}

/**
 * 无限分级数据前 加 --
 * @param list array 数据
 * @return array 返回 更改 name 后的数据
 */
function _____ddddd( $list , $_pid = 0 , $lv = '' ) {

	$new = array() ;
	foreach( $list as $v ){
		if( $v['pid'] == $_pid ){
			$v['name'] = $lv . $v['name'] ;
			$new[] = $v ;
			$new = array_merge( $new , _____ddddd( $list , $v['id'] , "--$lv" ) ) ;
		}
	}
	return $new ;

}

/**
 * 输出 数据到浏览器 并退出
 * @return void
 */
function _d( $json ) {

	@header( "Content-Type: text/text; charset=utf-8" ) ;
	print_r( $json ) ;
	die( ) ;

}

/**
 * 用户信息加密 签名生成
 * @param array $data 要加密的数据
 * @return string 加密后字符串
 */
function userDataEncode( $data = '' ) {

	return md5( sha1( http_build_query( $data ) ) ) ;

}

/**
 * 格式化 时间戳 (支持毫秒 x 为毫秒，要格式化的时间戳 小数部分为 毫秒, 用法同原生 date)
 * @param string $f 时间格式化
 * @return string 格式化后的字符串
 */
function _date( $f = false , $time = false ) {

	$time = $time ? $time : _time( ) ;
	
	if( $f === false ) return $time ;
	
	$u = substr( $time , strpos( $time , '.' ) + 1 ) ;
	
	return str_replace( 'x' , $u , date( $f , $time ) ) ;

}

/**
 * 返回当前时间戳 (单位秒, 精度 毫秒, 三位小数部分)
 * @return float 时间戳
 */
function _time( ) {

	return round( microtime( true ) , 3 ) ;

}

/**
 * APP 端 JSON 数据输出函数
 * @param string
 * @return void
 */
function appJson( $json ) {
	// @header("Access-Control-Allow-Origin: *");
	if( defined( 'UID' ) ) $json = array_merge( $json , array(
		'uid' => UID,'cookie' => isset( $GLOBALS['userData'] ) ? $GLOBALS['userData'] : ''
	) ) ;
	
	json( $json ) ;

}

/**
 * APP 端 返回错误信息
 * @param string
 * @return void
 */
function appError( $msg = '操作错误' ) {

	appJson( array(
		'code' => 500,'msg' => $msg
	) ) ;

}

/**
 * APP 端 返回成功信息
 * @param string
 * @return void
 */
function appSuccess( $msg = '操作成功' ) {

	appJson( array(
		'code' => 1,'msg' => $msg
	) ) ;

}

/**
 * 删除url中的指定参数
 * @date: 2015年11月13日 下午1:32:43
 * @author: 王崇全
 * @param: string $param 要去除的参数
 * @param: string $url 要去除指定参数的url
 * @return:string $sds 去除指定参数的url
 */
function unsetParam( $param , $url ) {

	return preg_replace( array(
		"/{$param}=[^&]*/i",'/[&]+/','/\?[&]+/','/[?&]+$/'
	) , array(
		'','&','?',''
	) , $url ) ;

}

/**
 * 后台 JSON 数据输出函数
 * @param string
 * @return void
 */
function admJson( $json ) {
	// @header("Access-Control-Allow-Origin: *");
	if( is_array( $json ) ) $json = array_merge( array(
		'code' => 200
	) , $json ) ;
	json( $json ) ;

}

/**
 * 输出 JSON 数据
 * @param string
 * @return void
 */
function json( $json ) {

	$json = is_array( $json ) ? jsonEncode( $json ) : $json ;
	@header( "Content-Type: application/json; charset=utf-8" ) ;
	die( $json ) ;

}

/**
 * JSON 编码保留中文
 * @param array $data 要编码的数据 数组或对象
 * @return string JSON 字符串
 */
function jsonEncode( $data ) {

	if( version_compare( PHP_VERSION , '5.4.0' , '>=' ) )
		return json_encode( $data , JSON_UNESCAPED_UNICODE ) ;
	else
		return json_encode( $data ) ;

}
//获取真实IP
function getip() {
	$unknown = 'unknown';
	if ( isset($_SERVER['HTTP_X_FORWARDED_FOR'])	&& $_SERVER['HTTP_X_FORWARDED_FOR']	&& strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'],	$unknown) ) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} elseif ( isset($_SERVER['REMOTE_ADDR'])
		&& $_SERVER['REMOTE_ADDR'] &&
		strcasecmp($_SERVER['REMOTE_ADDR'], $unknown) ) {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	/*
    处理多层代理的情况
    或者使用正则方式：$ip = preg_match("/[d.]
    {7,15}/", $ip, $matches) ? $matches[0] : $unknown;
    */
	if (false !== strpos($ip, ','))
		$ip = reset(explode(',', $ip));
	return $ip;
}

/**
 * 验证手机号码
 * @param string $phone 要验证的手机号码
 * @return boolean
 */
function checkPhoneNum( $phone = '' ) {

	return preg_match( '/^1\d{10}$/' , $phone ) ;

}

/**
 * 生成订单号码
 * @param int $len 订单号长度
 * @return string 生成的订单号
 */
function getOrderNumber( $len = 32 ) {

	return substr( date( 'YmdHis' ) . md5( uniqid( mt_rand( ) , true ) ) , 0 , $len ) ;

}

/**
 * HTTP 请求函数 模拟 GET、POST、PUT、DELETE 请求
 * @param string $url 请求地址
 * @param string/array $post POST 数据 字符串或数组
 * @param array $headers 自定义请求头部信息
 * @param string $type 请求方式 GET/POST/PUT/DELETE  (GET POST 自动识别, 其它需定义)
 * @param int $timeout 超时时间 默认 25 秒
 * @return void
 */
function httpGet( $url = false , $post = array() , $headers = array() , $type = 'GET' , $timeout = 25 ) {

	if( ! $url ) return false ;
	
	$c = curl_init( ) ;
	curl_setopt( $c , CURLOPT_URL , $url ) ;
	// curl_setopt($c, CURLOPT_PORT, '80');
	curl_setopt( $c , CURLOPT_HEADER , false ) ;
	if( ! empty( $post ) ){
		curl_setopt( $c , CURLOPT_POST , true ) ;
		curl_setopt( $c , CURLOPT_POSTFIELDS , ( is_array( $post ) ? http_build_query( $post ) : $post ) ) ;
	}
	// curl_setopt($c, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
	

	curl_setopt( $c , CURLOPT_CUSTOMREQUEST , $type ) ; // GET POST PUT DELETE
	curl_setopt( $c , CURLOPT_RETURNTRANSFER , true ) ;
	curl_setopt( $c , CURLOPT_SSL_VERIFYPEER , false ) ;
	curl_setopt( $c , CURLOPT_SSL_VERIFYHOST , false ) ;
	
	curl_setopt( $c , CURLOPT_TIMEOUT , $timeout ) ;
	if( ! empty( $headers ) ){
		curl_setopt( $c , CURLOPT_HTTPHEADER , $headers ) ;
	}
	
	$content = curl_exec( $c ) ;
	$response = curl_getinfo( $c ) ;
	curl_close( $c ) ;
	
	// if($response['http_code'] > 400) return false;
	

	return array(
		'response' => $response,'content' => $content
	) ;

}

/**
 * 判断用户是否登录
 * @return int 用户 ID   0： 未登录
 */
function is_login( ) {

	$user = session( 'user_auth' ) ;
	
	if( empty( $user ) ){
		return 0 ;
	}else{
		return session( 'user_auth_sign' ) == data_auth_sign( $user ) ? $user['uid'] : 0 ;
	}

}

/**
 * 判断用户是否登录 0元购
 * @return int 用户 ID   0： 未登录
 */
function is_login_zero( ) {

	$user = session( 'userInfo' ) ;
	if( empty( $user ) ){
		return 0 ;
	}else{
		return $user['id'] ;
	}

}

function encrypt_pass( $pass ) {

	return md5( sha1( $pass ) ) ;

}

/**
 * 数据签名认证
 * @param  array $data 被认证的数据
 * @return string       签名
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function data_auth_sign( $data ) {
	//数据类型检测
	if( ! is_array( $data ) ){
		$data = (array) $data ;
	}
	ksort( $data ) ; //排序
	$code = http_build_query( $data ) ; //url编码并生成query字符串
	$sign = sha1( $code ) ; //生成签名
	return $sign ;

}

/**
 * 根据用户ID获取用户昵称
 * @param  integer $uid 用户ID
 * @return string       用户昵称
 */
function get_nickname( $uid = 0 ) {

	static $list ;
	if( ! ( $uid && is_numeric( $uid ) ) ){ //获取当前登录用户名
		return session( 'user_auth.username' ) ;
	}
	
	/* 获取缓存数据 */
	if( empty( $list ) ){
		$list = S( 'sys_user_nickname_list' ) ;
	}
	
	/* 查找用户信息 */
	$key = "u{$uid}" ;
	if( isset( $list[$key] ) ){ //已缓存，直接使用
		$name = $list[$key] ;
	}else{ //调用接口获取用户信息
		$info = M( 'Adminer' ) -> field( 'nickname' ) -> find( $uid ) ;
		if( $info !== false && $info['nickname'] ){
			$nickname = $info['nickname'] ;
			$name = $list[$key] = $nickname ;
			/* 缓存用户 */
			$count = count( $list ) ;
			$max = C( 'USER_MAX_CACHE' ) ;
			while( $count -- > $max ){
				array_shift( $list ) ;
			}
			S( 'sys_user_nickname_list' , $list ) ;
		}else{
			$name = '' ;
		}
	}
	return $name ;

}

function writeLog( ) {


}

/*
 * 生成随机卡号及密码
 */
function crtpaycard( ) {

	$count = 10 ;
	$data = null ;
	$temp = M( ) -> query( "select max(payCardNum) as num from yytb_pay_card" ) ;
	if( ! ( $temp[0]['num'] > 0 ) ){
		$num = 100000000000 ;
	}else{
		$num = $temp[0]['num'] ;
	}
	
	for( $i = 1 ; $i <= 10 ; $i ++ ){
		$num += 1 ;
		
		$data["payCardNum"] = $num ;
		
		$data["pass"] = getrandchar( 8 ) ;
		
		$data["payCardSize"] = 50 ;
		
		$data["overdueDate"] = 1449658284 ;
		M( 'pay_card' ) -> add( $data ) ;
	}

}

function getrandchar( $length ) {

	$str = null ;
	$strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz" ;
	$max = strlen( $strPol ) - 1 ;
	
	for( $i = 0 ; $i < $length ; $i ++ ){
		$str .= $strPol[rand( 0 , $max )] ; //rand($min,$max)生成介于min和max两个数之间的一个随机整数
	}
	
	echo $str ;

}

/**
 * 格式化开奖日期
 */
function formattime( $time ) {

	return date( 'm/d/Y H:i:s' , $time ) ;

}

/**
 * 更加goodsid获取商品图片
 * $goodsid 商品id
 */
function getimages( $goodsid ) {

	$list = M( 'goods_detail' ) -> field( 'content' ) 
		-> where( 'goodsId=' . $goodsid ) 
		-> select( ) ;
	if( $list ){
		return $list ;
	}

}

/*
 * 根据商品id获取商品详情
 */
function getGoodsinfo( $goodsid ) {

	$arr = M( 'goods' ) -> where( 'id = ' . $goodsid ) -> find( $goodsid ) ;
	if( $arr ){
		return $arr ;
	}

}

/**
 * 根据uid获取用户信息
 * $uid
 */
function getUserinfo( $uid ) {

	if( $uid ){
		$info = D( 'user' ) -> field( 'member_id,nickname,sex,birthday,pic,phone,addr' ) -> find($uid) ;
		if( $info ){
			return $info ;
		}
	}

}

/**
 * 生成随机云购码
 * $pid 云购表periods id
 */
function buildPcode( $pid = false ) {

	if( ! $pid ) die( 'error' ) ;
	
	//判断云购码是否存在
	$pcode = M( 'pcode' ) -> where( "pid=$pid" ) -> find( ) ;
	if( $pcode ) return false ;
	
	//获取云购码份数
	$originprice = M( 'goods' ) -> alias( 'a' ) 
		-> join( 'left join __PERIODS__ b on a.id=b.goodsid' ) 
		-> where( 'b.id=' . $pid ) 
		-> find( ) ;
	$fenshu = intval( $originprice['fenshu'] ) ;
	
	if( $fenshu <= 0 ) return false ; //判断份数是否为空
	

	$data = array() ;
	for( $i = 1 ; $i <= $fenshu ; $i ++ ){
		$pcode = 10000000 + $i ;
		
		$data[] = array(
			'pid' => $pid,'pcode' => $pcode,'status' => 0
		) ;
		
		if( $i % 20000 == 19999 ){
			shuffle( $data ) ; //随机排序
			//插入云购码
			if( ! empty( $data ) ){
				M( 'pcode' ) -> addAll( $data ) ;
				$data = array() ;
			}
		}
	}
	
	shuffle( $data ) ; //随机排序
	//插入云购码
	if( ! empty( $data ) ){
		M( 'pcode' ) -> addAll( $data ) ;
	}
	
	return true ;

}

/**
 * 获取随机云购码
 * $pid 云购表periods id
 */
//		function getPcode($pid){
//			if (!$pid) return false;
//
//			//判断云购码是否存在
//			$pcode = M('pcode')->field('pcode')->where("pid=$pid and status = 0")->order('rand()')->limit(1)->find();
//			if(!$pcode) return false;
//
//			if(M('pcode')->data(array('status'=>1))->where($pcode)->save()){
//				return $pcode['pcode'];
//			}
//			return false;
//		}
function getPcode( $pid , $num ) {

	if( ! $pid ) return ;
	if( ! isset( $num ) ) $num = 1 ;
	//判断云购码是否存在
	$pcode = M( 'pcode' ) -> field( 'id,pcode' ) 
		-> where( "pid=$pid and status = 0" ) 
		-> limit( $num ) 
		-> select( ) ;
	//var_dump(count($pcode));exit;
	if( empty( $pcode ) ){ //如果pcode为空
		$s_pcode = M( 'pcode' ) -> field( 'id,pcode' ) 
			-> where( "pid=$pid" ) 
			-> find( ) ; //获取pid云购码
		if( ! $s_pcode ){
			if( buildPcode( $pid ) ){
				//获取detail表
				$pericods_info = M( 'periods' ) -> field( 'goodsid,qishu' ) 
					-> where( 'id=' . $pid ) 
					-> find( ) ;
				
				$where = array(
					'goodsId' => $pericods_info['goodsid'],'qishu' => $pericods_info['qishu']
				) ;
				$exits_pcode = M( 'periods_detail' ) -> field( 'pcode' ) 
					-> where( $where ) 
					-> select( ) ;
				
				foreach( $exits_pcode as $pv ){
					M( 'pcode' ) -> data( array(
						'status' => 1
					) ) 
						-> where( array(
						'pcode' => $pv['pcode'],'pid' => $pid
					) ) 
						-> save( ) ; //更新状态
				}
			}
		}else{
			return false ;
		}
		
		$pcode = M( 'pcode' ) -> field( 'id,pcode' ) 
			-> where( "pid=$pid and status = 0" ) 
			-> limit( $num ) 
			-> select( ) ; //再次获取
	}
	
	if( ! $pcode ) return false ;
	
	$id = '' ;
	foreach( $pcode as $v ){
		$id .= ',' . $v['id'] ;
	}
	$id = substr( $id , 1 ) ;
	
	//更新云购码状态
	if( M( 'pcode' ) -> data( array(
		'status' => 1
	) ) 
		-> where( 'id in(' . $id . ')' ) 
		-> save( ) ){
		return $pcode ; //返回云购码
	}
	return false ;

}

/**
 * 生成随机云购码
 * $pid 云购表periods id
 */
function buildbbPcode( $bbid ) {
	//判断云购码是否存在
	$pcode = M( 'bbpcode' ) -> where( "bbid=$bbid" ) -> find( ) ;
	if( $pcode ) return false ;
	
	//获取云购码份数
	/*
	 $originprice = M( 'goods' ) -> alias( 'a' ) 
	 -> join( 'left join __BBOOKING__ b on a.id=b.goods_id' ) 
	 -> where( 'b.id=' . $bbid ) 
	 -> find( ) ;
	 $fenshu = intval( $originprice['fenshu'] ) ;
	 */
	
	$fenshu = M( "bbooking" ) -> where( "id = $bbid" ) -> getField( 'amount' ) ;
	if( $fenshu <= 0 ) return false ; //判断份数是否为空
	

	$data = array() ;
	//生成云购码
	for( $i = 1 ; $i <= $fenshu ; $i ++ ){
		$pcode = 10000000 + $i ;
		$data[] = array(
			'bbid' => $bbid,'pcode' => $pcode,'status' => 0
		) ;
		
		if( $i % 20000 == 19999 ){
			shuffle( $data ) ; //随机排序
			//插入云购码
			if( ! empty( $data ) ){
				M( 'bbpcode' ) -> addAll( $data ) ;
				$data = array() ;
			}
		}
	}
	shuffle( $data ) ; //随机排序
	//插入云购码
	if( ! empty( $data ) ){
		if( M( 'bbpcode' ) -> addAll( $data ) ){
			return true ;
		}
		return false ;
	}
	return false ;

}

/**
 * 验证码检查
 */
function check_verify($code, $id = ""){
	$verify = new \Think\Verify();
	return $verify->check($code, $id);
}

/**
 * 获取包场随机云购码
 * bbid 包场id
 */
function getbbPcode( $bbid , $num = 1 ) {

	if( ! $bbid ) return ;
	if( ! $num ) $num = 1 ;
	
	//判断云购码是否存在
	$bbpcode = M( 'bbpcode' ) -> field( 'id,pcode' ) 
		-> where( "bbid=$bbid and status = 0" ) 
		-> limit( $num ) 
		-> select( ) ;
	
	//$bbpcode = M('')->query('select id,pcode from __BBPCODE__ where status = 0 and id >= (select floor(rand() * ((select max(id) from __BBPCODE__) - (select min(id) from __BBPCODE__)) + (select min(id) from __BBPCODE__))) order by id limit '.$num);
	if( ! $bbpcode ){
		buildbbPcode( $bbid ) ;
		
		$bbcodes = M( 'bbooking_detail' ) -> where( "Id = $bbid" ) -> getField( "bbcode" , true ) ;
		
		if( is_array( $bbcodes ) ){
			M( 'bbpcode' ) -> where( array(
				'pcode' => array(
					'in',$bbcodes
				),'bbid' => $bbid
			) ) -> save( array(
				'status' => 1
			) ) ;
		}
		
		$bbpcode = M( 'bbpcode' ) -> field( 'id,pcode' ) 
			-> where( "bbid=$bbid and status = 0" ) 
			-> limit( $num ) 
			-> select( ) ;
	}
	
	$id = '' ;
	foreach( $bbpcode as $v ){
		$id .= ',' . $v['id'] ;
	}
	$id = substr( $id , 1 ) ;
	
	//更新云购码状态
	if( M( 'bbpcode' ) -> data( array(
		'status' => 1
	) ) 
		-> where( 'id in(' . $id . ')' ) 
		-> save( ) ){
		return $bbpcode ; //返回云购码
	}
	return false ;

}

/**
 * 通过 WebsocketClient 发送调试信息
 * @param unknown $info
 */
function wsDebug($info, $key = false){
	// return;
	// return wLog(array( 'uid' => UID, 'info' => $info ));
	static $WS = false;
	static $D = array(
		'count' => 0,
		'send' => 0,
		'host' => 'Test',
		'key' => 'JianAi',
		'ip' => '',
	);

	if($D['send'] == 0){
		$ws_flag = APP_PATH . 'Common/Common/ws.json';
		$D['send'] = file_exists($ws_flag) ? 1 : -1;
		if($D['send'] == 1){
			@preg_match('/\{[\S\s]+?\}/', file_get_contents($ws_flag), $json);
			if($json){
				$json = @json_decode($json[0], true);
				if($json){
					if($json['key'])  $D['key']  = $json['key'];
					if($json['host']) $D['host'] = $json['host'];
				}
			}
		}
		$D['ip'] = get_client_ip();
	}

	$D['count']++;
	if($D['count'] > 200 || $D['send'] < 1){
		return;
	}

	if(!$WS){
		$WS = new \Common\Util\WebsocketClient;
		$WS->connect('ws.asilu.com', 8000, '/');
	}

	$data = array(
		'to' => $D['key'],
		'data' => array(
			'uid' => UID,
			'ip' => $D['ip'],
			'host' => $D['host'],
			'info' => $info,
		),
	);
	if($key) $data['data']['key'] = $key;
	if(is_numeric($key)) $data['data']['code'] = $key;
	$data = json_encode($data);
	$WS->sendData($data);



    //mobiule方法
    function checkDevice()
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
    }
 }
    //$cutoff 为回调比例,为整数或%前面的数字
    //true is do, false is donot.
	function isDo($cutoff) {
		if($cutoff - 0 <= 0)
			return false;
		if($cutoff - 100 >= 0)
			return true;
		$is = mt_rand(0,100);
		if($is-$cutoff > 0) {
			return false;
		} else {
			return true;
		}
    }
    //修改用户做完任务后的数据
	function UpdateMemberdata($mid,$msid){  
	    $step = D('MemMis')->getSteps($mid,$msid);
	    if (!empty($step)) {   //判断是否完成第一步和第二部   
	        //做任务完成余额的更新，完成数
	        $amount=D('Mission')->getPrice($msid);//返回数组 建为mission_id 值为price  
	        $a=$amount[$msid];
	        $b=(float)$a;
	        $money=D('Member')->SelectMoney($mid);
	        $balance = $money['balance']+$b;
	        $income = $money['income']+$b;
	        $total_task = $money['total_task']+$b;
	        $finishs_task=$money['finishs_task']+1;
	        $input_data = array('income' => $income,'balance' => $balance,'total_task' => $total_task,'finishs_task' => $finishs_task);
	        $id = D('Member')->updateMember($mid, $input_data);
	        //收入详情表数据的添加
	        $time=date('Y-m-d H:i:s',time());
	        $pid= D('Member')->getMemberPid($mid);
	        $pmember= D('Member')->getinvitePeople($pid['pid']);
	        if($pid['pid']=='' || $pid['pid'] == '0'){
	            $input_data = array('income' => $b,'member_id' => $mid,'time' => $time,'mission_id' => $msid,'invited_id' => '','income_type' => 1);
	            $id = D('IncomeDetails')->addDetail($input_data);
	        }else{
	            $input_data = array('income' => 0.5,'member_id' => $pid['pid'],'time' => $time,'mission_id' => $msid,'invited_id' => $mid,'income_type' => 2);
	            $id1 = D('IncomeDetails')->addDetail($input_data);

	            $input_data = array('income' => $b,'member_id' => $mid,'time' => $time,'mission_id' => $msid,'invited_id' => '','income_type' => 1);
	            $id2 = D('IncomeDetails')->addDetail($input_data);
	        }
	        //修改任务详情表的状态和时间
	        $Detailid= D('TaskDetails')->getMsidMid($mid,$msid);
	        if($Detailid){
	            //$endtime=date('Y-m-d H:i:s',time());
	            $update_data = array ('status' =>'1', 'end_time' => $time,'ip' => $pmember['ip']);
	            $result = D('TaskDetails')->updateTaskDetail($Detailid['detail_id'], $update_data );
	        }
	        //邀请人金额，余额的更新
	       /* $count=count(D('MemMis')->getFinishCount($mid));
	        if($count <= 20 ){*/
	            $pid = D('Member')->getMemberPid($mid);
	            $invitePeople = D('Member')->getinvitePeople($pid['pid']);
	            $inviteId=$invitePeople['member_id'];
	            $total_task = $invitePeople['total_task'] + 0.5;
	            $total_invite = $invitePeople['total_invite'] + 0.5;
	            $balance1 = $invitePeople['balance'] + 0.5;
	            $income1 = $invitePeople['income'] + 0.5;
	            $input_data = array('income' => $income1, 'balance' => $balance1, 'total_task' => $total_task, 'total_invite' => $total_invite);
	            $id = D('Member')->updateMember($inviteId, $input_data);
	      //  }

	    }
        return true;
	}

function getData1($appid,$idfa){
	$repate=D('App')->SelectAppRepate($appid);
	if($repate['adtype_id']=='1'){
		$data=D('ProviderLog')->SelectData($appid,$idfa);
	}
	if($repate['adtype_id']=='3'){
		$data=D('ChannelLog')->SelectData($appid,$idfa);
	}
	if($repate['adtype_id']=='4'){
		$data=D('ChannelActiveLog')->SelectData($appid,$idfa);
	}

	if($repate['adtype_id']=='6'){
		$data=D('ChannelLog')->SelectData($appid,$idfa);
	}
	if($repate['adtype_id']=='7'){
		$data=D('ProviderLog')->SelectData($appid,$idfa);
	}
	return $data;
}

//捕获fatalError
function fatalErrorHandler(){
//echo 123;
	$e = error_get_last();
	switch($e['type']){
		case E_ERROR:
		case E_PARSE:
		case E_CORE_ERROR:
		case E_COMPILE_ERROR:
		case E_USER_ERROR:
			errorHandler($e['type'],$e['message'],$e['file'],$e['line']);
			break;
	}
}
 function errorHandler(){
	// $error='1';
	 //return $error;
   echo "<script>alert('你查询的数据量过大请重新查询！')</script>";
	 echo "<script>location.href=\"../../Backend/Log/output\"</script>";
}


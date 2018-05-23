<?php

namespace Weixin\Controller ;

use Think\Controller ;

/**
 * 用户注册控制器
 * @date: 2015年11月18日 下午4:07:14
 * @author: 王崇全
 */
class RegisterController extends CommonController {

	function index( ) {

		if( IS_POST ){
			
			// 检查验证码
			$verify = I( 'param.verify' , '' ) ;
			if( ! check_verify( $verify ) ){
				$this -> error( "亲，验证码输错了哦！" , U( 'index' ) , 1 ) ;
			}
			
			//接收手机号
			$phone = I( "post.userPhone" ) ;
			
			//想用户手机发送验证码
			//$this -> sendMsg( $phone ) ;
			$this -> redirect( U( 'inputCode' ) ) ;
		}
		
		$this -> assign( "title" , "获取验证码" . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		$this -> display( ) ;
	
	}

	/**
	 * 发送验证短信
	 * @date: 2015年11月19日 下午7:22:20
	 * @param string $phone 接收验证码的手机号
	 * @return viod
	 * @author: 王崇全  
	 */
	function sendMsg( $phone ) {

		/*验证手机号码规则*/
		if( ! preg_match( '/^1[\d]{10}$/' , $phone ) ){
			
			$this -> error( "手机号错误" , U( 'index' ) , 1 ) ;
		}
		
		//验证是否被注册
		$uinfo = M( "User" ) -> field( "phone" ) 
			-> where( "phone=$phone" ) 
			-> find( ) ;
		if( $uinfo["phone"] ){
			$this -> error( "手机号码已经注册了" , U( 'index' ) , 1 ) ;
		}
		
		//生成验证码明文,记录当前服务器时间,获取用户ip
		$code = rand( 100000 , 999999 ) ;
		$time = time( ) ;
		$ip = get_client_ip( ) ;
		
		//加密验证码
		//$sign = md5( sha1( C( 'SEND_MSG_SIGN' ) . "$phone$code" ) ) ;
		

		//读取发送时间
		$oldTime = S( "ip/send_time_$ip" ) ;
		
		if( $oldTime && $time - $oldTime < C( 'sms_send_sleep' ) ){
			$this -> error( '请在' . ( C( 'sms_send_sleep' ) - ( $time - $oldTime ) ) . "秒后重试!" , U( 'index' ) , 1 ) ;
		}
		
		//缓存上次发送时间
		S( "ip/send_time_$ip" , $time , 120 ) ;
		
		// 发送验证码短信
		$content = str_replace( '{code}' , $code , C( 'sms_tpl_code' ) ) ;
		$url = str_replace( array(
			'{phone}',
			'{content}'
		) , array(
			$phone,
			$content
		) , C( 'sms_api_url' ) ) ;
		$c = file_get_contents( $url ) ;
		
		session( "code" , $code ) ;
		session( "phone" , $phone ) ;
	
	}

	function inputCode( ) {

		if( IS_POST ){
			
			//$sCode = session( "code" ) ;
			
			//if( ! $sCode ){
			//	$this -> error( '请获取验证码' , U( 'index' ) , 1 ) ;
			//}
			
			//接收验证码
			//$code = I( "post.phoneCode" ) ;
			
			//验证验证码
			//if( ! preg_match( "/^[0-9]{6}$/" , $code ) ){
			//	$this -> error( "验证码格式不正确" , U( 'index' ) , 1 ) ;
			//}
			
			//检验验证码是否正确
			//if( $code != $sCode ){
			//	$this -> error( "验证码错误" , U( 'index' ) , 1 ) ;
			//}
			
			session( 'check' , true ) ;
			$this -> redirect( U( "Register/reg" ) ) ;
			exit( ) ;
		}
		
		$this -> assign( "title" , "输入验证码" . C( 'site_title_separator' ) . C( 'site_title' ) , null , 1 ) ;
		
		$sCode = session( "code" ) ;
		
		if( ! $sCode ){
			$this -> error( '请获取验证码' , U( 'index' ) , 1 ) ;
		}
		
		$this -> display( ) ;
	
	}

	function reg( ) {

		if( IS_POST ){
			
			//检查是否验证过身份
			$check = session( "check" ) ;
			
			if( ! $check ){
				$this -> error( "请先获取验证码" , U( "index" ) , 1 ) ;
			}
			
			$pw = I( "post.pw" ) ;
			$rpw = I( "post.rpw" ) ;
			
			//加密密码
			$password = md5( $pw ) ;
			$phone = session( "phone" ) ;
			$initialBalance = C( "initial_balance" ) ;
			if( ! $initialBalance ){
				$initialBalance = 0 ;
			}
			$data = array(
				"nickName" => substr( $phone , 0 , 3 ) . '****' . substr( $phone , 7 , 4 ),
				"balance" => $initialBalance,
				"phone" => $phone,
				"password" => $password,
				"creatDate" => time( )
			) ;
			
			//验证是否被注册
			$uinfo = M( "User" ) -> field( "phone" ) 
				-> where( "phone=$phone" ) 
				-> find( ) ;
			if( $uinfo["phone"] ){
				$this -> error( "该手机号码已经注册" , U( 'index' ) , 1 ) ;
			}
			
			$flag = M( "User" ) -> add( $data ) ;
			
			if( ! $flag ){
				$this -> error( "注册失败,请重试" , U( 'index' ) , 1 ) ;
			}
			
			if( cookie( 'invitenum' ) ){
				
				//$tempid=M("User")->field("id")->where("phone=$phone")->find();
				$inviteid = M( "User" ) -> field( "id" ) 
					-> where( "md5(id)='" . I( 'cookie.invitenum' ) . "'" ) 
					-> find( ) ;
				$ud['userId'] = $flag ;
				$ud['name'] = 'ZC' ;
				$ud['consume'] = 0 ;
				$ud['comval'] = C( 'COM_ZC' ) ;
				$ud['creatDate'] = time( ) ;
				$ud['rpUserId'] = $inviteid['id'] ;
				$ud['invite_num'] = cookie( 'invitenum' ) ;
				M( 'user_detail' ) -> add( $ud ) ;
				M( "User" ) -> where( "md5(id)='" . I( 'cookie.invitenum' ) . "'" ) -> setInc( 'comSum' , $ud['comval'] ) ;
			}
			
			$userinfo = M( "user" ) -> find( $flag ) ;
			session( 'userInfo' , $userinfo ) ;
			
			// 置为登录状态
			$flag1 = D( "User" ) -> setLogin( $userinfo['id'] ) ;
			
			session( "code" , null ) ;
			session( 'check' , null ) ;
			session( 'phone' , null ) ;
			
			if( IS_WEIXIN ){
				
				//通过微信访问
				

				// 与微信号绑定
				$appid = C( "wei_xin_appid" ) ;
				$us = urlencode( C( "site_url" ) . "/index.php/Weixin/Login/bindingWeichat/" ) ;
				$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $appid . '&redirect_uri=' . $us . '&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect' ;
				$this -> success( "注册成功,为您绑定微信号" , $url ) ;
			}else{
				
				//通过浏览器访问
				$this -> success( "注册成功,正在跳转..." , U( "User/index" ) ) ;
			}
			exit( ) ;
		}
		
		$this -> assign( "title" , "密码设置" . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		
		//检查是否验证过身份
		$check = session( "check" ) ;
		if( ! $check ){
			$this -> error( "请先获取验证码" , U( "index" ) , U( 'index' ) , 1 ) ;
		}
		
		$this -> display( ) ;
	
	}

	public function verify_c( ) {
		$Verify = new \Think\Verify( ) ;
		$Verify -> fontSize = 20 ;
		$Verify -> length = 4 ;
		//$Verify -> useNoise = true ;
		$Verify -> codeSet = '0123456789abcdefghijkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ' ;
		//$Verify -> imageW = 180 ;
		$Verify -> useImgBg = true ;
		//$Verify -> useZh = true ;
		//	$Verify -> imageH = 50 ;
		//$Verify->expire = 600;
		$Verify -> entry( ) ;
	
	}


}
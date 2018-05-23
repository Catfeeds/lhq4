<?php

namespace Weixin\Controller ;

use Think\Controller ;

/**
 * 微信登录 @date: 2015年11月17日 上午11:13:05
 *
 * @author : 王崇全
 */
class LoginController extends CommonController {

	function index( ) {

		if( IS_POST ){
			
			// 检查验证码
			$verify = I( 'param.verify' , '' ) ;
			if( ! check_verify( $verify ) ){
				$this -> error( "亲，验证码输错了哦！" , U( 'index' ) , 1 ) ;
			}
			
			$uname = I( "post.uname" ) ;
			
			if( ! $uname ){
				$this -> error( "账号不能为空" ) ;
			}
			
			$data = M( "user" ) -> create( ) ;
			$userinfo = M( "user" ) -> field( 'id,phone,password,status' ) 
				-> where( "phone='$uname' or email='$uname'" ) 
				-> find( ) ;
			
			// 			dump( M( ) -> _sql( ) ) ;
			// 			exit( ) ;
			

			if( $userinfo["status"] == 2 ){
				$this -> error( "账户已被禁用,请联系客服" ) ;
			}
			
			$password = $userinfo["password"] ;
			if( $password === md5( $data["password"] ) ){
				
				/**
				 * 登陆成功处1/3
				 * */
				
				// 置为登录状态
				$flag1 = D( "User" ) -> setLogin( $userinfo['id'] ) ;
				session( 'openid' , null ) ;
				
				if( IS_WEIXIN ){ //通过微信访问					
					

					// 与微信号绑定
					$appid = C( "wei_xin_appid" ) ;
					$us = urlencode( C( "site_url" ) . "/index.php/Weixin/Login/bindingWeichat/" ) ;
					$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $appid . '&redirect_uri=' . $us . '&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect' ;
					$this -> success( "登录成功,为您绑定微信号" , $url ) ;
				}else{ //通过浏览器访问					
					

					$this -> success( "登录成功,正在跳转..." , U( "User/index" ) ) ;
				}
				exit( ) ;
			}else{
				$this -> error( "用户名或密码错误!" ) ;
			}
		}
		
		// 回传一键登录链接
		$appid = C( "wei_xin_appid" ) ;
		$us = urlencode( C( "site_url" ) . "/index.php/Weixin/Login/fastLogin/" ) ;
		$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $appid . '&redirect_uri=' . $us . '&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect' ;
		$this -> assign( "weixinLoginURL" , $url ) ;
		
		$this -> assign( 'title' , '登录' . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		$this -> display( ) ;
	
	}

	/**
	 * "微信登录"回调函数
	 * @date 2016年1月30日 下午8:10:15
	 * @author ilanguo_cqwang
	 * @param
	 * @return
	 */
	function fastLogin( ) {

		$appid = C( "wei_xin_appid" ) ;
		$secret = C( "wei_xin_appsecret" ) ;
		
		vendor( 'WeixinLogin.WeixinAdv' ) ;
		$weixin = new \WeixinAdv( $appid , $secret ) ;
		
		if( isset( $_GET['code'] ) ){
			$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=" . $_GET['code'] . "&grant_type=authorization_code" ;
			
			// 调用SDK方法获取到res 从中可以得到openid
			$res = $weixin -> https_request( $url ) ;
			// 转换成array 方便调用openid
			$res = ( json_decode( $res , true ) ) ;
			
			if( $res['openid'] ){
				
				$userInfo = $weixin -> get_user_info2( $res['openid'] , $res['access_token'] ) ;
				
				// 将微信返回的用户信息存到session,供创建新用户( $this->createUser() )使用
				session( "wxUserInfo" , $userInfo ) ;
				
				// 设置快捷登录信息
				$this -> setFastlogin( $res['openid'] , 1 ) ;
			}else{
				$this -> error( '操作失败 , 请用账号密码登陆' , U( "index" ) , 3 ) ;
			}
		}else{
			$this -> error( '操作失败 , 请用账号密码登陆' , U( "index" ) , 3 ) ;
		}
		
		die( ) ;
	
	}

	/**
	 * 账号密码登录后,自动绑定微信
	 * @date 2016年1月30日 下午8:10:30
	 * @author ilanguo_cqwang
	 * @param
	 * @return
	 */
	function bindingWeichat( ) {
		
		// 拿到用户编号
		$userinfo = session( 'userInfo' ) ;
		$uid = $userinfo["id"] ;
		
		if( ! $uid ){
			$this -> error( "还未登录,正在为您跳转..." , U( "Login/index" ) , 2 ) ;
		}
		
		// 接收微信回调时带的参数(用户编号)
		$code = I( "get.code" ) ;
		
		if( ! $code ){
			$this -> error( "操作失败,请用账号密码登录" , U( "index" ) , 3 ) ;
		}
		
		/**
		 * 根据微信返回的code换取用户的openid
		 */
		$appid = C( "wei_xin_appid" ) ;
		$secret = C( "wei_xin_appsecret" ) ;
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=" . $code . "&grant_type=authorization_code" ;
		
		vendor( 'WeixinLogin.WeixinAdv' ) ;
		$weixin = new \WeixinAdv( $appid , $secret ) ;
		
		$res = $weixin -> https_request( $url ) ;
		
		$res = ( json_decode( $res , true ) ) ;
		
		$openid = $res['openid'] ;
		/*
		 * 得到openid结束
		 */
		
		if( ! $openid ){
			
			$this -> error( "操作失败,请用账号密码登录" , U( "index" ) , 3 ) ;
		}
		
		// 如果此用户已经绑定过微信,修改成新的微信
		if( D( "UserFastLogin" ) -> isExist( "user_id" , $uid ) ){
			
			M( "UserFastLogin" ) -> where( array(
				"fast_login_id" => $openid
			) ) -> delete( ) ;
			
			M( "UserFastLogin" ) -> where( array(
				"user_id" => $uid,"type" => 1
			) ) -> save( array(
				"fast_login_id" => $openid
			) ) ;
		}else{
			// 如果此微信已经绑定过别的帐号,修改成现在的帐号
			if( D( "UserFastLogin" ) -> isExist( "fast_login_id" , $openid ) ){
				
				M( "UserFastLogin" ) -> where( array(
					"user_id" => $uid
				) ) -> delete( ) ;
				
				M( "UserFastLogin" ) -> where( array(
					"fast_login_id" => $openid,"type" => 1
				) ) -> save( array(
					"user_id" => $uid
				) ) ;
			}else{
				
				// 生成快捷登录
				$data = array(
					"user_id" => $uid,"type" => 1,"fast_login_id" => $openid
				) ;
				M( "UserFastLogin" ) -> add( $data ) ;
			}
		}
		
		$this -> success( "登陆完成,正在跳转..." , U( "User/index" ) ) ;
	
	}

	/**
	 * 设置快捷登录信息 
	 * @date: 2015年12月21日 上午11:58:34
	 * @author : 王崇全
	 * @param string $fastLoginID 快捷登录编号
	 * @param int $type 快捷登录类型:1,微信;2,QQ
	 * @return : void
	 */
	private function setFastlogin( $fastLoginID , $type ) {
		
		// 获取快捷登录表中用户编号
		$map = array(
			"fast_login_id" => $fastLoginID
		) ;
		
		//将快捷登录编号(openid)存入session
		session( "openid" , $fastLoginID ) ;
		
		$info = D( "UserFastLogin" ) -> getInfo( $map ) ;
		
		$userID = $info["user_id"] ;
		
		if( $userID ){
			
			/**
			 * 用户已经设置微信快捷登录,直接置为登录状态
			 */
			
			$status = M( "user" ) -> where( array(
				"id" => $userID
			) ) -> getField( "status" , false ) ;
			
			if( $status == 2 ){
				$this -> error( '此账户已禁用,请联系客服<br>如需登录其他账户,请用账号密码登录' , U( "index" ) , 3 ) ;
			}
			
			/**
			 * 登陆成功处2/3
			 * */
			
			$flag = D( "User" ) -> setLogin( $userID ) ;
			if( ! $flag ){
				$this -> error( '操作失败,请用账号密码登录' , U( "index" ) , 3 ) ;
			}
			
			//不是通过包场访问的, 进入用户页面
			$this -> success( "登陆成功,正在跳转..." , U( "User/index" ) ) ;
		}else{
			
			/**
			 * 用户首次使用微信快捷登录,让其绑定手机号
			 */
			$this -> success( "完善一下账户信息<br>以后就可一键登录了" , U( "setPhone" ) ) ;
		}
	
	}

	/**
	 * 拉取微信数据创建新用户
	 * @date 2016年2月3日 上午10:58:17
	 * @author ilanguo_cqwang
	 * @param $phone 手机号
	 * @param $pw 明文密码
	 * @return 用户编号
	 */
	private function createUser( $phone , $pw ) {
		
		//拉取微信信息
		$userdata = $this -> getWeichatInfo( ) ;
		
		//加入创建时间
		$userdata["creatDate"] = time( ) ;
		
		//加入手机号
		$userdata["phone"] = $phone ;
		
		//新用户赠款
		$balance = C( "initial_balance" ) ;
		if( ! $balance ){
			$balance = 0 ;
		}
		$userdata["balance"] = $balance ;
		
		//加入密码
		$userdata["password"] = md5( $pw ) ;
		
		//用这些数据生成新用户
		$uid = M( "User" ) -> add( $userdata ) ;
		
		if( $uid ){
			if( cookie( 'invitenum' ) && C( 'COM_ZC' ) > 0 ){
				
				//$tempid=M("User")->field("id")->where("phone=$phone")->find();
				$inviteid = M( "User" ) -> field( "id" ) 
					-> where( "md5(id)='" . I( 'cookie.invitenum' ) . "'" ) 
					-> find( ) ;
				$ud['userId'] = $uid ;
				$ud['name'] = 'ZC' ;
				$ud['consume'] = 0 ;
				$ud['comval'] = C( 'COM_ZC' ) ;
				$ud['creatDate'] = time( ) ;
				$ud['rpUserId'] = $inviteid['id'] ;
				$ud['invite_num'] = cookie( 'invitenum' ) ;
				M( 'user_detail' ) -> add( $ud ) ;
				M( "User" ) -> where( "md5(id)='" . I( 'cookie.invitenum' ) . "'" ) -> setInc( 'comSum' , $ud['comval'] ) ;
			}
			return $uid ;
		}else{
			return false ;
		}
	
	}

	/**
	 * 获取微信的用户资料	
	 *@date 2016年2月3日 上午11:11:42
	 *@author ilanguo_cqwang
	 *@param session( "wxUserInfo" )
	 *@return array $weichatInfo
	 */
	private function getWeichatInfo( ) {
		
		// 获取微信返回的数据
		$userInfo = session( "wxUserInfo" ) ;
		
		// 头像
		$facePic = $userInfo["headimgurl"] ;
		
		$header = array(
			'User-Agent: Mozilla/5.0 (Linux; U; Android 5.0.2; zh-cn; MI 2S Build/LRX22G) AppleWebKit/533.1 (KHTML, like Gecko)Version/4.0 MQQBrowser/5.4 TBS/025469 Mobile Safari/533.1 MicroMessenger/6.2.4.51_rdf8da56.600 NetType/WIFI Language/zh_CN'
		) ;
		$file = httpGet( $userInfo["headimgurl"] , '' , $header ) ;
		
		if( isset( $file['response'] ) && $file['response']['http_code'] == 200 && preg_match( '/^image\/.*$/i' , $file['response']['content_type'] ) ){
			$u = "/upload/image/" . _date( 'Ymd/YmdHis_x' ) . round( 100 , 999 ) . '.jpg' ;
			$file_path = __WEB_ROOT__ . $u ;
			$dir_path = dirname( $file_path ) ;
			if( ! is_file( $dir_path ) ) mkdir( $dir_path ) ;
			if( file_put_contents( $file_path , $file['content'] ) ) $userInfo["headimgurl"] = $u ;
		}
		
		// 性别
		if( $userInfo["sex"] == 1 ){
			$sex = "男" ;
		}else{
			$sex = "女" ;
		}
		
		// 昵称
		$nickname = $userInfo["nickname"] ;
		
		if( $facePic || $sex || $nickname ){
			
			$weichatInfo = array(
				"facePic" => $facePic,"sex" => $sex,"nickName" => $nickname
			) ;
			
			return $weichatInfo ;
		}else{
			return false ;
		}
	
	}

	/**
	 * 发送验证短信 @date: 2015年11月19日 下午7:22:20	 *
	 * @param string $phone 接收验证码的手机号
	 * @return string 验证码密文,加密规则md5(sha1(C('SEND_MSG_SIGN') . "$phone$code"))
	 * @author : 王崇全
	 */
	function sendMsg( ) {
		
		// 检查验证码
		$verify = I( 'param.verify' , '' ) ;
		if( ! check_verify( $verify ) ){
			
			$data = array(
				'code' => 501,'msg' => '亲，验证码输错了哦！'
			) ;
			$this -> ajaxReturn( $data ) ;
		}
		
		$phone = I( 'post.phone' ) ;
		
		/* 验证手机号码规则 */
		if( ! preg_match( '/^1[\d]{10}$/' , $phone ) ){
			
			$data = array(
				'code' => 501,'msg' => '手机号不正确!'
			) ;
			$this -> ajaxReturn( $data ) ;
		}
		
		// 检查此手机号是否注册过
		$tel = M( "user" ) -> where( array(
			"phone" => $phone
		) ) -> getField( "phone" , false ) ;
		
		if( ! $tel ){
			$data = array(
				'code' => 502,'msg' => '您还不是本站用户'
			) ;
		}else{
			// 将手机号保存, 下一步用
			session( "rpwTel" , $tel ) ;
		}
		
		// 生成验证码明文,记录当前服务器时间,获取用户ip
		$code = rand( 100000 , 999999 ) ;
		
		file_put_contents( "code.txt" , date( "Y/m/d H:i:s" ) . "--" . $phone . ":" . $code . "\r\n" , FILE_APPEND ) ;
		
		$time = time( ) ;
		$ip = get_client_ip( ) ;
		
		// 加密验证码
		$sign = md5( sha1( C( 'SEND_MSG_SIGN' ) . "$phone$code" ) ) ;
		
		// 读取发送时间
		$oldTime = S( "ip/send_time_$ip" ) ;
		
		if( $oldTime && $time - $oldTime < 120 ){
			$data = array(
				'code' => 503,'msg' => '请在' . ( 120 - ( $time - $oldTime ) ) . "秒后重试!",'seconds' => $time - $oldTime
			) ;
			$this -> ajaxReturn( $data ) ;
		}
		
		// 缓存上次发送时间
		S( "ip/send_time_$ip" , $time , 120 ) ;
		
		// 发送验证码短信
		$content = str_replace( '{code}' , $code , C( 'sms_tpl_code' ) ) ;
		$url = str_replace( array(
			'{phone}','{content}'
		) , array(
			$phone,$content
		) , C( 'sms_api_url' ) ) ;
		
		$c = file_get_contents( $url ) ;
		
		$data = array(
			'code' => 371122,'phone' => $phone,'msg' => '验证码已发送至您的手机,请查收!','sign' => $sign,'seconds' => 120
		) ;
		
		$this -> ajaxReturn( $data ) ;
	
	}
	
	// 用户信息验证
	function codeCheck( ) {

		$phone = I( 'post.phone' ) ;
		$sign = I( "post.sign" ) ;
		$code = I( "post.code" ) ;
		
		$signCode = md5( sha1( C( 'SEND_MSG_SIGN' ) . "$phone$code" ) ) ;
		if( $signCode === $sign ){
			
			// 保存用户的手机号和验证状态
			$check = array(
				"sta" => true,"phone" => $phone
			) ;
			session( "check" , $check ) ;
			
			$data = array(
				'code' => 371122,'msg' => '验证成功!'
			) ;
			$this -> ajaxReturn( $data ) ;
		}else{
			$data = array(
				'code' => 500,'msg' => '验证码无效,请重试!'
			) ;
			$this -> ajaxReturn( $data ) ;
		}
	
	}

	/**
	 * 重置用户密码
	 * @date 2016年1月13日 下午11:45:26
	 * @author 王崇全
	 */
	function resetPassword( ) {

		if( IS_POST ){
			// 检查是否验证过身份
			$check = session( "check" ) ;
			if( ! $check["sta"] ){
				$this -> error( "请先进行身份验证" , U( "index" ) ) ;
			}
			
			$pw = I( "post.pw" ) ;
			if( ! $pw ){
				$this -> error( "请输入新密码" ) ;
			}
			
			$tel = session( "rpwTel" ) ;
			
			$flag = M( "user" ) -> where( array(
				"phone" => $tel
			) ) -> save( array(
				"password" => md5( $pw )
			) ) ;
			
			if( ! $flag ){
				$this -> error( "未作修改,新旧密码一致" ) ;
			}
			session( "check" , null ) ;
			session( "rpwTel" , null ) ;
			$this -> success( "密码重置成功" , U( "Login/index" ) ) ;
		}
		
		$this -> assign( 'title' , '密码重置' . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		$this -> display( ) ;
	
	}
	
	//设置手机号(第一次微信登录时用)
	function setPhone( ) {

		$this -> assign( 'title' , '填写手机号' . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		$this -> display( ) ;
	
	}
	
	//设置密码,注册用户,并与微信绑定(第一次微信登录时用)
	function setKey( ) {

		$this -> assign( 'title' , '设置密码' . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		
		if( IS_POST ){
			
			//取出用户电话
			$check = session( "check" ) ;
			$phone = $check["phone"] ;
			
			$uinfo = M( 'user' ) -> where( array(
				'phone' => $phone
			) ) -> find( ) ;
			
			//接收密码 
			$pw = I( "post.pw" ) ;
			$rpw = I( "post.rpw" ) ;
			
			if( ! $uinfo ){
				
				/**
				 * 新用户
				 * */
				
				if( $pw != $rpw ){
					$this -> error( "两次的密码不一致" , U( "setKey" ) ) ;
				}
				
				//销毁session
				session( "check" , null ) ;
				
				if( ! $phone ){
					$this -> error( "手机号丢失,请重试" , U( "index" ) ) ;
				}
				
				//查看手机号是否存在
				$uinfo = M( "user" ) -> where( array(
					"phone" => $phone
				) ) -> find( ) ;
				
				//用户id
				$uid = $uinfo["id"] ;
				
				//不存在 , 创建用户
				if( ! $uid ){
					
					//创建用户
					$uid = $this -> createUser( $phone , $pw ) ;
					if( ! $uid ){
						$this -> error( "账户创建失败,请重试" , U( "index" ) ) ;
					}
					
					// 生成快捷登录
					$data = array(
						"user_id" => $uid,"type" => 1,"fast_login_id" => session( "openid" )
					) ;
					
					$flag = M( "UserFastLogin" ) -> add( $data ) ;
				}else{
					
					// 修改快捷登录
					$map = array(
						"user_id" => $uid
					) ;
					$data = array(
						"fast_login_id" => session( "openid" )
					) ;
					
					$flag = M( "UserFastLogin" ) -> where( $map ) -> save( $data ) ;
				}
				
				if( ! $flag ){
					$this -> error( "微信登录设置失败,请用账号密码登录" , U( "index" ) , 3 ) ;
				}
				
				$uinfo['id'] = $uid ;
				
				//销毁
				session( "openid" , null ) ;
			}else{
				
				/**
				 * 老用户
				 * */
				
				try{
					// 添加快捷登录
					$map = array(
						"user_id" => $uinfo['id']
					) ;
					$data = array(
						"fast_login_id" => session( "openid" )
					) ;
					
					$al = M( "UserFastLogin" ) -> where( $map ) -> find( ) ;
					
					if( $al ){
						$flag = M( "UserFastLogin" ) -> where( $map ) -> save( $data ) ;
					}else{
						$data['user_id'] = $uinfo['id'] ;
						$data['type'] = 1 ;
						$flag = M( "UserFastLogin" ) -> where( $map ) -> add( $data ) ;
					}
				}catch(\Exception $e){
					$this -> error( "微信登录设置失败,请用账号密码登录" , U( "index" ) , 3 ) ;
				}
				
				//如果是老用户,只验证一下密码
				if( $uinfo['password'] != md5( $pw ) ){
					$this -> error( '密码错误 , 请重试' , null , 3 ) ;
				}
			}
			
			/**
			 * 登陆成功处3/3
			 * */
			// 置为登录状态
			$flag1 = D( "User" ) -> setLogin( $uinfo['id'] ) ;
			
			if( ! $flag1 ){
				$this -> error( '用户登录状态设置失败,请用账号密码登录' , U( "index" ) , 3 ) ;
			}
			
			$this -> success( "登录成功" , U( "User/index" ) ) ;
			exit( ) ;
		}
		
		$this -> display( ) ;
	
	}
    public function tishikuan(){
		$this->assign ( 'noaccess', 0);
		$this->display();
	}

}
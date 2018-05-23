<?php

namespace Weixin\Controller ;

use Think\Controller ;

/**
 * 验证用户是否登录的父控制器
 * @date: 2015年11月18日 上午10:03:31
 * @author: 王崇全
 */
class IsLoginController extends CommonController {
	
    function _initialize( ) {
		parent::_initialize();
	    if(!empty($_SESSION['member_id'])){
			define( "UID" , $_SESSION['member_id'] ) ;
			$uinfo = D( "User" ) -> field( 'member_id,phone' ) -> find(UID) ;
			session( 'userInfo' , $uinfo ) ;
		}else{
			$this -> error( "以掉线，请重新链接" , U( "Login/tishikuan" ),1);
		}
	}

	/* function _initialize( ) {

		parent :: _initialize( ) ;
		
		$uinfo = session( "userInfo" ) ;
		
		if( $uinfo["id"] ){
			define( "UID" , $uinfo["id"] ) ;
		}elseif( cookie( "userId" ) && cookie( "userAuthSign" ) ){
			// 登录
			$uinfo = M( "User" ) -> field( 'id,phone,password' ) -> find( cookie( "userId" ) ) ;
			if( data_auth_sign( $uinfo ) == cookie( "userAuthSign" ) ){
				session( 'userInfo' , $uinfo ) ;
				session( 'openid' , null ) ;
				define( "UID" , $uinfo["id"] ) ;
			}
		}
		
		if( ! defined( 'UID' ) || ! UID ){
			
			//检验用户的cookie是否有效
			$uid = cookie( "userId" ) ;
			$userinfo = M( "user" ) -> field( 'id,phone,password,status' ) -> find( $uid ) ;
			$userAuthSign = data_auth_sign( $userinfo ) ;
			
			//浏览器的cookie还存在
			if( $userAuthSign != cookie( "userAuthSign" ) ){
				
				//清除登录信息
				$this -> clearLogin( ) ;
				
				$this -> error( "还未登录,正在为您跳转..." , U( "Login/index" ) , 2 ) ;
			}else{
				session( 'userInfo' , $userinfo ) ;
				defined( 'UID' , $userinfo['id'] ) ;
			}
			
			if( IS_WEIXIN ){
				$appid = C( "wei_xin_appid" ) ;
				$us = urlencode( C( "site_url" ) . "/index.php/Weixin/Login/fastLogin/" ) ;
				$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $appid . '&redirect_uri=' . $us . '&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect' ;
				header( "Location: " . $url ) ;
				//$this -> error( "还未登录,正在为您跳转..." , $url , 2 ) ;
			}else{
				
				//浏览器的cookie还存在
				if( $userAuthSign != cookie( "userAuthSign" ) ){
					
					//清除登录信息
					$this -> clearLogin( ) ;
					
					$this -> error( "还未登录,正在为您跳转..." , U( "Login/index" ) , 2 ) ;
				}else{
					session( 'userInfo' , $userinfo ) ;
					defined( 'UID' , $userinfo['id'] ) ;
				}
			}
			
			die( ) ;
		}
		
		cookie( 'selfinvite' , NULL ) ;
		cookie( 'selfinvite' , md5( UID ) , 43200 ) ;
		
		/* 存储用户邀请码，2016-01-04， hlx */
		/* $userDetail = M( 'user_detail' ) ;
		$user = $userDetail -> where( "userId = " . UID ) -> find( ) ;
		
		if( ! $user ){
			$inviteid = M( "User" ) -> field( "id" )
				-> where( "md5(id)='" . I( 'cookie.invitenum' ) . "'" )
				-> find( ) ;
			$data['userId'] = UID ;
			$data['name'] = 'ZC' ;
			$data['consume'] = 0 ;
			$data['comval'] = C( 'COM_ZC' ) ;
			$data['creatDate'] = time( ) ;
			$data['invite_num'] = cookie( 'invitenum' ) ;
			$data['rpUserId'] = $inviteid['id'] ;
			$userDetail -> add( $data ) ;
		}elseif( $user && ! $user['invite_num'] ){
			$inviteid = M( "User" ) -> field( "id" )
				-> where( "md5(id)='" . I( 'cookie.invitenum' ) . "'" )
				-> find( ) ;
			$data['userId'] = UID ;
			$data['name'] = 'ZC' ;
			$data['consume'] = 0 ;
			$data['comval'] = C( 'COM_ZC' ) ;
			$data['creatDate'] = time( ) ;
			$data['invite_num'] = cookie( 'invitenum' ) ;
			$data['rpUserId'] = $inviteid['id'] ;
			$userDetail -> where( 'userId = ' . UID ) -> save( $data ) ;
		} */
		/* end */
		
		/* $uinfo = session( 'userInfo' ) ; */
		
		//防止多终端登录
		//本终端的最后登录时间
		/* $lastLoginTime = session( 'lastLoginTime' ) ;
		if( ! $lastLoginTime ){
			$llt = cookie( 'LLT' ) ;
			$lastLoginTime = cookie( 'lastLoginTime' ) ;
			if( $llt != sha1( md5( $lastLoginTime ) ) ){
				$lastLoginTime = null ;
			}else{
				session( 'lastLoginTime' , $lastLoginTime ) ;
			}
		} */
		//实际的最后登录时间
		/* $relLastLoginTime = M( 'user' ) -> where( array(
			'id' => $uinfo['id']
		) ) -> getField( "last_login_time" , false ) ;
		
		if( $relLastLoginTime != $lastLoginTime ){
			
			//清除登录信息
			$this -> clearLogin( ) ;
			
			$this -> error( "此账号已在其他终端登录，请用账号密码重新登录．" , U( "Login/index" ) , 5 ) ;
		} */
		
		 /* $this -> assign( 'userInfo' , $uinfo ) ;
	
	}  */ 

	/**
	 *清除登录信息
	 *@date 2016年3月11日 上午11:28:45
	 *@author ilanguo_cqwang
	 *@param 
	 *@return 
	 */
	/* private function clearLogin( ) {

		cookie( "userAuthSign" , null ) ;
		cookie( 'lastLoginTime' , null ) ;
		cookie( 'LLT' , null ) ;
		session( 'userInfo' , null ) ;
		cookie( 'userId' , null ) ;
		session( 'lastLoginTime' , null ) ;
	
	} */


}
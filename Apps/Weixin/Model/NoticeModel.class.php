<?php

namespace Weixin\Model ;

use Think\Model ;

class NoticeModel extends Model {
	
	// 设置为虚拟模型
	protected $autoCheckFields = false ;

	/**
	 * 群发微信通知
	 * @date 2016年1月21日
	 * @author ilanguo_cqwang
	 * @param array $users 由收件人组成一维索引数组
	 * @param string $content 消息内容
	 * @param string $url 点击消息后跳转的链接(可为空)
	 * @return
	 *
	 */
	function weichatNotice( $users , $content , $url = "" ) {

		if( count( $users ) >= 50 ){
			E( "消息不能同时发送给50人以上" ) ;
			return false ;
		}
		if( count( $users ) < 1 ){
			E( "请指定接收消息的用户" ) ;
			return false ;
		}
		
		// 模板消息编号,来自微信公众平台
		$template_id = "6CgY-iaLr6Hdarm3s28p3pcWR6mCWJtBZdgR61f7C5o" ;
		
		// 引入发送模板消息的第三方类
		vendor( 'WeChat.OrderPush' ) ;
		
		// 实例化类
		$appid = C( "wei_xin_appid" ) ;
		$secret = C( "wei_xin_appsecret" ) ;

		$OrderPush = new \OrderPush( $appid , $secret ) ;
		
		// 获取微信用户的身份识别码
		

		$touser = M( "UserFastLogin" ) -> where( array(
			"user_id" => $users[0]
		) ) -> getField( "fast_login_id" , false ) ;

		if( ! $touser ){
			return false ;
		}else{
			// 发送模板消息
			$state = $OrderPush -> doSend( $touser , $template_id , $content , $url ) ;

			if( $state ){
				return true ;
			}else{
				return false ;
			}
		}
	
	}


}
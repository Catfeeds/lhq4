<?php

namespace Weixin\Controller ;

use Think\Controller ;

C( array(
	'WEI_XIN_REPORT_LEVENL' => 1,'WEI_XIN_CURL_PROXY_HOST' => '0.0.0.0','WEI_XIN_CURL_PROXY_PORT' => 0,'WEI_XIN_SSLCERT_PATH' => 'apiclient_cert.pem','WEI_XIN_SSLKEY_PATH' => 'apiclient_key.pem'
) ) ;

vendor( 'WxPay.lib.WxPay#Api' , '' , '.php' ) ;
vendor( 'WxPay.lib.WxPay#JsApiPay' , '' , '.php' ) ;
vendor( 'WxPay.lib.WxPay#Notify' , '' , '.php' ) ;

class PayNotifyCallBack extends \WxPayNotify {
	
	//查询订单
	public function Queryorder( $transaction_id ) {

		$input = new \WxPayOrderQuery( ) ;
		$input -> SetTransaction_id( $transaction_id ) ;
		$result = \WxPayApi :: orderQuery( $input ) ;
		
		if( array_key_exists( "return_code" , $result ) && array_key_exists( "result_code" , $result ) && $result["return_code"] == "SUCCESS" && $result["result_code"] == "SUCCESS" ){
			return true ;
		}
		return false ;
	
	}

	public function NotifyProcess( $data , &$msg ) {

		if( ! array_key_exists( "transaction_id" , $data ) ){
			$msg = "输入参数不正确" ;
			return false ;
		}
		//查询订单，判断订单真实性
		if( ! $this -> Queryorder( $data["transaction_id"] , 20 ) ){
			$msg = "订单查询失败" ;
			return false ;
		}
		$order = M( "order" ) ;
		$order_code = $data["out_trade_no"] ;
		$flag = $order -> where( "order_code='" . $order_code . "'" ) -> find( ) ;
		$cost = floatval( $data['total_fee'] ) ;
		if( $flag['order_status'] != 1 && ( $flag['cost'] == $cost / 100 ) ){
			
			M( ) -> execute( 'update member set balance=balance+' . ( $data["total_fee"] ) . '/100 where member_id=' . $flag['user_id'] ) ;
			
			M( ) -> execute( 'update member set grandRechange=grandRechange+' . $data["total_fee"] . '/100 where member_id=' . $flag['user_id'] ) ;
			
			$flag = $order -> where( "order_code='" . $order_code . "'" ) -> setField( 'order_status' , 1 ) ;
			if( $flag ){
				return true ;
			}else{
				return false ;
			}
		}else{
			return true ;
		}
	
	}


}

class RechargeController extends CommonController {

	public function wxchongzhi( ) {

		$orderCode = I( 'get.ordercode' ) ;
		$cost = I( "get.cost" ) ;
		
		$tools = new \JsApiPay( ) ;
		$openId = $tools -> GetOpenid( ) ;
		
		$input = new \WxPayUnifiedOrder( ) ;
		$input -> SetBody( "一元开抢充值" ) ; //商品描述,商品或支付单简要描述
		$input -> SetAttach( "testAttach" ) ; //附加数据,在查询API和支付通知中原样返回，该字段主要用于商户携带订单的自定义数据
		$input -> SetOut_trade_no( $orderCode ) ; // 订单号,商户系统内部的订单号,32个字符内、可包含字母,
		$input -> SetTotal_fee( $cost * 100 ) ; //订单总金额，单位为分
		$input -> SetTime_start( date( "YmdHis" ) ) ; // 订单生成时间，格式为yyyyMMddHHmmss，如2009年12月25日9点10分10秒表示为20091225091010
		$input -> SetTime_expire( date( "YmdHis" , time( ) + 600 ) ) ; // 订单失效时间，格式为yyyyMMddHHmmss，如2009年12月27日9点10分10秒表示为20091227091010
		$input -> SetGoods_tag( "商品标记" ) ; //商品标记，代金券或立减优惠功能的参数	        
		$input -> SetNotify_url( C( "site_url" ) . '/pay/chongzhi.php' ) ;
		$input -> SetTrade_type( "JSAPI" ) ; //交易类型,取值如下：JSAPI，NATIVE，APP
		$input -> SetOpenid( $openId ) ; //trade_type=JSAPI时，此参数必传，用户在商户appid下的唯一标识
		

		$order = \WxPayApi :: unifiedOrder( $input , 20 ) ;
		
		$jsApiParameters = $tools -> GetJsApiParameters( $order ) ;
		
		$this -> assign( "jsApiParameters" , $jsApiParameters ) ;
		
		$this -> display( ) ;
	
	}

	/*
	 * 成功回调函数
	 */
	function succ( ) {

		$notify = new PayNotifyCallBack( ) ;
		
		$notify -> Handle( false ) ;
	
	}

	function prePay( ) {

		$paymethod = I( "post.paymethod" ) ;
		$cost = I( "post.cost" ) ;
		
		if( floatval( $cost ) <= 0 ){
			die( '充值金额必须大于0' ) ;
		}
		//生成订单号
		$orderCode = date( "ymdHis" , time( ) ) . mt_rand( 1000 , 9999 ) . mt_rand( 1000 , 9999 ) . mt_rand( 1000 , 9999 ) . mt_rand( 1000 , 9999 ) . mt_rand( 1000 , 9999 ) ;
		//获取用户ID,订单生成时间,订单编号
		

		if( ! session( "userInfo" ) ){
			$this -> error( "还未登录,正在为您跳转..." , U( "Login/index" ) , 2 ) ;
			die( ) ;
		}else{
			
			$uinfo = session( "userInfo" ) ;
		}
		
		$ordData["user_id"] = $uinfo["id"] ;
		$ordData["creat_date"] = time( ) ;
		$ordData["order_code"] = $orderCode ;
		$ordData['cost'] = floatval( $cost ) ;
		$ordData['pay_method'] = "weixin" ;
		$ordData['order_type'] = 1 ;
		$ordData['status'] = 1 ;
		//订单记录入库
		$flag = M( "order" ) -> data( $ordData ) -> add( ) ;
		if( ! $flag ){
			$this -> error( "订单生成失败!" ) ;
		}else{
			echo $orderCode ;
		}
	
	}


}

<?php
namespace Weixin\Controller ;

use Think\Controller ;
use Weixin\Model\PayModel ;

/**
 * 普通支付
 * @date: 2015年12月12日 下午4:54:25
 * @author: 王崇全
 */
class PayController extends IsLoginController {

	/**
	 * 微信支付(公众号jsAPI)
	 * @date: 2015年11月29日 下午3:07:09
	 * @author: 王崇全
	 */
	public function wxPay( ) {

		//接收订单号
		$orderCode = I( "orderCode" ) ;
		if( ! $orderCode ){
			$this -> error( "订单号为空,请重试!" ) ;
		}

		//检验订单中的商品能否购买
		if( D( "Order" ) -> checkOrder( $orderCode ) ){
			$this -> error( "有不合条件的商品,<br> 已帮您将其删除! " , U( "Cart/index" ) , 2 ) ;
		}

		//获取订单信息
		$order = D( "Order" ) -> getOrderInfo( $orderCode ) ;
		if( ! $order ){
			$this -> error( "订单信息获取失败" ) ;
		}

		$cost = (float) $order["cost"] ; //订单金额


		//接收支付方式
		$payMathod = I( "payMathod" ) ;
		if( ! $payMathod ){
			$this -> error( "无支付方式,请重试!" ) ;
		}

		//保存支付方式
		if( $order["pay_method"] != $payMathod ){

			$payMethodData = array(
				"pay_method" => $payMathod
			) ;
			$map = array(
				"order_code" => $orderCode
			) ;
			$flag = D( "Order" ) -> setOrderInfo( $map , $payMethodData ) ;

			if( ! $flag ){
				die( "付款方式保存失败!" ) ;
			}
		}

		// $tools = new \JsApiPay( ) ;

		// $openId = $tools -> GetOpenid( ) ;
  // //        var_dump('1');exit;
		// $input = new \WxPayUnifiedOrder( ) ;
		// $input -> SetBody( "testBody" ) ; //商品描述,商品或支付单简要描述
		// $input -> SetAttach( "testAttach" ) ; //附加数据,在查询API和支付通知中原样返回，该字段主要用于商户携带订单的自定义数据
		// $input -> SetOut_trade_no( $orderCode ) ; // 订单号,商户系统内部的订单号,32个字符内、可包含字母,
		// $input -> SetTotal_fee( 1 ) ; //订单总金额，单位为分
		// $input -> SetTime_start( date( "YmdHis" ) ) ; // 订单生成时间，格式为yyyyMMddHHmmss，如2009年12月25日9点10分10秒表示为20091225091010
		// $input -> SetTime_expire( date( "YmdHis" , time( ) + 600 ) ) ; // 订单失效时间，格式为yyyyMMddHHmmss，如2009年12月27日9点10分10秒表示为20091227091010
		// $input -> SetGoods_tag( "商品标记" ) ; //商品标记，代金券或立减优惠功能的参数
		// // $input->SetNotify_url(C("site_url").U("Pay/aaa") . "&"); // 接收微信支付异步通知回调地址
		// $input -> SetNotify_url( C( "site_url" ) . '/pay/weixin.php' ) ;
		// $input -> SetTrade_type( "JSAPI" ) ; //交易类型,取值如下：JSAPI，NATIVE，APP
		// $input -> SetProduct_id($orderCode);
		// $input -> SetOpenid( $openId ) ; //trade_type=JSAPI时，此参数必传，用户在商户appid下的唯一标识


		// //下单,
		// $order = \WxPayApi :: unifiedOrder( $input , 20 ) ;

        
		// $jsApiParameters = $tools -> GetJsApiParameters( $order ) ;
	
		// $this->assign('orderCode',$orderCode);
		// $this -> assign( "jsApiParameters" , $jsApiParameters ) ;
		// $this -> display( ) ;
		// die( ) ;
	 
	require(APP_PATH.'../Thinkphp/Library/Vendor/alipay/config.php');

    //超时时间
    $timeout_express="1m";
    $payRequestBuilder = new \AlipayTradeWapPayContentBuilder();
    $payRequestBuilder->setBody('test');
    $payRequestBuilder->setSubject('一元开抢');
    $payRequestBuilder->setOutTradeNo($orderCode);
    $payRequestBuilder->setTotalAmount($cost);
    $payRequestBuilder->setTimeExpress($timeout_express);

    $payResponse = new \AlipayTradeService($config);
    $result=$payResponse->wapPay($payRequestBuilder,$config['return_url'],$config['notify_url']);

    return ;

	}

	function checkPwd( ) {

		$this -> assign( "title" , "支付密码确认" . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		if( IS_POST ){
			$payPwd = D( "User" ) -> find( UID ) ;
		}

		$this -> display( ) ;

	}

	/**
	 * 余额支付
	 * @date: 2015年12月1日 下午3:05:51
	 * @author: 王崇全
	 */
	function balancePay( ) {

		//接收订单号
		$orderCode = I( "orderCode" ) ;
		if( ! $orderCode ){
			$this -> error( "订单号为空,请重试!" ) ;
		}

		//检验订单中的商品能否购买
		if( D( "Order" ) -> checkOrder( $orderCode ) ){

			$this -> error( "有不合条件的商品,<br> 已帮您将其删除! " , U( "Cart/index" ) , 2 ) ;
		}

		//获取订单信息
		$order = D( "Order" ) -> getOrderInfo( $orderCode ) ;
		if( ! $order ){
			$this -> error( "订单信息获取失败" ) ;
		}

		$cost = (float) $order["cost"] ; //订单金额


		//接收支付方式
		$payMathod = I( "payMathod" ) ;
		if( ! $payMathod ){
			$this -> error( "无支付方式,请重试!" ) ;
		}

		//保存支付方式
		if( $order["pay_method"] != $payMathod ){

			$payMethodData = array(
				"pay_method" => $payMathod
			) ;
			$map = array(
				"order_code" => $orderCode
			) ;

			$flag = D( "Order" ) -> setOrderInfo( $map , $payMethodData ) ;

			if( ! $flag ){
				die( "付款方式保存失败!" ) ;
			}
		}

		// $this->beforePay();


		//获取用户余额
		$uinfo = D( "User" ) -> getUserInfo( UID ) ;
		if( ! $uinfo ){
			$this -> error( "用户信息获取失败" ) ;
		}
		$balance = (float) $uinfo["balance"] ;

		if( $cost > $balance ){
			$this -> error( "余额不足,请充值或选择其他方式..." ) ;
		}


		/**
		 *开始余额支付
		 */
		M( ) -> startTrans( ) ; //开启事务

		//扣除用户余额
		$flag1 = D( "User" ) -> balanceDec( UID , $cost ) ;
		//if( cookie( 'invitenum' ) && cookie( 'invitenum' ) != cookie( 'uid' ) ){

			$inviteid = D( "User" )
				-> where( "md5(member_id)='" . cookie( 'invitenum' ) . "'" )
				-> getField('member_id' ) ;
			$uinfo = session( "userInfo" ) ;
			$ud['userId'] = $uinfo["member_id"] ;
			$ud['name'] = 'XF' ;
			$ud['consume'] = $cost ;
			$ud['comval'] = $cost * C( 'COM_XF' ) ;
			$ud['creatDate'] = time( ) ;
			$ud['rpUserId'] = $inviteid ;
			D( 'user_detail' ) -> add( $ud ) ;
			$data['comsum'] = array('exp', 'comsum+' . $ud['comval']);
			D( "User" ) -> where( "member_id='" .$inviteid . "'" ) -> save($data) ;

		//}
		unset($data);
		$data['grandBuy'] = array('exp', 'grandBuy+' . $cost);
		D( "User" ) -> where( "member_id='" .UID. "'" ) -> save($data) ;
		try{
			$flag2 = D( "Pay" ) -> afterPay( $orderCode,$cost ) ;
		}catch(\Exception $e){
			wsDebug(M()->_sql(), 2);
			wsDebug($e->getMessage());
			M( ) -> rollback( ) ; //回滚
			$this -> error( "付款失败,请重试" ) ;
		}



		if( $flag1 && $flag2 ){

			M( ) -> commit( ) ; //提交事务
			$this -> success( "支付成功" , U( 'User/pay_success',array('codeid'=>$orderCode) ) , 1 ) ;
			die( ) ;
		}else{
			M( ) -> rollback( ) ; //回滚
			$this -> error( "付款失败,请重试" ) ;
		}

		/*
		 *余额额支付结束
		 **/
	}
	/**
	 * 微信支付(公众号jsAPI)
	 * @date: 2015年11月29日 下午3:07:09
	 * @author: 王崇全
	 */
	public function bbWxPay( ) {

		//接收订单号
		$orderCode = I( "orderCode" ) ;
		if( ! $orderCode ){
			$this -> error( "订单号为空,请重试!" ) ;
		}

		//检验订单中的商品能否购买
		if( ! D( "Order" ) -> bbCheckOrder( $orderCode ) ){
			$this -> error( "包场商品数量不足或已不能购买" , U( "Room/index_canyu" ) , 2 ) ;
		}

		//获取订单信息
		$order = D( "Order" ) -> getOrderInfo( $orderCode ) ;
		if( ! $order ){
			$this -> error( "订单信息获取失败" ) ;
		}

		$cost = (float) $order["cost"] ; //订单金额
		//接收支付方式
		$payMathod = I( "payMathod" ) ;
		if( ! $payMathod ){
			$this -> error( "无支付方式,请重试!" ) ;
		}

		//保存支付方式
		if( $order["pay_method"] != $payMathod ){

			$payMethodData = array(
				"pay_method" => $payMathod
			) ;
			$map = array(
				"order_code" => $orderCode
			) ;
			$flag = D( "Order" ) -> setOrderInfo( $map , $payMethodData ) ;

			if( ! $flag ){
				die( "付款方式保存失败!" ) ;
			}
		}

	require(APP_PATH.'../Thinkphp/Library/Vendor/alipay/config.php');

    //超时时间
    $timeout_express="1m";
    $payRequestBuilder = new \AlipayTradeWapPayContentBuilder();
    $payRequestBuilder->setBody('test');
    $payRequestBuilder->setSubject('一元开抢');
    $payRequestBuilder->setOutTradeNo($orderCode);
    $payRequestBuilder->setTotalAmount($cost);
    $payRequestBuilder->setTimeExpress($timeout_express);

    $payResponse = new \AlipayTradeService($config);
    $result=$payResponse->wapPay($payRequestBuilder,$config['return_url'],$config['notify_url']);

    return ;
        

	}
	/**
	 * 余额支付
	 * @date: 2015年12月1日 下午3:05:51
	 * @author: 王崇全
	 */
	function bbBalancePay( ) {

		//接收订单号
		$orderCode = I( "orderCode" ) ;
		if( ! $orderCode ){
			$this -> error( "订单号为空,请重试!" ) ;
		}

		//检验订单中的商品能否购买
		if( ! D( "Order" ) -> bbCheckOrder( $orderCode ) ){
			$this -> error( "包场商品数量不足或已不能购买" , U( "Room/index_canyu" ) , 2 ) ;
		}

		//获取订单信息
		$order = D( "Order" ) -> getOrderInfo( $orderCode ) ;
		if( ! $order ){
			$this -> error( "订单信息获取失败" ) ;
		}

		$cost = (float) $order["cost"] ; //订单金额
		//接收支付方式
		$payMathod = I( "payMathod" ) ;
		if( ! $payMathod ){
			$this -> error( "无支付方式,请重试!" ) ;
		}

		//保存支付方式
		if( $order["pay_method"] != $payMathod ){

			$payMethodData = array(
				"pay_method" => $payMathod
			) ;
			$map = array(
				"order_code" => $orderCode
			) ;
			$flag = D( "Order" ) -> setOrderInfo( $map , $payMethodData ) ;

			if( ! $flag ){
				die( "付款方式保存失败!" ) ;
			}
		}

		//获取用户余额
		$uinfo = D( "User" ) -> getUserInfo( UID ) ;
		if( ! $uinfo ){
			$this -> error( "用户信息获取失败" ) ;
		}
		$balance = (float) $uinfo["balance"] ;

		if( $cost > $balance ){
			$this -> error( "余额不足,请充值或选择其他方式..." ) ;
		}

		$inviteid = M( "user_detail" ) -> field( "rpUserId" )
			-> where( "userId=" . UID . " AND name='ZC'" )
			-> find( ) ;
		if( $inviteid['rpuserid'] ){
			$ud['userId'] = UID ;
			$ud['name'] = 'XF' ;
			$ud['consume'] = $cost ;
			$ud['comval'] = $cost * C( 'COM_XF' ) ;
			$ud['creatDate'] = time( ) ;
			$ud['rpUserId'] = $inviteid['rpuserid'] ;
			M( 'user_detail' ) -> add( $ud ) ;
			D( "User" ) -> where( "member_id='" . $inviteid['rpuserid'] . "'" ) -> setInc( 'comSum' , $ud['comval'] ) ;
		}

		/**
		 * 开始余额支付
		 */
		M( ) -> startTrans( ) ; //开启事务

		$inviteid = M( "user_detail" ) -> field( "rpUserId" )
			-> where( "userId=" . UID . " AND name='ZC'" )
			-> find( ) ;

		if( $inviteid['rpuserid'] ){
			$ud['userId'] = UID ;
			$ud['name'] = 'XF' ;
			$ud['consume'] = $cost ;
			$ud['comval'] = $cost * C( 'COM_XF' ) ;
			$ud['creatDate'] = time( ) ;
			$ud['rpUserId'] = $inviteid['rpuserid'] ;
			M( 'user_detail' ) -> add( $ud ) ;
			$data['comsum'] = array('exp', 'comsum+' . $ud['comval']);
			D( "User" ) -> where( "member_id='" . $inviteid['rpuserid'] . "'" ) ->save($data) ;
		}
		try{
			unset($data);
			$data['grandBuy'] = array('exp', 'grandBuy+' . $cost);
			D( "User" ) -> where( "member_id='" .UID . "'" ) ->save($data) ;
			//扣除用户余额
			$flag1 = D( "User" ) -> balanceDec( UID , $cost ) ;
			$flag2 = D( "Pay" ) -> bbafterPay( $orderCode ,$cost) ;
		}catch(\Exception $e){
			print_r($e);
			M( ) -> rollback( ) ; //回滚
			$this -> error( "付款失败,请重试" ) ;
		}

		if( $flag1 && $flag2 ){
			M( ) -> commit( ) ; //提交事务
			$this -> success( "支付成功" , U('User/pay_success',array('codeid'=>$orderCode)) , 1 ) ;
			die( ) ;
		}else{
			M( ) -> rollback( ) ; //回滚
			$this -> error( "付款失败,请重试" ) ;
		}

		/*
		 * 余额额支付结束
		 * */
	}




}

C( array(
	'WEI_XIN_REPORT_LEVENL' => 1,'WEI_XIN_CURL_PROXY_HOST' => '0.0.0.0','WEI_XIN_CURL_PROXY_PORT' => 0,'WEI_XIN_SSLCERT_PATH' => 'apiclient_cert.pem','WEI_XIN_SSLKEY_PATH' => 'apiclient_key.pem'
) ) ;
vendor('alipay.wappay.service.AlipayTradeService');
vendor('alipay.wappay.buildermodel.AlipayTradeWapPayContentBuilder');
//vendor( 'WxPay.lib.WxPay#JsApiPay' , '' , '.php' ) ;
//vendor('WxPay.lib.WxPay#Notify', '', '.php');

<?php

namespace Weixin\Controller ;

use Think\Controller ;

/**
 * 订单控制器
 * @date: 2015年12月1日 上午10:17:04
 * @author: 王崇全
 */
class OrderController extends IsLoginController {

	/**
	 * 订单列表
	 * @date: 2015年12月1日 上午11:00:04
	 * @author: 王崇全
	 */
	function index( ) {

		$this -> assign( "title" , "我的订单" . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		
		$map = array(
			"user_id" => UID,"status" => 1
		) ;
		$ord = array(
			"order_status" => "asc","creat_date" => "desc"
		);
		 
		$list = M( "Order" ) -> where( $map ) 
			-> order( $ord ) 
			-> select( ) ;
		if (!empty($list)) {		
		    $this -> assign( "list" , $list ) ;
		}else{
			$this -> assign( "mei" , '1' ) ;
		}
		
		$this -> display( ) ;
	
	}

	/**
	 * 删除订单, 异步post
	 * @date: 2015年12月1日 上午11:00:27
	 * @author: 王崇全
	 * @param: string orderCode 订单编号
	 */
	function orderDel( ) {

		$orderCode = I( "orderCode" ) ;
		
		$map = array(
			"user_id" => UID,"order_code" => $orderCode
		) ;
		$data = array(
			"status" => 2
		) ;
		
		$flag = M( "Order" ) -> where( $map ) -> save( $data ) ;
		
		if( $flag ){
			die( "ok" ) ;
		}else{
			die( "删除失败" ) ;
		}
	
	}

	/**
	 * 订单付款  同步GET接收
	 * @date: 2015年12月1日 上午11:15:52
	 * @author: 王崇全
	 * @param: string orderCode 订单编号
	 */
	function orderPay( ) {

		$this -> assign( "title" , "订单付款" . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		
		//接收订单编号
		$orderCode = I( "orderCode" ) ;
		
		//回传总金额
		$map = array(
			"user_id" => UID,"order_code" => $orderCode
		) ;
		$order = M( "Order" ) -> where( $map ) -> find( ) ;
		$this -> assign( "total" , $order["cost"] ) ;
		
		//回传余额
		$uinfo = D( "user" ) -> field( "balance" ) -> find( UID ) ;
		$this -> assign( "balance" , $uinfo["balance"] ) ;
		
		//回传订单编号
		$this -> assign( "orderCode" , $orderCode ) ;
		
		//获取订单明细,并回传
		$ordDetail = M( "OrderDetail" ) -> where( "order_code=$orderCode" ) -> select( ) ;
		$this -> assign( "ordDetail" , $ordDetail ) ;
		
		layout( 'Layout/noFoot' ) ;
		$this -> display( "Cart/prePay" ) ;
	
	}

	/**
	 * 云购记录
	 * @date: 2015年12月1日 下午1:45:37
	 * @param [ string $orderCode 订单编号 可选] 
	 * @author: 王崇全
	 */
	function records( ) {

		$this -> assign( "title" , "抢购记录" . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		
		//接收订单编号
		$orderCode = I( "orderCode" ) ;
		
		/**
		 * 创建 商品信息 视图 v_goods 
		 */
		M( ) -> execute( "DROP VIEW IF  EXISTS `__PREFIX__v_goods` " ) ;
		
		if( $orderCode ){ //按订单查询
			

			M( ) -> execute( "
                CREATE VIEW __PREFIX__v_goods AS 
                SELECT order_code , goods_id, title, image, nums, qishu, pid
                FROM `__PREFIX__order_detail`  
                WHERE `order_code`=$orderCode 
                " ) ;
		}else{ //查询用户所有            
			

			// 获取用户的订单
			$map = array(
				"user_id" => UID, //当前用户
"status" => 1
			) //未删除的订单
 ;
			$orderCodes = M( "order" ) -> where( $map ) -> getField( "order_code" , true ) ;
			$orderCodes = implode( "," , $orderCodes ) ; //转成字符串
			

			M( ) -> execute( "
                CREATE VIEW __PREFIX__v_goods AS
                SELECT order_code , goods_id, title, image, nums, qishu,pid
                FROM `__PREFIX__order_detail`
                WHERE `order_code` IN ($orderCodes)
                " ) ;
		}
		
		/*
		 * 创建 商品信息 视图 v_goods 结束
		 **/
		
		//获取中奖者,揭晓时间
		$orderCodes = M( "v_goods" ) -> where( $map ) -> getField( "order_code" , true ) ;
		$orderCodes = implode( "," , $orderCodes ) ; //转成字符串
		

		dump( M( "v_goods" ) -> select( ) ) ;
		exit( ) ;
		
		$this -> display( ) ;
	
	}
	
	//获奖者订单进度信息
	function order( ) {

		$date = time( ) ;
		$pid = I( 'get.pid' , 0 , 'int' ) ;
		//if(!$pid){
		//	$this->error('暂无数据');
		//	}
		$where = array(
			'id' => $pid,'status' => 1,'flag' => 0,'winUserId' => UID
		) ;
		
		$user_addr = M( 'user_addr' ) ;
		$users = $user_addr -> where( 'userId=' . UID ) -> find( ) ;
		
		if( empty( $users['addr'] ) ){
			
			$jindutiao = array(
				'hr1' => 'side2','hr2' => 'side1'
			) ;
		}else{
			$jindutiao = array(
				'hr1' => 'side1','hr2' => 'side2'
			) ;
		}
		$periods = M( 'periods' ) ;
		$periodss = $periods -> where( $where ) -> find( ) ;
		$this -> assign( 'jindutiao' , $jindutiao ) ;
		$this -> assign( 'users' , $users ) ;
		$this -> assign( 'dates' , $date ) ;
		$this -> assign( 'periodss' , $periodss ) ;
		$this -> assign( "title" , "订单进度" . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		$this -> display( ) ;
	
	}


}
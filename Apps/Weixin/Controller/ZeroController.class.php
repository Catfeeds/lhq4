<?php

namespace Weixin\Controller ;

use Think\Controller ;
use Weixin\Model\PeriodsZeroModel ;

/**
 * 0元购控制器
 * @date: 2015年12月29日 下午2:57:53
 * @author : hlx
 */
class ZeroController extends CommonController {
	
	//正在倒计时
	function countdown( ) {

		$zero = D( "PeriodsZero" ) ;
		$zero -> comeout( ) ;
		
		$time = time( ) ;
		
		$zero = D( "PeriodsZero" ) ;
		$periodsZero = $zero -> getListbytime( 1 , $time ) ;
		
		$this -> assign( 'empty' , '<span class="empty">没有数据！</span>' ) ;
		$this -> assign( "periodsZero" , $periodsZero ) ;
		$this -> display( "zero_countdown" ) ;
	
	}
	
	//已揭晓
	function publish( ) {

		$zero = D( "PeriodsZero" ) ;
		$zero -> comeout( ) ;
		
		$zero = D( "PeriodsZero" ) ;
		
		$periodsZero = $zero -> getList( 2 ) ;
		
		$this -> assign( 'empty' , '<span class="empty">没有数据！</span>' ) ;
		$this -> assign( "periodsZero" , $periodsZero ) ;
		$this -> display( "zero_publish" ) ;
	
	}

	/* 	function publish(){
	 
	 $zero = D("PeriodsZero");
	 $zero->comeout();
	 
	 $zero = D("PeriodsZero");
	 
	 $start = I('post.start');
	 $count = I('post.count');
	 if(!$start)
	 $start = 0;
	 if(!$count)
	 $count = 10;
	 
	 $periodsZero = $zero->getList(2, $start, $count);
	 
	 $this -> assign('empty','<span class="empty">没有数据！</span>');
	 $this -> assign("periodsZero", $periodsZero);
	 $this -> display("zero_publish");
	 }
	 */
	
	//购买
	function zerobuy( ) {

		$zero = D( "PeriodsZero" ) ;
		
		define( UID , is_login_zero( ) ) ;
		if( UID == 0 || UID == null ){
			$this -> error( '请登录' , U( 'Login/index' ) ) ;
		}
		
		$userId = UID ;
		$pid = I( 'post.pid' ) ;
		
		$returnMap = array() ;
		$returnMap['b'] = false ;
		
		if( ! $pid ){
			$returnMap['msg'] = "系统错误，请刷新页面" ;
			json( $returnMap ) ;
		}
		
		$periodDetailZero = M( "periods_detail_zero" ) ;
		
		$list = $periodDetailZero -> where( "userId = $userId and pid = $pid" ) -> select( ) ;
		
		if( $list != null ){
			$returnMap['msg'] = "您已购买过本期0元购，请等待开奖结果。" ;
			json( $returnMap ) ;
		}
		
		$b = $zero -> insertDetail( $userId , $pid ) ;
		
		if( $b ){
			$returnMap['msg'] = "购买成功！" ;
			$returnMap['b'] = $b ;
			json( $returnMap ) ;
		}else{
			$returnMap['msg'] = "抱歉，购买没有成功。" ;
			json( $returnMap ) ;
		}
	
	}
	
	//未揭晓商品详情
	function productDetail1( ) {

		$zero = D( "PeriodsZero" ) ;
		$zero -> comeout( ) ;
		
		$status = 1 ;
		$pid = I( 'get.pid' ) ;
		$periodsZero = $zero -> getDetailById( $pid ) ;
		
		$images = $zero -> getImagesById( $pid ) ;
		
		$status = $periodsZero[0]['status'] ;
		if( $status == 2 ){
			$this -> assign( "images" , $images ) ;
			$this -> assign( "periodsZero" , $periodsZero ) ;
			$this -> display( "product_detail2" ) ;
		}
		
		$this -> assign( "images" , $images ) ;
		$this -> assign( "periodsZero" , $periodsZero ) ;
		$this -> display( "product_detail1" ) ;
	
	}
	
	//已揭晓商品详情
	function productDetail2( ) {

		$zero = D( "PeriodsZero" ) ;
		$zero -> comeout( ) ;
		
		$pid = I( 'get.pid' ) ;
		
		$periodsZero = $zero -> getDetailById( $pid ) ;
		
		$images = $zero -> getImagesById( $pid ) ;
		
		$this -> assign( "images" , $images ) ;
		$this -> assign( "periodsZero" , $periodsZero ) ;
		$this -> display( "product_detail2" ) ;
	
	}
	
	//查看夺宝号(ajax调用)
	/* 	function successEvent(){
	 $zero = D("PeriodsZero");
	 
	 define(UID,is_login_zero());
	 if(UID == 0 || UID == null){
	 $this->error('请登录',U('Login/index'));
	 }
	 
	 $userId = UID;
	 $pid = I('post.pid');
	 $returnMap = array();
	 $returnMap['b'] = false;
	 
	 if(!$pid){
	 $returnMap['msg'] = "系统错误，请刷新页面";
	 json($returnMap);
	 }
	 
	 $pcode = M("periods_detail_zero")
	 ->where("userId = $userId and pid = $pid")
	 ->getField("pcode");
	 if(!$pcode){
	 $returnMap['msg'] = "请还未0元购得此商品，赶紧购买吧！";
	 json($returnMap);			
	 }
	 
	 $b = $zero->getMyCode($userId, $pid);
	 
	 if(!$b){
	 $returnMap['msg'] = "获得我的夺宝号出现错误，请联系管理员！";
	 json($returnMap);
	 }else{
	 $returnMap['b'] = $b;
	 json($returnMap);
	 }
	 
	 }
	 */
	
	//href调用
	function successEvent( ) {

		$zero = D( "PeriodsZero" ) ;
		
		define( UID , is_login_zero( ) ) ;
		if( UID == 0 || UID == null ){
			$this -> error( '请登录' , U( 'Login/index' ) ) ;
		}
		
		$userId = UID ;
		$pid = I( 'get.pid' ) ;
		
		$b = $zero -> getMyCode( $userId , $pid ) ;
		
		$this -> assign( 'b' , $b ) ;
		$this -> display( "success_events" ) ;
	
	}
	
	//揭晓中奖号码
	function comeout( ) {

		$zero = D( "PeriodsZero" ) ;
		$zero -> comeout( ) ;
	
	}

	/**
	 * 0元购的cheat
	 * 选出一个人买所有0元购的商品
	 */
	function ch( ) {

		$pids = M( 'periods_zero' ) -> alias( 't' ) 
			-> field( 't.id, t.goodsId, t.creatDate, a.start_time, a.last_time, a.interval_time, a.id as cid' ) 
			-> where( 't.status = 1 and t.goodsId in (select goods_id from yytb_cheat where switch = 1 and c_type = 2)' ) 
			-> join( 'left join (select id, goods_id, start_time, last_time, interval_time from yytb_cheat where c_type = 2) a on a.goods_id = t.goodsId' ) 
			-> select( ) ;
		
		//echo M()->getLastSql();
		//exit();
		

		//循环所有在倒计时的0元购商品
		foreach( $pids as $val ){
			$pid = $val['id'] ;
			
			/*			
			 $goodsId = $val['goodsid'];
			 
			 //判断时间是否有效
			 $c = M('cheat')
			 ->field('id, start_time, last_time, interval_time')
			 ->where("goods_id = $goodsId and c_type = 2")
			 ->find();
			 
			 
			 if(!$c)
			 continue;
			 */
			if( time( ) - $val['last_time'] < $val["interval_time"] ){
				continue ;
			}
			
			// 判断此商品是否已经超过设定的开始作弊时间
			if( time( ) - $val["creatdate"] < $val["start_time"] * 60 ){
				continue ;
			}
			
			//购买这个商品
			$zero = D( "PeriodsZero" ) ;
			
			$userId = $zero -> getRobotUserId( $pid ) ;
			
			if( ! $userId ) continue ;
			
			$returnMap = array() ;
			$returnMap['b'] = false ;
			
			if( ! $pid ){
				continue ;
			}
			
			$periodDetailZero = M( "periods_detail_zero" ) ;
			
			$list = $periodDetailZero -> where( "userId = $userId and pid = $pid" ) -> select( ) ;
			
			if( $list != null ){
				continue ;
			}
			
			$b = $zero -> insertDetail( $userId , $pid ) ;
			
			echo 1 ;
			//改变当前时间
			if( ! $b ) continue ;
			
			$cId = $val['cid'] ;
			$data['last_time'] = time( ) ;
			$b = M( 'cheat' ) -> where( "id = $cId" ) -> save( $data ) ;
		}
		
		die( 'ok' ) ;
	
	}


}




























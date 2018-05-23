<?php

namespace Admin\Model ;

use Think\Model ;

/**
 * 发货信息
 * @date: 2016年2月22日 上午10:30:05
 */
class ExpressModel extends Model {

	/**
	 * 返回 发货信息 json 数据
	 */
	public function jsonList( ) {

		$page = I( 'post.page' , 1 , 'int' ) ;
		$row = I( 'post.rows' , 10 , 'int' ) ;
		$type = I( 'post.type' , 0 , 'int' ) ;
		$status = I( 'post.status' , 0 , 'int' ) ;
		$keyword = I( 'post.keywrod' , '' , 'trim' ) ;
		$express_id = I( 'post.express_id' , 0 , 'int' ) ;
		
		$skip = ( $page - 1 ) * $row ;
		$order = array(
			'e.id' => 'desc'
		) ;
		
		// 筛选
		$where = array() ;
		if( ! empty( $keyword ) ){
			$where['e.name|e.phone|e.address'] = array(
				'like',"%$keyword%"
			) ;
		}
		
		
		$date = I('date/s');
		if(!empty($date)){
			list($start, $end) = explode(' - ', $date);
			if($start && $end){
				$start = strtotime("$start 0:00:00");
				$end = strtotime("$end 23:59:59");
				$where['e.deliver_time']  = array('between', array($start, $end));
			}
		}
		
		
		if( $status ) $where['e.status'] = $status ;
		if( $express_id ) $where['e.express_id'] = $express_id ;
		
		$types = array(
			'全部','普通商品','包场商品','0 元购'
		) ;
		
		if( $type && array_key_exists( $type , $types ) ) $where['e.type'] = $type ;
		
		// 获取商品
		$field = "
				e.id, e.express_no, e.address, e.name as recipient,	e.get_time,e.deliver_time, e.type, e.status,
				ec.name as exp_com,ec.key,
				case e.type
					when 1 then p.title 		
					when 2 then b.goods_title		

				end as goods_title,
				case e.type
					when 1 then p.discloseDate 		
					when 2 then b.lottery_time		

				end as lottery_time,
				if( u.nickName is not null, u.nickName, u1.nickName ) as winner,
				if( u.member_id is not null, u.member_id, u1.member_id) as ur_id
			" ;
		
		$list = M( 'express' ) -> alias( 'e' ) 
			-> field( $field ) 
			-> join( 'LEFT JOIN __EXPRESS_COM__ as ec ON e.express_id = ec.id' ) 
			-> join( 'LEFT JOIN __PERIODS__ as p ON ( e.pid = p.id and e.type=1 )' ) 
			-> join( 'LEFT JOIN __BBOOKING__ as b ON ( e.pid = b.id and e.type=2 )' ) 
			-> join( 'LEFT JOIN __BBOOKING_DETAIL__ as bd ON (b.id = bd.bbid and b.lottery_code=bd.bbcode and e.type=2)' ) 
			-> join( 'LEFT JOIN member as u ON ( p.winUserId=u.member_id and e.type=1) ' ) 
			-> join( 'LEFT JOIN member as u1 ON ( bd.participator=u1.member_id and e.type=2 ) ' ) 
			-> where( $where ) 
			-> order( $order ) 
			-> limit( $skip , $row ) 
			-> select( ) ;
		//echo M( ) -> getLastSql( ) ;
		// 		foreach( $list as $key => $value ){
		// 			if( ! $value['nickName'] ){
		// 				$list[$key]['nickname'] = M( 'User' ) -> where( array(
		// 					'id' => $value['ur_id']
		// 				) ) -> getField( 'nickName' ) ;
		// 			}
		// 		}
		//dump( $list ) ;
		

		//die( ) ;
		//$_sql[] = M( ) -> _sql( ) ;		
		

		$count = M( 'express' ) -> alias( 'e' ) 
			-> join( 'LEFT JOIN __EXPRESS_COM__ as ec ON e.express_id = ec.id' ) 
			-> join( 'LEFT JOIN __PERIODS__ as p ON e.pid = p.id' ) 
			-> join( 'LEFT JOIN __BBOOKING__ as b ON e.pid = b.id' ) 
			-> join( 'LEFT JOIN __BBOOKING_DETAIL__ as bd ON (b.id = bd.bbid and b.lottery_code=bd.bbcode)' ) 
			-> join( 'LEFT JOIN member as u ON ( u.member_id = p.winUserId  and e.type<>2) ' ) 
			-> join( 'LEFT JOIN member as u1 ON ( bd.participator=u1.member_id and e.type=2 ) ' ) 
			-> where( $where ) 
			-> count( ) ;
		//echo M( ) -> getLastSql( ) ;
		//die( ) ;
		return array(
			'count' => count( $list ),
			// '_sql' => $_sql,
			'total' => $count,'page' => $page,'row' => $row,'list' => $list
		) ;
	
	}

	/**
	 * 获取运单详情
	 */
	public function getInfo( $id = false ) {

		if( ! $id ) return ;
		
		$where = array(
			'id' => $id
		) ;
		$field = "*" ;
		
		$express = M( 'express' ) -> alias( 'exp' ) 
			-> field( $field ) 
			-> 
		// ->join('LEFT JOIN __EXPRESS_COM__ as expcom ON exp.express_id = expcom.id')
		// ->join('LEFT JOIN __PERIODS__ as ps ON exp.pid = ps.id')
		where( $where ) 
			-> find( ) ;
		
		if( ! $express ) return ;
		
		// Express 表 type 字段信息
		switch( $express['type'] ){
			case 1 : // 普通商品 查询 periods 表
				$goods = M( 'periods' ) -> find( $express['pid'] ) ;
				break ;
			case 2 : // 包场
				$goods = M( 'bbooking' ) -> find( $express['pid'] ) ;
				break ;
			case 3 : // 0元购
				$goods = M( 'periods_zero' ) -> find( $express['pid'] ) ;
				break ;
			default:
				$goods = M( 'periods' ) -> find( $express['pid'] ) ;
				break ;
		}
		
		// 包场时商品信息字段改变
		if( $goods['sales'] ){
			
			$goods['canyushu'] = $goods['sales'] ;
			$goods['title'] = $goods['goods_title'] ;
			$goods['total'] = $goods['goods_value'] ;
		}
		if( $goods ) $express = array_merge( $goods , $express ) ;
		
		return $express ;
	
	}

	/**
	 * 更新数据
	 */
	public function update( ) {

		$id = I( 'id/d' , 0 ) ;
		$title = I( 'title' ) ;
		if( ! $id ) return ;
		
		M( ) -> startTrans( ) ;
		
		try{
			$data['type'] = 1 ;
			$data['send_time'] = time( ) + 10 ;
			$data['send_to'] = I( 'ur_id' ) ;
			$data['content'] = '您中得商品' . $title . '已经发货，请保持电话畅通，收到商品后记得晒单呦，晒单好礼相送！' ;
			$data['create_time'] = time( ) ;
			$data['sended'] = 0 ;
			
			unset( $_POST['title'] ) ;
			unset( $_POST['ur_id'] ) ;
			
			$this -> create( ) ;
			$this -> status = 2 ;
			$this -> deliver_time = time( ) ;
			$this -> save( ) ;
			
			$express = $this -> find( $id ) ;
			
			if( $express['type'] == 3 ){
				$data['go_url'] = C( "site_url" ) . U( "Weixin/User/myaward_zero" ) ;
			}elseif( $express['type'] == 2 ){
				$data['go_url'] = C( "site_url" ) . U( "Weixin/User/myaward_room" ) ;
			}else{
				$data['go_url'] = C( "site_url" ) . U( "Weixin/User/myaward" ) ;
			}
			M( "msg_queue" ) -> add( $data ) ;
			// 更新对应中奖码表信息 普通商品 periods 表
			switch( $express['type'] ){
				/*
				 * 普通商品 更新 periods 表 flag 字段
				 * 0 未填写收货地址  1 已填写收货地址  2 后台已发货  3 用户已确认收货
				 */
				case 1 :
					$goods = M( 'periods' ) -> where( array(
						'id' => $express['pid']
					) ) -> save( array(
						'flag' => 2
					) ) ;
					break ;
				default:
					break ;
			}
			M( ) -> commit( ) ;
			admJson( array() ) ;
		}catch(\Exception $e){
			M( ) -> rollback( ) ;
		}
		json( array(
			'code' => 500
		) ) ;
	
	}


}






















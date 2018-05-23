<?php

namespace Admin\Model ;

use Think\Model ;

/**
 * 商品模型
 */
class GoodsModel extends Model {

	public function jsonList( ) {

		$page = I( 'get.page' , 1 , 'int' ) ;
		
		$row = I( 'get.rows' , 10 , 'int' ) ;
		
		$skip = ( $page - 1 ) * $row ;
		$order = array(
			'creatDate' => 'desc'
		) ;
		//	'updateDate' => 'desc'
		

		// 筛选
		$where = array() ;
		
		$keyword = I( 'get.keyword' , 0 ) ;
		if( $keyword ) $where['title'] = array(
			'like',"%$keyword%"
		) ;
		
		$typeId = I( 'get.typeId' , 0 ) ;
		if( $typeId ) $where['typeId'] = $typeId ;
		
		$status = I( 'get.status' ) ;
		
		if( in_array( $status , array(
			0,1
		) ) ){
			$where['t.status'] = $status ;
		}
		
		// 获取商品
		$GoodsM = M( 'goods' ) ;
		$goods = $GoodsM -> alias( "t" ) 
			-> field( 't.id, t.title, t.typeId, t.canyushu, t.fenshu, t.price, t.qishu, t.image, t.creatDate, t.updateDate, t.status, t.recommend' )
			-> 
		//-> join( "left join (select a.goodsId, a.`status` from yytb_periods_zero a join(select goodsId, max(qishu) as qishu from yytb_periods_zero GROUP BY goodsId) b on a.goodsId = b.goodsId and a.qishu = b.qishu) z on t.id = z.goodsId " ) 
		where( $where ) 
			-> order( $order ) 
			-> limit( $skip , $row ) 
			-> select( ) ;
// 		echo M( ) -> _sql( ) ;
// 		dump( $goods ) ;
// 		exit( ) ;
		$count = $GoodsM -> alias( "t" ) 
			-> where( $where ) 
			-> count( ) ;
		
		return array(
			'count' => count( $goods ),'total' => $count,'page' => $page,'row' => $row,'goods' => $goods
		) ;
	
	}

	public function test( ) {

		echo ' === Goods === ' ;
		return ;
	
	}


}
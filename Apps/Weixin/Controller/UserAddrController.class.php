<?php

namespace Weixin\Controller ;

use Think\Controller ;

// 用户地址修改
class UserAddrController extends IsLoginController {
	
	// 获取地址列表
	public function index( ) {

		$list = M( 'user_addr' ) -> where( 'userId=' . UID ) 
			-> order( 'status desc' ) 
			-> select( ) ;
		
		if( ! $list ){
			$data = array(
				'code' => 500,
				'msg' => '您还没有收货地址，请添加'
			) ;
		}else{
			$data = array(
				'code' => 1,
				'userAddr' => $list
			) ;
		}
		appJson( $data ) ;
	
	}
	
	// 修改地址信息
	public function update( ) {

		$addr_id = I( 'post.addr_id' , 0 , 'int' ) ;
		
		$data = array(
			'code' => 500
		) ;
		if( ! $addr_id ){
			$data['msg'] = 'id 错误' ;
		}else{
			$UserAddr = D( 'UserAddr' ) ;
			if( ! $UserAddr -> create( ) ){
				$data['msg'] = $UserAddr -> getError( ) ;
			}else{
				$UserAddr -> where( "addr_id=$addr_id and userId=" . UID ) -> save( ) ;
				$data['code'] = 1 ;
			}
		}
		
		appJson( $data ) ;
	
	}
	
	// 添加地址信息
	public function add( ) {

		$data = array(
			'code' => 500
		) ;
		$UserAddr = D( 'UserAddr' ) ;
		if( ! $UserAddr -> create( ) ){
			$data['msg'] = $UserAddr -> getError( ) ;
		}else{
			$UserAddr -> add( ) ;
			$data['code'] = 1 ;
		}
		
		appJson( $data ) ;
	
	}
	
	// 删除地址信息
	public function del( ) {

		$addr_id = I( 'post.addr_id' , 0 , 'int' ) ;
		$i = D( 'UserAddr' ) -> where( "addr_id=$addr_id and userId=" . UID ) -> delete( ) ;
		appJson( array(
			'code' => 1
		) ) ;
	
	}
	
	// 修改地址信息
	public function set( ) {

		$addr_id = I( 'post.addr_id' , 0 , 'int' ) ;
		
		$UserAddr = D( 'UserAddr' ) ;
		$UserAddr -> where( "userId=" . UID ) -> save( array(
			'status' => 0
		) ) ;
		$UserAddr -> where( "addr_id=$addr_id and userId=" . UID ) -> save( array(
			'status' => 1
		) ) ;
		
		appJson( array(
			'code' => 1
		) ) ;
	
	}


}
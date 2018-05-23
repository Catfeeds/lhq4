<?php

/**
 * 简爱
 * Date 2016年01月05日
 */
namespace Admin\Controller ;

use Think\Controller ;

class ExpressController extends AdminController {

	function _initialize( ) {

		parent :: _initialize( ) ;
		$types = M( 'express_com' ) -> field( 'id, name' ) 
			-> where( 'status=1' ) 
			-> select( ) ;
		$this -> assign( 'types' , $types ) ;
	
	}

	/**
	 * 发货管理
	 */
	public function index( ) {

		if( IS_AJAX ){
			$data = D( 'Express' ) -> jsonList( ) ;
			json( $data ) ;
		}
		
		$this -> assign( 'title' , '发货管理' ) ;
		$this -> display( ) ;
	
	}

	/**
	 * 发货管理
	 */
	public function deliver( ) {

		$id = I( 'id' , 0 , 'int' ) ;
		if( ! $id ){
			return ;
		}
        //dump($id);
		if( IS_POST ){
			D( 'Express' ) -> update( ) ;
		}

		$express = D( 'express' )->getInfo( $id ) ;
		//dump($express);die;
		layout( "inc/tpl.min" ) ;
		$this -> assign( 'title' , '发货操作' ) ;
		//$this -> assign( 'i_data' , I( ) ) ;
		$this -> assign( 'express' , $express ) ;
		$this -> display( ) ;
	
	}


}












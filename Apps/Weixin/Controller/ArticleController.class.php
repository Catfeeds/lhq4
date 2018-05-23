<?php

namespace Weixin\Controller ;

use Think\Controller ;

class ArticleController extends CommonController {
	
	//了解云团
	function aboutUs( $id ) {

		$this -> assign( "title" , "关于我们" . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
//dump($id);//die;
		if( $id ){
			$article = M( 'article' ) -> find( $id ) ;
		}else{
			$this -> error( '内容未找到' ) ;
		}
      //  dump($article);die;
		$this -> assign( 'article' , $article ) ;
		
		$this -> display( ) ;
	
	}

	function help( ) {

		$this -> assign( "title" , "帮助中心" ) ;
		$this -> display( ) ;
	
	}


}

<?php
namespace Admin\Controller ;

use Think\Controller ;

/**
 * 自动购买控制器
 * @date 2016年1月5日 下午10:05:45
 * @author 王崇全
 */
class CheatController extends AdminController {

	function index( ) { // 自动购买管理

		$this -> assign( 'title' , "自动购买管理" ) ;	
		
		
		//$tUser = C( "DB_PREFIX" ) . "user" ; //订单明细表
        $tUser ="member" ;
        $tGoods = C( "DB_PREFIX" ) . "goods" ; //订单明细表
		$tCheat = C( "DB_PREFIX" ) . "cheat" ; //订单明细表
		

		//级联设置
		$join1 = "
		LEFT JOIN $tGoods
		ON $tCheat.goods_id =$tGoods.id
		" ;
		
		//所需字段
		$field = "
			$tCheat.id,
			$tCheat.start_time,
			$tCheat.interval_time,
			$tCheat.switch,
			$tCheat.c_type,
			$tCheat.k_type,
			$tGoods.title
		" ;
		
		//接收检索类型
		$cheatType=I("get.cheatType",'',"int");
		
		if($cheatType){
			$map["c_type"]=$cheatType;
			$this -> assign( "cheatType" , $cheatType ) ;
		}
		if($cheatType==0){
			unset($map["c_type"]);
		}
		
		//接收检索关键词
		$keyword=I("get.key");
		if($keyword){
			$map["$tGoods.title"]=array("like","%$keyword%");
			$this -> assign( "keyword" , $keyword ) ;
		}else{
			unset($map["$tGoods.title"]);
		}
		

		$count= M( "cheat" )-> join( $join1 )->where($map)->count();// 查询满足要求的总记录数
		$Page= new \Think\Page($count,13);// 实例化分页类 传入总记录数和每页显示的记录数
		$show= $Page->show();// 分页显示输出
		$this->assign('page',$show);// 赋值分页输出
		
		$list = M( "cheat" ) -> field( $field ) 
			-> join( $join1 ) ->where($map)->limit($Page->firstRow.','.$Page->listRows)
			-> select( ) ;
		
		//dump($show);exit;
		
		$cheatStatus = M( "cheat_status" ) -> where( "cheat_id=1" ) -> getField( "switch" , false ) ;
		
		$this -> assign( "cheatStatus" , $cheatStatus ) ;
		$this -> assign( "list" , $list ) ;
		$this -> display( ) ;
	
	}

	function addCheat( ) { // 添加自动购买管理

		if( IS_POST ){
			
			$goodsid = I( "post.goodsid" ) ;
			$intervalTime = I( "post.intervalTime" ) ;
			
			if( $intervalTime < 60 ){
				$this -> error( "间隔时间不能低于60秒" ) ;
			}
			
			//添加是否0元购或普通云购
			$cType = I( 'post.cType' ) ;
			if( ! $cType ){
				$this -> error( "必须选择类型" ) ;
			}
			
			//选择开奖类型
			$kType = I( 'post.kType' ) ;
			if( ! $kType ){
				$this -> error( "必须选择开奖类型" ) ;
			}
			
			$startTime = I( "post.startTime" ) ;
			$startTime = (float) $startTime ;
			
			if( $goodsid <= 0 || $startTime <= 0 ){
				$this -> error( "请指定商品和自动购买时间" ) ;
			}
			
			$goodsInfo = M( "Goods" ) -> field( "qishu,maxqishu,status" ) -> find( $goodsid ) ;
			if( ! $goodsInfo || $goodsInfo["status"] == 0 ){
				$this -> error( "此商品已关闭或无此商品" ) ;
			}
			
			//检查此商品是否已经设置自动购买
			$cheatinfo = M( "cheat" ) -> where( array(
				"goods_id" => $goodsid,"c_type" => $cType
			) ) -> find( ) ;
			if( $cheatinfo ){
				$this -> error( "此商品是否已经设置自动购买" ) ;
			}
			
			$flag = M( "cheat" ) -> add( array(
				"goods_id" => $goodsid,"start_time" => $startTime,"interval_time" => $intervalTime,"create_time" => time( ),"c_type" => $cType,"k_type" => $kType
			) ) ;
			
			if( ! $flag ){
				$this -> error( "操作失败" ) ;
			}
			
			$this -> success( "操作成功" ) ;
		}
		
		$this -> assign( 'title' , "自动购买添加" ) ;
		
		//商品列表
		$map = array(
			"rstatus" => 0,"status" => 1
		) ;
		$goodsList = M( "Goods" ) -> field( "id,title,qishu,maxqishu" ) 
			-> where( $map ) 
			-> order( "title" ) 
			-> select( ) ;
		$this -> assign( "goodsList" , $goodsList ) ;
		
		$this -> display( ) ;
	
	}

	function getGoods( ) { // Ajax 获取商品信息

		$goodsid = I( "post.goodsid" ) ;
		$goodsInfo = M( "goods" ) -> field( "qishu,maxqishu" ) -> find( $goodsid ) ;
		$qishu = $goodsInfo["qishu"] ;
		$maxqishu = $goodsInfo["maxqishu"] ;
		//最小期数
		$data["min"] = (int) $qishu ;
		//最大期数
		if( $maxqishu - $qishu >= 5 ){
			$data["max"] = $qishu + 5 ;
		}else{
			$data["max"] = $maxqishu ;
		}
		
		$this -> ajaxReturn( $data ) ;
	
	}

	function cheatSwitch( ) { // 开关自动购买

		if( IS_AJAX ){
			$cheatSwitch = I( "post.cheatSwitch" , '' , "int" ) ;
			$cheatId = I( "post.cheatId" , '' , "int" ) ;
            $time=time();
            $data['switch'] = $cheatSwitch;
            $data['last_time'] = $time;
			//$flag = M( ) -> execute( "UPDATE `__PREFIX__cheat` SET `switch`=$cheatSwitch WHERE `id`=$cheatId" ) ;
			//die( M( ) -> getLastSql( ) ) ;
            $flag =M( "cheat" )->where(array('id'=>$cheatId))-> save($data);
			if( ! $flag ){
				$this -> error( "操作失败" ) ;
			}
			$this -> success( "操作成功" ) ;
			exit( ) ;
		}
	
	}

	function cheatSwitchAll( ) { // 开关所有自动购买

		$cheatSwitch = I( "cheatStatus" ) ;
		if( ( $cheatSwitch != 0 ) && ( $cheatSwitch != 1 ) ){
			die( ) ;
		}
        $time=time();
       /* $data['switch'] = $cheatSwitch;
        $data['last_time'] = $time;*/
        //dump($time) ;die;
      // M( "cheat" )-> save($data);
		M( ) -> execute( "UPDATE `__PREFIX__cheat` SET `switch`=$cheatSwitch,`last_time`=$time" ) ;
		
		die( ) ;
	
	}

	function del( ) { // 删除自动购买

		$id = I( "post.id" , '' , "int" ) ;
		if( ! $id ){
			$this -> error( "参数有误" ) ;
		}
		$flag = M( "cheat" ) -> where( array(
			"id" => $id
		) ) -> delete( ) ;
		
		if( ! $flag ){
			$this -> error( "删除失败" ) ;
		}
		
		$this -> success( "删除成功" ) ;
		exit( ) ;
	
	}

	function edit( ) { // 编辑自动购买

		if( IS_POST ){
			$cheatid = I( "post.cheatid" , '' , "int" ) ;
			$goodsid = I( "post.goodsid" , '' , "int" ) ;
			$intervalTime = I( "post.intervalTime" , '' , "int" ) ;
			$startTime = I( "post.startTime" , '' , "int" ) ;
			
			if( ! ( $goodsid && $intervalTime && $startTime ) ){
				$this -> error( "请填写所有参数" ) ;
			}
			
			if( $intervalTime < 60 ){
				$this -> error( "间隔时间不能低于60秒" ) ;
			}
			
			//添加是否0元购或普通云购
			$cType = I( 'post.cType' ) ;
			if( ! $cType ){
				$this -> error( "必须选择类型" ) ;
			}
			
			//选择开奖类型
			$kType = I( 'post.kType' ) ;
			if( ! $kType ){
				$this -> error( "必须选择开奖类型" ) ;
			}
			
			//检查此商品是否已经设置自动购买
			/*$cheatinfo = M( "cheat" ) -> where( array(
			 "goods_id" => $goodsid, "c_type" => $cType
			 ) ) -> find( ) ;
			 if( $cheatinfo ){
			 $this -> error( "此商品此种类型是否已经设置自动购买" ) ;
			 }*/
			
			//检查此商品是否已经设置自动购买
			$cheatData = array(
				"id" => $cheatid,"goods_id" => $goodsid,"start_time" => $startTime,"interval_time" => $intervalTime,"c_type" => $cType,"k_type" => $kType
			) ;
			
			$a = M( "cheat" ) -> create( $cheatData ) ;
			$ff = M( "cheat" ) -> save( ) ;
			
			if( ! $ff ){
				$this -> error( "修改失败或未做修改" ) ;
			}
			$this -> success( "修改成功" ) ;
		}
		
		$this -> assign( 'title' , '编辑自动购买' ) ;
		
		$tGoods = C( "DB_PREFIX" ) . "goods" ; //订单明细表
		$tCheat = C( "DB_PREFIX" ) . "cheat" ; //订单明细表
		

		//级联设置
		$join1 = "
		LEFT JOIN $tGoods
		ON $tCheat.goods_id =$tGoods.id
		" ;
		
		//所需字段
		$field = "
		$tCheat.goods_id,
		$tCheat.id,
		$tCheat.start_time,
		$tCheat.interval_time,
		$tCheat.switch,
		$tCheat.c_type,
		$tCheat.k_type,
		$tGoods.title
		" ;
		$id = I( "get.id" , '' , "int" ) ;
		
		$list = M( "cheat" ) -> field( $field ) 
			-> where( array(
			"$tCheat.id" => $id
		) ) 
			-> join( $join1 ) 
			-> find( ) ;
		
		$cheatStatus = M( "cheat_status" ) -> where( "cheat_id=1" ) -> getField( "switch" , false ) ;
		
		if( ! $list ){
			$this -> error( "无此信息" ) ;
		}
		$this -> assign( "info" , $list ) ;
		
		$this -> display( ) ;
	
	}


	/* 	function edit( ) {
	 
	 $this -> assign( 'title' , '新增自动购买' ) ;
	 
	 $user = M( 'User' ) -> field( 'uid,nickname,username,email' ) -> find( $uid ) ;
	 $this -> assign( 'user' , $user ) ;
	 
	 layout( "inc/tpl.min" ) ;
	 $types = M( 'goods_type' ) -> where( "status = 1" ) 
	 -> order( array(
	 'taxis, id desc'
	 ) ) 
	 -> select( ) ;
	 $this -> assign( 'do' , 'add' ) ;
	 $this -> assign( 'types' , $types ) ;
	 $this -> display( 'edit' ) ;
	 
	 } */
}
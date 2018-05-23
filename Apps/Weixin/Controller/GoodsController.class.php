<?php

namespace Weixin\Controller ;

use Think\Controller ;

class GoodsController extends CommonController {
	
	// 限购专区
	function index( ) {
		// 同步获取云购商品和获奖者姓名
		$map = array() ;
		$map['a.status'] = 1 ;
		$field = "a.id, a.winUserId,a.title, a.goodsId, a.winningCode, a.discloseDate, a.canyushu, a.qishu, a.status, b.nickname" ;
		$list = M( 'periods' ) -> field( $field ) 
			-> alias( 'a' ) 
			-> join( 'LEFT JOIN member b ON a.winUserId = b.member_id' )
			-> where( $map ) 
			-> limit( 10 ) 
			-> order( "a.discloseDate desc, a.id desc" ) 
			-> select( ) ;
		$this -> assign( "periodsArr" , $list ) ;
		
		// 获取商品列表
		

		$page = I( 'param.page' , 1 , 'int' ) ;
		$show_index = 30 ;
		
		if( IS_AJAX ){
			$goods = M( 'goods' ) ;
			$goodsarr = $goods -> where( "limit_buy>0" ) 
				-> order( 'goods_popular desc' ) 
				-> limit( 20 ) 
				-> page( $pages , $show_index ) 
				-> select( ) ;
			// print_r($goodsarr);die;
			foreach( $goodsarr as &$v ){
				$v['url'] = U( 'Weixin/Goods/product_detail2' , 'id=' . $v['id'] ) ;
			}
			
			$max_page = ceil( $goods -> where( "limit_buy>0" ) -> count( ) / $show_index ) ;
			json( array(
				'page' => $page,'max_page' => $max_page,'list' => $goodsarr
			) ) ;
		}
		$this -> assign( 'max_page' , $max_page ) ;
		
		$this -> assign( "title" , "限购专区" . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		$this -> display( ) ;
	
	}
	
	// 获取商品列表
	function product_list_new( ) {
		
		// 获取商品类型
		$goodstype = M( 'goods_type' ) ;
		$goodstypes = $goodstype -> where( "status=1" ) 
			-> order( 'taxis' ) 
			-> select( ) ;
		$this -> assign( 'goodstypes' , $goodstypes ) ;
		
		// 获取分类
		$where = array() ;
		$typeid = I( 'get.typeid' , 0 , 'int' ) ;
		if( $typeid ){
			$where['typeId'] = $typeid ;
		}
		$where['status'] = 1 ;
		// 获取最新，人气，价值
		$order1 = array() ;
		$ord ;
		if( isset( $_GET["ord"] ) ){
			$ord = $_GET['ord'] ;
			switch( $_GET["ord"] ){
				
				case 1 : // 即将揭晓
					$order1[] = 'canyushu/fenshu desc' ;
					$page = I( 'get.page' , 1 , 'int' ) ;
					$show_index = 30 ;
					
					// 获取商品
					$goods = M( 'goods' ) ;
					$max_page = ceil( $goods -> where( $where ) -> count( ) / $show_index ) ;
					
					if( IS_AJAX ){
						$goodsarr = $goods -> order( $order1 ) 
							-> where( $where ) 
							-> page( $page , $show_index ) 
							-> select( ) ;
						
						foreach( $goodsarr as &$v ){
							$v['url'] = U( 'Weixin/Goods/product_detail2' , 'id=' . $v['id'] ) ;
						}
						
						json( array(
							'page' => $page,'max_page' => $max_page,'list' => $goodsarr
						) ) ;
					}
					break ;
				case 2 : // 最新
					$field = "a.id,a.title,a.description,a.content,a.image,a.originPrice,
        				a.price,a.qishu,a.fenshu,a.canyushu,a.limit_buy,a.maxqishu,a.status,
        				a.recommend" ;
					$order1["b.creatDate"] = "desc" ;
					$page = I( 'get.page' , 1 , 'int' ) ;
					$show_index = 30 ;
					
					// 获取商品
					$goods = M( 'goods' ) ;
					$max_page = ceil( $goods -> where( $where ) -> count( ) / $show_index ) ;
					
					if( IS_AJAX ){
						$goodsarr = $goods -> field( $field ) 
							-> alias( 'a' ) 
							-> join( 'left join ( SELECT goodsId, max(id) as id, COUNT(DISTINCT goodsId)
						            				FROM (select id, goodsId from  yytb_periods_detail order by id desc )
						            				a GROUP BY a.goodsId ORDER BY id DESC LIMIT 0,30) b on a.id = b.goodsId' ) 
							-> order( 'b.id desc' ) 
							-> page( $page , $show_index ) 
							-> select( ) ;
						
						foreach( $goodsarr as &$v ){
							$v['url'] = U( 'Weixin/Goods/product_detail2' , 'id=' . $v['id'] ) ;
						}
						
						json( array(
							'page' => $page,'max_page' => $max_page,'list' => $goodsarr
						) ) ;
					}
					break ;
				case 3 : // 人气
					$order1["qishu"] = "desc" ;
					$page = I( 'get.page' , 1 , 'int' ) ;
					$show_index = 30 ;
					
					// 获取商品
					$goods = M( 'goods' ) ;
					$max_page = ceil( $goods -> where( $where ) -> count( ) / $show_index ) ;
					
					if( IS_AJAX ){
						$goodsarr = $goods -> order( $order1 ) 
							-> where( $where ) 
							-> page( $page , $show_index ) 
							-> select( ) ;
						
						foreach( $goodsarr as &$v ){
							$v['url'] = U( 'Weixin/Goods/product_detail2' , 'id=' . $v['id'] ) ;
						}
						
						json( array(
							'page' => $page,'max_page' => $max_page,'list' => $goodsarr
						) ) ;
					}
					break ;
				case 4 : // 价值(由高到低)
					$order1["originPrice"] = "desc" ;
					$page = I( 'get.page' , 1 , 'int' ) ;
					$show_index = 30 ;
					
					// 获取商品
					$goods = M( 'goods' ) ;
					$max_page = ceil( $goods -> where( $where ) -> count( ) / $show_index ) ;
					
					if( IS_AJAX ){
						$goodsarr = $goods -> order( $order1 ) 
							-> where( $where ) 
							-> page( $page , $show_index ) 
							-> select( ) ;
						
						foreach( $goodsarr as &$v ){
							$v['url'] = U( 'Weixin/Goods/product_detail2' , 'id=' . $v['id'] ) ;
						}
						
						json( array(
							'page' => $page,'max_page' => $max_page,'list' => $goodsarr
						) ) ;
					}
					break ;
				case 5 : // 价值(由低到高)
					$order1["originPrice"] = "asc" ;
					$page = I( 'get.page' , 1 , 'int' ) ;
					$show_index = 30 ;
					
					// 获取商品
					$goods = M( 'goods' ) ;
					$max_page = ceil( $goods -> where( $where ) -> count( ) / $show_index ) ;
					
					if( IS_AJAX ){
						$goodsarr = $goods -> order( $order1 ) 
							-> where( $where ) 
							-> page( $page , $show_index ) 
							-> select( ) ;
						
						foreach( $goodsarr as &$v ){
							$v['url'] = U( 'Weixin/Goods/product_detail2' , 'id=' . $v['id'] ) ;
						}
						
						json( array(
							'page' => $page,'max_page' => $max_page,'list' => $goodsarr
						) ) ;
					}
					break ;
				case 6 : // 剩余人次
					$order1[] = 'fenshu-canyushu asc' ;
					$page = I( 'get.page' , 1 , 'int' ) ;
					$show_index = 30 ;
					
					// 获取商品
					$goods = M( 'goods' ) ;
					$max_page = ceil( $goods -> where( $where ) -> count( ) / $show_index ) ;
					
					if( IS_AJAX ){
						$goodsarr = $goods -> order( $order1 ) 
							-> where( $where ) 
							-> page( $page , $show_index ) 
							-> select( ) ;
						
						foreach( $goodsarr as &$v ){
							$v['url'] = U( 'Weixin/Goods/product_detail2' , 'id=' . $v['id'] ) ;
						}
						
						json( array(
							'page' => $page,'max_page' => $max_page,'list' => $goodsarr
						) ) ;
					}
					break ;
				default:
					//$order1 ["taxis"] = "desc";
					$order1[] = 'canyushu/fenshu desc' ;
					$page = I( 'get.page' , 1 , 'int' ) ;
					$show_index = 30 ;
					
					// 获取商品
					$goods = M( 'goods' ) ;
					$max_page = ceil( $goods -> where( $where ) -> count( ) / $show_index ) ;
					
					if( IS_AJAX ){
						$goodsarr = $goods -> order( $order1 ) 
							-> where( $where ) 
							-> page( $page , $show_index ) 
							-> select( ) ;
						
						foreach( $goodsarr as &$v ){
							$v['url'] = U( 'Weixin/Goods/product_detail2' , 'id=' . $v['id'] ) ;
						}
						
						json( array(
							'page' => $page,'max_page' => $max_page,'list' => $goodsarr
						) ) ;
					}
					break ;
			}
		}else{
			$order1[] = 'canyushu/fenshu desc' ;
			$page = I( 'get.page' , 1 , 'int' ) ;
			$show_index = 30 ;
			
			// 获取商品
			$goods = M( 'goods' ) ;
			$max_page = ceil( $goods -> where( $where ) -> count( ) / $show_index ) ;
			
			if( IS_AJAX ){
				$goodsarr = $goods -> order( $order1 ) 
					-> where( $where ) 
					-> page( $page , $show_index ) 
					-> select( ) ;
				
				foreach( $goodsarr as &$v ){
					$v['url'] = U( 'Weixin/Goods/product_detail2' , 'id=' . $v['id'] ) ;
				}
				
				json( array(
					'page' => $page,'max_page' => $max_page,'list' => $goodsarr
				) ) ;
			}
		}
		
		$this -> assign( 'max_page' , $max_page ) ;
		// $this->assign('goodsarr',$goodsarr);
		if( $typeid ){
			$category = M( 'goods_type' ) -> find( $typeid ) ;
		}
		$this -> assign( "ord" , $ord ) ;
		$this -> assign( 'category' , $category ) ;
		$this -> assign( "title" , "所有商品" . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		$this -> display( ) ;
	
	}
	
	// 获取开奖表
	function my_award( ) {
		
		// 获取开奖时间
		$id = I( 'get.id' , 0 , 'int' ) ; // periods表id
		$periods = M( 'periods' ) ;
		$periodsArr = $periods -> where( array(
			'id' => $id,'status' => '1'
		) ) -> find( ) ;
		$this -> assign( 'periodsArr' , $periodsArr ) ;
		
		foreach( $periodsArr as $valu )
			
			// 获取显示图片
			$goods = M( 'goods' ) ;
		$goodsArr = $goods -> field( 'image' ) 
			-> where( "id=" . $valu ) 
			-> find( ) ;
		$this -> assign( 'goodsArr' , $goodsArr ) ;
		$this -> assign( "title" , "开奖" . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		$this -> display( ) ;
	
	}
	
	// 最新揭晓页面
	function new_publish( ) {
		
		// 获取当前时间
		$date = time( ) ;
		$this -> assign( 'date' , $date ) ;
		
		if( IS_AJAX ){
			$time = time( ) ;
			// 同步获取云购商品和商品主图
			$map = array(
				'ps.status' => array(
					'in',array(
						'1','2'
					)
				)
			) ;
			$field = "
	        		ps.id, ps.winUserId,ps.title, ps.goodsId, ps.winningCode,if(ps.discloseDate - $time > 0, 0, winUserId) as winuserid,
              ps.discloseDate,ps.creatMicrotime, ps.canyushu, ps.qishu, ps.status,
	        		od.image,sum(od.nums) nums" ;
			$list = $list = M( 'periods' ) -> alias( 'ps' ) 
				-> join( 'JOIN __ORDER_DETAIL__ od ON od.pid=ps.id' ) 
				-> join( 'JOIN __ORDER__ odr ON odr.order_code = od.order_code and odr.status = od.status and odr.user_id = od.user_id' ) 
				-> field( $field ) 
				-> where( $map ) 
				-> group( 'ps.id' )
				-> order( "ps.discloseDate desc, ps.id desc" ) 
				-> limit( 30 ) 
				-> select( ) ;
			foreach( $list as &$v ){
				$v['seconds'] = $v['disclosedate'] - $time ;
				$v['disclosedate'] = date( 'Y-m-d H:i' , $v['disclosedate'] ) ;
				if( $v['status'] == 1){
					$v['nickname'] = D( 'user' ) -> where( 'member_id=' . $v['winuserid'] ) -> getField( 'nickName' ) ;
					$v['url'] = U( 'Goods/product_detail3' , array(
						'id' => $v['id']
					) ) ;
				}else{
					// $v['url'] = U( 'Goods/product_detail1' , array(
					// 	'id' => $v['id']
					// ) ) ;
					$v['url'] = 'javascript:void(0)';
				}
			}
			
			$json = array(
				'list' => $list
			) ;
			
			json( $json ) ;
		}
		
		$this -> assign( "periodsArr" , $list ) ;
		$this -> assign( "title" , "最新揭晓" . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		
		$this -> assign( 'cur' , "cur" ) ;
		$this -> display( ) ;
	
	}
	
	// 商品详情（即将揭晓倒计时状态接收periods表id）
	function product_detail1( ) {
		// 获取当前时间
		

		// 获取开奖时间
		$id = I( 'get.id' , 0 , 'int' ) ; // periods表id
		$periods = M( 'periods' ) ;
		$temp = $periods -> field( "goodsId,qishu" ) -> find( $id ) ;
		if( ! $temp ){
			$this -> error( '无数据，正在为您跳转' ) ;
		}
		$date = time( ) ;
		$this -> assign( 'date' , $date ) ;
		$this -> assign( 'currqishu' , $temp["qishu"] ) ;
		$cloudlist = $periods -> field( 'id,goodsId,qishu' ) 
			-> where( 'goodsId=' . $temp["goodsid"] . " and qishu<=" . ( $temp["qishu"] + 1 ) ) 
			-> order( 'qishu desc' ) 
			-> limit( 0 , 3 ) 
			-> select( ) ;
		
		$this -> assign( 'cloudlist' , $cloudlist ) ;
		$this -> assign( "width" , 100 / ( count( $cloudlist ) + 1 ) ) ;
		$where = array(
			'ps.id' => $id,'ps.status' => 2
		) ;
		$periodsArr = $periods -> alias( 'ps' ) 
			-> join( 'JOIN __GOODS__ gs ON gs.id=ps.goodsid' ) 
			-> field( "gs.title,ps.total,ps.fenshu,ps.discloseDate,ps.goodsId,ps.id,ps.qishu,gs.description" ) 
			-> where( $where ) 
			-> find( ) ;
        //dump($periodsArr);die;
		if( ! $periodsArr ){
			$this -> error( '无数据，正在为您跳转' ) ;
		}
		$this -> assign( 'periodsArr' , $periodsArr ) ;
		$carryqishu = $periodsArr['qishu'] + 1 ;
		$this -> assign( 'carryqishu' , $carryqishu ) ;
		$goodsdetails = M( 'goods_detail' ) -> where( "goodsId=" . $temp["goodsid"] ) -> select( ) ;
		
		$this -> assign( 'goodsdetails' , $goodsdetails ) ;
		$this -> assign( "title" , "即将揭晓" . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		$this -> assign( 'id' , $periodsArr['goodsid'] ) ;
		
		layout( 'Layout/noFoot' ) ;
		$this -> display( ) ;
	
	}
	
	// 商品详情（在售状态接收goods商品表id）
	function product_detail2( ) {

		$id = I( 'get.id' , 0 , 'int' ) ; // 商品id
		$qishu = I( 'get.qishu' ) ;
		$periods = M( 'periods' ) ;
		if( ! $qishu ){
			$temp = M( 'goods' ) -> field( 'id,qishu' ) 
				-> where( 'status=1' ) 
				-> find( $id ) ;
			if( ! $temp ){
				$this -> error( '不存在该商品，正在为您跳转' ) ;
			}
			$res = $periods -> field( "goodsid,qishu" ) 
				-> where( "goodsid=" . $temp["id"] . " and qishu<=" . ( $temp["qishu"] + 1 ) ) 
				-> order( 'qishu desc' ) 
				-> limit( 0 , 3 ) 
				-> select( ) ;
			// echo M()->getLastSql();return;
			$resonce = $periods -> field( "id,status" ) 
				-> where( "goodsid=" . $id . " and qishu=" . $temp["qishu"] ) 
				-> find( ) ;
			$this -> assign( 'currqishu' , $temp["qishu"] ) ;
		}else{
			$res = $periods -> field( "goodsid,qishu" ) 
				-> where( "goodsid=" . $id . " and qishu<=" . ( $qishu + 1 ) ) 
				-> order( 'qishu desc' ) 
				-> limit( 0 , 3 ) 
				-> select( ) ;
			$resonce = $periods -> field( "id,status" ) 
				-> where( "goodsid=" . $id . " and qishu=" . $qishu ) 
				-> find( ) ;
			$this -> assign( 'currqishu' , $qishu ) ;
		}
		if( ! $res && ! $resonce ){
			$this -> error( '不存在该抢购，正在为您跳转' ) ;
		}
		$this -> assign( 'cloudlist' , $res ) ;
		$this -> assign( "width" , 100 / ( count( $res ) + 1 ) ) ;
		
		if( $resonce['status'] == 1 ){
			redirect( U( 'product_detail3' , array(
				'id' => $resonce['id']
			) ) ) ;
		}
		if( $resonce['status'] == 2 ){
			redirect( U( 'product_detail1' , array(
				'id' => $resonce['id']
			) ) ) ;
		}
		// 获取当前时间
		$date = time( ) ;
		// 获取显示图片
		$goodsdetail = M( 'goods_detail' ) ;
		$goodsdetails = $goodsdetail -> where( "goodsId=" . $id ) -> select( ) ;
		$this -> assign( 'goodsdetails' , $goodsdetails ) ;
		
		// 获取商品列表
		$goods = M( 'goods' ) ;
		$goodsarr = $goods -> where( 'id=' . $id ) 
			//-> order( 'goods_popular desc' ) 
			-> find( ) ;
		
		$where = array(
			'goodsId' => $id,'qishu' => $goodsarr['qishu']
		) ;
		$periods_detail = M( 'periods_detail' ) ;
		$periods_details = $periods_detail -> where( $where ) -> select( ) ;
		$this -> assign( 'periods_details' , $periods_details ) ;
		
		$this -> assign( 'vo1' , $goodsarr ) ;
		$this -> assign( 'date' , $date ) ;
		$this -> assign( 'id' , $id ) ;
		$this -> assign( "title" , "在售商品" . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		
		if( $id ){
			M( 'goods' ) -> where( "id = $id" ) -> setInc( 'goods_popular' ) ;
		}
		
		layout( 'Layout/noFoot' ) ;
		$this -> display( ) ;
	
	}
	
	// 开奖页面，获奖者和获奖奖品详情（即获奖公共页面periods表id）
	function product_detail3( ) {

		$id = I( 'get.id' , 0 , 'int' ) ; // periods表id
		$periods = M( 'periods' ) ;
		$temp = $periods -> field( "goodsId,qishu" ) -> find( $id ) ;
		$this -> assign( 'currqishu' , $temp["qishu"] ) ;
		$cloudlist = $periods -> field( 'id,goodsId,qishu' ) 
			-> where( 'goodsId=' . $temp["goodsid"] . " and qishu<=" . ( $temp["qishu"] + 1 ) ) 
			-> order( 'qishu desc' ) 
			-> limit( 0 , 3 ) 
			-> select( ) ;
		$goodsdetail = M( 'goods_detail' ) ;
		$goodsdetails = $goodsdetail -> where( "goodsId=" . $temp["goodsid"] ) -> select( ) ;
		$this -> assign( 'goodsdetails' , $goodsdetails ) ;
		$this -> assign( 'cloudlist' , $cloudlist ) ;
		$this -> assign( "width" , 100 / ( count( $cloudlist ) + 1 ) ) ;
		// $periods = M('periods');
		$periodsArr = $periods -> where( "status=1 and id=" . $id ) -> find( ) ; // 开奖状态
		if( ! $periodsArr ){
			
			$this -> error( '无数据，正在为您跳转' ) ;
		}
		$periodsArr['disclosedate'] = date( 'Y-m-d H:i:s' , $periodsArr['disclosedate'] ) ;
		
		$this -> assign( 'periodsArr' , $periodsArr ) ;
		$userid = $periodsArr['winuserid'] ;
		$goodsid = $periodsArr['goodsid'] ;
		$qishu = $periodsArr['qishu'] ;
		
		$user = D( 'user' ) ;
		$users = $user -> where( 'member_id=' . $userid ) -> find( ) ;
        //dump($users);die;
		$this -> assign( 'users' , $users ) ;
		
		$field = "sum(nums) as nums" ;
		$order_detail = M( 'order_detail' ) ;
		$order_details = $order_detail -> where( array(
			'user_id' => $userid,'goods_id' => $goodsid,'qishu' => $qishu
		) ) 
			-> field( $field ) 
			-> group( 'qishu' ) 
			-> find( ) ;
		$this -> assign( 'order_details' , $order_details ) ;
		
		//获取正在进行期数
		$periods = M( 'periods' ) ;
		$filedss = "max(qishu) as qishu,goodsId" ;
		$periodsArr3 = $periods -> where( 'goodsId=' . $goodsid ) 
			-> field( $filedss ) 
			-> find( ) ;
		$this -> assign( 'periodsArr3' , $periodsArr3 ) ;
		$this -> assign( "title" , "获奖信息" . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		
		layout( 'Layout/noFoot' ) ;
		$this -> display( ) ;
	
	}
	
	// 获得者本云云购码详情
	function buy_detail( ) {

		$uid = I( 'get.uid' , 0 , 'int' ) ; // 用户id
		$qishu = I( 'get.qishu' ) ;
		$goodId = I( 'get.goodsid' ) ;
		$where = array(
			'user_id' => $uid,'qishu' => $qishu,'goods_id' => $goodId
		) ;
		
		// 获取中奖人次
		$field = "sum(nums) as nums" ;
		$order_detail = M( 'order_detail' ) ;
		$order_details = $order_detail -> where( $where ) 
			-> field( $field ) 
			-> group( 'qishu' ) 
			-> find( ) ;
		
		$this -> assign( 'order_details' , $order_details ) ;
		
		// 获取中奖码的时间
		$periods_detail = M( 'periods_detail' ) ;
		$periods_details = $periods_detail -> field( 'id,userId,goodsId,qishu,microtime' ) 
			-> where( array(
			'userId' => $uid,'qishu' => $qishu,'goodsId' => $goodId
		) ) 
			-> find( ) ;
		
		// 获取购买的云购码
		$pcodes = $periods_detail -> field( 'pcode' ) 
			-> where( array(
			'userId' => $uid,'qishu' => $qishu,'goodsId' => $goodId
		) ) 
			-> order( 'rand()' ) 
			-> select( ) ;
		
		$this -> assign( 'periods_details' , $periods_details ) ;
		$this -> assign( 'pcodes' , $pcodes ) ;
		$this -> display( ) ;
	
	}
	
	// 个人晒单详情
	function share_detail( ) {

		$id = I( 'get.id' , 0 , 'int' ) ; // periods表id
		$where = array(
			'id' => $id,'status' => '1'
		) ;
		$periods = M( 'periods' ) ;
		$periodsArr = $periods -> where( $where ) -> find( ) ;
		if( ! $periodsArr ){
			$this -> error( '暂无数据,正在为您跳转' ) ;
		}
		$this -> assign( 'periodsArr' , $periodsArr ) ;
		$userid = $periodsArr['winuserid'] ;
		$goodsid = $periodsArr['goodsid'] ;
		$qishu = $periodsArr['qishu'] ;
		
		// 获取用户信息
		$user = D( 'user' ) ;
		$users = $user -> where( 'member_id=' . $userid ) -> find( ) ;
		$this -> assign( 'users' , $users ) ;
		// 获取本云用户信息
		

		$order_detail = M( 'order_detail' ) ;
		$order_details = $order_detail -> where( array(
			'user_id' => $userid,'goods_id' => $goodsid,'qishu' => $qishu
		) ) -> find( ) ;
		$this -> assign( 'order_details' , $order_details ) ;
		
		$periods_show = M( 'periods_show' ) ;
		$shows = $periods_show -> where( array(
			'pid' => $id
		) ) -> find( ) ;
		$this -> assign( 'shows' , $shows ) ;
		$psid = $shows['id'] ;
		$show_detail = M( 'periods_show_detail' ) ;
		$details = $show_detail -> where( 'psid=' . $psid ) -> select( ) ;
		$this -> assign( 'details' , $details ) ;
		$this -> assign( "title" , "晒单详情" . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		$this -> display( ) ;
	
	}
	
	// 所有商品晒单列表
	function share_list( ) {

		$order1 = array() ;
		if( isset( $_GET["ord"] ) ){
			
			switch( $_GET["ord"] ){
				
				case 1 : // 最新
					$order1["creatDate"] = "desc" ;
					break ;
				case 2 : // 精华
					$order1["essence"] = "desc" ;
					break ;
				case 3 : // 推荐
					$order1["recommend"] = "desc" ;
					break ;
				case 4 : // 人气
					$order1["popular"] = "desc" ;
					break ;
				
				default:
					$order1["id"] = "desc" ;
					break ;
			}
		}else{
			$order1["creatDate"] = "desc" ;
		}
		
		$field = "a.id, a.goodsId,a.pid, a.userId, a.content,a.creatDate,a.pic1,a.pic2,a.pic3,a.essence,a.recommend,a.popular, b.nickname,b.pic,c.qishu,c.title" ;
		
		$page = I( 'param.page' , 1 , 'int' ) ;
		$show_index = 5 ;
		
		if( IS_AJAX ){
			$periods_show = M( 'periods_show' ) ;
			$shows1 = $periods_show -> field( $field ) 
				-> alias( 'a' ) 
				-> join( 'LEFT JOIN member b ON a.userId = b.member_id' )
				-> join( 'LEFT JOIN __PERIODS__ c ON a.pid = c.id' ) 
				-> order( $order1 ) 
				-> page( $page , $show_index ) 
				-> select( ) ;
			
			foreach( $shows1 as &$v ){
				$v['url'] = U( 'Weixin/Goods/user_page' , 'userid=' . $v['userid'] ) ;
				$v['url1'] = U( 'Weixin/Goods/share_detail' , 'id=' . $v['pid'] ) ;
				$v['content1'] = mb_substr( $v['content'] , 0 , 25 , 'utf-8' ) ;
				$v['title1'] = mb_substr( $v['title'] , 0 , 15 , 'utf-8' ) ;
			}
			
			$max_page = ceil( $periods_show -> count( ) / $show_index ) ;
			json( array(
				'page' => $page,'max_page' => $max_page,'list' => $shows1
			) ) ;
		}
		//dump($shows1);die;
		$this -> assign( 'max_page' , $max_page ) ;
		
		$this -> assign( "orders" , $_GET["ord"] ) ;
		$this -> assign( "title" , "晒单列表" . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		$this -> display( ) ;
	
	}
	
	// 单个商品晒单列表
	function share_product( ) {

		$order = array() ;
		$id = I( 'get.id' , 0 , 'int' ) ; // 接收的是goodsid
		

		$order["creatDate"] = "desc" ;
		$field = "a.id, a.goodsId,a.pid, a.userId, a.content,a.creatDate,a.pic1,a.pic2,a.pic3,a.essence,a.recommend,a.popular, b.nickname" ;
		
		$page = I( 'param.page' , 1 , 'int' ) ;
		$show_index = 10 ;
		
		if( IS_AJAX ){
			$periods_show = M( 'periods_show' ) ;
			$shows = $periods_show -> field( $field ) 
				-> alias( 'a' ) 
				-> join( 'LEFT JOIN member b ON a.userId = b.member_id' )
				-> where( 'a.goodsId=' . $id ) 
				-> order( $order1 ) 
				-> page( $pages , $show_index ) 
				-> select( ) ;
			foreach( $shows as &$v ){
				$v['url'] = U( 'Weixin/Goods/user_page' , 'userid=' . $v['userid'] ) ;
				$v['url1'] = U( 'Weixin/Goods/share_detail' , 'id=' . $v['pid'] ) ;
				$v['content1'] = mb_substr( $v['content'] , 0 , 15 , 'utf-8' ) ;
			}
			
			$max_page = ceil( $periods_show -> count( ) / $show_index ) ;
			json( array(
				'page' => $page,'max_page' => $max_page,'list' => $shows
			) ) ;
		}
		
		$this -> assign( 'max_page' , $max_page ) ;
		
		// 计算晒单数量
		$count = M( 'periods_show' ) -> where( 'goodsId=' . $id ) -> count( ) ;
		$this -> assign( 'count' , $count ) ;
		
		$this -> assign( "shows" , $shows ) ;
		$this -> assign( "title" , "晒单列表" . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		$this -> display( ) ;
	
	}
	
	// 参与记录
	function player_record( ) {

		$goodid = I( 'get.goodsId' , 0 , 'int' ) ;
		$qishu = I( 'get.qishu' , 0 , 'int' ) ;

		$where = array(
			'goods_id' => $goodid,'qishu' => $qishu
		) ;
		
		$field = "c.member_id, c.nickname,c.pic, c.addr,b.user_id,b.order_code,b.creat_date,b.area,b.ip,
        		a.order_code,a.pid,a.goods_id,sum(a.nums) as nums,a.qishu" ;
		$order_detail = M( 'order_detail' ) ;
		$users = $order_detail -> field( $field ) 
			-> alias( 'a' )
			-> join( 'LEFT JOIN __ORDER__ b ON a.order_code = b.order_code' ) 
			-> join( 'LEFT JOIN member c ON b.user_id = c.member_id' )
			-> where( $where ) 
			-> group( 'b.user_id' ) 
			-> order( 'creat_date desc' ) 
			-> select( ) ;
		//echo M()->getLastsql();exit;
		//dump($users);die;

		/*获取豪秒数开始*/
		foreach( $users as $key => $v ){
			$microtime = M( 'periods_detail' ) -> field( 'microtime' ) 
				-> where( array(
				'userId' => $v['user_id'],'goodsId' => $v['goods_id'],'qishu' => $v['qishu']
			) ) 
				-> limit( '1' ) 
				-> select( ) ;
			$microtime = reset( $microtime ) ;
			$users[$key]['microtime'] = $microtime['microtime'] ;
		}
		//var_dump($users);exit;
		/*获取豪秒数结束*/
		
		if( ! $users ){
			$this -> error( '本期还没有人参与' ) ;
		}
		$this -> assign( "title" , "参与记录" . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		$this -> assign( 'users' , $users ) ;
		$this -> display( ) ;
	
	}


	// BB参与记录
	function bbplayer_record( ) {

		$goodid = I( 'get.goodsId' , 0 , 'int' ) ;
		$bbid = I( 'get.bbid' , 0 , 'int' ) ;

		$where = array(
			'goods_id' => $goodid,'bbid' => $bbid,'order_status' => '1'
		) ;
		
		$field = "c.member_id, c.nickname,c.pic, c.addr,b.user_id,b.order_code,b.creat_date,b.area,b.ip,
        		a.order_code,a.pid,a.goods_id,sum(a.nums) as nums,a.qishu,a.bbid" ;
		$order_detail = M( 'order_detail' ) ;
		$users = $order_detail -> field( $field ) 
			-> alias( 'a' ) 
			-> join( 'LEFT JOIN __ORDER__ b ON a.order_code = b.order_code' ) 
			-> join( 'LEFT JOIN member c ON b.user_id = c.member_id' )
			-> where( $where ) 
			-> group( 'b.user_id' ) 
			-> order( 'creat_date desc' ) 
			-> select( ) ;
		//echo M()->getLastsql();exit;
		//dump($users);die;

		/*获取豪秒数开始*/
		foreach( $users as $key => $v ){
			$microtime = M( 'bbooking_detail' ) -> field( 'create_time' ) 
				-> where( array(
				'participator' => $v['user_id'],'goodsId' => $v['goods_id'],'bbid' =>$v['bbid']
			) ) 
				-> limit( '1' ) 
				-> order( 'create_time desc' ) 
				-> select( ) ;
			$microtime = reset( $microtime ) ;
			$users[$key]['create_time'] = $microtime['create_time'] ;
		}
		//var_dump($users);exit;
		/*获取豪秒数结束*/
		
		if( ! $users ){
			$this -> error( '本期还没有人参与' ) ;
		}
		$this -> assign( "title" , "参与记录" . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		$this -> assign( 'users' , $users ) ;
		$this -> display( ) ;
	
	}
	
	// 图文详情
	function photo_detail( ) {

		$id = I( 'get.id' , 0 , 'int' ) ; // goodsid
		$goods = M( 'goods' ) ;
		$goodsArr = $goods -> field( 'id,title,content' ) 
			-> where( 'id=' . $id ) 
			-> find( ) ;
		$this -> assign( 'goodsArr' , $goodsArr ) ;
		
		$this -> assign( "title" , $goodsArr['title'] . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		$this -> display( ) ;
	
	}
	
	// 计算详情
	function jisuan( ) {

		$id = I( 'get.id' , 0 , 'int' ) ; // 获取periods的id
		

		$where = array(
			'id' => $id
		) ;
		$periods = M( 'periods' ) ;
		$periodsArr = $periods -> where( $where ) -> find( ) ;

		$field = "a.id,a.userId,a.pcode,a.microtime,b.nickname" ;
		$periods_detail = M( 'periods_detail' ) -> field( $field ) 
			-> alias( 'a' ) 
			-> join( 'LEFT JOIN member b ON a.userId = b.member_id' )
			-> where( 'a.microtime <= ' . $periodsArr['creatmicrotime'] )
			-> group( 'a.userId,a.goodsId,a.qishu,a.microtime' )
			-> limit( 100 ) 
			-> order( "a.microtime desc" ) 
			-> select( ) ;

		// 时间总和计算
		$tt = 0 ;
		foreach( $periods_detail as &$v ){
			$v['a'] = $v['microtime'] ;
			$v['b'] = _date( 'Hisx' , $v['microtime'] ) ;
			$v['c'] = _date( 'Y/m/d H:i:s.x' , $v['microtime'] ) ;
			$tt += $v['b'] ;
		}
//		M( 'periods' ) -> where( array('id'=>$periodsArr['id']) ) -> save( array('sendId'=>$tt) ) ;
		$this -> assign( 'periodsArr' , $periodsArr ) ;
		$this -> assign( 'periods_detail' , $periods_detail ) ;
		$this -> assign( 'tt' , $tt ) ;
		$this -> assign( "title" , '计算结果' . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		$this -> display( ) ;
	
	}


	// 包场计算详情
	function bbjisuan( ) {

		$id = I( 'get.id' , 0 , 'int' ) ; // 获取bbooking的id
		
		$where = array(
			'id' => $id
		) ;
		$Bbooking = M( 'bbooking' ) -> where( $where ) -> find( ) ;
        $field = "a.id,a.participator,a.bbcode,a.create_time,b.nickname" ;
		$list = M( "bbooking_detail" ) -> alias( 'a' )
		    -> field( $field ) 
			-> join( 'LEFT JOIN member b ON a.participator = b.member_id' )
			-> where( 'a.create_time <= ' . $Bbooking['sellout_time'] )
			-> group( 'a.participator,a.bbid,a.create_time' )
			-> order( 'a.create_time desc' )
			-> limit( 100 )
			-> select( ) ;
        // 时间总和计算
		$tt = 0 ;
		foreach( $list as &$v ){
			$_time = $v['microtime'] == 0 ? $v['creatDate'] : $v['microtime'] ;
			$v['time1'] = _date( 'Hisx' , $v['microtime'] ) ;
			$v['time2'] = _date( 'Y-m-d H:i:s.x' , $v['microtime'] ) ;

			$tt += $v['time1'] ;
		}

		$this -> assign( 'Bbooking' , $Bbooking ) ;
		$this -> assign( 'list' , $list ) ;
		$this -> assign( 'tt' , $tt ) ;
		$this -> assign( "title" , '计算结果' . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		$this -> display( ) ;
	
	}
	
	// 往期揭晓
	function come_out( ) {

		$id = I( 'get.id' , 0 , 'int' ) ; // goods表id
		

		if( ! IS_AJAX ){
			$this -> assign( "title" , '往期揭晓' . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
			$this -> display( ) ;
		}else{
			$map = array(
				'a.status' => 1,'a.goodsId' => $id
			) ;
			
			$field = "a.id,a.status,a.goodsId,a.qishu,FROM_UNIXTIME(a.discloseDate) as discloseDate,a.winningCode,a.winUserId,b.nickname,b.pic,c.user_id,sum(c.nums) as nums,c.goods_id,c.qishu as qishus,
	        		a.area, a.ip" ;
			$periods = M( 'periods' ) -> field( $field ) 
				-> alias( 'a' ) 
				-> join( 'LEFT JOIN member b ON a.winUserId = b.member_id' )
				-> join( 'LEFT JOIN __ORDER_DETAIL__ c ON a.winUserId = c.user_id AND a.goodsId = c.goods_id AND a.qishu = c.qishu' ) 
				-> group( 'c.qishu' ) 
				-> where( $map ) 
				-> page( I( 'page' , '1' ) , 30 ) 
				-> order( "a.discloseDate desc" ) 
				-> select( ) ;
			
			$count = M( 'periods' ) -> alias( 'a' ) 
				-> join( 'LEFT JOIN member b ON a.winUserId = b.member_id' )
				-> join( 'LEFT JOIN __ORDER_DETAIL__ c ON a.winUserId = c.user_id AND a.goodsId = c.goods_id AND a.qishu = c.qishu' ) 
				-> group( 'c.qishu' ) 
				-> where( $map ) 
				-> count( ) ;
			
			json( array(
				'page' => I( 'page' , '1' ),'max_page' => ceil( $count / 30 ),'count' => $count,'list' => $periods
			) ) ;
		}
	
	}
	
	// 个人主页页面
	function user_page( ) {
		
		// 获取user信息
		$uid = I( 'get.userid' , 0 , 'int' ) ;
        //dump($uid);
		$user = D( 'user' ) ;
		$users = $user -> field( "member_id,nickname,pic,addr,phone" )
			-> where( 'member_id=' . $uid )
			-> find( ) ;
        //dump($users);die;
		$tempph = $users['phone'] ;
		unset( $users['phone'] ) ;
		if( $tempph ){
			$users['phone'] = substr( $tempph , 0 , 3 ) . '****' . substr( $tempph , 7 , 4 ) ;
		}else{
			$users['phone'] = '' ;
		}
		
		$this -> assign( 'users' , $users ) ;
		
		$page = I( 'get.page' , 1 , 'int' ) ;
		$type = I( 'get.type' , 1 , 'int' ) ;
		$show_index = 10 ;
		
		// 云购记录
		$field = "od.pid,od.user_id,goodsId,od.qishu,ps.title,od.image,ps.status,
					ps.fenshu,ps.total,
					order_status,
					if(ps.status = 1, ps.winningCode, '') as winningCode,
					sum(od.nums) nums,
					FROM_UNIXTIME(ps.discloseDate) as discloseDate,winUserId" ;
		
		if( IS_AJAX && $type == 1 ){
			$skip = $page * $show_index ;
			$map['odr.user_id'] = $uid ;
			$map['odr.status'] = 1 ; //状态可用
			$map['odr.order_type'] = 0 ; //云购
			$map['odr.order_status'] = 1 ; //已付款,0为未付款
			

			$list = M( 'order_detail' ) -> alias( 'od' ) 
				-> join( 'right JOIN __PERIODS__ ps ON od.pid=ps.id' ) 
				-> join( 'left JOIN __ORDER__ odr ON odr.order_code=od.order_code ' ) 
				-> field( $field ) 
				-> where( $map ) 
				-> group( 'ps.id' ) 
				-> order( 'odr.creat_date desc' ) 
				-> 
			//	-> limit( $skip , $show_index ) 
			select( ) ;
			// 			echo M( ) -> getLastSql( ) ;
			// 			dump( $list ) ;
			// 			exit( ) ;
			
        //dump( $list ) ;die;
			for( $i = 0 ; $i < count( $list ) ; $i ++ ){
				// if( $list[$i]['winuserid'] != 0 ){
				if( $list[$i]['status'] == 1 ){
					$temp = D( 'user' ) -> find( $list[$i]['winuserid'] ) ;
					$list[$i]['nickname'] = $temp['nickname'] ;
				}else{
					$list[$i]['nickname'] = '暂无' ;
					$list[$i]['disclosedate'] = '暂未揭晓' ;
					
					if( $list[$i]['status'] == 3 ){
						//$list[$i]['canyushu'] = M('goods')->where('canyushu')->getField('canyushu');
						$list[$i]['canyushu'] = M( 'goods' ) -> where( 'id=' . $list[$i]['goodsid'] ) -> getField( 'canyushu' ) ;
					}
				}
				
				$list[$i]['url'] = U( "goods/product_detail2" , array(
					'id' => $list[$i]['goodsid']
				) ) ;
			}
			//            var_dump($list);exit;
			// 获取总数量
			$count = M( 'periods' ) -> alias( 'ps' ) 
				-> join( 'JOIN __ORDER_DETAIL__ od ON od.pid = ps.id' ) 
				-> join( 'JOIN __ORDER__ odr ON odr.order_code = od.order_code and odr.status = od.status and odr.user_id = od.user_id' ) 
				-> field( $field ) 
				-> where( $map ) 
				-> count( 'DISTINCT ps.id' ) ;
			
			json( array(
				'page' => $page,'count' => $count,'max_page' => ceil( $count / $show_index ),'list' => $list,'type' => $type
			) ) ;
		}
		
		//获取奖品
		if( IS_AJAX && $type == 2 ){
			$skip = $page * $show_index ;
			$map['odr.user_id'] = $uid ;
			$map['ps.winUserId'] = $uid ;
			$map['odr.status'] = 1 ; //状态可用
			$map['order_type'] = 0 ; //云购
			$map['order_status'] = 1 ; //已付款,0为未付款
			$map['ps.status'] = 1 ;
			
			$list = M( 'periods' ) -> alias( 'ps' ) 
				-> join( 'JOIN __ORDER_DETAIL__ od ON od.pid=ps.id' ) 
				-> join( 'JOIN __ORDER__ odr ON odr.order_code = od.order_code and odr.status = od.status and odr.user_id = od.user_id' ) 
				-> join( 'join member ur on ur.member_id=ps.winUserId' )
				-> field( $field ) 
				-> where( $map ) 
				-> group( 'ps.id' ) 
				-> order( 'odr.creat_date desc' ) 
				-> 
			// 				-> limit( $skip , $show_index ) 
			select( ) ;
			// 获取总数量
			//echo M()->getLastSql();return ;
			$count = M( 'periods' ) -> alias( 'ps' ) 
				-> join( 'JOIN __ORDER_DETAIL__ od ON od.pid=ps.id' ) 
				-> join( 'JOIN __ORDER__ odr ON odr.order_code=od.order_code and odr.status=od.status' ) 
				-> join( 'join member ur on ur.member_id=ps.winUserId' )
				-> field( $field ) 
				-> where( $map ) 
				-> count( 'DISTINCT ps.id' ) ;
			
			json( array(
				'page' => $page,'count' => $count,'max_page' => ceil( $count / $show_index ),'list' => $list,'type' => $type
			) ) ;
		}
		
		// 晒单
		$field = "id,goodsId,pid,userId,content,creatDate,pic1,pic2,pic3" ;
		$periods_show = M( 'periods_show' ) ;
		$max_page = ceil( $periods_show -> where( 'userId=' . $uid ) -> count( ) / $show_index ) ;
		
		if( IS_AJAX && $type == 3 ){
			$shows = $periods_show -> field( $field ) 
				-> order( "creatDate desc" ) 
				-> page( $page , $show_index ) 
				-> where( 'userId=' . $uid ) 
				-> select( ) ;
			
			foreach( $shows as &$v ){
				$v['url'] = U( 'Weixin/Goods/share_detail' , 'id=' . $v['pid'] ) ;
				$v['content1'] = mb_substr( $v['content'] , 0 , 15 , 'utf-8' ) ;
			}
			
			json( array(
				'page' => $page,'max_page' => $max_page,'list' => $shows,'type' => $type
			) ) ;
		}
		
		$this -> assign( 'type' , $type ) ;
		$this -> assign( "title" , '个人主页' . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		$this -> display( ) ;
	
	}

	function product_status( ) {

		$pid = I( 'get.id' ) ;
		if( ! $pid ){
			$this -> error( "无数据，正在为您跳转..." ) ;
		}
		M( 'periods' ) -> where( 'id=' . $pid ) -> setField( 'status' , '1' ) ;
		
		$res = M( 'Periods' ) -> find( $pid ) ;
		/*发送验证码开始*/
		$phone = D( 'user' ) -> where( 'id=' . $res['winuserid'] ) -> getField( 'phone' ) ;
		
		if( ! empty( $phone ) ){
			
			// 发送验证码短信
			$content = str_replace( '{code}' , $res['winningcode'] , C( 'sms_tpl_lottery_code' ) ) ;
			$url = str_replace( array(
				'{phone}','{content}'
			) , array(
				$phone,$content
			) , C( 'sms_api_url' ) ) ;
			
			file_get_contents( $url ) ;
		}
		/*发送验证码结束*/
		//开奖信息推送
		$appid = C( "wei_xin_appid" ) ;
		$secret = C( "wei_xin_appsecret" ) ;
		vendor( 'WeChat.OrderPush' ) ;
		$OrderPush = new \OrderPush( $appid , $secret ) ;
		$template_id = "QvgfxypBx1tUHehIb5ClQMAZkSaIJHMXgSidnshEGlM" ;
		$touser = M( "UserFastLogin" ) -> where( array(
			"user_id" => $res['winuserid']
		) ) -> getField( "fast_login_id" , false ) ;
		$OrderPush -> TheLottery( $touser , $template_id , array(
			'感谢您选择一元开抢,您的中奖信息如下:','一元开抢',$res['title']
		) , C( 'site_url' ) . '/index.php?m=Weixin&c=User&a=myaward' ) ;
		
		redirect( U( 'product_detail3' , array(
			'id' => $pid
		) ) ) ;
	
	}
    

    public function getCode( ) {

		$id = I( 'post.id' , 0 , 'int' ) ;
		if( ! $id ) appJson( array(
			'code' => 230,'msg' => '参数错误'
		) ) ;
		   //修改状态为以开奖状态
 		$date = array('id' => $id,'status' => 1);
 		M( 'periods' )->save($date);
		
		$where = array(
			'ps.id' => $id,'ps.status' => 1
		) ;
		$field = "ps.id,ur.nickname,sum(od.nums) nums,ps.winningCode,DATE_FORMAT(FROM_UNIXTIME(ps.discloseDate),'%Y-%m-%d %H:%i') discloseDate" ;
			$info = M( 'periods' ) -> alias( 'ps' ) 
				-> join( 'JOIN __ORDER_DETAIL__ od ON od.pid=ps.id' ) 
				-> join( 'JOIN __ORDER__ odr ON odr.order_code = od.order_code and odr.status = od.status and odr.user_id = od.user_id' ) 
				-> join( 'JOIN member ur ON ur.member_id=ps.winUserId' )
				-> where( $where ) 
				-> field( $field ) 
				-> group( 'ps.id' ) 
				-> order( "ps.discloseDate desc, ps.id desc" ) 
				-> find( ) ;
		if( ! $info ){
			appJson( array(
				'code' => 500,'info' => false
			));
		}else{
			appJson( array(
				'code' => 1,'info' => $info
			)) ;
		}
	
	}
	// public function getCode( ) {

	// 	$id = I( 'post.id' , 0 , 'int' ) ;
	// 	if( ! $id ) appJson( array(
	// 		'code' => 230,'msg' => '参数错误'
	// 	) ) ;
	// 	//开奖
	    
	// 	$goodsId = M('periods')->find($id);
	
	// 	$goodsInfo = M('goods')->find($goodsId['goodsid']);
		
	// 	if (!$goodsInfo) {
	// 		 appJson( array(
	// 		'code' => 230,'msg' => '参数错误'
	// 	    ) ) ;
	// 	}
	// 	$islottery = D('pay')->lottery($goodsInfo);
	// 	if ($islottery) {
	//         //修改状态为以开奖状态
	// 		$date = array('id' => $id,'status' => 1);
	// 		M( 'periods' )->save($date);

	// 		$where = array(
	// 			'ps.id' => $id,'ps.status' => 1
	// 		) ;
	// 		$field = "ps.id,ur.nickname,sum(od.nums) nums,ps.winningCode,DATE_FORMAT(FROM_UNIXTIME(ps.discloseDate),'%Y-%m-%d %H:%i') discloseDate" ;
	// 		$info = M( 'periods' ) -> alias( 'ps' ) 
	// 			-> join( 'JOIN __ORDER_DETAIL__ od ON od.pid=ps.id' ) 
	// 			-> join( 'JOIN __ORDER__ odr ON odr.order_code = od.order_code and odr.status = od.status and odr.user_id = od.user_id' ) 
	// 			-> join( 'JOIN member ur ON ur.member_id=ps.winUserId' )
	// 			-> where( $where ) 
	// 			-> field( $field ) 
	// 			-> group( 'ps.id' ) 
	// 			-> order( "ps.discloseDate desc, ps.id desc" ) 
	// 			-> find( ) ;
	// 	}
	// 	if( ! $info ){
	// 		$info = false ;
	// 		$code = 500 ;
	// 	}
	// 	appJson( array(
	// 		'code' => 1,'info' => $info
	// 	) ) ;
	
	// }


}

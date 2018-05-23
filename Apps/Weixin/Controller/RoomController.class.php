<?php

namespace Weixin\Controller ;

use Think\Controller ;

class RoomController extends IsLoginController {
	
	// 可以用作包场商品的列表
	function index( ) {

		$field = "a.image,a.originPrice,a.title,a.id,a.rstatus as rstatus,a.status as status,b.goods_id,b.create_time" ;
		$where['a.status'] = 1 ;
		$where['rstatus'] = 0 ;
		
		$page = I( 'get.page' , 1 , 'int' ) ;
		$show_index = 7 ;
		
		$goods = M( 'goods' ) ;
		$max_page = ceil( $goods -> alias( 'a' ) 
			-> where( $where ) 
			-> count( ) / $show_index ) ;
		
		if( IS_AJAX ){
			$goodsarr = $goods 
				-> alias( 'a' ) 
				//-> join( 'LEFT JOIN __BBOOKING__ b ON a.id = b.goods_id' ) 
				-> order( 'a.id desc' ) 
				-> where( $where )
                //-> group('b.goods_id')
				-> page( $page , $show_index ) 
				-> select( ) ;


			//echo M()->getLastSql();
			foreach( $goodsarr as &$v ){
				$v['url'] = U( 'Weixin/Room/goodsView' , 'id=' . $v['id'] ) ;
			}
			//var_dump($goodsarr);exit;
			json( array(
				'page' => $page,'max_page' => $max_page,'list' => $goodsarr
			) ) ;
		}
		
		$this -> assign( 'max_page' , $max_page ) ;
		$this -> assign( 'index' , "style=\"color:#de4849\"" ) ;
		$this -> assign( "title" , "包场专区" . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		$this -> display( ) ;
	
	}
	
	// 查看商品页
	function goodsView( ) {

		if( IS_POST ){
			$goodsID = I( "post.goodsID" ) ;
			if( ! $goodsID ){
				$this -> error( "未收到商品信息" ) ;
			}
			
			$bbData = array() ;
			$bbData["goodsID"] = $goodsID ;
			
			// 将商品ID存入session
			session( "bbData" , $bbData ) ;
			
			$this -> success( "商品信息保存成功" ) ;
		}
		
		$id = I( 'get.id' , 0 , 'int' ) ;
		$field = "id,originPrice,title" ;
		$where = array(
			'id' => $id,'rstatus' => 0,'status' => 1
		) ;
		$goods_detail = M( 'goods_detail' ) -> field( 'content' ) 
			-> where( 'goodsId=' . $id ) 
			-> select( ) ;
		$goods = M( 'goods' ) -> field( $field ) 
			-> where( $where ) 
			-> find( ) ;
		
		$this -> assign( 'goods' , $goods ) ;
		$this -> assign( 'goods_detail' , $goods_detail ) ;
		$this -> assign( "title" , "查看商品" . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		$this -> display( ) ;
	
	}
	
	// 商品详情页
	function goodsDetail( ) {

		$INVITENUM = I( 'get.invitenum' ) ;
		if( $INVITENUM ){
			cookie( 'invitenum' , null ) ;
			cookie( 'invitenum' , $INVITENUM , 43200 ) ;
		}
		// 包场邀请码
		$inviteDate["code"] = I( 'get.invite' ) ;
		$inviteDate["bbid"] = I( 'get.bbid' ) ;
		if( $inviteDate["code"] && $inviteDate["bbid"] ){
			cookie( 'invite' , null ) ;
			cookie( 'invite' , $inviteDate , 3600 * 24 ) ;
		}
		
		$id = I( 'get.id' , 0 , 'int' ) ; // goodsid
		$goods = M( 'goods' ) ;
		$goodsArr = $goods -> field( 'id,content' ) 
			-> where( 'id=' . $id ) 
			-> find( ) ;
		$this -> assign( 'id' , $id ) ;
		$this -> assign( 'goodsArr' , $goodsArr ) ;
		$this -> assign( "title" , "商品详情" . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		$this -> display( ) ;
	
	}

	/**
	 * 获取参与记录
	 */
	function playerRecord( ) {

		$bbid = I( 'get.id' , 0 , 'int' ) ; // bbid
		

		$field = "b.nickname,FROM_UNIXTIME(a.create_time) as create_time,a.participator,a.bbid,b.pic,b.addr" ;
		$bbooking_detail = M( 'bbooking_detail' ) -> field( $field ) 
			-> alias( 'a' ) 
			-> join( 'JOIN member b ON a.participator = b.member_id' )
			-> where( 'bbid=' . $bbid ) 
			-> group( 'a.participator' ) 
			-> select( ) ;
		
		for( $i = 0 ; $i < sizeof( $bbooking_detail ) ; $i ++ ){
			
			$bbooking_detail[$i]['num'] = M( 'bbooking_detail' ) -> where( 'participator=' . $bbooking_detail[$i]['participator'] . ' and bbid=' . $bbooking_detail[0]['bbid'] ) -> count( ) ;
		}
		// dump($bbooking_detail);
		$this -> assign( 'bb_details' , $bbooking_detail ) ;
		$this -> assign( "title" , "参与记录" . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		$this -> display( ) ;
	
	}
	
	// 显示好友
	function showFriends( ) {

		$this -> assign( "title" , "选择好友" . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		
		if( IS_POST ){
			;
		}
		
		// 取出商品id
		$bbData = session( "bbData" ) ;
		$goodsID = $bbData["goodsID"] ;
		$bbID = $bbData["bbID"] ;
		$tpin = $bbData["tpin"] ;

		// 如果已经选择好友,显示到页面
		if( $tpin ){
			
			// 取出用户昵称跟头像等其他信息
			$i = 0 ;
			foreach( $tpin as $k => $v ){
				$userInfo = D( "User" ) -> getUserInfo( $v ) ;
				$tpin1[$i]["member_id"] = $v ;
				$tpin1[$i]["nickname"] = $userInfo["nickname"] ;
				$tpin1[$i]["pic"] = $userInfo["pic"] ;
				$i ++ ;
               // var_dump($tpin);die;
			}
			
			$tpinCount = count( $tpin1 ) ;

			$this -> assign( "tpin" , $tpin1 ) ;
		}
		if( ! $tpinCount ){
			$tpinCount = 0 ;
		}
		$this -> assign( "tpinCount" , $tpinCount ) ;
		
		// 获取商品简介
		$goods = D( "Goods" ) -> getGoodsInfo( $goodsID ) ;
		$this -> assign( 'goods' , $goods ) ;
		
		$this -> display( ) ;
	
	}
	
	// 选择可参与的朋友
	function tpin( ) {

		if( IS_POST ){
			// 接收朋友列表
			$tpin = I( "post.friends" ) ;
			
			// 将朋友信息存入session
			$bbData = session( "bbData" ) ;
			$bbData["tpin"] = $tpin ;
			session( "bbData" , $bbData ) ;
			$this -> success( "好友选择成功" ) ;
		}
		
		$this -> assign( "title" , "选择朋友" . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		layout( "Layout/noFoot" ) ;
		
		$myFriends = D( "UserFriends" ) -> myFriends( UID ) ;
		$this -> assign( "myFriends" , $myFriends ) ;
		
		$this -> display( ) ;
	
	}

	/**
	 * 创建包场
	 * @date 2015年12月31日 上午10:21:21
	 *
	 * @author 王崇全
	 * @param string $goodsid 用作包场的商品编号
	 */
	function createRoom( ) {

		$bbData = session( "bbData" ) ;
		$goodsid = $bbData["goodsID"] ;
		$tpin = $bbData["tpin"] ;
		
		// 获取此商品的数据
		$goodsInfo = D( "Goods" ) -> getGoodsInfo( $goodsid ) ;
		if( ( ! $goodsInfo ) || ( $goodsInfo["rstatus"] != 0 ) ){
			$this -> error( "此商品不能创建包场" ) ;
		}
		
		// 判断用户是否已创建该商品的包场
		$bbooking = D( "Bbooking" ) -> existBBooking( UID , $goodsid ) ;
		
		if( $bbooking ){
			$this -> success( 'index.php?m=Weixin&c=Room&a=index_canyu',"已创建该商品的包场",2) ;
			exit;
        }
		
		// 包场数据
		$bbookingData["goods_id"] = $goodsInfo["id"] ;
		$bbookingData["goods_title"] = $goodsInfo["title"] ;
		$bbookingData["goods_type"] = $goodsInfo["typeid"] ;
		$bbookingData["goods_price"] = $goodsInfo["price"] ;
		$bbookingData["goods_img"] = $goodsInfo["image"] ;
		$bbookingData["goods_value"] = $goodsInfo["originprice"] ;
		
		$bbookingData["amount"] = $goodsInfo["fenshu"] ;
		$bbookingData["create_time"] = time( ) ;
		$bbookingData["creater"] = UID ;
		
		$bbid = D( "Bbooking" ) -> addBbooking( $bbookingData ) ;
		if( ! $bbid ){
			$this -> error( "包场创建失败" ) ;
		}
		// 创建包场云购码开始
		if( buildbbPcode( $bbid ) == false ){
			$this -> error( "包场失败" ) ;
		}
		// 创建包场云购码结束
		

		// 添加包场明细表数据
		D( "Bbooking" ) -> addTpin( $bbid , $tpin ) ;
		
		// 向消息队列中添加中奖通知
		$url = C( "site_url" ) . U( "Room/bookingDetail" ) . "&bbid=" . $bbid ;
		foreach( $tpin as $uid ){
			M( "msg_queue" ) -> add( array(
				"type" => 1,"go_url" => $url,"sended" => 0,"create_time" => time( ),"send_to" => $uid,"send_time" => time( ),"content" => C( "bb_create_msg" )
			) ) ;
		}
		
		//向这些好友发送通知
		if( $tpin ){
			$content = "您的好友创建了一个一元开抢包场,诚邀您的加入! (点击本消息即可快速进入)" ;
			$url = C( "site_url" ) . U( "Room/bookingDetail" ) . "&bbid=" . $bbid ;
			D( "Notice" ) -> weichatNotice( $tpin , $content , $url ) ;
		}
        $data1["bbid"] = $bbid ;
        $data1["tpin"] = UID ;
        $data1["status"] = 1 ;
        M( "bbooking_tpin" ) -> add( $data1 ) ;
		// 生成邀请链接
		$invite = md5( $bbid ) ;
		$flag1 = D( "Bbooking" ) -> setInfo( array(
			"id" => $bbid
		) , array(
			"invite_code" => $invite
		) ) ;
		if( ! $flag1 ){
			$this -> error( "包场邀请码设置失败" ) ;
		}
		$inviteURL = U( "Room/bookingDetail" ) . "&bbid=" . $bbid . "&invite=" . $invite ;
		
		$this -> success( $inviteURL ) ;
	
	}
	
	// 参与包场
	function index_canyu( ) {

		$this -> assign( "title" , "包场专区" . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		
		// 包场进行中列表
		$field2 = "a.id,a.goods_img,a.creater,a.lottery_time,a.goods_title,a.goods_value,a.goods_id,a.status,b.bbid,u.nickname" ;
		$where2 = array(
			'a.status' => array(
				'lt',3
			),'a.creater | b.tpin' => UID
		) ;
		$bbooking = M( 'bbooking' ) -> alias( 'a' ) 
			-> distinct( true ) 
			-> join( 'LEFT JOIN __BBOOKING_TPIN__ b ON a.id = b.bbid' )
			-> join( 'LEFT JOIN member u ON a.creater = u.member_id' )
			-> where( $where2 ) 
			-> field( $field2 ) 
			-> select( ) ;
		//dump($bbooking);die;
		foreach( $bbooking as $key => $value ){
			if( $value['lottery_time'] ){
				$bbooking[$key]['lottery_time'] = substr( $bbooking[$key]['lottery_time'] , 0 , 10 ) ;
			}
		}
		
		$this -> assign( 'index_canyu' , "style=\"color:#de4849\"" ) ;
		$this -> assign( 'bbooking' , $bbooking ) ;
		$this -> display( ) ;
	
	}
	
	// 我参与的包场记录
	function my_record( ) {

		$this -> assign( "title" , "我的包场记录" . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		
		$field2 = "
				b.id,b.goods_img,b.creater,b.goods_title,b.goods_value,b.goods_id,
				b.status,
				u.nickname" ;
		
		$where = array(
			'b.creater | bt.tpin' => UID
		) ;
		
		$userId = UID ;
		
		$bbooking = M( 'bbooking' ) -> field( 't.*, u.nickname' )
			-> alias( 't' ) 
			-> where( "(t.creater = $userId or t.id in  (select bbid from yytb_bbooking_tpin where tpin = $userId) )" ) 
			-> join( 'LEFT JOIN member as u ON u.member_id = t.creater' )
			-> order( "lottery_time desc" ) 
			-> select( ) ;
		
		foreach( $bbooking as $key => $v ){
			if( $v['creater'] == $userId )
				$bbooking[$key]['is_creater'] = 1 ;
			else
				$bbooking[$key]['is_creater'] = 0 ;
		}
		
		$this -> assign( 'bbooking' , $bbooking ) ;
		
		$this -> display( ) ;
		die( ) ;
	
	}
	
	// 忽略该邀请
	function del_bbook_detail( ) {

		$bbid = I( 'post.bbid' ) ;
		
		if( ! $bbid ){
			
			$this -> error( "没有包场编号" ) ;
			return ;
		}
		
		$map['bbid'] = $bbid ;
		$map['tpin'] = UID ;
		
		$al = M( 'bbooking_detail' ) -> where( array(
			'participator' => UID,'bbid' => $bbid
		) ) -> find( ) ;
		
		if( $al ){
			$this -> error( "您已参与本包场" ) ;
		}
		
		$res = M( 'bbooking_tpin' ) -> where( $map ) -> delete( ) ;

		if( $res ){
			$this -> success( "您已忽略该包场邀请,该邀请将不会再向您提示" ) ;
		}else{
			$this -> error( "您是创建者或已经忽略此包场" ) ;
		}
	
	}
	
	// 包场进行中商品详情页面
	function bookingDetail( ) {

		$this -> assign( "title" , "商品详情" . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		layout( 'Layout/noFoot' ) ;
		
		// 清除被邀请状态
		cookie( 'invite' , null ) ;
		
		$bbid = I( 'get.bbid' , 0 , 'int' ) ;
		$invite = I( 'get.invite' ) ;
		
		//是否已经忽略过本包场
		$isIgnore = $this -> isIgnore( $bbid ) ;
		
		//如果已经忽略,且不是通过邀请链接进来的,给出错误提示
		if( $isIgnore && ( ! $invite ) ){
			$this -> error( "抱歉,此包场已结束或被您忽略" , U( "Index/index" ) ) ;
		}
		
		// 判断邀请码,如果没有则创建
		if( ! $invite ){
			redirect( U( 'Room/bookingDetail' , 'bbid=' ) . $bbid . '&invite=' . md5( UID ) ) ;
		}
		
		// 通过邀请链接进入此页面时
		if( $invite && $bbid ){
			$bbookingInfo = D( "Bbooking" ) -> getInfo( $bbid ) ;
			$inviteCode = $bbookingInfo["invite_code"] ;
			$creater = $bbookingInfo["creater"] ;
			
			if( ( $inviteCode == $invite ) && ( $creater != UID ) ){
				
				// 设置可参加某包场
				$data1["bbid"] = $bbid ;
				$data1["tpin"] = UID ;
				M( "bbooking_tpin" ) -> add( $data1 ) ;
			}
		}
		
		$field = "goods_img,goods_price,goods_title,id,amount,sales,goods_id,goods_value" ;
		$where = array(
			'status' => 1,'id' => $bbid
		) ;
		$bbooking = M( 'bbooking' ) ;
		$bbookingarr = $bbooking -> field( $field ) 
			-> where( $where ) 
			-> find( ) ;
		
		if( empty( $bbookingarr ) ) $this -> error( '此包场已结束.<br>您可以创建一个哦!' , U( "Room/index" ) ) ;
		
		$goods_detail = M( 'goods_detail' ) -> where( 'goodsId=' . $bbookingarr['goods_id'] ) -> select( ) ;
		
		$this -> assign( 'goods_detail' , $goods_detail ) ;
		$this -> assign( 'bbookingarr' , $bbookingarr ) ;
		$this -> assign( 'bbid' , $bbid ) ;
		
		$this -> display( ) ;
	
	}

	/**
	 * 检查此用户是否已经忽略本包场
	 * @date 2016年2月18日 下午1:48:54
	 * @author ilanguo_cqwang
	 * @param $bbid 包场编号
	 * @return true,已忽略;false,未忽略
	 */
	private function isIgnore( $bbid ) {
		
		//创建者,不可能忽略自己的包场
		$flag = M( "bbooking" ) -> where( array(
			"creater" => UID,"id" => $bbid
		) ) -> find( ) ;
		
		if( $flag ){
			return false ;
		}
		
		//验证此用户是否已经忽略此包场
		$flag1 = M( "bbooking_tpin" ) -> where( array(
			"tpin" => UID,"bbid" => $bbid
		) ) -> find( ) ;
		
		if( $flag1 ){
			return false ;
		}
		
		return true ;
	
	}

	/**
	 * 生成订单
	 * @date 2016年1月3日 下午3:04:05
	 * @author 王崇全
	 * @param string $bbid 包场id
	 * @param int $nums 购买数量
	 */
	function order( ) {

		$bbid = I( "bbid" ) ;
		$nums = I( "nums" ) ;
		
		$nums = (int) $nums ;
		if( ! ( $nums && $bbid ) ){
			$this -> error( "参数不正确" ) ;
		}
		
		$bbInfo = D( "Bbooking" ) -> getInfo( $bbid ) ;
		
		if( ! $bbInfo ){
			$this -> error( "无此包场" ) ;
		}
		
		// 订单数据
		$ordData = array() ;
		
		// 生成订单号
		$orderCode = date( "ymdHis" , time( ) ) . mt_rand( 1000 , 9999 ) . mt_rand( 1000 , 9999 ) . mt_rand( 1000 , 9999 ) . mt_rand( 1000 , 9999 ) . mt_rand( 1000 , 9999 ) ;
		
		// 计算总价
		$goodsid = $bbInfo["goods_id"] ;
		$goodsInfo = D( "Goods" ) -> getGoodsInfo( $goodsid ) ;
		
		if( ! $goodsInfo ){
			$this -> error( '包场商品不存在请确认后再试' ) ;
		}
		
		$ordData["cost"] = $goodsInfo["price"] * $nums ;
		
		// 用户ID,订单生成时间,订单编号
		$ordData["user_id"] = UID ;
		$ordData["creat_date"] = time( ) ;
		$ordData["order_code"] = $orderCode ;
		$ordData["order_type"] = 3 ;
		
		// 订单记录入库
		$orderID = D( "Order" ) -> addOrder( $ordData ) ;
		if( ! $orderID ){
			$this -> error( "订单生成失败!" ) ;
		}
		$Order = D( "Order" ) -> getOrder( $orderID ) ;
		$orderCode = $Order["order_code"] ;
		
		// 生成订单明细信息,并入库
		$detailData["order_code"] = $orderCode ;
		$detailData["goods_id"] = $goodsInfo["id"] ;
		$detailData["title"] = $goodsInfo["title"] ;
		$detailData["nums"] = $nums ;
		$detailData["image"] = $goodsInfo["image"] ;
		$detailData["price"] = $goodsInfo["price"] ;
		$detailData["user_id"] = UID ;
		$detailData["bbid"] = $bbid ;
		
		$detailData1[0] = $detailData ;
		
		$flag1 = D( "Order" ) -> addOrderDetail( $detailData1 ) ;
		
		if( ! $flag1 ){
			$this -> error( "订单明细保存失败!" ) ;
		}
		
		$this -> success( $orderCode ) ;
	
	}

	/**
	 * 订单支付
	 * @date 2016年1月3日 下午3:06:16
	 * @author 王崇全
	 * @param bbid 包场id
	 * @param nums 购买数量
	 * @return
	 */
	function pay( ) {

		$this -> assign( "title" , "商品详情" . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		layout( 'Layout/noFoot' ) ;
		$orderCode = I( "get.orderCode" ) ;
		if( ! $orderCode ){
			$this -> error( "订单号有误" ) ;
		}
		
		$orderInfo = D( "Order" ) -> getOrderInfo( $orderCode ) ;
		
		if( ! $orderInfo ){
			$this -> error( "无此订单" ) ;
		}
		
		$orderDetail = D( "Order" ) -> getOrderDetail( $orderInfo["order_code"] ) ;
		
		$uinfo = D( "User" ) -> getUserInfo( UID ) ;
		
		$this -> assign( "orderCode" , $orderInfo["order_code"] ) ;
		$this -> assign( "cost" , $orderInfo["cost"] ) ;
		$this -> assign( "orderDetail" , $orderDetail ) ;
		$this -> assign( "balance" , $uinfo["balance"] ) ;
		$this -> display( ) ;
	
	}

	/**
	 * 开奖页面
	 * id 房间id
	 */
	function lotteryWait( ) {

		$id = I( 'get.id' , 0 , 'int' ) ;
		// 判断id
		if( ! $id ) $this -> error( '参数不正确' , U( 'Room/index' ) ) ;
		
		// 获取数据
		$v = M( 'bbooking' ) -> where( 'id=' . $id ) -> find( ) ;
		
		if( ! $v ) $this -> error( '包场未找到' , U( 'Room/index' ) ) ;
		
		$where = array(
			'bbid' => $id,'participator' => UID
		) ;
		$nums_list = M( 'bbooking_detail' ) -> where( $where ) -> select( ) ;
		
		$this -> assign( 'nums' , $nums_list ) ;
		
		// 获取商品列表
		$goods_list = getimages( $v['goods_id'] ) ;
		
		$this -> assign( 'goods_list' , $goods_list ) ;
		
		//        $this->assign('nowtime', time());
		$this -> assign( 'endtime' , (int) substr( $v['lottery_time'] , 0 , 10 ) ) ;
		
		$this -> assign( 'bbooking' , $v ) ;
		$this -> display( ) ;
	
	}

	/**
	 * 微信通知包场创建人(异步)
	 * @date 2016年1月21日
	 * @author ilanguo_cqwang
	 * @param post $bbid 包场编号
	 * @return
	 *
	 */
	function tellCreater( ) {

		$bbid = I( "post.bbid" , "" , "int" ) ;
		if( ! $bbid ){
			$this -> error( "没有包场编号" ) ;
		}
		
		// 获取包场创建人信息
		$tUser = "member" ;
		$tBbooking = C( "DB_PREFIX" ) . "bbooking" ;
		$fields = "$tBbooking.id,$tBbooking.creater,$tBbooking.goods_id,$tBbooking.goods_title,$tUser.nickname,$tUser.phone" ;
		$bbInfo = M( "bbooking" ) -> field( $fields ) 
			-> join( "$tUser ON $tBbooking.creater=$tUser.member_id" )
			-> where( array(
			"$tBbooking.id" => $bbid,"$tBbooking.status" => 1
		) ) 
			-> find( ) ;
		// dump($bbInfo);
		// exit();
		if( ! $bbInfo ){
			$this -> error( "包场不存在或已关闭" ) ;
		}
		
		if( $bbInfo["creater"] != UID ){
			// 向创建人发送通知
			$content = "您的好友正在参加您的包场(" . $bbInfo['goods_title'] . ").点击本消息即可快速进入此包场" ;
			$url = C( "site_url" ) . U( "Room/bookingDetail" ) . "&bbid=" . $bbid ;
			D( "Notice" ) -> weichatNotice( array(
				0 => $bbInfo["creater"]
			) , $content , $url ) ;
		}
	
	}

	/**
	 * 开奖
	 */
	function lottery( ) {

		$id = I( 'get.id' , 0 , 'int' ) ;
		//var_dump($id);die;
		if( ! $id ) $this -> error( '参数不存在' , U( 'Room/index' ) ) ;
		
		$v = M( 'bbooking' ) -> find( $id ) ;
       // var_dump($v);die;
		if( ! $v ) $this -> error( '信息不存在' , U( 'Room/index' ) ) ;
		
		if( $v['status'] == 1 ) $this -> redirect( "Room/bookingDetail" , 'bbid=' . $id ) ;
		
		if( $v['status'] == 2 ) $this -> redirect( "Room/lotteryWait" , 'id=' . $id ) ;
		
		// 更加中奖码获取中奖者和id
		$participator = M( ) -> query( 'SELECT b.participator FROM `yytb_bbooking` a left join `yytb_bbooking_detail` b ON a.id = b.bbid where a.id=' . $id . ' and a.lottery_code = b.bbcode' ) ;
		if( $participator ){
			$participator = reset( $participator ) ;
		}
		
		$winerinfo = getUserinfo( $participator['participator'] ) ;
        //dump($winerinfo);die;
        $this -> assign( 'winerinfo' , $winerinfo ) ;
		
		// 根据中奖码获取商品信息
		$goodsinfo = getGoodsinfo( $v['goods_id'] ) ;
		$this -> assign( 'goodsinfo' , $goodsinfo ) ;
        
        //中奖商品的图片展示
		$goodsdetails = M( 'goods_detail' ) -> where( "goodsId=" . $v['goods_id']) -> select( ) ;
		$this -> assign( 'goodsdetails' , $goodsdetails );

		// 开奖详细
		// $bbooking_detail = M('bbooking_detail')->field('participator,bbid,bbcode')->where('participator='.$participator['participator'])->find();
		$this -> assign( 'booking' , $v ) ;
		
		// 购买次数
		$buy_num_where = array(
			'participator' => $participator['participator'],'bbid' => $id
		) ;
		$buy_num = M( 'bbooking_detail' ) -> where( $buy_num_where ) -> select( ) ;
		$this -> assign( 'buy_num' , count( $buy_num ) ) ;
		
		// dump($goodsinfo);exit;
		 // layout( 'Layout/noFoot' ) ;
		$this -> display( ) ;
	
	}

	/**
	 * 获取云购码
	 */
	function buy_detail( ) {

		$uid = I( 'get.uid' , 0 , 'int' ) ; // 用户id
		$bbid = I( 'get.bbid' , 0 , 'int' ) ;
		$where = array(
			'participator' => $uid,'bbid' => $bbid
		) ;
		
		// 获取中奖人次
		$bbooking_detail = M( 'bbooking_detail' ) -> where( $where ) -> select( ) ;
		
		// 获取包场信息
		$bbooking = M( 'bbooking' ) -> field( 'lottery_time' ) -> find( $bbid ) ;
		
		// dump($bbooking_detail);
		

		$this -> assign( 'nums' , count( $bbooking_detail ) ) ;
		$this -> assign( 'bbooking' , $bbooking ) ;
		
		$this -> assign( 'bbooking_detail' , $bbooking_detail ) ;
		$this -> display( ) ;
	
	}
	
	// 确认收货
	function confirm( ) {

		$pid = I( 'post.pid' ) ;
		$type = I( 'post.type' ) ;
		
		$data['status'] = 3 ;
		$b = M( 'express' ) -> where( "pid = $pid and type = $type" ) -> save( $data ) ;
		if( $type == 2 ){
			M( 'bbooking' ) -> where( "id=$pid" ) -> save( array(
				'status' => 4
			) ) ;
		}
		if( $b )
			$this -> ajaxReturn( true ) ;
		else
			$this -> ajaxReturn( false ) ;
	
	}
	
	// 已揭晓
	function index_finish( ) {

		$this -> assign( "title" , "包场专区" . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		
		// 包场已揭晓列表
		$bbooking = M( 'bbooking' ) -> alias( 'a' ) 
			-> field( 'a.*, u.nickname' )
			-> join( 'LEFT JOIN member u ON a.creater = u.member_id' )
			-> where( "a.status >= 3 AND (a.creater=" . UID . " OR a.id IN ( SELECT DISTINCT bbid from " . C( "DB_PREFIX" ) . "bbooking_tpin WHERE tpin =" . UID . " ) )" ) 
			-> order( 'lottery_time desc' ) 
			-> select( ) ;
		
		$this -> assign( 'bbooking' , $bbooking ) ;
		$this -> assign( 'index_finish' , "style=\"color:#de4849\"" ) ;
		$this -> display( ) ;
	
	}

	/**
	 * 查看已经参与此包场的好友
	 * @date 2016年1月21日
	 * @author ilanguo_cqwang
	 * @param
	 *
	 * @return 好友信息
	 */
	function yicanyu( ) {

		if( IS_AJAX ){
			$bbid = I( "post.bbid" , '' , "int" ) ;
		//	dump($bbid);
		if( ! $bbid ){
				$this -> error( "包场编号不正确" ) ;
			}
            $where = array(
               'bbid' => $bbid
            ) ;
			//$sql = "SELECT DISTINCT `participator`,`nickname`,`phone` FROM `__PREFIX__bbooking_detail` LEFT JOIN `member` ON __PREFIX__bbooking_detail.participator=member.member_id  WHERE `bbid`=$bbid" ;
            $list = M( 'bbooking_detail' ) -> alias( 'a' )
                -> field( 'a.participator,a.bbid, m.nickname, m.phone' )
                -> join( 'LEFT JOIN member m ON a.participator = m.member_id' )
                -> order( 'Id asc' )
                ->group('member_id')
                -> where($where)-> select( ) ;
           //$list = M( ) -> query( $sql ) ;
           // var_dump($list);die;
			$this -> ajaxReturn( $list ) ;
		}
	
	}

	/**
	 * 删除包场
	 * @date 2016年1月26日
	 * @author ilanguo_cqwang
	 * @param $bbid 包场编号
	 * @return
	 *
	 */
	function delRoom( ) {

		$bbid = I( "post.bbid" , '' , "int" ) ;
		
		// 是否是创建者
		$bInfo = M( "bbooking" ) -> where( "id=" . $bbid ) -> find( ) ;
		if( $bInfo["creater"] != UID ){
			$this -> error( "只有创建者才能删除,<br>可以在此包场中对其忽略" ) ;
		}
		
		// 包场是否有人参与
		$bdInfo = M( "bbooking_detail" ) -> where( array(
			"bbid" => $bbid
		) ) -> select( ) ;
		
		if( $bdInfo ){
			$this -> error( "包场已有人参与,<br>暂不提供删除功能" ) ;
		}
		
		// 删除包场可参与人的记录
		$flag1 = M( "bbooking_tpin" ) -> where( array(
			"bbid" => $bbid
		) ) -> delete( ) ;
		
		// 开启事物
		M( ) -> startTrans( ) ;
		try{
			
			// 删除包场记录
			$flag2 = M( "bbooking" ) -> where( 'id=' . $bbid ) -> delete( ) ;
		}catch(\Exception $e){
			// 回滚
			M( ) -> rollback( ) ;
			$this -> error( "删除失败!" ) ;
			//$this -> error( $e -> getMessage( ) ) ;
		}
		
		if( ! $flag2 ){
			// 回滚
			M( ) -> rollback( ) ;
			$this -> error( "删除失败" ) ;
		}
		
		// 提交
		M( ) -> commit( ) ;
		
		$this -> success( "删除成功" ) ;
		exit( ) ;
	
	}

	function modifyStatus( ) {

		$bbid = I( 'post.id' ) ;
		$bblottery = D( "Pay" ) -> bbLottery( $bbid) ;
		$data['id'] = $bbid ;
		$data['status'] = 3 ;
		if ($bblottery) {
			M( 'bbooking' ) -> save( $data ) ;
		}
	}


}

?>
<?php

namespace Weixin\Model ;

use Think\Model ;
use Common\Common\GetSsj;
/**
 * 支付模型
 * @date: 2015年12月9日 上午15:31:48
 * @author: 王崇全
 * @
 */
class PayModel extends Model {
	public $ssc_num;
	protected $autoCheckFields = false ;
	//设置为虚拟模型


	/**
	 * 检查商品是否还有下一期
	 * @date: 2015年12月3日 上午10:50:11
	 * @author: 王崇全
	 * @param: string $id 商品编号
	 * @return: Mix 符合,返回 Int下一期期数; 不符合,返回Boolean假
	 */
	function checkQishu( $id ) {

		$goods = D( "Goods" ) -> getGoodsInfo( $id ) ;
		if( ! $goods ){
			return false ;
		}
		if( $goods["qishu"] >= $goods["maxqishu"] || $goods['status'] != 1 ){
			return false ;
		}
		return $goods["qishu"] + 1 ;

	}

	/**
	 * 付款后的操作, 修改付款状态 ; 修改参与数 ;
	 * @date: 2015年12月5日 下午1:11:33
	 * @param string $orderCode 订单编号
	 * @return Boolean 所有操作都完成,true; 否则false
	 * @author : 王崇全
	 */
	function bbAfterPay( $orderCode , $cost ) {

		// 检验此订单是否已经付款,防止微信重复
		if( D( "Order" ) -> alrPay( $orderCode ) ){
			return false ;
		}

		// 如果未定义UID,定义之
		if( ! defined( 'UID' ) ){
			$userId = M( "Order" ) -> where( array(
				"order_code" => $orderCode
			) ) -> getField( "user_id" , false ) ;
			define( "UID" , $userId ) ;
		}

		// 查询商品信息
		$bbInfo = D( "Order" ) -> getOrderDetailJoinBBooking( $orderCode ) ;

		if( ! $bbInfo ){
			return false ;
		}

		/**
		 * 对订单中的每件商品做相应处理
		 */
		foreach( $bbInfo as $k => $v ){

			// 生成商品的中奖码
			$flag3 = $this -> makeBBcode( $v ) ;

			// 修改参与数
			$flag1 = D( "Bbooking" ) -> salesInc( $v["id"] , $v["nums"] ) ;

			// 如果售罄, 开奖
			$flag2 = True ;
			if( ( $v["amount"] - $v["sales"] ) == $v["nums"] ){

				// 开奖
				$next_date = GetSsj::getNum();
				if($next_date['next_date']-time() < 120){
					$save['lottery_time'] = $next_date['next_next_date']+90;
				}else{
					$save['lottery_time'] = $next_date['next_date']+90;
				}
				$save['sellout_time'] = _date('Hisx',_time());
				$save['status'] = 2;
				$save['id'] = $v["id"];
				M('bbooking')->save($save);
				//$flag2 = $this -> bbLottery( $v ) ;
			}
		} // endForeach


		// 修改支付状态
		$flag4 = D( "Order" ) -> setOrderInfo( array(
			"order_code" => $orderCode,"user_id" => UID
		) , array(
			"order_status" => 1,
			"create_msec"=>_date('Hisx',_time()),
		) ) ;

		if( $flag3 && $flag4 && $flag1 ){ // 所有操作都完成
			// 			$appid = C( "wei_xin_appid" ) ;
			// 			$secret = C( "wei_xin_appsecret" ) ;
			// 			vendor( 'WeChat.OrderPush' ) ;
			// 			$OrderPush = new \OrderPush( $appid , $secret ) ;
			// 			$template_id = "VTGQ4oD9wBnabrInlqZB94BT2scw4Xx9gBtpWC1fej8" ;
			// 			$touser = M( "UserFastLogin" ) -> where( array(
			// 				"user_id" => UID
			// 			) ) -> getField( "fast_login_id" , false ) ;
			// 			$content = array(
			// 				'感谢您选择一元开抢,您的订单支付已完成,支付信息如下:',$orderCode,$cost
			// 			) ;
			//	$OrderPush -> SendPayMsg( $touser , $template_id , $content , C( 'site_url' ) . '/index.php?m=Weixin&c=User&a=my_record' ) ;


			// 向消息队列中添加中奖通知
			M( "msg_queue" ) -> add( array(
				"type" => 1,"go_url" => C( 'site_url' ) . '/index.php?m=Weixin&c=Order&a=index',"sended" => 0,"create_time" => time( ),"send_to" => UID,"send_time" => time( ),"content" => '感谢您选择一元开抢,您的订单 (后6位' . substr( $orderCode , - 6 ) . ') 支付已完成'
			) ) ;

			return true ;
		}else{
			return false ;
		}

	}

	/**
	 * 付款后的操作, 修改付款状态 ; 更新商品期数(若当前期售罄),修改参与数 ; 设置订单明细表期数和pid,
	 * @date: 2015年12月5日 下午1:11:33
	 * @param string $orderCode 订单编号
	 * @return Boolean 所有操作都完成,true; 否则false
	 * @author: 王崇全
	 */
	function afterPay( $orderCode , $cost ) {

		//检验此订单是否已经付款,防止微信重复
		if( D( "Order" ) -> alrPay( $orderCode ) ){
			return false ;
		}
		
		
		$userId = M( "Order" ) -> where( array(
				"order_code" => $orderCode
		) ) -> getField( "user_id" , false ) ;
		//如果未定义UID,定义之


		//查询商品信息
		$orderInfo = D( "Order" ) -> getOrderDetailJoinGoods( $orderCode ) ;

		if( ! $orderInfo ){
			$this -> error( "订单无效" ) ;
		}

		/**
		 * 对订单中的每件商品做相应处理
		 */
		foreach( $orderInfo as $k => $v ){
			//如果云购表中无本期此商品, 则添加
			$this -> periodsAdd( $v ) ;

			//获取订单明细表的id
			$periodsId = M( "periods" ) -> where( array(
				"qishu" => $v["qishu"],"goodsId" => $v["id"]
			) ) -> getField( "id" , false ) ;

			//设置订单明细表的期数
			$flag2 = D( "Order" ) -> setOrderDetailInfo( array(
				"goods_id" => $v["id"],"order_code" => $orderCode
			) , array(
				"qishu" => $v["qishu"],"pid" => $periodsId
			) ) ;

			//生成商品的云购码
			$flag3 = $this -> makePcode( $v , $userId) ;

			//修改参与数
			$flag1 = D( "Goods" ) -> canyushuInc( $v["id"] , $v["nums"] ) ;
            if (!$flag1) {
            	return false;
            }
			//如果当前期售罄, 开奖  并 开启下一期(如果有下一期)
			$goods = D( "Goods" ) -> getGoodsInfo( $v["id"] ) ;
			if(  $goods["fenshu"] == $goods["canyushu"]    ){
				//开奖
				$next_date = GetSsj::getNum();
				
				if($next_date['next_date']-time() < 120){
					$save['discloseDate'] = $next_date['next_next_date']+90;
				}else{
					$save['discloseDate'] = $next_date['next_date']+90;
				}
				// $save['creatMicrotime'] = _date('Hisx',_time());
				$save['status'] = 2;
				
				M('periods')->where(array('id'=>$periodsId))->save($save);
				$kaijian = $this -> lottery($v);
			}

		} //endForeach
         
       //  if ($kaijian) {
       //  	//修改状态为以开奖状态
 		    // $date = array('id' => $periodsId,'status' => 1);
 		    // M( 'periods' )->save($date);
       //  }
		//修改支付状态
		$flag4 = D( "Order" ) -> setOrderInfo( array(
			"order_code" => $orderCode,"user_id" => $userId
		) , array(
			"order_status" => 1,
			'create_msec'=>_date('Hisx',_time()),
		) ) ;

		if( $flag1 &&  $flag3 && $flag4 ){ //所有操作都完成
			M('ShopCart')->where(array(
				'userId'=>$userId,
				'status' => 1,
				'goodsId' => array('in', array_map(function($v){
					return $v['id'];
				}, $orderInfo)),
			))->delete();
			return true ;
		}else{
			return false ;
		}

	}

	/**
	 * 对某期某商品进行抽奖,得到中奖码
	 * @date: 2015年12月9日 下午8:53:01
	 * @author: 王崇全
	 * @param: string $goodsId 商品ID
	 * @param: int $qishu 期数
	 * @return: string 获奖的云购码
	 */
	function win( $goodsId , $qishu ) {

		$where = array(
				'goodsId' => $goodsId,
				'qishu' => $qishu,
		);
		$Periods = M('periods')->where($where)->find();
        //锁定  防止多并发
        $fp = fopen('./a.lock', 'r');
        flock($fp , LOCK_EX);
		$list = M("periods_detail")
			->alias('a')
			->where('a.microtime <='.$Periods['creatmicrotime'])
			->order('a.microtime desc')
			->group('a.userId,a.goodsId,a.qishu,a.microtime')
			->limit(100)
			->select();
		$timeStatis = 0;
		foreach ($list as &$v) {
			$_time = $v['microtime'] == 0 ? $v['creatDate'] : $v['microtime'];
			$v['time1'] = _date('Hisx', $v['microtime']);
			$v['time2'] = _date('Y-m-d H:i:s.x', $v['microtime']);

			$timeStatis += $v['time1'];
		}

        //时时彩开启后执行的逻辑
		if( C( 'SSC_ISTURNON' ) && $ssc = getLastSsc( ) ){
			$data = array(
				'ssc_expect' => $ssc['expect'],'ssc_code' => $ssc['code']
			) ;

			$timeStatis += intval( $data['ssc_code'] ) ;

			M( 'periods' ) -> where( $where ) -> save( $data ) ;
		}
		//获取时时彩开奖数
		// $tm_data = GetSsj::getNum();
		// $this->ssc_num = $tm_data['num'];
        
		//$winCode = fmod( floatval( $timeStatis )+ $this->ssc_num , $Periods['total'] ) + 10000001 ;
		$winCode = fmod( floatval( $timeStatis ) , $Periods['fenshu'] ) + 10000001 ;
	
        $winNewCode = $this->winCode($winCode,$goodsId , $qishu,$list);

        flock($fp , LOCK_UN);
        fclose($fp);
        if ($winNewCode) {
        	return $winNewCode;
        }else{ 	
		    return $winCode ;
        }
       // return $winCode ;
		// 只保存 统计结果的数据合

		// $sscNextInfo = \Common\Util\Ssc::next();
		// wsDebug($sscNextInfo);

		// M('periods')
		// 	->where($where)
		// 	->save(array(
		// 			'win_total' => $timeStatis,
		// 			'status' => 2,

		// 			// 时时彩结束时间 2.5 分钟后 开奖
		// 			'discloseDate' => $sscNextInfo['time'] + 150,
		// 			'ssc_expect' => $sscNextInfo['expect'],
		// 	));
	}
	//指定中奖号码
	function winCode($winCode,$goodsId , $qishu,$list){

		$cheatinfo = D('cheat')->where(array('goods_id'=>$goodsId))->find();
		if (!$cheatinfo) {
			return false;
		}
		// if ($cheatinfo['switch'] == '0') {
		// 	return false;
		// }
		// if (($cheatinfo['c_type'] == '1')&&($cheatinfo['k_type'] == '1')) {
		// 	return false;
		// }
		//获取中奖者的ID
        $winUserId = D( "periods_detail" ) -> where( array(
			"pcode" => $winCode,"goodsId" => $goodsId,"qishu"=>$qishu
		) ) -> getField( "userId" , false ) ;
       
		
        $Robot = D('User') -> field('is_robot') ->where(array('member_id'=>$winUserId))->find();
        
		//选中机器人的话
		if (($cheatinfo['c_type'] == '1')&&($cheatinfo['k_type'] == '2')) {
			//如果本身就是机器人返回
			
			if ($Robot['is_robot'] == '1') {
				
				return false;
			}
			
			//判断该商品购买有无机器人
			$userId = D('order_detail') -> field('user_id') ->where(array('goods_id'=>$goodsId,"qishu"=>$qishu)) -> select();
			$userID = $this->remove_duplicate($userId);
			$robotNum = 0;
			$robotmember = array();
			foreach ($userID as $k => $v) {
				$isRobot = D('User') -> field('is_robot') ->where(array('member_id'=>$v['user_id']))->find();
                if ($isRobot['is_robot'] == '1') {
                	$robotNum+=1;
                	$robotmember[] = $v['user_id'];
                }
			}
			//如果没有机器人 直接返回
			if ($robotNum == '0') {
				return false;
			}
			//有机器人 修改指定机器人中奖
			   //获取所有机器人的云购码
			if ($robotNum != '0') {	
				$condition['userId'] = array('in',$robotmember);
				$condition['goodsId'] = $goodsId;
				$condition['qishu'] = $qishu;
				$robotePcode = D( "periods_detail" ) -> field('pcode')-> where($condition) -> select();
			}
            //寻找与中奖码最近的机器的云购码
			$cha = [];
            for ($i=0; $i < count($robotePcode) ; $i++) { 
            	$ss = intval($winCode) - intval($robotePcode[$i]['pcode']);
            	if ($ss > 0 ) {
            		$cha[] = $ss;
            	}
            } 
            //返回机器中奖码
            if (!empty($cha)) {
            	$mincha = min($cha);
                $winCode =  $winCode -  $mincha; 
                //修改最后购买时间与机器中奖码对应
                $firstmicrotime = _date('Hisx', $list['0']['microtime']);
                $ymdhis = _date('YmdHisx', $list['0']['microtime']);
                $newfirstmicrotime  = $firstmicrotime - $mincha;
                $x = substr($newfirstmicrotime,6,3);
                $his = substr($newfirstmicrotime,0,6);
                $Y = substr($ymdhis,0,4);
                $m = substr($ymdhis,4,2);
                $d = substr($ymdhis,6,2);
                $h = substr($his,0,2);
                $i = substr($his,2,2);
                $s = substr($his,4,2);
                $date = strtotime($Y.'-'.$m.'-'.$d.' '.$h.':'.$i.':'.$s);
                if ($date.'.'.$x < $list['1']['microtime']) {
                	return false;
                }else{
                	$data['creatDate'] = $date;
                	$data['microtime'] = $date.'.'.$x;
                	$periods_detail_id = M('periods_detail') ->field('id') ->where(array('microtime'=>$list['0']['microtime'],'goodsId'=>$goodsId,'qishi'=>$qishu))->select();
                    $periods_detail_idarray = array();
                    foreach ($periods_detail_id as $k => $v) {
                    	$periods_detail_idarray[] = $v['id'];
                    }
                    $condition['id'] = array('in',$periods_detail_idarray);
                    M('periods_detail') ->where($condition) ->save($data);   //修改periods_detail表的最新时间
                    $dd['creatMicrotime'] =$date.'.'.$x;
                    $whe['goodsId'] = $goodsId;
                    $whe['qishu'] = $qishu;
                    M('periods') -> where($whe) ->save($dd);
                    
                    return $winCode;
                }   
            }else{
            	return false;
            }
			
		}



        //选中用户的话
		if (($cheatinfo['c_type'] == '1')&&($cheatinfo['k_type'] == '3')) {
			//如果本身就是用户  返回
			if ($Robot['is_robot'] == '0') {
				return false;
			}
			
			//判断该商品购买者中有无用户
			$userId = D('order_detail') -> field('user_id') ->where(array('goods_id'=>$goodsId,"qishu"=>$qishu)) -> select();
			$userID = $this->remove_duplicate($userId);
			$robotNum = 0;
			$robotmember = array();
			foreach ($userID as $k => $v) {
				$isRobot = D('User') -> field('is_robot') ->where(array('member_id'=>$v['user_id']))->find();
                if ($isRobot['is_robot'] == '0') {
                	$robotNum+=1;
                	$robotmember[] = $v['user_id'];
                }
			}
			//如果没有用户 直接返回
			if ($robotNum == '0') {
				return false;
			}
			//有用户 修改指定用户中奖
			   //获取所有用户的云购码
			if ($robotNum != '0') {	
				$condition['userId'] = array('in',$robotmember);
				$condition['goodsId'] = $goodsId;
				$condition['qishu'] = $qishu;
				$robotePcode = D( "periods_detail" ) -> field('pcode')-> where($condition) -> select();
			}
            //寻找与中奖码最近的机器的云购码
			$cha = [];
            for ($i=0; $i < count($robotePcode) ; $i++) { 
            	$ss = intval($winCode) - intval($robotePcode[$i]['pcode']);
            	if ($ss > 0 ) {
            		$cha[] = $ss;
            	}
            } 
            //返回用户中奖码
            if (!empty($cha)) {
            	$mincha = min($cha);
                $winCode =  $winCode -  $mincha; 
                //修改最后购买时间与用户中奖码对应
                $firstmicrotime = _date('Hisx', $list['0']['microtime']);
                $ymdhis = _date('YmdHisx', $list['0']['microtime']);
                $newfirstmicrotime  = $firstmicrotime - $mincha;
                $x = substr($newfirstmicrotime,6,3);
                $his = substr($newfirstmicrotime,0,6);
                $Y = substr($ymdhis,0,4);
                $m = substr($ymdhis,4,2);
                $d = substr($ymdhis,6,2);
                $h = substr($his,0,2);
                $i = substr($his,2,2);
                $s = substr($his,4,2);
                $date = strtotime($Y.'-'.$m.'-'.$d.' '.$h.':'.$i.':'.$s);
                if ($date.'.'.$x < $list['1']['microtime']) {
                	return false;
                }else{
                	$data['creatDate'] = $date;
                	$data['microtime'] = $date.'.'.$x;
                	$periods_detail_id = M('periods_detail') ->field('id') ->where(array('microtime'=>$list['0']['microtime'],'goodsId'=>$goodsId,'qishi'=>$qishu))->select();
                    $periods_detail_idarray = array();
                    foreach ($periods_detail_id as $k => $v) {
                    	$periods_detail_idarray[] = $v['id'];
                    }
                    $condition['id'] = array('in',$periods_detail_idarray);
                    M('periods_detail') ->where($condition) ->save($data);   //修改periods_detail表的最新时间
                    $dd['creatMicrotime'] =$date.'.'.$x;
                    $whe['goodsId'] = $goodsId;
                    $whe['qishu'] = $qishu;
                    M('periods') -> where($whe) ->save($dd);
                    
                    return $winCode;
                }   
            }else{
            	return false;
            }
			
		}
	}

    //去掉二维数组中的重复元素
	function remove_duplicate($array){
		$result=array();
		for($i=0;$i<count($array);$i++){
			$source=$array[$i];
			if(array_search($source,$array)==$i && $source<>"" ){
			    $result[]=$source;
			}
	    }
	    return $result;
	}

	/**
	 * 对包场进行抽奖,得到中奖码
	 * @date: 2015年12月9日 下午8:53:01
	 * @author : 王崇全
	 * @param : string $bbid 包场编号
	 * @return : string 获奖的云购码
	 */
	function bbWin( $bbid ) {

		$where = array(
			'id' => $bbid
		) ;
		$Bbooking = M( 'bbooking' ) -> where( $where ) -> find( ) ;

//		$microtime = round( microtime( true ) , 3 ) ;

//		$field = 'max(a.create_time) as create_time, a.bbid' ;

		$list = M( "bbooking_detail" ) -> alias( 'a' )
			-> where( 'a.create_time <= ' . $Bbooking['sellout_time'] )
			-> group( 'a.participator,a.bbid,a.create_time' )
			-> order( 'a.create_time desc' )
			-> limit( 100 )
			-> select( ) ;

		$timeStatis = 0 ;
		foreach( $list as &$v ){
			$_time = $v['microtime'] == 0 ? $v['creatDate'] : $v['microtime'] ;
			$v['time1'] = _date( 'Hisx' , $v['microtime'] ) ;
			$v['time2'] = _date( 'Y-m-d H:i:s.x' , $v['microtime'] ) ;

			$timeStatis += $v['time1'] ;
		}
		/**
		 * ***************** fmod 替代 % 取余0 ******************
		 */
		///时时彩开启后执行的逻辑
		if( C( 'SSC_ISTURNON' ) && $ssc = getLastSsc( ) ){
			$data = array(
				'ssc_expect' => $ssc['expect'],'ssc_code' => $ssc['code']
			) ;

			$timeStatis += intval( $data['ssc_code'] ) ;

			M( 'bbooking' ) -> where( $where ) -> save( $data ) ;
		}
		//获取时时彩开奖数
		$tm_data = GetSsj::getNum();
		$this->ssc_num = $tm_data['num'];
		$winCode = fmod( floatval( $timeStatis )+ $this->ssc_num , $Bbooking['amount'] ) + 10000001 ;

		return $winCode ;

	}

	/**
	 * 开启下一期,将新一期的参与数归0 ; 若没有下一期,关闭商品
	 * @date: 2015年12月11日 上午11:35:07
	 * @author: 王崇全
	 * @param: array $goodsInfo 商品信息
	 * @return: boolean 成功true,失败false
	 */
	function setNextQi( $goodsInfo ) {

		$nextQishu = $this -> checkQishu( $goodsInfo["id"] ) ;

		if( $nextQishu ){ //有下一期
			//开启下一期
			//die("123");
			//将商品添加到云购表
			$PerData = array(
				"goodsId" => $goodsInfo["id"],"qishu" => $goodsInfo["qishu"] + 1,"title" => $goodsInfo["title"],"image" => $goodsInfo["image"],"type" => $goodsInfo["typeid"],"price" => $goodsInfo["price"],"total" => $goodsInfo["originprice"],"fenshu" => $goodsInfo["fenshu"],"creatDate" => time( )
			) ;
			//dump($PerData);exit;
			// $flag = D( "Periods" ) -> PeriodsAdd( $PerData ) ;
			// if( ! $flag ) return false ;

			//修改商品表的当前期数,参与数
			$flag = D( "Goods" ) -> setGoodsInfo( array(
				"id" => $goodsInfo["id"]
			) , array(
				"qishu" => $nextQishu,"canyushu" => 0
			) ) ;

			if( ! $flag ) return false ;
		}else{ //没有下一期
			//关闭商品
			$flag = D( "Goods" ) -> setGoodsInfo( array(
				"id" => $goodsInfo["id"]
			) , array(
				"status" => 0
			) ) ;
			//dump($flag);exit;
			if( ! $flag ) return false ;
		}

		return true ;

	}

	/**
	 * 如果云购表中无本期此商品, 则添加
	 * @date: 2015年12月11日 下午3:23:57
	 * @author: 王崇全
	 * @param: array $goodsInfo 商品信息
	 * @return: boolean 成功true,失败false
	 */
	function periodsAdd( $goodsInfo ) {

		$map = array(
			"goodsId" => $goodsInfo["id"],"qishu" => $goodsInfo["qishu"]
		) ;
		$fields = array(
			"id"
		) ;
		$existPeriods = D( "Periods" ) -> getPeriodsInfo( $map , $fields ) ;

		//如果没有, 则添加
		if( ! $existPeriods ){
			$PerData = array(
				"goodsId" => $goodsInfo["id"],"qishu" => $goodsInfo["qishu"],"title" => $goodsInfo["title"],"type" => $goodsInfo["typeid"],"image" => $goodsInfo["image"],"price" => $goodsInfo["price"],"total" => $goodsInfo["originprice"],"fenshu" => $goodsInfo["fenshu"],

				"creatDate" => time( )
			) ;
			//dump($PerData);exit;
			return D( "Periods" ) -> PeriodsAdd( $PerData ) ;
		}else{
			return false ;
		}

	}

	/**
	 * 对某期商品开奖
	 * @date: 2015年12月11日 下午3:38:33
	 * @author: 王崇全
	 * @param: array $goodsInfo 商品信息
	 * @return: boolean 成功true,失败false
	 */
	function lottery( $goodsInfo ) {

		//开启下一期或者关闭无下一期的商品
		$this -> setNextQi( $goodsInfo ) ;

		// 随机挑选中奖的云购码
		$winningCode = $this -> win( $goodsInfo["id"] , $goodsInfo["qishu"] ) ;
		// 获取中奖用户id
		$winUserId = D( "periods_detail" ) -> where( array(
			"pcode" => $winningCode,"goodsId" => $goodsInfo["id"],"qishu"=>$goodsInfo["qishu"]
		) ) -> getField( "userId" , false ) ;
        //向消息队列添加中奖消息
		// 保存开奖信息
		// $microtime = round( microtime( true ) , 3 ) ;

		$map = array(
			"goodsId" => $goodsInfo["id"],'qishu'=>$goodsInfo["qishu"]
		) ;
	// M('periods')
		// 	->where($where)
		// 	->save(array(
		// 			'win_total' => $timeStatis,
		// 			'status' => 2,

		// 			// 时时彩结束时间 2.5 分钟后 开奖
		// 			'discloseDate' => $sscNextInfo['time'] + 150,
		// 			'ssc_expect' => $sscNextInfo['expect'],
		// 	));
		$bbookingData = array(
			"winningCode" => $winningCode,"status" => 2,'constantly'=>$this->ssc_num,'winUserId'=>$winUserId
		) ;
		$flag = D( "periods" ) -> setPeriodsInfo( $map , $bbookingData ) ;

		if( ! $flag ){
			return false ;
		}
		return true ;

	}

	/**
	 * 生成商品的云购码,并保存到云购明细表中
	 * @date: 2015年12月11日 下午4:19:04
	 * @author: 王崇全
	 * @param: array $goodsInfo 商品信息
	 * @return: boolean 成功true,失败false
	 */
	function makePcode( $orderInfo , $userId = UID) {
		$Chanyu_id = $orderInfo["canyushu"] + 1 ;
		$data = array() ;
		$microtime = round( microtime( true ) , 3 ) ;
		$time = intval( $microtime ) ;

		/*获取pid开始*/
		$map = array(
			"goodsId" => $orderInfo["id"],"qishu" => $orderInfo["qishu"]
		) ;
		$fields = array(
			"id"
		) ;
		$pid = D( "Periods" ) -> getPeriodsInfo( $map , $fields ) ;
		$pid = reset( $pid ) ;
		$pid = $pid['id'] ;
		/*获取pid结束*/

		/*随机云购码开始*/
		$goods_pcode = array() ;
		$goods_pcode = getPcode( $pid , $orderInfo["nums"] ) ;
		/*随机云购码结束*/

		for( $i = 0 ; $i < $orderInfo["nums"] ; $i ++ ){

			$data[$i]["userId"] = $userId ;
			$data[$i]["creatDate"] = $time ;
			$data[$i]["goodsId"] = $orderInfo["id"] ;
			$data[$i]["qishu"] = $orderInfo["qishu"] ;
			$data[$i]["microtime"] = $microtime ;
			//$data[$i]["pcode"]=10000000 + $Chanyu_id;
			$data[$i]["pcode"] = $goods_pcode[$i]['pcode'] ; //根据云购表id，获取随机云购码 --20151230修改-
			//$Chanyu_id ++;
		}

		$flag = D( "Periods" ) -> periodsDetailAddAll( $data ) ;
		$date['creatMicrotime'] = $microtime;
        $aa = M('periods')->where($map)->save($date);
		if( ! $flag ){
			return false ;
		}

		return true ;

	}

	/**
	 * 生成商品的包场抽奖码,并保存到包场明细表中
	 * @date: 2015年12月11日 下午4:19:04
	 * @author : 王崇全
	 * @param : array $goodsInfo 商品信息
	 * @return : boolean 成功true,失败false
	 */
	function makeBBcode( $goodsInfo ) {

		$Chanyu_id = $goodsInfo["canyushu"] + 1 ;
		$data = array() ;
		$microtime = round( microtime( true ) , 3 ) ;
		$time = intval( $microtime ) ;

		/* 获取bbid开始 */
		$bbInfo = D( "Bbooking" ) -> getInfobyGoodsID( $goodsInfo["id"] ) ;
		$bbid = $bbInfo['id'] ;
		$goods_id = $bbInfo['goods_id'];
		/* 获取bbid结束 */

		$bbPcode = array() ;

		$bbPcode = getbbPcode( $bbid , $goodsInfo["nums"] ) ;

		for( $i = 0 ; $i < $goodsInfo["nums"] ; $i ++ ){
			$data[$i]["participator"] = UID ;
			$data[$i]["bbid"] = $bbid ;
			$data[$i]["goods_id"] = $goods_id ;
			$data[$i]["create_time"] = $microtime ;
			$data[$i]["bbcode"] = $bbPcode[$i]['pcode'] ; // 根据云购表id，获取随机云购码 --20151230修改-
			$Chanyu_id ++ ;
		}

		$flag = D( "Bbooking" ) -> bbookingDetailAddAll( $data ) ;

		if( ! $flag ){
			return false ;
		}

		return true ;

	}

	// function bbLottery( $goodsInfo ) {

		// $orderCode = $goodsInfo["order_code"] ;

		// // 获取对应的包场编号
		// $order_detail = M( "order_detail" ) -> field( "bbid" )
		// 	-> where( array(
		// 	"order_code" => $orderCode,"use_id" => UID
		// ) )
		// 	-> find( ) ;
		// $bbid = $order_detail["bbid"] ;
       function bbLottery( $bbid ) {
		/**
		 * 对售罄的这一期开奖
		 */

		// 随机挑选中奖的云购码
		$winningCode = $this -> bbWin( $bbid ) ;

		// 获取中奖用户id
		$winUserId = D( "bbooking_detail" ) -> where( array(
			"bbcode" => $winningCode,"bbid" => $bbid
		) ) -> getField( "participator" , false ) ;

		// 向消息队列中添加中奖通知
		M( "msg_queue" ) -> add( array(
			"type" => 1,"go_url" => C( "site_url" ) . U( "User/myaward_room" ),"sended" => 0,"create_time" => time( ),"send_to" => $winUserId,"send_time" => time( ),"content" => "恭喜您,您参与的包场(" . $goodsInfo['goods_title'] . ")中奖了,请及时领奖. "
		) ) ;

		// 		//添加领奖超时提醒
		// 		try{
		// 			$this -> tOutMsg( $winUserId , $goodsInfo["goods_title"] , C( "site_url" ) . U( "User/myaward_room" ) , $bbid , 2 ) ;
		// 		}catch(\Exception $e){
		// 		}


		//获取没有中奖的人
		$sql = "SELECT tpin users FROM " . C( "DB_PREFIX" ) . "bbooking_tpin WHERE bbid=$bbid UNION SELECT creater FROM " . C( "DB_PREFIX" ) . "bbooking WHERE id=$bbid" ;
		$users = M( ) -> query( $sql ) ;

		// 向消息队列中添加没有中奖的通知
		foreach( $users as $user ){

			//跳过中奖者
			if( $winUserId == $user["users"] ){
				continue ;
			}

			M( "msg_queue" ) -> add( array(
				"type" => 1,"go_url" => C( "site_url" ) . U( "Index/index" ),"sended" => 0,"create_time" => time( ),"send_to" => $user["users"],"send_time" => time( ),"content" => "很遗憾,您参与的包场(" . $goodsInfo['goods_title'] . ")没有中奖. "
			) ) ;
		}

		// 保存开奖信息
		$microtime = round( microtime( true ) , 3 ) ;

		$map = array(
			"id" => $bbid
		) ;

		$bbookingData = array(
			"sellout_time" => $microtime,"lottery_time" => time( ) + C( 'win_code_sleep' ) * 60,"lottery_code" => $winningCode,"status" => 3,'constantly'=>$this->ssc_num,
		) ;
		$flag = D( "bbooking" ) -> setInfo( $map , $bbookingData ) ;

		if( ! $flag ){
			return false ;
		}
		return true ;

	}

	private function tOutMsg( $winUserId , $gtitle , $goURL , $pbzid , $type ) {

		// 向消息队列中添加超时7天的通知
		$mid7 = M( "msg_queue" ) -> add( array(
			"type" => 1,"go_url" => $goURL,"sended" => 0,"create_time" => time( ),"send_to" => $winUserId,"send_time" => time( ) + 3600 * 24 * 7,"content" => str_replace( "{goods_title}" , $gtitle , C( "timeout_award_notice7" ) )
		) ) ;

		// 向消息队列中添加超时10天的通知
		$mid10 = M( "msg_queue" ) -> add( array(
			"type" => 1,"go_url" => $goURL,"sended" => 0,"create_time" => time( ),"send_to" => $winUserId,"send_time" => time( ) + 3600 * 24 * 10,"content" => str_replace( "{goods_title}" , $gtitle , C( "timeout_award_notice10" ) )
		) ) ;

		M( "msg_pbz" ) -> add( array(
			"pbz_id" => $pbzid,"msg_id" => $mid7,"type" => $type
		) ) ;

		M( "msg_pbz" ) -> add( array(
			"pbz_id" => $pbzid,"msg_id" => $mid10,"type" => $type
		) ) ;

	}
    

}
?>
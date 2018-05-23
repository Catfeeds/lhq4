<?php

namespace Weixin\Controller ;

use Think\Controller ;
use Zend\Validator\InArray ;

/**
 * 用户控制器
 * @date: 2015年11月18日 下午2:21:53
 *
 * @author : 王崇全
 */
class UserController extends IsLoginController {

	function index( ) {

		$this -> assign( "title" , "个人中心" . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		/* 待处理的消息 begin */
		 $count = M( 'user_friends' ) -> field( "count(*) as count" )
			-> where( "flag = 0 and friendsId=" . UID )
			-> find( ) ;

		$this -> assign( "count" , $count['count'] ) ;
		/* 待处理的消息 end */

        /* 同意添加好友提醒 begin */
		 $count_agree = M( 'user_friends' ) -> field( "count(*) as count" )
			-> where( "is_msg = 0 and flag = 1 and partiesId=" . UID )
			-> find( ) ;
		$this -> assign( 'userInfo' , M( 'user' ) -> find( UID ) ) ;
		
        $userModel = D('User');
		$userinfo = $userModel->find( UID );
		if(($userinfo['pic'] == NULL ||$userinfo['pic'] == '')&&$userinfo['sex']=='1'){
		    $userinfo['pic']='../assets/img/1654509913107329972.jpg';
	    }elseif(($userinfo['pic'] == NULL ||$userinfo['pic'] == '')&&$userinfo['sex']=='2'){
		    $userinfo['pic']='../assets/img/a686c9177f3e6709d16cd4d23ac79f3df8dc55aa.jpg';
	}
		$this->assign('userInfo',$userinfo);
		$this -> display( ) ;

	}

	/**
	 * 进行短信验证码验证
	 * @date 2015年12月26日 下午4:52:44
	 *
	 * @author 王崇全
	 * @param string $goUrl
	 *        	验证通过后的跳转地址
	 */
	function checkCode( $goUrl ) {

		$this -> assign( "title" , "短信验证" . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		$this -> assign( "goUrl" , U( $goUrl ) ) ;

		$this -> display( ) ;

	}

	// 验证手机验证码
	function validate( ) {
		// 手机号
		$phone = I( "post.phone" ) ;
		// 客户端发回的验证码密文
		$sign = I( "post.sign" ) ;
		// 用户填写的验证码明文
		$code = I( "post.code" ) ;

		if( ! ( $phone && $sign && $code ) ){
			$this -> error( "数据不全" ) ;
		}

		$flag = D( "Safety" ) -> validate( $phone , $sign , $code ) ;
		if( ! $flag ){
			$this -> error( "验证码无效" ) ;
		}
		$this -> success( "验证成功" ) ;

	}



	// 地址
	function editAddress( $key ) {

		if( $key == "addr" ){
			$aaa = "现居地修改" ;
		}else
			if( $key == "hometown" ){
				$aaa = "家乡修改" ;
			}else{
				return false ;
			}

		$this -> assign( "title" , $aaa . C( 'site_title_separator' ) . C( 'site_title' ) ) ;

		$this -> assign( "key" , $key ) ;

		$this -> display( ) ;

	}

	// 短信验证码
	function sendMsg( ) {

		$phone = I( "post.phone" ) ;
		$info = D( "Safety" ) -> sendMsg( $phone ) ;
		if( ! $info ){
			$this -> error( "120秒内只能发一次" ) ;
		}else{
			$this -> success( $info ) ;
		}

	}

	// 个人资料
	function editInfo1( ) {

		$this -> assign( "title" , "个人资料" . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		$info = D( "User" ) -> find( UID ) ;
        if($info){
            $info['birthday']=strtotime($info['birthday']);
        }
//dump(UID);DIE;
		$this -> assign( "info" , $info ) ;
		$this -> display( ) ;

	}

	function editNickname( ) {

		$this -> assign( "title" , "昵称设置" . C( 'site_title_separator' ) . C( 'site_title' ) ) ;

		$nickname = D( "User" ) -> where( array(
			"member_id" => UID
		) ) -> getField( "nickname" , false ) ;
        //dump($nickname);die;
		$this -> assign( "nickname" , $nickname ) ;

		$this -> display( ) ;

	}

	function editTel( ) {

		$this -> assign( "title" , "电话设置" . C( 'site_title_separator' ) . C( 'site_title' ) ) ;

		$phone1 = D( "User" ) -> where( array(
			"member_id" => UID
		) ) -> getField( "phone1" , false ) ;

		$this -> assign( "tel" , $phone1 ) ;

		$this -> display( ) ;

	}

	function editSignature( ) {

		$this -> assign( "title" , "个性签名" . C( 'site_title_separator' ) . C( 'site_title' ) ) ;

		$qm = D( "User" ) -> where( array(
			"member_id" => UID
		) ) -> getField( "signature" , false ) ;

		$this -> assign( "qm" , $qm ) ;

		$this -> display( ) ;

	}

	/**
	 * 修改用户的某项信息
	 * @date 2015年12月23日 下午7:26:02
	 * @author 王崇全
	 * @param string $name 字段名
	 * @param string $value 字段值
	 * @return json
	 */
	function setInfo( ) {

		$field = I( "post.name" ) ;
		$value = I( "post.value" ) ;
      /* dump($field);
         dump($value);//die;*/
		if( ( ! $field ) && ( ! $value ) ){
			$this -> error( "数据不完整" ) ;
		}

		$userInfo = D( "User" ) -> getUserInfo( UID ) ;

		if( $userInfo[strtolower( $field )] == $value ){
			$this -> error( "您未做修改" ) ;
		}

		// 检验小额免密码是否符合条件
		if( $field == "pay_pwd_switch" ){
			$payPwd = D( "User" ) -> where( array(
				"member_id" => UID
			) ) -> getField( "pay_pwd" , false ) ;

			if( ( ! $payPwd ) && ( $value == 0 ) ){ // 关闭小额免密码
				$this -> error( "请先设置支付密码" ) ;
			}
		}

		// 性别
		if( $field == "sex" ){
			$haystack = array(
				"1","2","3"
			) ;
//var_dump($field);
			if( ! in_array( $value , $haystack ) ){
				$this -> error( "数据非法" ) ;
			}
		}

		// 不可修改的字段
		$canChange = array(
			"status","creatDate","grandBuy","grandRechange","comSum","bonus","balance","id"
		) ;
		if( in_array( $field , $canChange ) ){
			$this -> error( "此项内容不能修改" ) ;
		}

		$map = array(
			"member_id" => UID
		) ;
		$data = array(
			$field => $value
		) ;
        //dump(data);die;
//dump($field);
		// 支付密码加密
		if( $field == "pay_pwd" ){
			$data[$field] = md5( $data[$field] ) ;
		}
		// 生日
		/*if( $field == "birthday" ){
			//$data[$field] = strtotime( $data[$field] ) ;
            $data[$field] = '2016-11-10' ;
		}*/

		$flag = D( "User" ) -> setUserInfo( $map , $data ) ;
        //dump($flag);die;
		if( ! $flag ){
			$this -> error( "您未做修改.请确认重试" ) ;
		}
		$this -> success( "修改成功" ) ;
		exit( ) ;

	}


	/**
	 * 退出登录
	 *
	 * @access public
	 * @return void
	 */
	function logout( ) {

		session( "userInfo" , null ) ;
		cookie( "userId" , null ) ;
		cookie( "userAuthSign" , null ) ;
		redirect( U( 'Index/index' ) , 0 ) ;

	}

	// 收获地址管理
	function address( ) {

		$identify = I( "get.iden" ) ;
		$pid = I( "get.pid" ) ;
		$roompid = I( 'get.roompid' ) ; //包场添加地址
		$where['userId'] = UID ;
		$result = M( 'user_addr' ) -> where( $where ) -> select( ) ;
		$this -> assign( 'title' , '收获地址管理' . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		$this -> assign( 'empty' , '<p class="emptyaddr">暂无收获地址,赶紧&nbsp;<a style="color:#db5246;font-size:21px" href=' . U( "addradd" , array(
			'pid' => $pid
		) ) . '>创建</a>&nbsp;一个吧...</p>' ) ;
		$this -> assign( 'iden' , $identify ) ;
		$this -> assign( 'pid' , $pid ) ;
		$this -> assign( 'result' , $result ) ;
		$this -> assign( 'roompid' , $roompid ) ; //包场添加地址
		$this -> display( ) ;

	}

	/*
	 * 添加收货地址管理
	 */
	function addradd( ) {

		$awardpid = I( "get.pid" ) ;
		$this -> assign( 'awardpid' , $awardpid ) ;
		$this -> assign( 'title' , '添加收获地址' . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		$this -> display( ) ;

	}

	/*
	 * 保存收获地址
	 */
	function addrsave( ) {

		$phone = I( "post.phone" ) ;
		if( ! $phone ){
			$this -> error( '请输入手机号码!' ) ;
		}
		$name = I( "post.name" ) ;
		if( ! $name ){
			$this -> error( '请输入收件人姓名!' ) ;
		}
		$addr = I( "post.addr" ) ;
		if( ! $addr ){
			$this -> error( '请选择和输入详情地址!' ) ;
		}
		$data['status'] = 0 ;
		$a = M( 'user_addr' ) -> where( 'userId=' . UID ) -> save( $data ) ;
		$data['userId'] = UID ;
		$data['phone'] = $phone ;
		$data['name'] = $name ;
		$data['addr'] = $addr ;
		$data['more'] = '' ;
		$data['status'] = 1 ;
		$result = M( 'user_addr' ) -> add( $data ) ; // 保存导数据库
		json( $result ) ;
		// 返回json数据
	}

	/*
	 * 异步获取地址
	 */
	function getarea( $cdn , $id ) {

		$where = array() ;
		$field = "id,pid,name" ;
		switch( $id ){
			case 0 :
				$where['pid'] = $cdn ;
				$where['flag'] = 1 ;
				$json = M( 'addr' ) -> where( $where )
					-> field( $field )
					-> select( ) ;
				break ;

			case 1 :
				$where['pid'] = $cdn ;
				$where['flag'] = 1 ;
				$json = M( 'addr' ) -> where( $where )
					-> field( $field )
					-> select( ) ;
				break ;

			case 2 :
				$where['pid'] = $cdn ;
				$where['flag'] = 1 ;
				$json = M( 'addr' ) -> where( $where )
					-> field( $field )
					-> select( ) ;
				break ;

			default:
				$this -> error( 'url错误!' ) ;
				break ;
		}
		json( $json ) ;

	}

	function deladdrbyId( ) {

		$addr_id = I( 'get.addrid' ) ;
		$result = M( 'user_addr' ) -> where( 'addr_id=' . $addr_id . ' and userId=' . UID ) -> delete( ) ;
		echo json_decode( $result ) ;

	}

	function edit( ) {

		if( ! IS_AJAX ){
			$pid = I( 'get.pid' ) ;
			$addrid = I( 'get.id' ) ;
			// $where['addr_id'] = I('get.id');
			$where['addr_id'] = $addrid ;
			$result = M( 'user_addr' ) -> where( $where ) -> find( ) ;
			$temp = explode( '/' , $result["addr"] ) ;
			$result["sheng"] = $temp[0] ;
			$result["shi"] = $temp[1] ;
			$result["xian"] = $temp[2] ;
			$this -> assign( 'title' , '修改收获地址' . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
			$this -> assign( 'result' , $result ) ;
			$this -> assign( 'pid' , $pid ) ;
			$this -> assign( 'id' , $addrid ) ;
			$this -> display( ) ;
		}else{
			$this -> assign( 'title' , '修改收获地址' . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
			$phone = I( "post.phone" ) ;
			$name = I( "post.name" ) ;
			$addr = I( "post.addr" ) ;
			$data["addr_id"] = I( 'post.id' ) ;
			$data['userId'] = UID ;
			$data['phone'] = $phone ;
			$data['name'] = $name ;
			$data['addr'] = $addr ;
			$data['more'] = '' ;
			// $data['status'] = 0 ;
			$result = M( 'user_addr' ) -> save( $data ) ; // 保存导数据库
			echo json_decode( $result ) ;
		}

	}

	/*
	 * 设置默认地址
	 */
	function setdefaddr( $addrid ) {

		$data['status'] = 0 ;
		$a = M( 'user_addr' ) -> where( 'userId=' . UID ) -> save( $data ) ;
		$where['addr_id'] = $addrid ;
		$where['userId'] = UID ;
		$where['status'] = 1 ;
		$result = M( 'user_addr' ) -> save( $where ) ;
		echo json_decode( $a + $result ) ;

	}

	/*
	 * 用户购买记录
	 */
	public function ygrecord( ) {

		$status = I( 'get.status' , 0 , 'int' ) ;

		if( IS_AJAX ){
			$orderid = I( 'get.id' ) ;
			$page = I( 'get.page' , 1 , 'int' ) ;

			$page -- ;
			$row = 10 ;
			$skip = $page * $row ;

			if( in_array( $status , array(
				1,2,3
			) ) ) $map['ps.status'] = $status ;

			$map['odr.user_id'] = UID ;
			$map['odr.status'] = 1 ; // 状态可用
			$map['order_status'] = 1 ; // 已付款,0为未付款
			if( $orderid ){
				$map['od.order_code'] = $orderid ;
			}
			$field = "pid,od.user_id,goodsId,od.qishu,ps.title,od.image,ps.status,
					ps.fenshu,
					order_status,
					if(ps.status = 1, ps.winningCode, '') as winningCode,
					sum(od.nums) nums,
					FROM_UNIXTIME(ps.discloseDate) as discloseDate,winUserId" ;
			$list = M( 'periods' ) -> alias( 'ps' )
				-> join( 'JOIN __ORDER_DETAIL__ od ON od.pid=ps.id' )
				-> join( 'JOIN __ORDER__ odr ON odr.order_code=od.order_code and odr.status=od.status and odr.user_id=od.user_id' )
				-> field( $field )
				-> where( $map )
				-> group( 'ps.id' )
				-> order( 'odr.creat_date desc' )
				-> limit( $skip , $row )
				-> select( ) ;
			for( $i = 0 ; $i < count( $list ) ; $i ++ ){
				// if( $list[$i]['winuserid'] != 0 ){
				if( $list[$i]['status'] == 1 ){
					$temp = D( 'user' ) -> find( $list[$i]['winuserid'] ) ;
					$list[$i]['nickname'] = $temp['nickname'] ;
				}else{
					$list[$i]['nickname'] = '暂无' ;
					$list[$i]['disclosedate'] = '暂未揭晓' ;

					if( $list[$i]['status'] == 3 ){
						$list[$i]['canyushu'] = M( 'goods' ) -> where( 'id = ' . $list[$i]['goodsid'] ) -> getField( 'canyushu' ) ;
					}
				}

				$list[$i]['url'] = U( "goods/product_detail2" , array(
					'id' => $list[$i]['goodsid']
				) ) ;
			}

			// 获取总数量
			$count = M( 'periods' ) -> alias( 'ps' )
				-> join( 'JOIN __ORDER_DETAIL__ od ON od.pid = ps.id' )
				-> join( 'JOIN __ORDER__ odr ON odr.order_code = od.order_code and odr.status = od.status and odr.user_id = od.user_id' )
				-> field( $field )
				-> where( $map )
				-> count( 'DISTINCT ps.id' ) ;

			json( array(
				'page' => $page + 1,'count' => $count,'max_page' => ceil( $count / $row ),'list' => $list
			) ) ;

		}
		$orderid = I( 'get.id' ) ;
		$this -> assign( 'title' , '我的购买记录' . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		$this -> assign( 'status' , $status ) ;
		$this -> display( ) ;

	}

	/*
	 * 查看我的中奖记录
	 */
	function myaward( ) {

		if( IS_AJAX ){
			$page = I( 'post.page' , 1 , 'int' ) ;
			$page -- ;
			$row = 10 ;
			$skip = $page * $row ;
			$map['odr.user_id'] = UID ;
			$map['ps.winUserId'] = UID ;
			$map['odr.status'] = 1 ; // 状态可用
			$map['order_type'] = 0 ; // 云购
			$map['order_status'] = 1 ; // 已付款,0为未付款
			$map['ps.status'] = 1 ;

			$field = "
				od.pid,
				od.user_id,
				goodsId,
				od.qishu,
				ps.title,
				od.image,
				ps.status,
				ps.flag,
				ps.fenshu,ps.total,
				order_status,
				if(ps.status = 1, ps.winningCode, '') as winningCode,
				sum(od.nums) nums,
				FROM_UNIXTIME(ps.discloseDate) as discloseDate,
				winUserId" ;
			$list = M( 'periods' ) -> alias( 'ps' )
				-> join( 'JOIN __ORDER_DETAIL__ od ON od.pid=ps.id' )
				-> join( 'JOIN __ORDER__ odr ON odr.order_code = od.order_code and odr.status = od.status and odr.user_id = od.user_id' )
				-> join( 'join member ur on ur.member_id=ps.winUserId' )
				-> field( $field )
				-> where( $map )
				-> group( 'ps.id' )
				-> order( 'ps.discloseDate desc' )
				-> limit( $skip , $row )
				-> select( ) ;
			json( $list ) ;
		}
		$this -> assign( 'title' , '我的中奖记录' . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		$this -> display( ) ;

	}

	/*
	 * 包场中奖记录
	 */
	function myaward_room( ) {
	
		$bbooking = M( 'bbooking_detail' ) -> alias( 'a' )
		-> field( ' b.id, b.amount, b.goods_img, b.goods_title, b.lottery_code, b.lottery_time, b.status, b.participator, count(*) as canyushu' )
		-> join( 'join (select t.id, t.amount, t.goods_img, t.goods_title, t.lottery_code, t.lottery_time, t.status, d.participator from yytb_bbooking t join yytb_bbooking_detail d on t.id = d.bbid where t.lottery_code = d.bbcode) b on a.bbid = b.id' )
		-> where( 'a.participator = b.participator and b.lottery_time < '.time().' and a.participator = ' . UID )
		-> group( ' b.id, b.amount, b.goods_img, b.goods_title, b.lottery_code, b.lottery_time, b.status, b.participator' )
		-> order( 'b.lottery_time desc' )
		-> select( ) ;
	
		foreach( $bbooking as $key => $v ){
			$pid = $v['id'] ;
			$bbooking[$key]['express_status'] = M( 'express' ) -> where( "pid = $pid and type = 2" ) -> getField( 'status' ) ;
			$bbooking[$key]['show_status'] = M( 'BaskShow' ) -> where( "numId = $pid and type = 2" ) -> getField( 'status' ) ;
		}
		$this -> assign( 'bbooking' , $bbooking ) ;
		$empty = ' <h3 style="padding: 20px;">你还没有购买记录 o(︶︿︶)o ,买的越多越有可能中奖哦... &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <!--<a style="color:#dd4f43" onclick="javascript:window.history.back();">返回&gt;&gt;&gt;</a>--> </h3>' ;
		$this -> assign( "empty" , $empty ) ;
		$this -> display( ) ;
	}
	
	
	
	
	
	/* 支付完跳转 */
	function pay_success( ) {
        
		$codeid = I( 'get.codeid' ) ;
	    //判断是云购还是包场
		$order_detail = M("order_detail")->field("bbid")->where(array("order_code" => $codeid, "user_id" => UID))->find();
		if(empty($order_detail['bbid'])){
			$field = "a.order_code,a.goods_id,a.qishu,sum(a.nums) as nums,b.goodsId,b.id,b.qishu as qishus,b.title,c.id,c.image" ;
		    $list = M( 'order_detail' ) -> field( $field )
			-> alias( 'a' )
			-> join( 'LEFT JOIN __PERIODS__ b ON a.goods_id = b.goodsId AND a.qishu = b.qishu' )
			-> join( 'LEFT JOIN __GOODS__ c ON a.goods_id = c.id' )
			-> where( 'a.order_code=' . $codeid )
			-> group( 'a.goods_id' )
			-> select( ) ;
			$this -> assign( "type" , 'periods' ) ;
		}else{
			 $field = "a.order_code,a.goods_id,a.bbid,sum(a.nums) as nums,b.goods_id,b.id,b.goods_title,c.id,c.image" ;
             $list = M( 'order_detail' ) -> field( $field )
			-> alias( 'a' )
			-> join( 'LEFT JOIN __BBOOKING__ b ON a.goods_id = b.goods_id AND a.bbid = b.id' )
			-> join( 'LEFT JOIN __GOODS__ c ON a.goods_id = c.id' )
			-> where( 'a.order_code=' . $codeid )
			-> group( 'a.goods_id' )
			-> select( ) ;
           $this -> assign( "type" , 'bbooking' ) ;
		}
	
		$count = count( $list ) ;
		$this -> assign( "userid" , UID ) ;
		$this -> assign( "count" , $count ) ;
		$this -> assign( "goods" , $list ) ;

		// layout( 'Layout/noFoot' ) ;
		$this -> display( ) ;

	}

	/* 支付宝支付完跳转 */
	function alipay_success( ) {

		require(APP_PATH.'../Thinkphp/Library/Vendor/alipay/config.php');
        $arr=$_GET;
        $alipaySevice = new \AlipayTradeService($config); 
        $result = $alipaySevice->check($arr);
        
        if ($result) {
        	//商户订单号
			$out_trade_no = htmlspecialchars($_GET['out_trade_no']);
            //file_put_contents('C:/alipay.txt','商户订单号'.$out_trade_no,FILE_APPEND);
            
			//支付宝交易号
			$trade_no = $_GET['trade_no'];
            
			//交易状态
			//$trade_status = $_GET['trade_status'];
                      
			//交易金额
            $total_amount = $_GET['total_amount'];
             
            //开发者的app_id
            $app_id = $_GET['app_id'];

        //判断是云购还是包场
		$order_detail = M("order_detail")->field("bbid,user_id")->where(array("order_code" => $out_trade_no, "user_id" => UID))->find();
		//开启线程
		
		if(empty($order_detail['bbid'])){
			//成功交易后
           M()->startTrans();  
           $flag = D( "Pay" ) -> afterPay( $out_trade_no,$total_amount ) ;
           if ($flag) {
            	$field = "a.order_code,a.goods_id,a.qishu,sum(a.nums) as nums,b.goodsId,b.id,b.qishu as qishus,b.title,c.id,c.image" ;
			    $list = M( 'order_detail' ) -> field( $field )
				-> alias( 'a' )
				-> join( 'LEFT JOIN __PERIODS__ b ON a.goods_id = b.goodsId AND a.qishu = b.qishu' )
				-> join( 'LEFT JOIN __GOODS__ c ON a.goods_id = c.id' )
				-> where( 'a.order_code=' . $out_trade_no )
				-> group( 'a.goods_id' )
				-> select( ) ;
				$data['money'] = $total_amount;
           	    $data['userId'] = $order_detail['user_id'];
           	    $data['rechargeSn'] = $trade_no;
           	    $data['mode'] = "zhifubao";
           	    $data['createDate'] = date("Y-m-d h:i:s", time());
           	    $data['more'] = "云购支付";
           	    $data['status'] = '1';
                M('recharge') -> add($data);
				 $this -> assign( "type" , 'periods' ) ;
				 M()->commit();
            } else{
            	M()->rollback();
            }

			
		}else{
			 M()->startTrans();  
			 $flag = D( "Pay" ) -> bbAfterPay( $out_trade_no,$total_amount ) ;
			if ($flag) {  	
				$field = "a.order_code,a.goods_id,a.bbid,sum(a.nums) as nums,b.goods_id,b.id,b.goods_title,c.id,c.image" ;
	            $list = M( 'order_detail' ) -> field( $field )
				-> alias( 'a' )
				-> join( 'LEFT JOIN __BBOOKING__ b ON a.goods_id = b.goods_id AND a.bbid = b.id' )
				-> join( 'LEFT JOIN __GOODS__ c ON a.goods_id = c.id' )
				-> where( 'a.order_code=' . $out_trade_no )
				-> group( 'a.goods_id' )
				-> select( ) ;
				$data['money'] = $total_amount;
           	    $data['userId'] = $order_detail['user_id'];
           	    $data['rechargeSn'] = $trade_no;
           	    $data['mode'] = "zhifubao";
           	    $data['createDate'] = time();
           	    $data['more'] = "包场支付";
           	    $data['status'] = '1';
                M('recharge') -> add($data);
	            $this -> assign( "type" , 'bbooking' ) ;
	            M()->commit();
			}else{
				M()->rollback();
			}
		}
       		
        //dump($list);die;
		$count = count( $list ) ;
		$this -> assign( "userid" , UID ) ;
		$this -> assign( "count" , $count ) ;
		$this -> assign( "goods" , $list ) ;

		// // layout( 'Layout/noFoot' ) ;
	}
	$this -> display( ) ;
}

	/*
	 * 保存领奖地址
	 */
	function saveaddr( ) {
	
		$pid = I( 'post.pid' ) ;
	
		//读取用户的默认地址
		$defaddr = M( 'user_addr' ) -> where( 'status=1 and userId=' . UID )
		-> field( "name,addr,phone" )
		-> find( ) ;
		$result = M( 'order_detail' ) -> where( 'pid=' . $pid ) -> setField( $defaddr ) ;
		$data['id'] = $pid ;
		$data['flag'] = 0 ;
		$data['getDate'] = NOW_TIME ;
		$data['winUserId'] = UID ;
		// 发货地址表
		$data1['address'] = $defaddr['addr'] ;
		$data1['name'] = $defaddr['name'] ;
		$data1['phone'] = $defaddr['phone'] ;

		$data1['pid'] = $pid ;
		$data1['type'] = 1 ;
		$data1['status'] = 1 ;
	
		 M( 'express' ) -> add( $data1 ) ;
	
		 M( 'periods' ) -> save( $data ) ;
	
		$map = array(
				"pbz_id" => $pid,
				"type" => 1
		) ;
	
		//清除领奖超时的数据
		try{
			$msgs = M( "msg_pbz" ) -> where( $map ) -> getField( "msg_id" , true ) ;
			if( $msgs ){
				foreach( $msgs as $msg_id ){
					M( "msg_queue" ) -> delete( $msg_id ) ;
				}
				M( "msg_pbz" ) -> where( $map ) -> delete( ) ;
			}
		}catch(\Exception $e){
		}
	
		echo jsonEncode( $result ) ;
	
	}


	function saveaddr_room( ) {

		$roompid = I( 'post.roompid' ) ;

		$data['status'] = 4 ;

		$data['get_time'] = time( ) ;

		M( ) -> startTrans( ) ;

		try{
			$b = M( 'bbooking' ) -> where( "id=$roompid" ) -> save( $data ) ;

		$defaddr = M( 'user_addr' ) -> where( 'status=1 and userId=' . UID )
			-> find( ) ;

			$data1['address'] = $defaddr['addr'] ;
			$data1['name'] = $defaddr['name'] ;
			$data1['phone'] = $defaddr['phone'] ;
			$data1['remark'] = $defaddr['more'] ;
			$data1['get_time'] = time( ) ;
			$data1['pid'] = $roompid ;
			$data1['type'] = 2 ;
			$data1['status'] = 1 ;

			$b = M( 'express' ) -> add( $data1 ) ;

			M( ) -> commit( ) ;
		}catch(\Exception $e){
			M( ) -> rollback( ) ;
			$b = null ;
		}

		$map = array(
			"pbz_id" => $roompid,"type" => 2
		) ;

		//向后台发货人发送提醒消息
		M( "msg_queue" ) -> add( array(
			"type" => 1,"go_url" => C( "site_url" ) . U( "Admin/Express/index" ),"sended" => 0,"create_time" => time( ),"send_to" => C( 'consignor' ),"send_time" => time( ) + C( 'win_code_sleep' ) * 60,"content" => '有用户领奖了（发货编号为：' . $b . '），请尽快发货！'
		) ) ;

		//清除领奖超时的数据
		try{
			$msgs = M( "msg_pbz" ) -> where( $map ) -> getField( "msg_id" , true ) ;
			if( $msgs ){
				foreach( $msgs as $msg_id ){
					M( "msg_queue" ) -> delete( $msg_id ) ;
				}
				M( "msg_pbz" ) -> where( $map ) -> delete( ) ;
			}
		}catch(\Exception $e){
		}

		$returnMap['b'] = $b ;
		json( $returnMap ) ;

	}
	
	
	
	
	
	
	/*
	 * 确认收货
	 */
	function sh( ) {

		$pid = I( 'post.pid' , 0 , 'int' ) ;
		if( ! $pid ){
			return ;
		}

		$flag = M( 'periods' ) -> where( array(
			'id' => $pid,'winUserId' => UID
		) ) -> save( array(
			'flag' => 3
		) ) ;

		if( ! $flag ){
			json( array(
				'code' => 0,'msg' => '不存在此信息'
			) ) ;
		}

		M( 'express' ) -> where( array(
			'pid' => $pid,'type' => 1
		) ) -> save( array(
			'status' => 3
		) ) ;

		json( array(
			'code' => 1,'msg' => '收货成功'
		) ) ;

	}

	/*
	 * 微信充值模板
	 */
	function recharge( ) {

		$result = D( "user" ) -> field( 'balance' ) -> find( UID ) ;
		$this -> assign( 'balance' , $result['balance'] ) ;
		$this -> display( ) ;

	}

	/*
	 * 充值卡充值模板
	 */
	function recharge_card( ) {

		$result = D( "user" ) -> field( 'balance' ) -> find( UID ) ;
		$this -> assign( 'balance' , $result['balance'] ) ;
		$this -> display( ) ;

	}

	/*
	 * 查询充值卡不符合信息,并反馈给前台处理
	 */
	function GetCardMsg( ) {

		$cardid = I( 'post.cardid' ) ;
		$pass = I( 'post.pass' ) ;
		$field = 'payCardSize,overdueDate,payDate,status' ;
		$where['payCardNum'] = $cardid ;
		$where['pass'] = $pass ;

		$res = M( 'pay_card' ) -> field( $field )
			-> where( $where )
			-> find( ) ;

		$res['nowDate'] = time( ) ;
		json( $res ) ;

	}

	/*
	 * 充值到余额
	 */
	function CardToBalance( ) {

		$cardid = I( 'post.cardid' ) ;
		$pass = I( 'post.pass' ) ;
		$where['payCardNum'] = $cardid ;
		$where['pass'] = $pass ;
		$where['payDate'] = 0 ;
		$res = M( 'pay_card' ) -> field( 'payCardSize' )
			-> where( $where )
			-> find( ) ;

		$bflag = M( ) -> execute( 'update member set balance=balance+' . $res['paycardsize'] . ' where member_id=' . UID ) ;

		$gflag = M( ) -> execute( 'update member set grandRechange=grandRechange+' . $res['paycardsize'] . ' where member_id=' . UID ) ;
		$status['payDate'] = time( ) ;
		$res = M( 'pay_card' ) -> where( $where ) -> save( $status ) ;
		echo jsonEncode( $bflag + $gflag + $res ) ;

	}

	/**
	 * 个人资料编辑
	 *
	 * @access public
	 * @param
	 *        	void
	 * @return void
	 */
	public function editInfo( ) {

		$nickname = I( 'post.nickname' , 0 , 'trim' ) ;
		if( ! $nickname ) appError( '' ) ;
//dump($nickname);die;
		$_v = array(
			array(
				'nickname','',' 昵称已经存在！ ',0,'unique',1
			)
		) ;

		$User = D( 'user' ) ;
		if( ! $User -> validate( $_v ) -> create( array(
			'nickname' => $nickname
		) ) ){
			appError( $User -> getError( ) ) ;
		}

		$User -> where( "member_id=" . UID ) -> save( ) ;
       // dump($User);die;
		appSuccess( ) ;

	}

	public function updateImage( ) {

		if( D( 'user' ) -> updateImage( ) ){
			echo D( 'user' ) -> facePic ;
		}else{
			echo 0 ;
		}

	}

	/**
	 * 发布晒单信息
	 */
	public function addPeriodsShow( ) {

		if( ! IS_POST ){
			$this -> assign( "title" , "晒单" . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
			$this -> display( ) ;
			die( ) ;
		}

		$pid = I( 'get.pid' , 0 , 'int' ) ;
		$content = I( 'post.content' , '' ) ;
		$title = I( 'post.title' , '' ) ;

		if( $pid ){
			$where = array(
				'id' => $pid,'winUserId' => UID
			) ;

			$periods = M( 'periods' ) -> where( $where ) -> find( ) ;
			$goodsId = $periods['goodsid'] ;
			if( ! $periods ){
				$this -> error( '未找到当前用户中奖信息' ) ;
			}elseif( $periods['flag'] != 3 ){
				$this -> error( '请先确认收货' ) ;
			}

			$flag = M( 'periods_show' ) -> where( array(
				'userId' => UID,'pid' => $periods['id']
			) ) -> find( ) ;

			if( $flag ){
				$this -> error( '已经发布过晒单信息' ) ;
			}

			$insertdata = array(
				'userId' => UID,'goodsId' => $goodsId,'pid' => $periods['id'],'title' => $title,'content' => $content,'creatDate' => time( ),'status' => 1
			) ;

			$insert_result = M( 'periods_show' ) -> data( $insertdata ) -> add( ) ;

			if( $insert_result ){
				if( ! empty( $_FILES ) ){ // 存在 图片
					$insert_periods_show_detail = $real_pic_path = array() ;
					$PeriodsPic = $this -> uploadImage( ) ;

					if( $PeriodsPic ){
						foreach( $PeriodsPic as $value ){

							$real_pic_path[] = $value['savepath'] . $value['savename'] ;
							// 插入图片副表
							$insert_periods_show_detail[] = array(
								'psid' => $insert_result,'type' => 'img','content' => $value['savepath'] . $value['savename']
							) ;
						}

						M( 'Periods_show_detail' ) -> addAll( $insert_periods_show_detail ) ;

						// 更新图片字段
						$pic1 = $pic2 = $pic3 = '' ;

						$real_pic_path = array_slice( $real_pic_path , 0 , 3 ) ;
						if( count( $real_pic_path ) == 3 ) list ($pic1, $pic2, $pic3) = $real_pic_path ;
						if( count( $real_pic_path ) == 2 ) list ($pic1, $pic2) = $real_pic_path ;
						if( count( $real_pic_path ) == 1 ) list ($pic1) = $real_pic_path ;

						$updatedata = array(
							'pic1' => $pic1,'pic2' => $pic2,'pic3' => $pic3
						) ;

						M( 'periods_show' ) -> where( 'id=' . $insert_result )
							-> data( $updatedata )
							-> save( ) ;
					}
				}
			}else{
				$this -> error( '插入错误' ) ;
			}
		}else{
			$this -> error( '参数错误' ) ;
		}
		M( 'periods' ) -> where( $where ) -> save( array(
			"flag" => 4
		) ) ;
		$this -> success( '发表成功!' , U( 'myaward' ) ) ;

	}

	/**
	 * 图片上传 多图上传
	 */
	private function uploadImage( ) {

		if( empty( $_FILES ) ) return false ;

		$upload = new \Think\Upload( ) ;
		$upload -> maxSize = 1024 * 1204 * 100 ;
		$upload -> exts = array(
			'jpg','gif','png','jpeg'
		) ; // 设置附件上传类
		$upload -> rootPath = '.' ;
		$upload -> savePath = dirname(__ROOT__)."/yykq/upload/image/" ;
		$upload -> saveName = array(
			'getOrderNumber',24
		) ;

		// 上传文件
		$info = $upload -> upload( ) ;
		if( ! $info ){
			return false ;
		}else{
			return $info ;
		}

	}

}
vendor('alipay.wappay.service.AlipayTradeService');

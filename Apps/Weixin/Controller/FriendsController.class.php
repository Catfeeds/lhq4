<?php

namespace Weixin\Controller ;

use Think\Controller ;

// 用户地址修改
class FriendsController extends IsLoginController {
	
	//生成快速加好友的二维码
	function myQR( ) {

		$data = C( "site_url" ) . U( "Weixin/Friends/faFri" ) . "&uid=" . UID . "&mi=" . md5( sha1( UID ) . date( "Ymd" , time( ) ) ) ;
		
		$QR = new QRcodeController( ) ;
		$QR -> QRcode( "$data" ) ;
	
	}
	
	//通过扫描二维码加好友
	function faFri( ) {

		$uid = I( "get.uid" , 0 , "int" ) ;
        //dump($uid);
		$mi = I( "get.mi" ) ;
		
		if( $uid == UID ){
			$this -> error( "不能添加自己为好友" , U( "Index/index" ) ) ;
		}
		
		$uinfo = D( "user" ) -> find( $uid ) ;
		if( ! $uinfo ){
			$this -> error( "二维码出错,请重新点击" , U( "Index/index" ) ) ;
		}
		
		$myinfo =D( "user" ) -> find( UID ) ;
		
		//校验二维码的有效性
		if( $mi != md5( sha1( $uid ) . date( "Ymd" , time( ) ) ) ){
			$this -> error( "二维码已失效,请重新点击" , U( "Index/index" ) ) ;
		}
		
		M( ) -> startTrans( ) ;
		try{
			//添加对方为自己的好友
			$data = array(
				"friendsId" => $uid,
				"partiesId" => UID,
				"anchorLink" => $this -> getFirstCharter( $uinfo['nickname'] ),
				"remarks" => 1,
				"addDate" => time( ),
				"isSee" => 0,
				"flag" => 1,
				"is_msg" => 1
			) ;
			M( 'user_friends' ) -> add( $data ) ;
			
			//添加自己为对方的好友
			$data["friendsId"] = UID ;
			$data["partiesId"] = $uid ;
			$data["anchorLink"] = $this -> getFirstCharter( $myinfo['nickname'] ) ;
			M( 'user_friends' ) -> add( $data ) ;
		}catch(\Exception $e){
			M( ) -> rollback( ) ;
			$this -> error( "好友添加失败,您可能已经是好友" , U( "Friends/my_friends" ) ) ;
		}
		
		M( ) -> commit( ) ;
		
		$this -> success( "好友添加成功" , U( "Friends/my_friends" ) ) ;
		exit( ) ;
	
	}

	function seemsg( ) {

		$fid = I( 'get.fid' ) ;
		$ufmodel = M( 'user_friends' ) -> find( $fid ) ;
		unset( $ufmodel['issee'] ) ;
		$ufmodel['isSee'] = 0 ;
		M( 'user_friends' ) -> save( $ufmodel ) ;
	
	}

	/**
	 * 同意邀请并添加好友
	 * @return type
	 */
	function friendmsg_add( ) {

		if( IS_AJAX ){
			$fid = I( 'post.fid' ) ;
           // var_dump($fid);die;
			if( $fid ){
				$ufmodel = M( 'user_friends' ) -> find( $fid ) ;
               // var_dump($ufmodel);
				if( ! $ufmodel ){
					//$this->error("不存在的数据...");
					json( array(
						'code' => 0
					) ) ;
					return ;
				}
				unset( $ufmodel['issee'] ) ;
				$ufmodel['isSee'] = 1 ;
				$ufmodel['flag'] = 1 ;
				M( 'user_friends' ) -> save( $ufmodel ) ;
				$temp = D( 'user' ) -> where( "member_id=" . $ufmodel['partiesid'] ) -> getField( 'nickname' ) ;
				if( ! $temp ){
					M( 'user_friends' ) -> delete( $fid ) ;
					// $this->error("不存在的数据...");
					json( array(
						'code' => 0
					) ) ;
					return ;
				}
				$prid = $ufmodel['partiesid'] ;
				$frid = $ufmodel['friendsid'] ;
				unset( $ufmodel ) ;
				$ufmodel['friendsId'] = $prid ;
				$ufmodel['partiesId'] = $frid ;
				$ufmodel['anchorLink'] = $this -> getFirstCharter( mb_substr( $temp , 0 , 1 , 'utf-8' ) ) ;
				$ufmodel['addDate'] = time( ) ;
				$ufmodel['isSee'] = 1 ;
				$ufmodel['flag'] = 1 ;
				M( 'user_friends' ) -> add( $ufmodel ) ;
				json( array(
					'code' => 1
				) ) ;
				return ;
			}else{
				//$this->error("不存在的数据...");
				

				json( array(
					'code' => 0
				) ) ;
				return ;
			}
		}
		$id = I( 'get.id' ) ;
		$ufmodel = M( 'user_friends' ) -> find( $id ) ;
		unset( $ufmodel['issee'] ) ;
		$ufmodel['isSee'] = 0 ;
		M( 'user_friends' ) -> save( $ufmodel ) ;
		$res = M( 'user_friends' ) -> alias( 'uf' ) 
			-> join( 'member ur on uf.partiesId=ur.member_id' ) 
			-> field( "uf.id,nickname,phone,pic,sex,DATE_FORMAT(birthday,'%Y-%m-%d') as birthday,addr" ) 
			-> where( "uf.id=" . $id ) 
			-> find( ) ;
		$this -> assign( 'res' , $res ) ;
		$this -> assign( "title" , "添加好友记录" . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		$this -> display( ) ;
	
	}

	/**
	 * 添加好友记录
	 */
	function friendsmsg( ) {

		if( IS_AJAX ){
			$page = I( 'post.page' ) ;
			$page -- ;
			$row = 10 ;
			$res = M( 'user_friends' ) -> alias( 'uf' ) 
				-> join( 'member ur on uf.partiesId=ur.member_id' )
				-> field( "uf.id,FROM_UNIXTIME(uf.addDate) as adddate,
uf.isSee,uf.flag,ur.nickname,ur.sex" )
				-> where( "uf.friendsId=" . UID ) 
				-> order( "uf.addDate desc" ) 
				-> limit( $page * $row , $row ) 
				-> select( ) ;
			
			//echo M()->getLastSql();
			json( $res ) ;
		}
		
		//dump($res);
		//$this->assign('res',$res);
		$this -> assign( "title" , "添加好友记录" . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		$this -> display( ) ;
	
	}

	/**
	 * 好友信息及修改备注
	 * @return type
	 */
	public function friend_info( ) {

		if( IS_AJAX ){
			$ufid = I( 'post.ufid' ) ; //用户好友表主键
            //dump($ufid);die;
			$remarks = I( 'post.bzm' ) ;
			M( 'user_friends' ) -> save( array(
				'id' => $ufid,
				'remarks' => $remarks
			) ) ;
			//echo M()->getLastSql();
			json( array(
				'code' => 1
			) ) ;
			return ;
		}
		$uid = I( 'get.id' , 0 , 'int' ) ;
		
		$res = M( 'user_friends' ) -> alias( 'uf' ) 
			-> join( 'member ur on uf.friendsId=ur.member_id' )
			-> field( "uf.id,ur.nickname,ur.pic,ur.sex,DATE_FORMAT(FROM_UNIXTIME(birthday),'%Y-%m-%d') as birthday,addr,remarks" )
			-> where( "uf.Id=" . $uid . " and flag=1" ) 
			-> order( "uf.anchorLink asc" ) 
			-> find( ) ;
		//echo M()->getLastSql();
		//return;
		if( ! $res ){
			$this -> error( "不存在该好友..." ) ;
			//json(array('code'=>0));
			return ;
		}
		$this -> assign( "res" , $res ) ;
		$this -> assign( "title" , "好友资料" . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		$this -> display( ) ;
	
	}

	/**
	 * 好友查找
	 */
	function friends_add( ) {

		if( IS_AJAX ){
			$nickname = I( "post.keywords" ) ;
			$res = D( 'user' ) -> field( "member_id,pic,DATE_FORMAT(birthday,'%Y-%m-%d') as birthday,nickname,addr" )
				-> where( "( nickname LIKE '%$nickname%' OR phone LIKE '%$nickname%'  ) AND member_id<>" . UID ) 
				-> limit( 0 , 100 ) 
				-> select( ) ;
			// 			dump( $res ) ;
			// 			exit( ) ;
			json( $res ) ;
		}
		$this -> assign( "title" , "查找好友" . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		$this -> display( ) ;
	
	}

	/**
	 * 好友添加
	 * @return type
	 */
	function friend_info_add( ) {

		if( IS_AJAX ){
			$id = I( "post.id" ) ;
			$temp = M( 'user_friends' ) -> where( "friendsId=" . $id . " and partiesId=" . UID ) -> find( ) ;
			
			if( $temp ){
				if( $temp['flag'] == 1 ){
					//$this->error("该用户已和您成为好友,请不要重复添加...");
					json( array(
						'code' => 2
					) ) ;
				}
				unset( $temp['issee'] ) ;
				$temp['isSee'] = 1 ;
				$temp['flag'] = 0 ;
				$temp['addDate'] = time( ) ;
				M( 'user_friends' ) -> save( $temp ) ;
				
				json( array(
					'code' => 3
				) ) ;
				return ;
			}
			$res = D( 'user' ) -> field( "member_id,nickname" ) -> find( $id ) ;
			if( ! $res ){
				json( array(
					'code' => 0
				) ) ;
				return ;
			}
			// echo M()->getLastSql();
			$data['friendsId'] = $id ;
			$data['partiesId'] = UID ;
			$data['anchorLink'] = $this -> getFirstCharter( $res['nickname'] ) ;
			$data['remarks'] = '' ;
			$data['addDate'] = time( ) ;
			$data['isSee'] = 0 ;
			$data['flag'] = 0 ;
			$res = M( 'user_friends' ) -> add( $data ) ;
			
			if( $res ){
				json( array(
					'code' => 1
				) ) ;
				return ;
			}else{
				//$this->error("添加失败,请稍候重试...");
				json( array(
					'code' => 0
				) ) ;
				return ;
			}
		}
		$id = I( 'get.id' ) ;
		$res = D( 'user' ) -> field( "member_id,pic,phone,sex, birthday,nickname,addr" ) -> find( $id ) ;
		// $res['birthday'] = substr("$res['birthday']",0,9);
		$birthday = $res['birthday'];
		$res['birthday'] = substr("$birthday",0,9);
		$this -> assign( 'res' , $res ) ;
		$this -> assign( "title" , "好友添加" . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		$this -> display( ) ;
	
	}

	/**
	 * 我的好友列表
	 */
	function my_friends( ) {

		$res = M( 'user_friends' ) -> alias( 'uf' ) 
			-> join( 'member ur on uf.friendsId=ur.member_id' )
			-> field( "uf.id,nickname,phone,remarks,anchorLink,pic,sex" )
			-> where( "uf.partiesId=" . UID . " and flag=1" ) 
			-> order( "uf.anchorLink asc" ) 
			-> select( ) ;
		
		/* 同意好友请求的加标志位  begin */
		$data['is_msg'] = 1 ;
		$b = M( 'user_friends' ) -> where( 'flag = 1 and is_msg = 0 and partiesId=' . UID ) -> save( $data ) ;
		/* 同意好友请求的加标志位 end */
		if(($res['pic'] == NULL ||$res['pic'] == '')&&$res['sex']=='1'){
		    $res['pic']='../assets/img/1654509913107329972.jpg';
        }elseif(($res['pic'] == NULL ||$res['pic'] == '')&&$res['sex']=='2'){
		    $res['pic']='../assets/img/a686c9177f3e6709d16cd4d23ac79f3df8dc55aa.jpg';
        }
		$this -> assign( 'res' , $res ) ;
		$this -> assign( "title" , "我的好友" . C( 'site_title_separator' ) . C( 'site_title' ) ) ;
		$this -> display( ) ;
	
	}

	/**
	 * 删除好友
	 * @return type
	 */
	function delfriend( ) {

		if( IS_AJAX ){
			$postid = I( 'post.fid' ) ;
			
			$res = M( 'user_friends' ) -> find( $postid ) ;
			
			if( ! $res ){
				json( array(
					'code' => 0
				) ) ;
				return ;
			}
			$fid = $res['friendsid'] ;
			$uid = $res['partiesid'] ;
			if( $fid && $uid ){
				M( 'user_friends' ) -> delete( $postid ) ;
				//echo M()->getLastSql();
				M( 'user_friends' ) -> where( 'friendsid=' . $uid . ' and partiesid=' . $fid ) -> delete( ) ;
				// echo M()->getLastSql();
				//return;
				json( array(
					'code' => 1
				) ) ;
			}
		}
	
	}

	/**
	 * 锚链接生成
	 * @param type $str
	 * @return string
	 */
	private function getFirstCharter( $str ) {

		$fchar = ord( $str{0} ) ;
		
		if( $fchar >= ord( 'A' ) && $fchar <= ord( 'z' ) ) return strtoupper( $str{0} ) ;
		
		$s1 = iconv( 'UTF-8' , 'GB2312//IGNORE' , $str ) ;
		
		$s2 = iconv( 'GB2312' , 'UTF-8' , $s1 ) ;
		
		$s = $s2 == $str ? $s1 : $str ;
		
		$asc = ord( $s{0} ) * 256 + ord( $s{1} ) - 65536 ;
		
		if( $asc >= - 20319 && $asc <= - 20284 ) return 'A' ;
		
		if( $asc >= - 20283 && $asc <= - 19776 ) return 'B' ;
		
		if( $asc >= - 19775 && $asc <= - 19219 ) return 'C' ;
		
		if( $asc >= - 19218 && $asc <= - 18711 ) return 'D' ;
		
		if( $asc >= - 18710 && $asc <= - 18527 ) return 'E' ;
		
		if( $asc >= - 18526 && $asc <= - 18240 ) return 'F' ;
		
		if( $asc >= - 18239 && $asc <= - 17923 ) return 'G' ;
		
		if( $asc >= - 17922 && $asc <= - 17418 ) return 'H' ;
		
		if( $asc >= - 17417 && $asc <= - 16475 ) return 'J' ;
		
		if( $asc >= - 16474 && $asc <= - 16213 ) return 'K' ;
		
		if( $asc >= - 16212 && $asc <= - 15641 ) return 'L' ;
		
		if( $asc >= - 15640 && $asc <= - 15166 ) return 'M' ;
		
		if( $asc >= - 15165 && $asc <= - 14923 ) return 'N' ;
		
		if( $asc >= - 14922 && $asc <= - 14915 ) return 'O' ;
		
		if( $asc >= - 14914 && $asc <= - 14631 ) return 'P' ;
		
		if( $asc >= - 14630 && $asc <= - 14150 ) return 'Q' ;
		
		if( $asc >= - 14149 && $asc <= - 14091 ) return 'R' ;
		
		if( $asc >= - 14090 && $asc <= - 13319 ) return 'S' ;
		
		if( $asc >= - 13318 && $asc <= - 12839 ) return 'T' ;
		
		if( $asc >= - 12838 && $asc <= - 12557 ) return 'W' ;
		
		if( $asc >= - 12556 && $asc <= - 11848 ) return 'X' ;
		
		if( $asc >= - 11847 && $asc <= - 11056 ) return 'Y' ;
		
		if( $asc >= - 11055 && $asc <= - 10247 ) return 'Z' ;
		
		return "#" ;
	
	}


}

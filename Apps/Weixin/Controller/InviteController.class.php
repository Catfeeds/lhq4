<?php

namespace Weixin\Controller;

use Think\Controller;
use Common\Common\GetSsj;
class InviteController extends IsLoginController {
	function test(){
//		M()->query("ALTER TABLE `yytb_periods` ADD COLUMN `constantly`  int(6) UNSIGNED NULL DEFAULT 0 COMMENT '时时彩开奖码' AFTER `ssc_code`;");
//		M()->query(" ALTER TABLE `yytb_bbooking`ADD COLUMN `constantly`  int(6) UNSIGNED NULL DEFAULT 0 COMMENT '时时彩开奖码' AFTER `addr_id`;");
//		echo 111;
//		$result =  GetSsj::winning(1);
//		dump($result);
//		echo _time().'<br />';
//echo _date('Hisx',_time());
		$data = GetSsj::getNum();
//		echo $date = strtotime($data['time_draw']);
//		$tmp_date =  date('Hi',strtotime($data['time_draw']));
//		if($tmp_date >= 1000 && $tmp_date <= 2200){
//			$var = 600;
//		}elseif($tmp_date < 155 || ($tmp_date <= 2359 && $tmp_date > 2159)){
//			$var = 300;
//		}else{
//			$var = false;
//		}
////		echo date('Hi',time())."<br />";
//		$next_date = strtotime($data['time_draw'])+10*60;
//		echo date('Hi',$next_date);
		dump($data);
		die;
	}
	/*
	 * 邀请分享
	 */
	function share() {
		
		$this->assign ( 'title', "邀请分享" . C ( 'site_title_separator' ) . C ( 'site_title' ) );
		$sql = "select count(`id`) as count from yytb_user_detail ud WHERE `name`='ZC' AND ud.rpUserId=" . UID;
		$comsum = M ()->query ( $sql );
		$comYuE = D ( 'user' )->field ( 'comsum' )->find ( UID );
		$this->assign ( 'count', $comsum [0] ['count'] );
		$this->assign ( 'comye', $comYuE ['comsum'] );
		$this->display ();

	}

	/*
	 * 显示邀请记录模板
	 */
	function invite_record() {

		$this->assign ( 'title', "邀请记录" . C ( 'site_title_separator' ) . C ( 'site_title' ) );
		$this->display ();

	}

	/*
	 * 异步获取邀请记录
	 */
	function getInvRec() {

		$page = I ( 'post.page', 1, 'int' );
		$page --;
		$row = 10;
		$skip = $page * $row;

		$list = D ( 'user' )->alias ( 'ur' )->join ( 'JOIN __USER_DETAIL__ ud ON ur.member_id=ud.userId' )->field ( "phone,DATE(FROM_UNIXTIME(ud.creatDate)) as creatDate,ud.rpUserId,comval" )->where ( "ud.rpUserId=" . UID . ' and ud.name="ZC"' )->order ( 'ud.creatDate desc' )->limit ( $skip, $row )->select ();
		json ( $list );

	}

	/*
	 * 显示邀请记录模板
	 */
	function commission() {

		$this->assign ( 'title', "佣金明细" . C ( 'site_title_separator' ) . C ( 'site_title' ) );
		$sql = "SELECT SUM(comval) as sum FROM yytb_user_detail WHERE rpUserId=" . UID . " AND name='XF' ";
		$comsum = M ()->query ( $sql );
		$comYuE = D ( 'user' )->field ( 'comsum' )->find ( UID );
		$this->assign ( 'comye', $comYuE ['comsum'] );
		$this->assign ( 'sum', $comsum [0] ['sum'] );
		$this->display ();

	}

	/*
	 * 异步获取邀请记录
	 */
	function getcommis() {

		$page = I ( 'post.page', 1, 'int' );
		$page --;
		$row = 10;
		$skip = $page * $row;

		$list = D ( 'user' )->alias ( 'ur' )->join ( 'JOIN __USER_DETAIL__ ud ON ur.member_id=ud.userId' )->field ( "phone,DATE(FROM_UNIXTIME(ud.creatDate)) as creatDate,ud.rpUserId,consume,comval" )->where ( "ud.rpUserId=" . UID . " and name='XF'" )->order ( 'ud.creatDate desc' )->limit ( $skip, $row )->select ();

		json ( $list );

	}

	/*
	 * 一键转入余额
	 */
	function onekeybal() {

		$sql = "update member ur set ur.balance=ur.balance+round(ur.comsum/" . C ( 'COM_BL' ) . ",2),ur.comsum=0.00 where member_id=" . UID;
		$list = M ()->execute ( $sql );
		echo jsonEncode ( $list );

	}

}

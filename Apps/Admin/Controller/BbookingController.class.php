<?php

namespace Admin\Controller;

/**
 * 包场管理
 * @date 2016年1月22日下午1:21:16
 * @author ilanguo_cqwang
 *
 */
class BbookingController extends AdminController {

	function index() { // 指定包场信息

		$tC = C("DB_PREFIX") . "cheat_bbooking";
		$tB = C("DB_PREFIX") . "bbooking";
		//$tU = C("DB_PREFIX") . "user";
        $tU = C("DB_PREFIX") . "user";
		$tG = C("DB_PREFIX") . "goods";
		$list = M("cheat_bbooking")->field("$tC.id,$tC.bbid,$tC.switch,$tU.nickName,$tU.phone,$tG.title")
			->join("LEFT JOIN $tB ON $tB.id=$tC.bbid ")
			->join("LEFT JOIN $tU ON $tU.id=$tC.winner ")
			->join("LEFT JOIN $tG ON $tB.goods_id=$tG.id ")
			->where(array(
			"$tB.status" => 1
		))
			->select();
// 		dump($list);
// 		exit();
		$this->assign('list', $list);
		$this->assign('title', "已指定中奖人的包场");
		$this->display();
	
	}

	function addBCheat() { // 包场指定中奖人

		if (IS_POST) {
			
			$uid = I("post.uid", '', "int");
			$bbid = I("post.bbid", '', "int");
			
			if (! ($uid && $bbid)) {
				$this->error("请指定所有参数");
			}
			
			try {
				
				if (M("cheat_bbooking")->add(array(
					"winner" => $uid,"bbid" => $bbid
				))) {
					$this->success("添加成功");
				} else {
					$this->error("添加失败");
				}
			} catch (\Exception $e) {
				$this->error("请勿重复添加");
			}
		}
		
		$this->assign('title', "包场指定中奖人");
		$this->display();
	
	}

	function editBCheat() { // 包场中奖人修改

		if (IS_POST) {}
		
		$this->assign('title', "包场中奖人修改");
		$this->display();
	
	}

	function delBCheat() { // 删除包场中奖人

		$id = I("post.id", '', "int");
		if (! $id) {
			$this->error("参数有误");
		}
		$flag = M("cheat_bbooking")->where(array(
			"id" => $id
		))->delete();
		
		if (! $flag) {
			$this->error("删除失败");
		}
		
		$this->success("删除成功");
		exit();
	
	}

	function getBbs() { // Ajax 包场中奖人列表

		if (IS_AJAX) {
			$uid = I("post.uid", '', "int");
			
			// 包场进行中列表
			$field2 = "a.id,a.creater,a.goods_title,u.nickName";
			$where2 = array(
				'a.status' => 1,'a.creater | b.tpin' => $uid
			);
			
			$bbooking = M('bbooking')->alias('a')->distinct ( true )
				->join('LEFT JOIN __BBOOKING_TPIN__ b ON a.id = b.bbid')
				->join('LEFT JOIN __USER__ u ON a.creater = u.id')
				->where($where2)
				->field($field2)
				//->group('goods_id')
				->select();
			
			$this->ajaxReturn($bbooking);
		}
	
	}
	
	function cheatSwitch( ) { // 开关包场中奖人
	
		if( IS_AJAX ){
			$cheatSwitch = I( "post.cheatSwitch" , '' , "int" ) ;
			$cheatId = I( "post.cheatId" , '' , "int" ) ;
				
			$flag = M( ) -> execute( "UPDATE `__PREFIX__cheat_bbooking` SET `switch`=$cheatSwitch WHERE `id`=$cheatId" ) ;
			//die(M()->getLastSql());
			if( ! $flag ){
				$this -> error( "操作失败" ) ;
			}
			$this -> success( "操作成功" ) ;
			exit( ) ;
		}
	
	}


}
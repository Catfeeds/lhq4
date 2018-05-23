<?php
namespace Weixin\Controller ;

use Think\Controller ;


class AjaxGetController extends IsLoginController {

	function getExpress(){
		$id = I('id/d', 0);
		$pid = I('pid/d', 0);
		$type = I('type/d', 0);

		// where 条件设置
		if($id){
			$where['id'] = $id;
		}elseif($pid && $type){
			$where['pid'] = $pid;
			$where['type'] = $type;
		}else{
			json(array('code' => 0));
		}

		$info = M('express')
			->field('id, pid, express_id, express_no as exp_no')
			->where($where)
			->find();

		switch($info['type']) {
			case 1:
				$db = 'periods';
				$_z = 'winUserId';
				break;
			case 2:
				$_db = 'bbooking';
				$_z = 'win_user_id';
				break;
			case 3:
				$_db = 'periods_zero';
				$_z = 'winUserId';
				break;
			default:
				$db = 'periods';
				$_z = 'winUserId';
				break;
		}

		// 判断是否为当前用户
		if(!M($db)->where(array('id' => $info['pid'], $_z => UID))->find()){
			json(array(
					'code' => 0,
					'msg' => '无权查看他人信息',
					'info' => $info,
			));
		}

		if($info){
			$com = M('express_com')
				->field('key, name')
				->find($info['express_id']);

			if($com){
				$info['exp_key'] = $com['key'];
				$info['exp_name'] = $com['name'];
			}

			$info = array_merge(array('code' => 1), $info);
		}else{
			$info = array('code' => 0);
		}

		json($info);
	}

}
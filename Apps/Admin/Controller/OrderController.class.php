<?php

namespace Admin\Controller;

use Think\Controller;

class OrderController extends AdminController {

	/**
	 * 订单列表
	 */
	public function index() { // 订单管理首页
		if(IS_POST){
			$sql = array();
			$page = I('page', 1, 'int');
			$row =  I('rows', 10, 'int');
			$type =  I('type/d', 0);
			$order_status = I('status/d', 0);
			$is_robot =  I('is_robot', -1, 'int');
			

			$page = $page ? $page : 1;

			$keyword =  I('keyword', '', 'trim');
			if(!empty($keyword)){
				$where['od.order_code | u.nickname | u.phone'] = array('like', "%$keyword%");
			}

			if($type >= 0){
				$where['od.order_type'] = $type;
			}
			
			if($is_robot >= 0){
				$where['u.is_robot'] = $is_robot;
			}

			if($order_status >= 0){
				$where['od.order_status'] = $order_status;
			}

			$date = I('date/s');
			if(!empty($date)){
				list($start, $end) = explode(' - ', $date);
				if($start && $end){
					$start = strtotime("$start 0:00:00");
					$end = strtotime("$end 23:59:59");
					$where['od.creat_date']  = array('between', array($start, $end));
				}
			}
			if(I('totalVal')){
				$total = M('order')
					->field('sum(od.cost) as total, count(od.id) as count, count(distinct od.user_id) as member')
					->alias('od')
					->join('LEFT JOIN member as u ON od.user_id = u.member_id')
					->where($where)
					->find();
				json($total);
				die;
			}

			$list = M('order')
				->field('od.*, u.nickname, u.phone')
				->alias('od')
				->join('LEFT JOIN member as u ON od.user_id = u.member_id')
				->where($where)
				->page($page, $row)
				->order('id desc')
				->select()
			;

			$ids = array_unique(array_map(function($v){return $v['user_id'];}, $list));

			$sql[] = M()->_sql(); // DEBUG SQL

			$count = M('order')
				->alias('od')
				->join('LEFT JOIN member as u ON od.user_id = u.member_id')
				->where($where)
				->count()
			;

			$data = array(
					'count' => count($goods),
					'_sql' => $sql,
					'ids' => $ids,
					'total' => $count,
					'page' => $page,
					'row' => $row,
					'list' => $list,
			);

			admJson($data);
		}

		if(I('export')){
			$this->export();
		}

		$this->assign('title', '订单列表');
		$this->display();
	}



	function export(){
		$type =  I('type/d', 0);
		$order_status = I('status/d', 0);
		$keyword =  I('keyword', '', 'trim');
		if(!empty($keyword)){
			$where['od.order_code | u.nickname | u.phone'] = array('like', "%$keyword%");
		}

		if($type >= 0){
			$where['od.order_type'] = $type;
		}

		if($order_status >= 0){
			$where['od.order_status'] = $order_status;
		}

		$date = I('date/s');
		if(!empty($date)){
			list($start, $end) = explode(' - ', $date);
			if($start && $end){
				$start = strtotime("$start 0:00:00");
				$end = strtotime("$end 23:59:59");
				$where['od.creat_date']  = array('between', array($start, $end));
			}
		}

		$list = M('order')
			->field('od.*, u.nickname, u.phone')
			->alias('od')
			->join('LEFT JOIN member as u ON od.user_id = u.member_id')
			->where($where)
			->limit(10000)
			->order('id desc')
			->select();

		$type = array('云购', '充值', '', '包场');
		$pay_type = array('balance' => '余额', 'weixin' => '微信', 'app1' => 'APP');
		$status = array('失败', '成功');


		header("Content-Type: application/force-download");
		header("Content-Disposition: attachment; filename=\"". date('YmdHis') .".csv\"");

		echo iconv('utf-8', 'gb2312', '"ID","订单号","用户昵称","手机号","时间","订单类型","支付类型","支付状态"');
		echo "\r\n";
		foreach($list as $v){
			echo '"';
			echo iconv('utf-8', 'gb2312', implode('","', array(
					$v['id'],
					"\t". $v['order_code'],
					$v['nickname'],
					"\t". $v['phone'],
					"\t". date('Y/m/d H:i:s', $v['creat_date']),
					@$type[$v['order_type']],
					isset($pay_type[$v['pay_method']]) ? $pay_type[$v['pay_method']] : 'app',
					$status[$v['order_status']],
			)));
			echo "\"\r\n";
		}

		die;
	}


	/**
	 * 详情显示
	 */
	function showInfo(){ // 订单详情显示
		$id = I('id', 0, 'int');

		// $info = M('')->
		$info = M('order')
			->alias('od')
			->field('od.*, u.nickname, u.phone')
			->join('LEFT JOIN member as u ON od.user_id = u.member_id')
			->where('od.id = '. $id)
			->find()
		;

		$sql[] = M()->_sql();

		if($info){
				$oeders = M('order_detail')
					->field('goods_id, title, price, nums, qishu')
					->where(array(
							'order_code' => $info['order_code']
					))
					->select();

				$info['goods'] = $oeders;
		}


		$this->assign('types', array(
				'云购',
				'充值',
				'',
				'包场',
		));

		$payTypes = array(
				'weixin' => '微信支付',
				'weixin_app' => '微信APP',
				'weixin_pc' => '电脑端微信支付',
				'balance' => '余额支付',
				'app1' => '手机 APP 支付',
		);

		$this->assign('payTypes', $payTypes);


		layout("inc/tpl.min");
		$this->assign('sql', $sql);
		$this->assign('info', $info);
		$this->display();
	}

}







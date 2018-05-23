<?php

namespace Admin\Controller;

use Think\Controller;

class KaiController extends AdminController {

	/**
	 * 中奖列表
	 */
	public function index() { // 中奖管理
		if(IS_POST){
			$data = $this->list_1();

			admJson($data);
		}

		$this->assign('title', '中奖信息');
		$this->display();
	}



	/**
	 * 普通商品
	 */
	private function list_1(){
		$sql = array();
		$page = I('page', 1, 'int');
		$row =  I('rows', 10, 'int');
		$type =  I('type/d', 0);
		// $status = I('status/d', 0);

		$page = $page ? $page : 1;

		$keyword =  I('keyword', '', 'trim');

		$where = array();
		if(!empty($keyword)){
			$where['ps.title|u.nickName|u.phone'] = array('like', "%$keyword%");
		}

		$date = I('date/s');
		if(!empty($date)){
			list($start, $end) = explode(' - ', $date);
			if($start && $end){
				$start = strtotime("$start 0:00:00");
				$end = strtotime("$end 23:59:59");
				$where['ps.discloseDate']  = array('between', array($start, $end));
			}
		}
		$where[] = 'ps.status in (1, 2)';

		$list = M('periods')
			->alias('ps')
			->field('ps.*, u.nickname, u.phone')
			->join('LEFT JOIN member as u ON ps.winUserId = u.member_id')
			->where($where)
			->page($page, $row)
			->order('id desc')
			->select()
		;

		$count = M('periods')
			->alias('ps')
			->join('LEFT JOIN member as u ON ps.winUserId = u.member_id')
			->where($where)
			->count();

		$data = array(
				'count' => count($goods),
				'_sql' => $sql,
				'ids' => $ids,
				'total' => $count,
				'page' => $page,
				'row' => $row,
				'list' => $list,
		);

		return $data;
	}



	/**
	 * 详情显示
	 */
	function showInfo(){ // 中奖详情显示
		$id = I('id', 0, 'int');


		layout("inc/tpl.min");
		$this->assign('info', $info);
		$this->display();
	}
	
	/*
	 * 0元购的中奖纪录
	 */
	function zero(){ // 0元购的中奖纪录
		
		if(IS_POST){	
			$sql = array();
			$page = I('page', 1, 'int');
			$row =  I('rows', 10, 'int');
			$type =  I('type/d', 0);
			// $status = I('status/d', 0);
			
			$page = $page ? $page : 1;
			
			$keyword =  I('keyword', '', 'trim');
			if(!empty($keyword)){
				$where['t.title'] = array('like', "%$keyword%");
			}
			
			$where['t.status'] = 2;

			$date = I('date/s');
			if(!empty($date)){
				list($start, $end) = explode(' - ', $date);
				if($start && $end){
					$start = strtotime("$start 0:00:00");
					$end = strtotime("$end 23:59:59");
					$where['t.discloseDate']  = array('between', array($start, $end));
				}
			}

			$list = M('periods_zero')
				->alias('t')
				->field('t.id, t.goodsId, t.qishu, t.title, t.creatDate, t.discloseDate, t.winningCode, t.winUserId, t.flag, u.nickName, u.addr, u.phone')
				->join('left join __USER__ u on u.id = t.winUserId')
				->where($where)
				->page($page, $row)
				->order('discloseDate desc')
				->select();
			
			$count = M('periods_zero')
				->where($where)
				->getField('count(*)');
			
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
		$this->assign('title', '0元购中奖纪录');
		$this->display('index');
		
	}

	
	/*
	 * 包场的中奖纪录
	 */
	function room(){ // 包场的中奖纪录
		if(IS_POST){
			$sql = array();
			$page = I('page', 1, 'int');
			$row =  I('rows', 10, 'int');
			$type =  I('type/d', 0);
			// $status = I('status/d', 0);
				
			$page = $page ? $page : 1;
				
			$keyword =  I('keyword', '', 'trim');
			
			$where['t.status'] = array('exp', ' >= 3');
			if(!empty($keyword)){
				$where['t.goods_title'] = array('like', "%$keyword%");
			}

			$date = I('date/s');
			if(!empty($date)){
				list($start, $end) = explode(' - ', $date);
				if($start && $end){
					$start = strtotime("$start 0:00:00");
					$end = strtotime("$end 23:59:59");
					$where['t.lottery_time']  = array('between', array($start, $end));
				}
			}
	
			$list = M('bbooking')
				->alias('t')
				->field('t.id, t.creater, t.goods_id, t.goods_title as title, t.create_time, t.lottery_time, t.lottery_code as winningCode, u.nickName, u.addr, u.phone')
				->where($where)
				->join('left join __BBOOKING_DETAIL__ d on d.bbid = t.id and t.lottery_code = d.bbcode')
				->join('left join member u on u.member_id = d.participator')
				->page($page, $row)
				->select();
			
			foreach ($list as $key => $v){
				$creater = $v['creater'];
				
				$name = D('user')
					->where("member_id = $creater")
					->getField('nickName');
				
				$cphone = D('user')
					->where("member_id = $creater")
					->getField('phone');	
				
				$list[$key]['name'] = $name;
				$list[$key]['cphone'] = $cphone;
				
				$list[$key]['create_time'] = date('y-m-d h:i:s', $v['create_time']);
			}	
			
		//	echo M()->getLastSql();
		//	exit();
			
			//$count = M()->execute("select count(*) from yytb_bbooking where status = 3");
			$count = M('bbooking')
				->alias('t')
				->where($where)
				->getField('count(*)');
				
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
		$this->assign('title', '包场开奖纪录');
		$this->display();
	
	}	

}







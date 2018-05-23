<?php

namespace Admin\Model;

use Think\Model;

/**
 * 用户表模型
 * @date 2016年1月22日下午1:35:46
 * @author ilanguo_cqwang
 *
 */
class UserModel extends Model {
    protected $tablePrefix = '';
	protected $tableName = "member";
	function jsonList() {

		$page = I('get.page', 1, 'int');
		
		$row = I('get.rows', 10, 'int');
		
		$skip = ($page - 1) * $row;
		$order = array(
			'nickName','phone'
		);
		
		// 筛选
		$where = array();
		
		$keyword = I('get.keyword', "");
		if ($keyword)
			$where['nickName|phone'] = array(
				'like',"%$keyword%"
			);
		
		
		// 获取商品
		$UserM = D('user');
		$user = $UserM->alias("t")
			->field('t.member_id, t.nickname, t.phone')
			->where($where)
			->order($order)
			->limit($skip, $row)
			->select();
		$count = $UserM->alias("t")
			->where($where)
			->count();
		
		return array(
			'count' => count($user),'total' => $count,'page' => $page,'row' => $row,'user' => $user
		);
	
	}


}

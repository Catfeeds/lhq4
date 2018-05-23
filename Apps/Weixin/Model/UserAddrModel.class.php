<?php

namespace Weixin\Model;
use Think\Model;

/**
 * 用户地址
 */

class UserAddrModel extends Model {

	protected $tableName = 'user_addr';
	
	protected $_validate = array(
		array('name','require',' 收件人不能为空 '),
		array('phone','require',' 电话不能为空 '),
		array('addr','require',' 地址不能为空 '),
	);

	/*
	 * 1 新增
	 * 2 更新
	 * 3 新增与更新
	 * * 自定义
	 */
	protected $_auto = array (
		array('userId', UID, 3),
		array('phone', '', 2, 'ignore'),
		array('name', '', 2, 'ignore'),
		array('addr', '', 2, 'ignore'),
		array('more', '', 2, 'ignore'),
	);

	

    
    
    
	
}
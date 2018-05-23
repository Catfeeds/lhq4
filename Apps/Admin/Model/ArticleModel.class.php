<?php

namespace Admin\Model;
use Think\Model;

/**
 * 商品模型
 */

class ArticleModel extends Model {
	protected $_validate = array(
		array('price', array(1, 5), '值的范围不正确！', 2, 'in'),
	);

	protected $_auto = array (
		array('status','1'),  // 新增的时候把status字段设置为1
	);


	public function test(){
		echo ' === Good! === ';
		return ;
	}
}
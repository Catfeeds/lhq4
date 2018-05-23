<?php

namespace Admin\Controller;

use Think\Controller;

class AjaxGetController extends Controller {

	public function goods() { // Ajax 选择商品
		if(IS_AJAX){
			admJson(D('goods')->jsonList());
		}
	
		$types = M('goods_type')->where("status = 1")->order(array('taxis, id desc'))->select();
		$this->assign('types', $types);
		layout("inc/tpl.min");
		$this->display();
	}
	
	
	
	public function user() { // Ajax 选择用户
		if(IS_AJAX){
			admJson(D('user')->jsonList());
		}
		layout("inc/tpl.min");
		$this->display();
	}



}
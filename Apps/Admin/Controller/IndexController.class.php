<?php

namespace Admin\Controller;

use Think\Controller;

class IndexController extends AdminController {


	public function index() { // 后台管理首页
		$this->assign('title', '登录成功');
		$this->assign('debug', '');	
		$count = M('goods')->count();
		$countuser = D('user')->count();
		$info = date("Y-m-d",time());
		//$conuser = D('user')->where ("creatDate>". strtotime($info))->count();
		$conuser = D('user')->count();
		$congoods = M('goods')->where ("creatDate>". strtotime($info))->count();
		$this->assign('count', $count);
		$this->assign('countuser', $countuser);
		$this->assign('conuser', $conuser);
		$this->assign('congoods', $congoods);
		$this->display();
	}

}
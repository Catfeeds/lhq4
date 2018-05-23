<?php
namespace Admin\Controller;

use Think\Controller;

class SystemController extends AdminController{

	// 系统设置
	public function index(){ // 系统设置显示
		$scate = M('System_cate');
		$cate = $scate->order('orderid asc')->select();
		$this->assign('cate',$cate);
		
		$system = M('system');
		$systems = $system->where('cid='.I('get.cid','1','int'))->order('orderid, id')->select();
		$this->assign('systems',$systems);
		$this->assign('cid',I('get.cid','1','int'));

		$this->display();
	}

	public function update(){ // 编辑系统设置
		$system = M('system');
		$sys = I('post.sys', '', false);

		foreach($sys as $valu){
			$data = array(
					'val' =>$valu['val'],
			);
			$system->where('id='.$valu['id'])->data($data)->save();
		}

		S('system_config', null);
		M('system')->cache('system_config', 120)->getField('name,val');
		$this->redirect(U('System/index', 'cid='. I('post.cid','1','int')));
	}	

    
}
<?php

namespace Admin\Controller;

use Think\Controller;
use Think\Upload;

class BannerController extends AdminController {
	public function index() { // 幻灯片管理首页
		//分页
		$page = I('get.p', 1, 'int');	
		$banner = M('banner');
		$count = $banner->count();
		$show = array(
				'total' => $count,
				'page' => $page,
				'row' => 10,
				'href' => U('Admin/Banner/index', 'p=') . '{{page}}'
		);

		$list = $banner->order(array('taxis, id desc'))->page($page, 10)->select();
		$this->assign('title', '幻灯片管理');
		$this->assign('page',$show);
		$this->assign('banner', $list);
		$this->display();
	}
	
	public function add() { // 新增幻灯页面

		$types = array(
				'goods' => '商品',
		);	
		$this->assign('types', $types);
		layout("inc/tpl.min");
		$this->display();
	}
	
	
	public function addchk() { // Ajax 新增幻灯片	
		$banner = M('banner');
		$data = $banner->create();
		$data['creatDate'] = time();
        //dump($data);
		$flag=$banner->add($data);
       // dump($flag);die;
		 // 根据条件更新记录
		if($flag){
		    $this->success("新增成功", "javascript:window.close();");
		}else{
		    $this->error("新增失败", "javascript:window.close();");
		}
		
	}
	
	
	//编辑
	public function edit() { // 编辑幻灯片
		$types = array(
				'goods' => '商品',
		);
		$this->assign('types', $types);
		$id = I ("get.id",0,"int");
		$banner = M('banner')->where("id=".$id)->find();
		$this->assign("banner",$banner);
		layout("inc/tpl.min");
		$this->display();
	}
	//删除
	public function del() { // 删除幻灯片
		$id = I('get.id', 0, 'int');
		if($id){
			$banner = M('banner')->where("id=".$id)->delete();
		}
		$this->redirect('Banner/index');
	}
	/* 更新内容 */
	public function update() { // 更新幻灯片内容
	    $banner = D('banner');
	    $msg = false;
		$id = I('post.id', 0, 'int');
		$data = $banner->create();
		$flag=$banner->where("id=$id")->save($data); // 根据条件更新记录	
		if($flag){
		    $this->success("更新成功", "javascript:window.close();window.parent.location.reload();");
		}else{
		    $this->error("更新失败", "javascript:window.close();window.parent.location.reload();");
		}	
	}

	
}






	
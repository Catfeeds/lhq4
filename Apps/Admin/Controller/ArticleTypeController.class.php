<?php

namespace Admin\Controller;

use Think\Controller;
use Think\Upload;

class ArticleTypeController extends AdminController {
	public function index() { // 文章分类
		$this->assign('title', '文章类型管理');
		$types = M('article_type')->where("status = 1")->order(array('taxis, id desc'))->select();
		$this->assign('types', $types);
		$this->display();
	}


	public function add() { // 新增文章分类
	    
	    if(IS_POST){
	        
	        $data=M("article_type")->create();
	        if(!$data["typeName"]){
	            $this->error("类型名称不能为空");
	        }	        
	        
	        $upload = new Upload();// 实例化上传类 
	        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型        
	        $upload->rootPath="upload/" ;//文件上传保存的根路径	        
	        $upload->savePath  =     "icon/"; // 设置附件上传目录
	        $upload->autoSub=false;	       
	        
	        $info   =   $upload->upload();
	        
	        if(!$info) {
	            $this->error($upload->getError());exit;
	        }        
	        
	        $data["url"]=$upload->rootPath.$upload->savePath.$info["url"]["savename"];  
	        $flag=M("article_type")->data($data)->add();
	        
	        if($flag){
	            $this->success("添加成功","javascript:self.close()",3);
	        }else{
	            $this->error("添加失败");
	        }
	    }
	    
	    $this->display();
	}


// 返回商品类型列表 JSON
	public function lists() { // 文章分类列表
		$page = I('get.page', 1, 'int');

		$row =  I('get.rows', 10, 'int');

		$skip = ($page - 1) * $row;
		$order = array(
			'taxis',
			'id' => 'desc',
		);

		// 筛选
		$where = array();

		$keyword = I('get.keyword', 0);
		if($keyword) $where['title'] = array('like', "%$keyword%");

		$typeId = I('get.typeId', 0);
		if($typeId) $where['typeId'] = $typeId;

		$status = I('get.status', 0);
		if($status) $where['status'] = $status;



		// 获取文章类型
		$Article_typeM = M('article_type');
		$article_type= $Article_typeM
			->field('id, typename, url, description, status, taxis')
			->where($where)
			->order($order)
			->limit($skip, $row)
			->select();		
		
		$count = $Article_typeM
			->where($where)
			->count();

		$data = array(
				'count' => count($article_type),
				'total' => $count,
				'page' => $page,
				'row' => $row,
				'article_type' => $article_type,
		);

		admJson($data);
	}


// 商品 详情页
	public function edit() { // 编辑新增文章分类
		if(IS_POST) return $this->update();

		$id = I('get.id', 0);
		$this->assign('title', '文章类型');

		$article_type = M('article_type')->find($id);

		if(empty($article_type)) $this->error('文章类型不存在', 'javascript:self.close()');

		$this->assign('do', 'edit');		
		$this->assign('article_type', $article_type);
		layout("inc/tpl.min");
		$this->display();
	}


	/* 更新内容 */
	public function update() { // 编辑文章分类

		$article_type = D('article_type');
	    $msg = false;
		$id = I('post.id', 0, 'int');
		
		$data = $article_type->create();

		if($_POST["flag"]=="1"){
		    $flag=$article_type->where("id=$id")->save($data); // 根据条件更新记录
		    
		}

		$flag=$article_type->where("id=$id")->save($data); // 根据条件更新记录

		if($flag){
		    $this->success("添加成功","javascript:self.close()",3);
		}else{
		    $this->error("添加失败");
		}
		
	}



	public function del() // 删除文章分类
	{
		$id = I('post.id', 0, 'int');

		$arr = array(
			'id' => $id,
			'a' => 'del'
		);

		if (! $id) {
			$arr['code'] = 501;
			$arr['msg'] = '参数 id 错误';
		} elseif (! M('article_type')->delete($id)) {
			$arr['code'] = 500;
			$arr['msg'] = '删除 错误';
		}
		admJson($arr);
	}


	// 删除详情图片
	private function delImage() {
		$id = I('post.id', 0, 'int');

		$arr = array('id' => $id, 'a' => 'del');

		if(!$id){
			$arr['code'] = 501;
			$arr['msg'] = '参数 id 错误';
		}elseif(! M('article_detail')->delete($id)){
			$arr['code'] = 500;
			$arr['msg'] = '删除 错误';
		}
		return $arr;
	}

	// 切换状态
	public function toggleArticleType() // 开启关闭文章分类
	{
		$id = I('post.id', 0, 'int');

		if ($id)
			M()->execute("update __PREFIX__article_type set status = if(status != 1, 1, 2) where id = $id");

		admJson(array(
			'admin' => 32
		));
	}
	
}






	
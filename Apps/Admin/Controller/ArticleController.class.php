<?php

namespace Admin\Controller;

use Think\Controller;

class ArticleController extends AdminController {
	public function index() { // 文章管理首页
		$this->assign('title', '文章列表');
		$types = M('article_type')->where("status = 1")->order(array('taxis, id desc'))->select();

		$this->assign('types', $types);
		$this->display();
	}


	public function add() { // 新增文章
		$this->assign('title', '新增文章');

		// $user = M('User')->field('uid,nickname,username,email')->find($uid);
		$this->assign('user', $user);

		layout("inc/tpl.min");
		$types = M('article_type')->where("status = 1")->order(array('taxis, id desc'))->select();
		$this->assign('do', 'add');
		$this->assign('types', $types);
		$this->display('edit');
	}


	// 返回商品列表 JSON
	public function lists() { // Ajax 文章列表
		$page = I('get.page', 1, 'int');

		$row =  I('get.rows', 10, 'int');

		$skip = ($page - 1) * $row;
		$order = array(
			'taxis',
			'updateDate' => 'desc',
		);

		// 筛选
		$where = array();

		$keyword = I('get.keyword', 0);
		if($keyword) $where['title'] = array('like', "%$keyword%");

		$typeId = I('get.typeId', 0);
		if($typeId) $where['typeId'] = $typeId;

		$status = I('get.status', 0);
		if($status) $where['status'] = $status;



		// 获取文章
		$ArticleM = M('article');
		$article = $ArticleM
			->field('id, title, typeId, image, creatDate, updateDate, status')
			->where($where)
			->order($order)
			->limit($skip, $row)
			->select();
		$count = $ArticleM
			->where($where)
			->count();

		$data = array(
				'count' => count($article),
				'total' => $count,
				'page' => $page,
				'row' => $row,
				'article' => $article,
		);

		admJson($data);
	}


	// 文章 详情页
	public function edit() { // 编辑新增文章
		if(IS_POST) return $this->update();

		$id = I('get.id', 0);
		$this->assign('title', '文章详情');

		$article = M('article')->find($id);

		if(empty($article)) $this->error('文章不存在', 'javascript:self.close()');

		$types = M('article_type')->where("status = 1")->order(array('taxis, id desc'))->select();

		$images = M('article_detail')
			->where(array('articleId' => $id, 'type' => 'img'))
			->order('taxis, id desc')
			// ->getField('content', true)
			->field('id, taxis, content')
			->select()
		;

		// _d($images);

		$this->assign('do', 'edit');
		$this->assign('article', $article);
		$this->assign('types', $types);
		$this->assign('images', $images);

		layout("inc/tpl.min");
		$this->display();
	}


	/* 更新内容 */
	public function update() { // 更新文章内容
		$_validate = array(
		);
		$_auto = array (
			array('status','1'),
			array('updateDate', 'time', 3, 'function'),
			array('creatDate', 'time', 1, 'function'),
			array('content', 'htmlspecialchars_decode', 3, 'function'),
		);

		// 实例化 文章
		$article = D('article');
		$id = I('post.id', 0, 'int');


		// $goods = new \Admin\Model\GoodsModel();

		unset($_POST['status']);

		$msg = false;
		if($id){
			if(!$data = $article->validate($_validate)->auto($_auto)->create())
				$msg = $article->getError();
			else
				$on = $article->save($data);

		}else{
			// 新增 文章 信息
			$data = $article->auto($_auto)->create();
			
			// print_r($data); die;
            $article-> taxis = 0;
            $article-> maxqishu = 0;
            $article-> lock_version = '';
			$on = $article->add();

		}
		$code = $on ? 200 : 500;

		// 新增文章必须 返回 id
		admJson(array('code' => $code, 'msg' => $msg, 'id' => ($id ? 0 : $on), 'data' => $data));
	}


	// 操作详情图片
	public function editImage() { // 文章
		$do = I('post.ac', 'del');

		if($do == 'del')
			$r = $this->delImage($id);
		else
			$r = $this->addImage($url);

		admJson($r);
	}


	// 添加详情图片
	private function addImage() { // 新增文章图片
		$url = I('post.url', 0);
		$articleId = I('post.articleId', 0, 'int');

		$arr = array('id' => 0, 'url' => $url, 'a' => 'add');

		$article_detail = M('article_detail');

		$data = $article_detail->create(array('articleId'=>$articleId, 'content' => $url));

		$data['type'] = 'img';

		if(!$url || !$articleId){
			$arr['code'] = 501;
			$arr['msg'] = '参数 id 错误';
		}elseif(!$arr['id'] = $article_detail->add($data)){
			$arr['code'] = 500;
			$arr['msg'] = '新增 错误';
		}

		return $arr;
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

	// 删除
	public function del() { // 删除文章
		$id = I('post.id', 0, 'int');

		$arr = array('id' => $id, 'a' => 'del');

		if(!$id){
			$arr['code'] = 501;
			$arr['msg'] = '参数 id 错误';
		}elseif(! M('article')->delete($id)){
			$arr['code'] = 500;
			$arr['msg'] = '删除 错误';
		}
		admJson($arr);
	}

	// 操作详情图片
	public function togglearticle() { // 开启关闭文章
		$id = I('post.id', 0, 'int');

		if($id) M('article')->execute("
				update __PREFIX__article
				set status = if(status != 1, 1, 2)
				where id = $id
		");
	}
}







<?php

namespace Admin\Controller;

use Think\Controller;

class PeriodsShowController extends AdminController {
	public function index() { // 晒单列表首页
		$this->assign('title', '晒单列表');
		$this->display();
	}



	// 返回 晒单列表 JSON
	public function lists() {
		$page = I('get.page', 1, 'int');
		$status = I('get.status', 0, 'int');

		$page --;
		$row =  I('get.rows', 10, 'int');
		$skip = $page * $row;
		$order = array(
				'id',
				'creatdate' => 'desc',
		);

		$where = '';
		if($status) $where .= " and a.status = '$status' ";

		$Model = D();
		$list = $Model->query("
				select a.id, a.userId, a.content, a.pic1, a.pic2, a.status, a.pic3, a.creatDate,
					b.nickname, b.pic
				from __PREFIX__periods_show a,
					member b
				where a.userId = b.member_id
					$where
				--	and a.status = 1
				order by a.creatDate desc
				limit $skip, $row
		");

		if($status)
			$_where = array('status' => $status);
		else
			$_where = array();

		$total = M('periods_show')->where($_where)->count();
		$count = count($list);
		$pages = ceil($total / $row);

		$data = array(
				'code' => 1,
				'page' => $page + 1,   // 当前页数
				'pages' => $pages, // 总页数
				'count' => $count,
				'total' => $total, // 总条数
				'row' => $row,     // 每页条数
				'skip' => $skip,
				'list' => $list,
		);

		admJson($data);
	}

	// 切换状态
	public function toggleStatus() { // 开启关闭晒单
		$id = I('post.id', 0, 'int');

		if($id) M()->execute("
				update __PREFIX__periods_show
				set status = if(status != 1, 1, 2)
				where id = $id
		");

		admJson(array('admin' => 32));
	}


	// 晒单 详情页
	public function edit() { // 编辑晒单
		if(IS_POST) return $this->update();

		$id = I('get.id', 0);
		$this->assign('title', '晒单详情');

		$Model = D();
        $user = $Model->query("select userId from __PREFIX__periods_show where id = $id");
		$userid = $user['0']['userid'];
		$periodsShow = $Model->query("
				select a.id, a.userId, a.content, a.pic1, a.pic2, a.pic3, a.creatDate,
					b.nickName, b.pic
				from __PREFIX__periods_show a,
					member b
				where a.id = '$id' and b.member_id = '$userid'
		");
		$images = M('periods_show_detail')
			->where(array('psid' => $id, 'type' => 'img'))
			->order('id desc')
			// ->getField('content', true)
			->select();

		$data = array(
				'periodsShow' => $periodsShow,
				'images' => $images
		);

		// _d($data);

		$this->assign('periodsShow', $periodsShow[0]);
		$this->assign('images', $images);
		layout("inc/tpl.min");
		$this->display();
	}
        /**
         * 删除晒单
         */
        public function del(){ // 删除晒单
            $id = I('post.id');
            if (!$id) {
                die("缺少必备参数!");
            }
            $ps=M("periods_show")->delete($id);
            $psd=M('periods_show_detail')->where("psid=".$id)->delete();
            $res["iden"]=$ps+$psd;
            $res['code']=200;
            admJson($res);
        }
        

}










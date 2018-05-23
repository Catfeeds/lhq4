<?php

namespace Admin\Controller;

use Think\Controller;

class LoginController extends Controller {

	/**
	 * 登录页面
	 **/
	public function index() { // 登录页面
	 
	    
		if(is_login()){
			$this->redirect('Admin/Index/index');
			die;
		}
		if(IS_POST){
			$data = $this->check();

			$data = !is_array($data) ? array('code' => 1, 'data' => $data) : array(
					'code' =>200,
					'data' => array(
							'uid' => $data['uid'],
							'username' => $data['username'],
							'nickname' => $data['nickname'],
					)
			);

			if(IS_AJAX){
				$this->ajaxReturn($data);
			}else{
				if($data['code'] == 200)
					$this->redirect('Admin/Index/index');
			}
			die;
		}

		$title = 'Sign in';
		/*
			Sign in
			Registered
			Sign out
			*/
		// $list = D('User')->select();

		$this->assign('title', $title);
		$this->display();
	}



	/**
	 * 登录 验证
	**/
	private function check() {
		if(IS_POST){
			$this->User = M('Adminer');
			$where['username'] = I('post.username', '', 'trim');
			//$imgcode = I('post.imgcode', '', 'trim');
			// $where['password'] = encrypt_pass( I('post.password', '', 'trim') );
			$where['password'] = I('post.password', '', 'trim');

			// $Verify = new \Think\Verify();
			// if(! $Verify->check($imgcode, 'login'))
			// 	return '验证码错误';

			$U = $this->User->where($where)->find();
			if($U){
				if($U['status'] != 1){
					return '该户名已关闭';
				}else{
					$this->login($U['uid']);
					return $U;
				}
			}else{
				return '用户名或密码错误';
			}
		}
	}

	private function login($uid){
		/* 检测是否在当前应用注册 */
		$user = $this->User->field(true)->find($uid);
		if(!$user || 1 != $user['status']) {
			$this->error = '用户不存在或已被禁用！'; //应用级别禁用
			return false;
		}

		//记录行为
		// action_log('user_login', 'member', $uid, $uid);

		/* 登录用户 */
		$this->autoLogin($user);
		return true;
	}

	/**
	 * 注销当前用户
	 * @return void
	 */
	public function logout(){ // 注销当前用户
		session('user_auth', null);
		session('user_auth_sign', null);
		if(IS_AJAX)
			$this->ajaxReturn(array('code' => 200));
		else
			$this->redirect('Admin/Index/index');
	}


	public function code() { // 验证码
	ob_clean();
		$Verify = new \Think\Verify(array(
				'fontSize' => 14,
				'expire' => 120,
				'imageW' => 120,
				'imageH' => 30,
				'fontttf' => '2.ttf',
		        'useCurve'  =>  false,  // 是否画混淆曲线
		        'useNoise'  =>  true,  // 是否添加杂点
		));
		$Verify->entry('login');
	}

	/**
	 * 自动登录用户
	 * @param  integer $user 用户信息数组
	 */
	private function autoLogin($user){
		/* 更新登录信息 */
		$data = array(
				'uid'             => $user['uid'],
				'login'           => array('exp', '`login`+1'),
				'last_login_time' => NOW_TIME,
				'last_login_ip'   => get_client_ip(1),
		);
		$this->User->save($data);

		/* 记录登录SESSION和COOKIES */
		$auth = array(
				'uid'             => $user['uid'],
				'username'        => $user['username'],
				'nickname'        => $user['nickname'],
				'last_login_time' => $user['last_login_time'],
		);

		session('user_auth', $auth);
		session('user_auth_sign', data_auth_sign($auth));

	}

	private function getNickName($uid){
		return D('Adminer')->where(array('uid'=>(int)$uid))->getField('nickname');
	}

}
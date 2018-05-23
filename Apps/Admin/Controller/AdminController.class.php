<?php

namespace Admin\Controller;

use Think\Controller;

class AdminController extends Controller {

	public function _initialize(){
		$host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
        
        if(filter_var($host, FILTER_VALIDATE_IP)) {// 合法IP
            echo "<script>location.href='404.html'</script>"; 
            die; 
        }
		C(M('system')->cache('system_config', 120)->getField('name,val'));
		
		/*
		* RBAC
		*/
	
		//获取用户id
		$userInfo=session('user_auth');
		$userId=$userInfo["uid"];
	
		//获取相应的角色id
		$adminer=M("adminer")->field("role_id")->find($userId);
		$roleId=$adminer["role_id"];
	
		//获取相应权限的ids
		$adminer=M("role")->field("auth_ids,auth_ac")->find($roleId);
		$authIds=$adminer["auth_ids"];
		$order=array(
			"order"=>"asc"
		);
		//获取相应的权限详情
		if($userId==1){    //如果是超级管理员， 获取所有权限
			$auths1=M("auth")->where("level=0 and display=1")->order($order)->select();    //顶级权限
			$auths2=M("auth")->where("level=1 and display=1")->order($order)->select();     //二级权限
		}else{     //否则值获取相应权限
			if($authIds){ //只有用户登录时(未登录时，获取不到ids)
				$auths1=M("auth")->where("level=0 and display=1 and id in ($authIds)")->order($order)->select();     //顶级权限
				$auths2=M("auth")->where("level=1 and display=1 and id in ($authIds)")->order($order)->select();     //二级权限
			}
		}
// 		 echo"<meta charset='utf-8'>";
// 		 dump($auths1);exit;
		$this->assign("auths1", $auths1);
		$this->assign("auths2", $auths2);
	
	
		/*
		* 实现访问权限控制器过滤
		* */
		//获取当前请求的控制器和操作方法
		$now_ac=CONTROLLER_NAME."-".ACTION_NAME;
	
		//获取当前用户对应的角色权限
		$auth_ac=$adminer["auth_ac"];
	
		//允许开放一些不加限制的权限
		$allow_ac="Index-index,Login-Index";
	
		//如果：权限集中没有，且不是超级管理员，且不在开放的权限中
		if( (strpos($auth_ac,$now_ac)===false) && ($userId!=1) && (strpos($allow_ac,$now_ac)===false) ){
			$this->error("没有访问权限！");
		}
		/*
		* 实现访问权限控制器过滤
		* */


		$title = 'Sign in';
		$curUrl = $_SERVER["REQUEST_URI"];
		$this->assign('curUrl', $curUrl);
		$this->assign('title', $title);

		// 获取当前用户ID
		define('UID', is_login());


		$this->assign('user', session('user_auth'));

		if (!UID) { // 还没登录 跳转到登录页面
			$this->display('Login/index');
			die;
		}

		if(!IS_AJAX){
			$layout = 'layout';
			layout("inc/$layout");
		}
		$title = 'Sign in';

		$this->assign('title', $title);

	}

	public function _before_index() {
		$this->assign('before_index', '_before_index');

	}

}
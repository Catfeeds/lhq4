<?php
namespace Backend\Controller;
use Think\Controller;
use Common\Common\UserSession;

class IndexController extends ComController {
    public function index(){
        $user_info = UserSession::getSessionInfo();
		//var_dump($user_info);//die;
		//dump($_SESSION);
		$OSA_TEMPLATES=C('TEMPLATE');
		//dump($OSA_TEMPLATES);
		$menus = D('Menu_url')->getMenuByIds($user_info['shortcuts']);
        $num = count($menus);
        $this->assign('num',$num);
		$this->assign ('menus' ,$menus);
		$this->assign('osa_templates', $OSA_TEMPLATES);
        $this->display();
    }
}
?>
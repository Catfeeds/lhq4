<?php
namespace Backend\Controller;
use Think\Controller;
class IsShowActiveController extends ComController{
    public function index(){
    	if (IS_AJAX) {
    		$is_show_action = I('post.is_show_action');
    		$strs = D('is_show_active')->where('id=1')->save(array('status'=>$is_show_action));
    		if ($strs) {
    			echo 1;
    		}
    	}else{
    		$status = D('is_show_active')->find();
    		$this->assign('status',$status);
    	    $this->display();
    	}
    }
}

?>
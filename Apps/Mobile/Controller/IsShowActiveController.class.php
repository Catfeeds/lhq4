<?php
namespace Mobile\controller;
use Think\controller;
class IsShowActiveController extends CommonController{
	/*
    *判断是否显示激活页面
	*/
	public function index(){
        $member_id = I('get.member_id');
        $status = D('is_show_active')->field('status')->find();
        $member_info = D(member)->field('member_id')->find($member_id);
    	if ($status['status'] == '0') {
    		echo json_encode(array('is_show'=>false));exit;
    	}
        if (empty($member_info)) {
        	echo json_encode(array('is_show'=>false));exit;
        }
        echo json_encode(array('is_show'=>true));exit;
	}
}

?>
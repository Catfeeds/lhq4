<?php
namespace Backend\Controller;
use Think\Controller;
use Common\Model\UserModel;
use Common\Common\UserSession;
class ApiAjaxController extends ComController{
	//添加删除快捷键
    public function shortcut(){
		$method = $menu_id = '';
		extract ( $_GET, EXTR_IF_EXISTS );
		$current_user_info = UserSession::getSessionInfo();
		$user_id = $current_user_info['user_id'];
        $shortcuts = $current_user_info['shortcuts'];
        $userModel = new UserModel();
        if($method=="add"){

			$shortcut_arr = explode(',',$shortcuts);
			if(!in_array($menu_id,$shortcut_arr)){
				$shortcut_arr[]=$menu_id;
				asort($shortcut_arr);
			}
			$shortcuts = implode(',',$shortcut_arr);
			$update_data = array ('shortcuts' => $shortcuts );
				
			$result = $userModel->updateUser($user_id,$update_data );
			if($result !==false ){
				$ret = json_encode(array("result"=>"1","msg"=>"添加成功"));
				UserSession::reload();
			}else{
				$ret = json_encode(array("result"=>"0","msg"=>"oOops!"));
			}
			
			echo $ret;
		}else if($method=="del"){

			$shortcut_arr = explode(',',$shortcuts);
			$idx = array_search($menu_id,$shortcut_arr);
			if($idx !==false ){
				unset($shortcut_arr[$idx]);
			}
			$shortcuts = implode(',',$shortcut_arr);
			$update_data = array ('shortcuts' => $shortcuts );
				
			$result = $userModel->updateUser ( $user_id,$update_data );
			if($result !==false ){
				$ret = json_encode(array("result"=>"1","msg"=>"删除成功"));
				UserSession::reload();
			}else{
				$ret = json_encode(array("result"=>"0","msg"=>"oOops!"));
			}
			echo $ret;
		}
    }
}
?>
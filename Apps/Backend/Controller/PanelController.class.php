<?php
namespace Backend\Controller;
use Think\Controller;
use Common\Common\UserSession;
use Common\Model\UserModel;
use Common\Common\Common;
use Common\Model\User_groupModel;
use Common\Common\GroupRole;
use Commom\Model\ModuleModel;
use Common\Model\SystemModel;
use Common\Model\SysLogModel;
use Common\Common\SideBar;
use Common\Model\Menu_urlModel;

class PanelController extends ComController{
	public function users() {
//		dump(SideBar::getTree ());
		$user_group = $method = $user_name = $user_id = $page_no = $search = '';
		extract ( $_REQUEST, EXTR_IF_EXISTS );

		$userModel= new UserModel();
		if ($method == 'pause' && ! empty ( $user_id )) {
			$user_data=array("status"=>0);
		
			if($user_id == UserSession::getUserId()){
				//can not do it self
                $this->alert("error","不能删除或者封停自己");//ErrorMessage::CAN_NOT_DO_SELF
			}else{
				if($user_id==1){
                    $this->exitWithSuccess ( '不能封停初始管理员',U('Backend/Panel/users'),1 );
				}
			
				$result = $userModel->updateUser( $user_id,$user_data );
				if ($result>=0) {
                    D('SysLog')->addLog ( UserSession::getUserName(), 'PAUSE',  'User' ,$user_id ,json_encode($user_data) );
                    $this->exitWithSuccess ( '已封停',U('Backend/Panel/users'),1 );
				}else{
				    $this->alert("error");
				}
			}
		}
		
		if ($method == 'play' && ! empty ( $user_id )) {
			$user_data=array("status"=>1);
			$result = $userModel->updateUser ( $user_id,$user_data );
			if ($result>=0) {
                D('SysLog')->addLog ( UserSession::getUserName(), 'PLAY' , 'User' ,$user_id ,json_encode($user_data) );
                $this->exitWithSuccess ( '已解封',U('Backend/Panel/users'),1 );
			}else{
			$this->alert("error");
			}
		}
		
		if ($method == 'del' && ! empty ( $user_id )) {
			if($user_id == UserSession::getUserId()){
                $this->alert("error","不能删除或者封停自己");
			}else{
				if($user_id==1){
                    $this->exitWithSuccess ( '不能删除初始管理员',U('Backend/Panel/users'),1 );
				}
				$user = $userModel->getUserById($user_id);
				$result = $userModel->delUser( $user_id );
				if ($result>=0) {
					$user['password']=null;
                    D('SysLog')->addLog ( UserSession::getUserName(), 'DELETE',  'User' ,$user_id ,json_encode($user) );
				$this->exitWithSuccess ( '已删除',U('Backend/Panel/users'),1 );
				}else{
					$this->alert("error");
				}
			}
		}
		
		//START 数据库查询及分页数据
		$page_size = 25;//PAGE_SIZE;
		$page_no=$page_no<1?1:$page_no;
		
		if($search){
			$row_count = $userModel->countSearch($user_group,$user_name);
			$total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
			$total_page=$total_page<1?1:$total_page;
			$page_no=$page_no>($total_page)?($total_page):$page_no;
			$start = ($page_no - 1) * $page_size;
			$user_infos = $userModel->search($user_group,$user_name,$start , $page_size);
		
		}else{
			$row_count = $userModel->count ();
			$total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
			$total_page=$total_page<1?1:$total_page;
			$page_no=$page_no>($total_page)?($total_page):$page_no;
			$start = ($page_no - 1) * $page_size;
			$user_infos = $userModel->getAllUsers ( $start , $page_size );
		}
		
//		$page_html=Pagination::showPager("users.php?user_group=$user_group&user_name=$user_name&search=$search",$page_no,$page_size,$row_count);
		
		//追加操作的确认层
//		$confirm_html = OSAdmin::renderJsConfirm("icon-pause,icon-play,icon-remove");
		
		// 设置模板变量
		$userGroupModel = new User_groupModel();
		$group_options=$userGroupModel->getGroupForOptions();
		$group_options[0] = "全部";
		ksort($group_options);

		$this->assign ( 'group_options', $group_options );
		$this->assign ( 'user_infos', $user_infos );
		$this->assign ( '_GET', $_GET );
		$this->assign ( 'page_no', $page_no );
		$this->assign ( 'page_html', $page_html );
//		$this->assign ( 'osadmin_action_confirm' , $confirm_html);
		$this->display ( 'panel/users' );
	}
	
	public function user_add() {
		$user_name = $real_name = $mobile = $password  = $email = $user_desc = $user_group = '';
		extract ( $_POST, EXTR_IF_EXISTS );
		$userModel = new UserModel();
		$common = new Common();
		
		if ($common::isPost ()) {
			$exist = $userModel->getUserByName($user_name);
			if($exist){

                $this->alert("error",'名称冲突');//ErrorMessage::NAME_CONFLICT
			}else if($password=="" || $real_name=="" || $mobile =="" || $email =="" || $user_group <= 0 ){
                $this->alert("error",'缺少必填项');//ErrorMessage::NEED_PARAM
			}else{
				$input_data = array ('user_name' => $user_name, 'password' => md5 ( $password ), 'real_name' => $real_name, 'mobile' => $mobile, 'email' => $email,
						'user_desc' => $user_desc,'login_time'=>0,'login_ip'=>'::1','status'=>1,'template'=>'default','shortcuts'=>'','show_quicknote'=>1,'user_group' => $user_group );
				$user_id = $userModel->addUser ( $input_data );
				if ($user_id) {
					$input_data['password']="";
					(new SysLogModel())->addLog ( UserSession::getUserName(), 'ADD', 'User' ,$user_id, json_encode($input_data) );
                    $this->exitWithSuccess ('账号添加成功',U('Backend/Panel/users'),1 );
				}else{
                    $this->alert("error");
				}
			}
		}
		$userGroupModel = new User_groupModel();
		$group_options = $userGroupModel->getGroupForOptions();
		$this->assign("_POST" ,$_POST);
		$this->assign ( 'group_options', $group_options );
		$this->display ( 'panel/user_add' );
	}
	
	public function user_modify() {
		$user_id = $user_name = $real_name = $mobile = $password = $email = $user_desc = $user_group = '';
		extract ( $_REQUEST, EXTR_IF_EXISTS );

		$common = new Common();
//		$common::checkParam($user_id);
		$userModel = new UserModel();
        //dump($user_id);
		$user = $userModel->getUserById ( $user_id );
        //dump($user);die;
		if(empty($user)){
            $this->exitWithError('账户不存在',U('Backend/Panel/users'),1 );//ErrorMessage::USER_NOT_EXIST
		}
		if ($common::isPost ()) {

			if($real_name=="" || $mobile =="" || $email =="" || ($user_id !=1 && $user_group <= 0) ){
                $this->alert("error",'缺少必填项');//ErrorMessage::NEED_PARAM
			}else{
		
				$update_data = array ('real_name' => $real_name, 'mobile' => $mobile,
						'email' => $email, 'user_desc' => $user_desc );
				if($user_id > 1 ){
					$update_data["user_group"]=$user_group;
				}
		
				if (! empty ( $password )) {
					$update_data = array_merge ( $update_data, array ('password' => md5 ( $password ) ) );
				}
		
				$result = $userModel->updateUser ( $user_id,$update_data );
		
				if ($result>=0) {
				
					$current_user=UserSession::getSessionInfo();
					$ip = $common::getIp();
					$update_data['ip']=$ip;
					(new SysLogModel())->addLog ( UserSession::getUserName(), 'MODIFY', 'User' , $user_id, json_encode($update_data) );
                    $this->exitWithSuccess ('更新完成',U('backend/panel/users'),1 );
				} else {

                    $this->alert("error");
				}
			}
		}
		$userGroupModel = new User_groupModel();
		$group_options=$userGroupModel->getGroupForOptions();
		
		$this->assign ( 'user', $user );
		$this->assign ( 'group_options', $group_options );
		$this->display ( 'panel/user_modify' );
	}
	
	public function groups() {
		$method = $group_id = '';
		extract ( $_GET, EXTR_IF_EXISTS );

		$userGroupModel = new User_groupModel();
		
		if ($method == 'del' && ! empty ( $group_id )) {
			$users = $userGroupModel->getGroupUsers($group_id);
			if(sizeof($users)>0){
                $this->alert("error",'账号组被使用，不能删除；若要删除，请先将属于该组的用户划拨到其它账号组');//ErrorMessage::HAVE_USER
			}else if(intval($group_id) === 1){
                $this->alert("error",'不能对初始的超级管理员组进行此操作');//ErrorMessage::CAN_NOT_DO_FOR_SUPER_GROUP
			}else{
				$group = $userGroupModel->getGroupById($group_id);
				$result = $userGroupModel->delGroup ( $group_id );
				if ($result>0) {
					(new SysLogModel())->addLog ( UserSession::getUserName(), 'DELETE', 'UserGroup',$group_id, json_encode($group) );
                    $this->exitWithSuccess ('已将账号组删除',U('backend/panel/groups'),1);
				}else{
                    $this->alert("error");
				}
			}
		}
		
		$groups = $userGroupModel->getAllGroup();
//		$confirm_html = OSAdmin::renderJsConfirm("icon-remove");
		$this->assign ( 'osadmin_action_confirm' , $confirm_html);
		$this->assign ( 'groups', $groups );
		$this->display ( 'panel/groups' );
	}
	
	public function group() {
		$group_id = $method = $user_ids = $user_group = '';
		extract ( $_REQUEST, EXTR_IF_EXISTS );
		$common = new Common();
//		Common::checkParam($group_id);
		$userGroupModel = new User_groupModel();
		$userModel = new UserModel();

		$group = $userGroupModel->getGroupById( $group_id );

		if(empty($group)){
            $this->exitWithError('账户组不存在',U('backend/panel/groups'),1);//ErrorMessage::GROUP_NOT_EXIST
		}
		
		if ($common::isPost()) {

			if( empty($user_ids) || empty($user_group)){
                $this->alert("error",'缺少必填项');
			}else{
				if(in_array(1,$user_ids)){
                    $this->exitWithError ('不可更改初始管理员的账号组',U('backend/panel/groups'),1);
				}
				$user_ids=implode(',',$user_ids);

				$update_data= array('user_group' => $user_group);
				$result = $userModel->batchUpdateUsers ($user_ids,$update_data );

				if ($result>=0) {
					(new SysLogModel())->addLog ( UserSession::getUserName(), 'MODIFY', 'User' ,$user_ids, json_encode($update_data) );
                    $this->exitWithSuccess ('更新完成',U('backend/panel/groups'),1);
				} else {

                    $this->alert("error");
				}
			}
		}
		
		$user_infos = $userModel->getUsersByGroup($group_id);
		$groupOptions=$userGroupModel->getGroupForOptions();

		$this->assign ( 'group', $group );
		$this->assign ( 'user_infos', $user_infos );
		$this->assign ( 'groupOptions', $groupOptions );
		$this->display ( 'panel/group' );
	}
	
	public function group_modify() {
		$group_id = $group_name = $group_desc = '';
		extract ( $_REQUEST, EXTR_IF_EXISTS );
		$common = new Common();
//		Common::checkParam($group_id);
		$userGroupModel = new User_groupModel();
		
		$group = $userGroupModel->getGroupById ( $group_id );
        //dump($group);die;
		if(empty($group)){
            $this->exitWithError(ErrorMessage::GROUP_NOT_EXIST,U('backend/panel/groups'),1);
		}
		
		if ($common::isPost ()) {
			if($group_name =="" ){

                $this->alert("error",'缺少必填项');//ErrorMessage::NEED_PARAM
			}else{
				$update_data = array ('group_name' => $group_name, 'group_desc' => $group_desc);
				//dump($update_data);
				$result = $userGroupModel->updateGroupInfo($group_id,$update_data );
				//dump($result);
				if ($result>=0) {
					(new SysLogModel())->addLog ( UserSession::getUserName(), 'MODIFY', 'UserGroup' ,$group_id, json_encode($update_data) );
                    $this->exitWithSuccess ( '账号组修改完成',U('backend/panel/groups'),1 );
//					$this->success('账号组修改完成','panel/groups');
				} else {

                    $this->alert("error");
				}
			}
		}
		
		$groupOptions=$userGroupModel->getGroupForOptions();
		
		$this->assign ( 'group', $group );
		$this->assign ( 'groupOptions', $groupOptions );
		$this->display ( 'panel/group_modify' );
	}
	
	public function group_add() {
		$group_name = $group_desc = '';
		extract ( $_POST, EXTR_IF_EXISTS );
		$userGroupModel = new User_groupModel();
		if (Common::isPost ()) {

			$exist = $userGroupModel->getGroupByName($group_name);
			
			if($exist){

                $this->alert("error",'名称冲突');//ErrorMessage::NAME_CONFLICT
			}else if($group_name ==""){

                $this->alert("error",'缺少必填项');//ErrorMessage::NEED_PARAM
			}else{
				$input_data = array ('group_name' => $group_name, 'group_desc' => $group_desc, 'group_role' => "1,5,17,18,22,23,24,25" ,'owner_id' => UserSession::getUserId() );
				$group_id = D('user_group')->addGroup ( $input_data );
				if ($group_id) {
					(new SysLogModel)->addLog ( UserSession::getUserName(), 'ADD', 'UserGroup' ,$group_id, json_encode($input_data) );
                    $this->exitWithSuccess ('账号组添加完成',U('backend/panel/groups'),1);
				}
			}
		}
		
		$this->assign("_POST" ,$_POST);
		$this->display('panel/group_add' );
	}
	
	public function group_role() {
		$group_id = '';
		$menu_ids = array ();
		extract ( $_REQUEST, EXTR_IF_EXISTS );

		$group_id =  $group_id == ""? 1:intval($group_id);
		$common = new Common();
		$groupRole = new GroupRole();
		$userGroupModel = new User_groupModel();
		$group_option_list = $groupRole->getGroupForOptions ();
		
		$group_info = $userGroupModel->getGroupById ( $group_id );
		if(!$group_info){
			$group_id =1;
			$group_info = $userGroupModel->getGroupById ( $group_id );
		}
		$role_list = $groupRole->getGroupRoles ( $group_id );
		$group_role = $group_info ['group_role'];
		$group_role_array = explode ( ',', $group_role );
		if ($common::isPost ()) {
			if($group_id==1){
				$temp = array();
				foreach ($group_role_array as $group_role){
						
					//ϵͳԤ���˵�idΪ100����
					if($group_role>100){
						$temp[]=$group_role;
					}
				}
		
				$admin_role = array_diff($group_role_array,$temp);
		
				$menu_ids = array_merge($admin_role,$menu_ids);
				$menu_ids = array_unique($menu_ids);
				asort($menu_ids);
			}
			$group_role = join ( ',', $menu_ids );
			$group_data = array ('group_role' => $group_role );
			$result = $userGroupModel->updateGroupInfo ( $group_id, $group_data );
			if ($result>=0) {
                D('SysLog')->addLog ( UserSession::getUserName(), 'MODIFY', 'UserGroup' ,$group_id, json_encode($group_data) );
				UserSession::reload();
             //   $this->exitWithSuccess (ErrorMessage::SUCCESS_NEED_LOGIN,U('backend/panel/group_role'),1);
                $this->exitWithSuccess ('操作成功，部分功能需要用户重新登录才可使用',U('backend/panel/group_role'),1);
			}else{
                $this->alert("error");
			}
		}
		
		$this->assign ( 'role_list', $role_list );
		$this->assign ( 'group_id', $group_id );
		$this->assign ( 'group_option_list', $group_option_list );
		$this->assign ( 'group_role', $group_role_array );
		$this->display ( 'panel/group_role' );
	}
	
	public function modules() {
		$method = $module_id = '';
		extract ( $_GET, EXTR_IF_EXISTS );
		$moduleModel = new \Common\Model\ModuleModel();
		if ($method == 'del' && ! empty ( $module_id )) {
			$menus = $moduleModel->getModuleMenu($module_id);
			if(sizeof($menus)>0){
                $this->alert("error",'该模块下有菜单被使用，不能删除；若要删除，请先将属于该模块的菜单及链接删除或划拨到其它模块下');//ErrorMessage::HAVE_FUNC
			}else if(intval($module_id) === 1){
                $this->alert("error",'系统模块不能被删除');//ErrorMessage::CAN_NOT_DELETE_SYSTEM_MODULE
			}else{
				$module=$moduleModel->getModuleById($module_id);
				$result = $moduleModel->delModule ( $module_id );
		
				if ($result) {
					(new SysLogModel)->addLog ( UserSession::getUserName(), 'DELETE', 'Module' ,$module_id, json_encode($module) );
                    $this->exitWithSuccess ('模块删除成功',U('backend/panel/modules'),1);
				}
			}
		}
		
		$modules = $moduleModel->getAllModules();
//		$confirm_html = OSAdmin::renderJsConfirm("icon-remove");
		$this->assign ( 'modules', $modules );
		$this->assign ( 'osadmin_action_confirm' , $confirm_html);
		$this->display ( 'panel/modules' );
	}
	
	public function module_add() {
		$module_name = $module_desc = $module_sort = $module_url = $module_icon ='';
		$_POST['module_sort'] = 1;
		extract ( $_POST, EXTR_IF_EXISTS );

		$common = new Common();
		$moduleModel = new \Common\Model\ModuleModel();
		if ($common::isPost ()) {
			$exist = $moduleModel->getModuleByName($module_name);
			if($exist){
                $this->alert("error",'名称冲突');//ErrorMessage::NAME_CONFLICT
			}else if($module_name =="" || $module_url == ""){
                $this->alert("error",'缺少必填项');//ErrorMessage::NEED_PARAM
			}else{
				$input_data = array ('module_name' => $module_name, 'module_desc' => $module_desc, 'module_url' => $module_url ,'module_sort' =>$module_sort,'module_icon' =>$module_icon);
				$module_id = $moduleModel->addModule ( $input_data );
		
				if ($module_id) {
                    D('SysLog')->addLog ( UserSession::getUserName(), 'ADD', 'Module' , $module_id, json_encode($input_data) );
                    $this->exitWithSuccess ('模块添加成功',U('backend/panel/modules'),1);
				}
			}
		}
		$this->assign("_POST" ,$_POST);
		$this->display('panel/module_add');
	}
	
	public function module_modify() {
		$module_id = $module_name = $module_sort = $module_url = $module_desc = $module_icon = $online = '';
		extract ( $_REQUEST, EXTR_IF_EXISTS );
		$common = new Common();
//		Common::checkParam($module_id);
		$moduleModel = new \Common\Model\ModuleModel();
		$module = $moduleModel->getModuleById($module_id);

		if(empty($module)){
            $this->exitWithError(ErrorMessage::MODULE_NOT_EXIST,"panel/modules.php");
		}
		
		if ($common::isPost ()) {
		
			if($module_name =="" || $module_url == "" ){
                $this->alert("error",'缺少必填项');//ErrorMessage::NEED_PARAM
			}else{
				$update_data = array ('module_name' => $module_name, 'module_desc' => $module_desc, 'module_icon' => $module_icon ,'module_url' => $module_url ,
						'module_sort' =>$module_sort);
				if($module_id >1){
					$update_data['online'] =$online;
				}
				$result = $moduleModel->updateModuleInfo ( $module_id,$update_data );
		
				if ($result>=0) {
					(new SysLogModel)->addLog ( UserSession::getUserName(), 'MODIFY', 'Module' ,$module_id, json_encode($update_data) );
                    $this->exitWithSuccess ('更新完成',U('backend/panel/modules'),1);
				} else {
                    $this->alert("error");
				}
			}
		}

		$module_online_optioins = array("1"=>"在线","0"=>"下线");
		$this->assign ( 'modules', $module );
		$this->assign ( 'module_online_optioins', $module_online_optioins );
		$this->display ( 'panel/module_modify' );
	}
	
	public function module() {
		$module_id = $menu_ids = $module = '';
		extract ( $_REQUEST, EXTR_IF_EXISTS );
		$common = new Common();
//		Common::checkParam($module_id);
		$moduleModel = new \Common\Model\ModuleModel();
		 //mp($_REQUEST);
		$temp = $moduleModel->getModuleById ( $module_id );
      if(empty($temp)){
            $this->exitWithError(ErrorMessage::MODULE_NOT_EXIST,U('backend/panel/modules'),1);
		}
		
		if ($common::isPost ()) {
			if(empty($module) || empty($menu_ids)){
                $this->alert("error",'缺少必填项');
			}else{
				if($module !=1){
					foreach ($menu_ids as $menu_id){
						if($menu_id<=100){
                            $this->exitWithError ('系统菜单不能转移到其它模块',U('backend/panel/modules'),1);
						}
					}
				}
				$menu_ids=implode(',',$menu_ids);
				$update_data = array ('module_id' => $module);
				$result = D('menu_url')->batchUpdateMenus ( $menu_ids,$update_data );
                if ($result>=0) {
                    D('SysLog')->addLog ( UserSession::getUserName(), 'MODIFY', 'MenuUrl' ,$menu_ids, json_encode($update_data) );
                    $this->exitWithSuccess ('更新完成',U('backend/panel/modules'),1);
				} else {
                    $this->alert("error");
				}
			}
		}
		
		$menus = D('menu_url')->getListByModuleId($module_id );
		$module_options_list = $moduleModel->getModuleForOptions ();
		
		$this->assign ( 'module_options_list', $module_options_list );
		$this->assign ( 'menus', $menus );
		$this->assign ( 'module_id', $module_id );
		$this->display ( 'panel/module' );
	}
	
	public function menus() {
		$method = $menu_id = $module_id = $menu_name = $page_no = $search = '';
		extract ( $_GET, EXTR_IF_EXISTS );
		$menuUrlModel = new Menu_urlModel();
		if ($method == 'del' && ! empty ( $menu_id )) {
			$menu = $menuUrlModel->getMenuById($menu_id);
		
			//if(intval($menu['module_id']) === 1){
			if(intval($menu_id) <= 100){
                $this->alert("error",'系统菜单不能被删除');//ErrorMessage::CAN_NOT_DELETE_SYSTEM_MENU
			}else{
				$result = $menuUrlModel->delMenu ( $menu_id );
				if ($result) {
                    D('SysLog')->addLog ( UserSession::getUserName(), 'DELETE', 'MenuUrl' ,$menu_id, json_encode($menu) );
                    $this->exitWithSuccess ('已将菜单链接删除',U('backend/panel/menus'),1);
				}else{
                    $this->alert("error");
				}
			}
		}
		
		//START 数据库查询及分页数据
		$page_size = 25;//PAGE_SIZE;
		$page_no=$page_no<1?1:$page_no;
		
		if($search){
		
			$row_count = $menuUrlModel->countSearch($module_id,$menu_name);
			$total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
			$total_page=$total_page<1?1:$total_page;
			$page_no=$page_no>($total_page)?($total_page):$page_no;
			$start = ($page_no - 1) * $page_size;
			$menus = $menuUrlModel->search($module_id,$menu_name,$start , $page_size);
		
		}else{
			$row_count = $menuUrlModel->count ();
			$total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
			$total_page=$total_page<1?1:$total_page;
			$page_no=$page_no>($total_page)?($total_page):$page_no;
			$start = ($page_no - 1) * $page_size;
			$menus = $menuUrlModel->getAllMenus ( $start , $page_size );
		}
		
		
//		$page_html=Pagination::showPager("menus.php?module_id=$module_id&menu_name=$menu_name&search=$search",$page_no,$page_size,$row_count);
		
		$module_options_list = (new \Common\Model\ModuleModel())->getModuleForOptions ();
		$module_options_list[0]="全部";
		ksort($module_options_list);
		
//		$confirm_html = OSAdmin::renderJsConfirm("icon-remove");
		$this->assign ( 'page_no', $page_no );
		$this->assign ( 'menus', $menus );
		$this->assign ( '_GET', $_GET);
		$this->assign ( 'page_html', $page_html );
		$this->assign ( 'module_options_list', $module_options_list );
		$this->assign ( 'osadmin_action_confirm' , $confirm_html);
		$this->display ( 'panel/menus' );
	}
	
	public function menu_add() {
		$menu_name = $menu_url = $module_id = $is_show = $online = $shortcut_allowed = $menu_desc = $father_menu = '';
		extract ( $_POST, EXTR_IF_EXISTS );
		$common = new Common();
		$menuUrlModel = new Menu_urlModel();
		if ($common::isPost ()) {
			if($menu_name=="" || $menu_url == ""|| $module_id == ""){
                $this->alert("error",'缺少必填项');//ErrorMessage::NEED_PARAM
			}else{
				$menu = $menuUrlModel->getMenuByUrl($menu_url);
				if(!empty($menu)){
                    $this->alert("error",'链接地址冲突');//ErrorMessage::MENU_URL_CONFLICT
				}else{
					$input_data = array ('menu_name' => $menu_name, 'menu_url' => $menu_url, 'module_id' => $module_id,
							'is_show' => $is_show, 'online' =>1 , 'menu_desc' => $menu_desc ,'shortcut_allowed'=>$shortcut_allowed,'father_menu'=>$father_menu );
					$menu_id = $menuUrlModel->addMenu ( $input_data );
						
					if ($menu_id) {
                        D('SysLog')->addLog ( UserSession::getUserName(), 'ADD', 'MenuUrl' ,$menu_id ,json_encode($input_data));
                        $this->exitWithSuccess ('已将链接添加',U('backend/panel/menus'),1);
					}else{
                        $this->alert("error");
					}
				}
			}
		}
		
		$module_options_list = (new \Common\Model\ModuleModel())->getModuleForOptions ();
		$father_menu_options_list =$menuUrlModel->getFatherMenuForOptions ();
		$this->assign ( '_POST', $_POST );
		$this->assign ( 'module_options_list', $module_options_list );
		$this->assign ( 'father_menu_options_list', $father_menu_options_list );

		$this->display ( 'panel/menu_add' );
	}
	
	public function menu_modify() {
		$menu_id = $menu_name = $menu_url = $module_id = $is_show =$online = $shortcut_allowed = $menu_desc = $father_menu = '';
		extract ( $_REQUEST, EXTR_IF_EXISTS );
		$common = new Common();
//		Common::checkParam($menu_id);

		$menu = D('menu_url')->getMenuById ( $menu_id );
		if(empty($menu)){
            $this->exitWithError(ErrorMessage::MENU_NOT_EXIST,U('backend/panel/menus'),1);
		}
		
		if ($common::isPost ()) {
		
			if($menu_name == "" || $menu_url =="" || ($menu_id>100 && empty($module_id)) ){

                $this->alert("error",'缺少必填项');//\'ErrorMessage::NEED_PARAM

			}else{
				$exist = false;
				$menu_exist = D('menu_url')->getMenuByUrl($menu_url);
				if(!empty($menu_exist)){
					if($menu_id!=$menu_exist['menu_id']){
						$exist=true;
                        $this->alert("error",'链接地址冲突');//ErrorMessage::MENU_URL_CONFLICT
					}
				}
				if(!$exist){
					$update_data = array ('menu_name' => $menu_name,'$module_id' => $$module_id, 'menu_url' => $menu_url,
							'is_show' => $is_show, "online" => $online,'menu_desc' => $menu_desc, 'shortcut_allowed' => $shortcut_allowed,
							'father_menu' => $father_menu);
					if($menu_id > 100){
						$update_data['module_id'] = $module_id;
					}
						
					$result = D('menu_url')->updateMenuInfo ( $menu_id,$update_data );
						
					if ($result>=0) {
                        D('SysLog')->addLog ( UserSession::getUserName(), 'MODIFY', 'MenuUrl' ,$menu_id, json_encode($update_data) );
                        $this->exitWithSuccess ('更新完成',U('backend/panel/menus'),1);
					} else {
                        $this->alert("error");
					}
				}
			}
		}
		
		$module_options_list = (new \Common\Model\ModuleModel())->getModuleForOptions();
		$father_menu_options_list = D('menu_url')->getFatherMenuForOptions();
	//	dump($father_menu_options_list);die;
		$show_options_list=array("1"=>"显示","0"=>"不显示");
		$online_options_list=array("1"=>"在线","0"=>"下线");
		$shortcut_allowed_options_list = array("1"=>"允许","0"=>"不允许");
		$this->assign ( 'menu', $menu );
		$this->assign ( 'module_options_list', $module_options_list );
		$this->assign ( 'father_menu_options_list', $father_menu_options_list );
		$this->assign ( 'show_options_list', $show_options_list );
		$this->assign ( 'online_options_list', $online_options_list );
		$this->assign ( 'shortcut_allowed_options_list', $shortcut_allowed_options_list );
		$this->display ( 'panel/menu_modify' );
	}
	
	public function set() {
		$t = '';
		extract ( $_REQUEST, EXTR_IF_EXISTS );
		$current_user_id = UserSession::getUserId();
		$OSA_TEMPLATES = C("TEMPLATE");
		if($OSA_TEMPLATES[$t]==null){
			$t="default";
		}
		$userModel = new UserModel();
		$ret=$userModel->setTemplate(UserSession::getUserId(),$t);
		
		$_SESSION[UserSession::SESSION_NAME]['template']=$t;
		$rand=rand(0,10000);
		$back_url=$_SERVER['HTTP_REFERER']."#".$rand;
		header("Location:$back_url");
	}
	
	public function syslog() {
		$page_no = $user_name = $class_name =$start_date = $end_date ="";
		extract ( $_GET, EXTR_IF_EXISTS );
		
		if($class_name=='ALL'){
			$class_name ='';
		}
		$start_time = strtotime($start_date);
		$end_time = strtotime($end_date);
		$sysLogModel = new SysLogModel();
		//START 数据库查询及分页数据
		if($start_date != '' && $end_date !=''){
			$row_count =$sysLogModel->getCountByDate($class_name,$user_name,$start_time,$end_time);
		}else{
			$row_count = $sysLogModel->count ($class_name,$user_name);
		}
		
		$page_size = PAGE_SIZE;
		$page_no=$page_no<1?1:$page_no;
		
		$total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
		$total_page=$total_page<1?1:$total_page;
		$page_no=$page_no>($total_page)?($total_page):$page_no;
		
		$start = ($page_no - 1) * $page_size;
		//END
		$sys_logs = $sysLogModel->getLogs($class_name,$user_name, $start,$page_size,$start_time,$end_time );
		
		$loadedClz = array();
		$namePool = array();
		
		foreach ($sys_logs as &$log){
		
			if(array_key_exists($log['action'],C("COMMAND_FOR_LOG"))){
				$log['action']=C("COMMAND_FOR_LOG")[$log['action']];
			}
		
			$class_obj = $log['class_obj'];
			if(array_key_exists($log['class_name'],C("CLASS_FOR_LOG"))){
				$log['class_name'] = C("CLASS_FOR_LOG")[$log['class_name']];
			}
		
		
			if($log['class_obj']==""){
				$log['class_obj']='null';
			}
		
			if(empty($log['result'])){
				$log['result'] = '成功';
			}else{
				$result =json_decode($log['result'],true);
				if(is_array($result)){
					$temp = null;
					foreach($result as $key => $value){
						$temp[] = "$key=>$value";
					}
					$log['result']=implode(';',$temp);
				}else{
					$log['result']=$result;
				}
			}
		}
		
		// 显示分页栏
		
//		$page_html=Pagination::showPager("syslog.php?class_name=$class_name&user_name=$user_name&start_date=$start_date&end_date=$end_date",$page_no,PAGE_SIZE,$row_count);
		
		$this->assign ( 'page_no', $page_no );
		$this->assign ( 'page_size', PAGE_SIZE );
		$this->assign ( 'row_count', $row_count );
		$this->assign ( 'page_html', $page_html );
		$this->assign ( '_GET', $_GET );
		$this->assign ( 'class_options', C("CLASS_FOR_LOG") );
		$this->assign ( 'sys_logs', $sys_logs );
		$this->display ( 'panel/syslog' );
	}
	
	public function system() {
		$common = new Common();
		$sys_info = $common->getSysInfo();
		
		$this->assign ( 'sys_info', $sys_info );
		$this->display ( 'panel/system' );
	}
	
	public function setting() {
		$new_timezone = '';
		extract ( $_POST, EXTR_IF_EXISTS );
		$systemModel = new SystemModel();
		$common = new Common();
		$current_user_id=UserSession::getUserId();
		date_default_timezone_set('PRC');
		$timezone = $systemModel->get('timezone');
		$new_timezone = $timezone;

		if ($common::isPost()) {
			$systemModel->set('timezone',$new_timezone);
			$_SESSION['osa_timezone']=$new_timezone;
            $this->exitWithSuccess ('时区设置成功','/backend/index');
		
		}
		
		$timezone_options=array(
				"Asia/Shanghai"=>"北京,新加坡,香港",
		);
		//          "Asia/Tokyo"=>"东京,首尔",
		// "America/New_York"=>"纽约",
		// "Europe/London"=>"伦敦,卡萨布拉卡",
		//更新Session里的用户信息
		
		$this->assign("user_info",UserSession::getSessionInfo());
		$this->assign("timezone",$timezone);
		$this->assign("timezone_options",$timezone_options);
		$this->display ( 'panel/setting' );
	}
	
	public function profile() {
		$user_name = $password = $real_name = $mobile = $email = $user_desc = $change_password = $show_quicknote = $old = $new= '';
		extract ( $_POST, EXTR_IF_EXISTS );
		$current_user_id=UserSession::getUserId();
		$common = new Common();
		$userModel = new UserModel();
		$sysLogModel = new SysLogModel();
		if ($common::isPost()) {
			if($change_password){
				$ret=$userModel->checkPassword(UserSession::getUserName(),$old);
				if($ret){
					if(strlen($new)<6){

                        $this->alert("error",'密码不能少于6位');//ErrorMessage::PWD_TOO_SHORT
					}else{
						$user_data['password']=md5($new);
						$userModel->updateUser ( $current_user_id, $user_data );
						$sysLogModel->addLog ( UserSession::getUserName(), 'MODIFY', 'User' ,$current_user_id );
                        $this->exitWithSuccess (ErrorMessage::PWD_UPDATE_SUCCESS,'/backend/index');
		
					}
				}else{
                    $this->alert("error",'原密码错误');//ErrorMessage::OLD_PWD_WRONG
				}
			}else{
				$user_data['real_name']=$real_name;
				$user_data['mobile']=$mobile;
				$user_data['email']=$email;
				$user_data['user_desc']=$user_desc;
				$user_data['show_quicknote']=$show_quicknote;
		
				$userModel->updateUser ( $current_user_id, $user_data );
		
				UserSession::reload();
				$sysLogModel->addLog ( UserSession::getUserName(), 'MODIFY', 'User' ,$current_user_id, json_encode($user_data) );
                $this->exitWithSuccess ('资料修改成功','/backend/index');
		
			}
		}
		
		$quicknoteOptions=array("1"=>"显示","0"=>"不显示");
		
		
		//更新Session里的用户信息
		$this->assign("change_password",$change_password);
		$this->assign("user_info",UserSession::getSessionInfo());
		$this->assign("quicknoteOptions",$quicknoteOptions);
		$this->display ( 'panel/profile' );
	}
	
	public function verify_code_cn() {
		session_start();
		//dump(__ROOT__.'/Public/font/tahoma.ttf');
		header("Content-type: image/png");
		ob_clean();
		$im = imagecreatetruecolor(80, 28);
		$english = array(2,3,4,5,6,7,8,9,'A','B','C','D','E','F','G','H','I','J','K','L','M','N','P','Q','R','S','T','U','V','W','X','Y','Z','a','b','c','d','e','f','g','h','j','k','m','n','p','q','r','s','t','u','v','w','x','y','z');
		$chinese= array();
		//$chinese = array("人","出","来","友","学","孝","仁","义","礼","廉","忠","国","中","易","白","者","火 ","土","金","木","雷","风","龙","虎","天","地", "生","晕","菜","鸟","田","三","百","钱","福","爱","情","兽","虫","鱼","九","网","新","度","哎","唉","啊","哦","仪","老","少","日","月","星","于","文","琦","搜","狐","卓","望");
		$chars = array_merge($english,$chinese);
		// 创建颜色
		$fontcolor = imagecolorallocate($im, 0x6c, 0x6c, 0x6c);
		//$bg = imagecolorallocate($im, rand(0,85), rand(85,170), rand(170,255));
		$bg = imagecolorallocate($im, 0xfc, 0xfc, 0xfc);
		imagefill($im, 0, 0, $bg);
		// 设置文字
		$text = "";
		for($i=0;$i<6;$i++) 
			$text .= trim($chars[rand(0,count($chars)-1)]);
//		dump($text);
		$_SESSION['osa_verify_code'] = $text;
		
//		$font = __ROOT__.'/Public/font/tahoma.ttf';
		$font = './Public/font/tahoma.ttf';
		$gray = ImageColorAllocate($im, 200,200,200);
//		dump($font);
		// 添加文字
		imagettftext($im, 15, 0, 1, 23, $fontcolor, $font, $text);

		//加入干扰象素
		$r = rand()%50;
		for($i=0;$i<150;$i++){
			$x=sqrt($i)*2+$r;
			imagesetpixel($im, abs(sin($i)*80) , abs(cos($i)*28) , $gray);
			imagesetpixel($im, $x , $i , $gray);
			imagesetpixel($im, rand()%80 , rand()%28 , $gray);
		
		}
		
		// 输出图片
		imagepng($im);
		imagedestroy($im);
	}
	
	public function logout() {
		$common = new Common();
		$userModel = new UserModel();
		$sysLogModel = new SysLogModel();
		if(array_key_exists(UserSession::SESSION_NAME,$_SESSION)){
			$sysLogModel->addLog ( UserSession::getUserName(), 'LOGOUT','User' ,UserSession::getUserId() );
		}
		$userModel->logout();
      //  $this->exitWithSuccess("您已安全登出！",'Login/index);
        //$this->exitWithSuccess("您已安全登出！","/panel/login");
        $this->redirect('Login/index');
	}
}
?>
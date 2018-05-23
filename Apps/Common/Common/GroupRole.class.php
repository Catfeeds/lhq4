<?php
namespace Common\Common;
use Common\Model\User_groupModel;
use Common\Model\Menu_urlModel;
use Commom\Model\ModuleModel;

class GroupRole {
	public function getGroupRoles($group_id) {
		if (! $group_id || ! is_numeric ( $group_id )) {
			return false;
		}
//		$moduleModel = new ModuleModel();
		$menuUrlModel = new Menu_urlModel();
		$data = D('module')->getAllModules(1);
		//用户组的权限
		foreach ( $data as $k => $module ) {
			$list = $menuUrlModel->getListByModuleId ($module ['module_id'] ,"role");
			foreach ( $list as $menu ) {
				$data [$k] ['menu_info'][$menu ['menu_id']] = $menu ['menu_name'];
			}
		}
		
	
		return $data;
	}
	
	public function getGroupForOptions() {
		$userGroupModel = new User_groupModel();
		$group_list = $userGroupModel->getAllGroup ();
		
		foreach ( $group_list as $group ) {
			$group_options_array [$group ['group_id']] = $group ['group_name'];
		}
		
		return $group_options_array;
	}
	
}
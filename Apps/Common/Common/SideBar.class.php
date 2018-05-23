<?php
namespace Common\Common;
use Common\Common\UserSession;
class SideBar {
	//显示可见菜单
	const SHOW_MENU = 1;
	
	public static function getTree() {
		 
		$user_info = UserSession::getSessionInfo ();
		//功能菜单
		$data = array ();
		$data = D('Module')->getAllModules(1);
		//用户的权限
		$access = D('Menu_url')->getMenuByRole ( $user_info ['user_role'] );
		
		foreach ( $data as $k => $module ) {
			$list =D('Menu_url')->getlistByModuleId ($module ['module_id'],'sidebar' );
			 
			if (! $list) {
				unset ( $data [$k] );
				continue;
			}
			//去除无权限访问的
			foreach ( $list as $key => $value ) {
				if (! in_array ( $value ['menu_url'], $access )) {
					unset ( $list [$key] );
				}
			}
			$data [$k] ['menu_list'] = $list;
		}

		return $data;
	}
	
	public static function getMenuShortCuts() {
		 
		$user_info = UserSession::getSessionInfo ();
		//功能菜单
		$data = array ();
		$data = Module::getAllModule ();
		$user_info = UserSession::getSessionInfo();
		//用户的权限
		$access = MenuUrl::getMenuByRole ( $user_info ['user_role'] );
		
		foreach ( $data as $k => $module ) {
			$list = MenuUrl::getlistByModuleId ('shortcut' , $module ['module_id']);
			 
			if (! $list) {
				unset ( $data [$k] );
				continue;
			}
			//去除无权限访问的
			foreach ( $list as $key => $value ) {
				if (! in_array ( $value ['menu_url'], $access )) {
					unset ( $list [$key] );
				}
			}
			$data [$k] ['menu_list'] = $list;
		}
		return $data;
	}
}
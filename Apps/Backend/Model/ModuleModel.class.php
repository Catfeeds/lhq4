<?php
namespace Backend\Model;
use Think\Model;
class ModuleModel extends Model{
	protected $tablePrefix = 'osa_';
	// // 表名
	// private $table_name = 'module';
	// // 查询字段
	// private $columns = array('module_id', 'module_name', 'module_url', 'module_sort', 'module_desc','module_icon,online');
	
	// public function getTableName(){
	// 	return parent::$table_prefix.self::$table_name;
	// }
	//列表 
	public function getAllModules($is_online=null) {
		$conditon=array();
		
		if(isset($is_online)){
			$condition=array("online"=>$is_online);
		}
		$order = ' module_sort asc,module_id asc';

		$list = $this->where($condition)->order($order)->select();
		//$list = $db->select ( self::getTableName(), self::$columns, $condition );
		if ($list) {
			return $list;
		}
		return array ();
	}
	
	// public function addModule($module_data) {
	// 	if (! $module_data || ! is_array ( $module_data )) {
	// 		return false;
	// 	}
	// 	$db=self::__instance();
	// 	$id = $db->insert ( self::getTableName(), $module_data );
	// 	return $id;
	// }
	
	// public function getModuleById($module_id) {
	// 	if (! $module_id || ! is_numeric ( $module_id )) {
	// 		return false;
	// 	}
	// 	$db=self::__instance();
	// 	$condition['module_id'] = $module_id;
	// 	$list = $db->select ( self::getTableName(), self::$columns, $condition );
	// 	if ($list) {
	// 		return $list [0];
	// 	}
	// 	return array ();
	// }
	
	// public function getModuleByName($module_name) {
	// 	if (! $module_name || ! is_numeric ( $module_name )) {
	// 		return false;
	// 	}
	// 	$db=self::__instance();
	// 	$condition['module_name'] = $module_name;
	// 	$list = $db->select ( self::getTableName(), self::$columns, $condition );
	// 	if ($list) {
	// 		return $list [0];
	// 	}
	// 	return array ();
	// }
	
	// public function getModuleMenu($module_id) {
	// 	if (! $module_id || ! is_numeric ( $module_id )) {
	// 		return false;
	// 	}
	// 	$db=self::__instance();
	// 	$sql="select * from ".self::getTableName() ." m,".MenuUrl::getTableName()." u where m.module_id = $module_id and m.module_id = u.module_id order by m.module_id,u.menu_id";
	// 	$list = $db->query($sql)->fetchAll();
	// 	if ($list) {
	// 		return $list[0];
	// 	}
	// 	return array ();
	// }
	
	// public function updateModuleInfo($module_id,$module_data) {
	// 	if (! $module_data || ! is_array ( $module_data )) {
	// 		return false;
	// 	}
	// 	$db=self::__instance();
	// 	$condition=array("module_id"=>$module_id);
	// 	$id = $db->update ( self::getTableName(), $module_data, $condition );
	// 	return $id;
	// }
	
	// public function delModule($module_id) {
	// 	if (! $module_id || ! is_numeric ( $module_id )) {
	// 		return false;
	// 	}
	// 	$db=self::__instance();
	// 	$condition = array("module_id"=>$module_id);
	// 	$result = $db->delete ( self::getTableName(), $condition );
	// 	return $result;
	// }
	
	// public function getModuleForOptions() {
	// 	$module_options_array = array ();
	// 	$module_list = self::getAllModules (1);
		
	// 	foreach ( $module_list as $module ) {
	// 		$module_options_array [$module ['module_id']] = $module ['module_name'];
	// 	}
		
	// 	return $module_options_array;
	// }
}

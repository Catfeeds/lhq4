<?php
namespace Common\Model;
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
//	protected $tableName = "osa_module";
//	protected $fields = array('module_id', 'module_name', 'module_url', 'module_sort', 'module_desc','module_icon','online');
	//列表 
	public function getAllModules($is_online=null) {
		$conditon=array();
		
		if(isset($is_online)){
			$condition=array("online"=>$is_online);
		}
		$order = ' module_sort asc,module_id asc';

		$list = M('osa_module')->where($condition)->order($order)->select();
		//$list = $db->select ( self::getTableName(), self::$columns, $condition );
		if ($list) {
			return $list;
		}
		return array ();
	}

	
	 public function addModule($module_data) {
	 	if (! $module_data || ! is_array ( $module_data )) {
	 		return false;
	 	}
	 	$id = M('osa_module')->add($module_data );
	 	return $id;
	 }
	
	 public function getModuleById($module_id) {
	 	if (! $module_id || ! is_numeric ( $module_id )) {
	 		return false;
	 	}
	 	$condition['module_id'] = $module_id;
	 	$list = M('osa_module')->where($condition)->select();
	 	if ($list) {
	 		return $list [0];
	 	}
	 	return array ();
	 }
	
	 public function getModuleByName($module_name) {
	 	if (! $module_name || ! is_numeric ( $module_name )) {
	 		return false;
	 	}
	 	$condition['module_name'] = $module_name;
	 	$list = M('osa_module')->where($condition)->select();
	 	if ($list) {
	 		return $list [0];
	 	}
	 	return array ();
	 }
	
	 public function getModuleMenu($module_id) {
	 	if (! $module_id || ! is_numeric ( $module_id )) {
	 		return false;
	 	}
	 	$sql="select * from osa_module m, osa_menu_url u where m.module_id = $module_id and m.module_id = u.module_id order by m.module_id,u.menu_id";
	 	$list = M('osa_module')->query($sql);
	 	if ($list) {
	 		return $list[0];
	 	}
	 	return array ();
	 }
	
	 public function updateModuleInfo($module_id,$module_data) {
	 	if (! $module_data || ! is_array ( $module_data )) {
	 		return false;
	 	}
	 	$condition=array("module_id"=>$module_id);
	 	$id = M('osa_module')->where($condition)->data($module_data)->save();
	 	return $id;
	 }
	
	 public function delModule($module_id) {
	 	if (! $module_id || ! is_numeric ( $module_id )) {
	 		return false;
	 	}
	 	$condition = array("module_id"=>$module_id);
	 	$result = M('osa_module')->where($condition )->delete();
	 	return $result;
	 }
	
	 public function getModuleForOptions() {
	 	$module_options_array = array ();
	 	$module_list = $this->getAllModules (1);
		
	 	foreach ( $module_list as $module ) {
	 		$module_options_array [$module ['module_id']] = $module ['module_name'];
	 	}
		
	 	return $module_options_array;
	 }
}

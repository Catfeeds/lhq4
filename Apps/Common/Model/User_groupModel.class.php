<?php
namespace Common\Model;
use Think\Model;
use Think\Crypt\Driver\Think;
class User_groupModel extends Model {
//	protected $tablePrefix = 'osa_'; 
	// // 表名
	 protected  $tableName = 'osa_user_group';
	// // 查询字段
	 protected  $fields = array('group_id', 'group_name', 'group_role', 'owner_id' , 'group_desc');

	// public function getTableName(){
	// 	return parent::$table_prefix.self::$table_name;
	// }
	
	// //列表 
	 public function getAllGroup() {

	 	$columns = 'group_id, group_name, group_role, owner_id, group_desc';
	 	$sql="select ".$columns." ,u.user_name as owner_name from osa_user_group g left join osa_user u on g.owner_id =  u.user_id order by g.group_id";
	 	$list =(new \Think\Model())->query($sql);
	 	if ($list) {
			
	 		return $list;
	 	}
	 	return array ();
	 }

	 public function addGroup($group_data) {
	 	if (! $group_data || ! is_array ( $group_data )) {
	 		return false;
	 	}
	 	$id = M("osa_user_group")->add($group_data);//($group_data)->save();
	 	return $id;
	 }

	 public function getGroupById($group_id) {
	 	if (! $group_id || ! is_numeric ( $group_id )) {
	 		return false;
	 	}
	 	$condition['group_id'] = $group_id;
	 	$list = M('osa_user_group')->where($condition)->select();
	 	if ($list) {
	 		return $list [0];
	 	}
	 	return array ();
	 }
	
	 public function getGroupByName($group_name) {
	 	if ( $group_name == "" ) {
	 		return false;
	 	}
	 	$condition['group_name'] = $group_name;
	 	$list = M('osa_user_group')->where($condition)->select();
	 	if ($list) {
	 		return $list [0];
	 	}
	 	return array ();
	 }
	
	 public function updateGroupInfo($group_id,$group_data) {
	 	if (! $group_data || ! is_array ( $group_data )) {
	 		return false;
	 	}
	 	$condition["group_id"] = $group_id;

	 	$id = M('osa_user_group')->where($condition)->data($group_data)->save();

	 	return $id;
	 }
	
	 public function delGroup($group_id) {
	 	if (! $group_id || ! is_numeric ( $group_id )) {
	 		return false;
	 	}
	 	$condition = array("group_id" => $group_id);
	 	$result = M('osa_user_group')->where($condition)->delete();
	 	return $result;
	 }
	
	 public function getGroupForOptions() {
	 	$group_list = $this->getAllGroup ();
		
	 	foreach ( $group_list as $group ) {
	 		$group_options_array [$group ['group_id']] = $group ['group_name'];
	 	}
		
	 	return $group_options_array;
	 }
	
	 public function getGroupUsers($group_id) {
	 	$columns = 'group_id, group_name, group_role, owner_id, group_desc';;
	 	$sql="select ".$columns." ,u.user_id as user_id,u.user_name as user_name,u.real_name as real_name from osa_user_group g, osa_user u where g.group_id = $group_id and g.group_id = u.user_group order by g.group_id,u.user_id";
	 	$list = M('osa_user_group')->query($sql);
	 	if ($list) {
	 		return $list;
	 	}
	 	return array ();
	 }
}

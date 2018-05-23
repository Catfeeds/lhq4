<?php
namespace Common\Model;
use Think\Model;
class SystemModel extends Model{
	protected $tablePrefix = 'osa_'; 
	// private $table_name = 'system';
	// private $columns = array('key_name', 'key_value');
	
	// public function getTableName(){
	// 	return parent::$table_prefix.self::$table_name;
	// }
	
	 public function set($key_name, $key_value) {
	
	 	$key_value= json_encode($key_value);
	 	$data[$key_name]=''.$key_value.'';
	 	//$sql = "insert into ".self::getTableName() ." values ('$key_name' ,'$key_value') on duplicate key update key_value='$key_value'";
	 	//$sql = "insert into osa_system values ('$key_name' ,'$key_value') on duplicate key update key_value=".$key_value;

	 	$id = $this->save($data);
		
	 	return $id;
	 }
	
	 public function get($key_name) {

	 	$condition['key_name'] = $key_name;
	 	$list = $this->where($condition)->select();
	 	if($list){
	 		return json_decode($list[0]['key_value']);
	 	}
	 	return null;
	 }
}
?>
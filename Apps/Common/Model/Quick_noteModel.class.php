<?php
namespace Commom\Model;
use Think\Model;
class Quick_noteModel extends Model {
	protected $tablePrefix = 'osa_'; 
	// // 表名
	// private $table_name = 'quick_note';
	// // 查询字段
	// private $columns = array('note_id', 'note_content', 'owner_id');
	// //状态定义
	
	// public function getTableName(){
	// 	return parent::$table_prefix.self::$table_name;
	// }
	
	//列表 
	// public function getNotes($start ='' ,$page_size='') {
	// 	$db=self::__instance();
	// 	$limit ="";
	// 	if($page_size){
	// 		$limit =" limit $start,$page_size ";
	// 	}
	// 	$columns = implode(self::$columns,',');
	// 	$sql="select ".$columns." ,coalesce(u.user_name,'已删除') as owner_name from ".self::getTableName()." q left join ".User::getTableName()." u on q.owner_id =  u.user_id order by q.note_id desc $limit";
	// 	$list = $db->query($sql)->fetchAll();
	// 	if ($list) {
	// 		return $list;
	// 	}
	// 	return array ();		
	// }
	
	// public function addNote($note_data) {
	// 	if (! $note_data || ! is_array ( $note_data )) {
	// 		return false;
	// 	}
	// 	$db=self::__instance();
	// 	$id = $db->insert ( self::getTableName(), $note_data );
	// 	return $id;
	// }

	// public function getNoteById($note_id) {
	// 	if (! $note_id || ! is_numeric ( $note_id )) {
	// 		return false;
	// 	}
	// 	$db=self::__instance();
	// 	$condition['note_id'] = $note_id;
	// 	$list = $db->select ( self::getTableName(), self::$columns, $condition );
	// 	if ($list) {
	// 		return $list [0];
	// 	}
	// 	return array ();
	// }
	
	// public function getRandomNote() {
	// 	$db=self::__instance();
	// 	$sql="select min(note_id), max(note_id) from ".self::getTableName();
	// 	$list = $db->query($sql)->fetch();
	// 	if ($list) {
	// 		$note_id=rand($list[0],$list[1]);
	// 		$condition['note_id[>=]'] = $note_id;
	// 		$list = $db->select ( self::getTableName(), self::$columns, $condition );
	// 		if ($list) {
	// 			return $list [0];
	// 		}
	// 	}
	// 	return array ();
	// }
	
	// public function count($condition = '') {
	// 	$db=self::__instance();
	// 	$num = $db->count ( self::getTableName(), $condition );
	// 	return $num;
	// }
	
	// public function updateNote($note_id,$note_data) {
	// 	if (! $note_data || ! is_array ( $note_data )) {
	// 		return false;
	// 	}
	// 	$db=self::__instance();
	// 	$condition=array("note_id"=>$note_id);
	// 	$id = $db->update ( self::getTableName(), $note_data,$condition );
		
	// 	return $id;
	// }
	
	// public function delNote($note_id) {
	// 	if (! $note_id || ! is_numeric ( $note_id )) {
	// 		return false;
	// 	}
	// 	$db=self::__instance();
	// 	$condition = array("note_id" => $note_id);
	// 	$result = $db->delete ( self::getTableName(), $condition );
	// 	return $result;
	// } 
}

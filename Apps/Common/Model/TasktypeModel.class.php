<?php
namespace Common\Model;
use Think\Model;
class TasktypeModel extends Model {
	protected $tableName = "task_type";
	 
	// public function getTasks() {
	
	// 	//�������ַ�ʽ����Է���sample��DB
	// 	$db=self::__instance();
	// 	//$db=self::__instance(SAMPLE_DB_ID);
		
	// 	$sql="select ".self::$columns." from ".self::getTableName()." order by id desc";
	// 	$list = $db->query($sql)->fetchAll();
	// 	if ($list) {
	// 		return $list;
	// 	}
	// 	return array ();		
	// }
	
	// public function getTasksArray() {
	
	// 	//�������ַ�ʽ����Է���sample��DB
	// 	$db=self::__instance();
	// 	//$db=self::__instance(SAMPLE_DB_ID);
	
	// 	$sql="select ".self::$columns." from ".self::getTableName();
	// 	$list = $db->query($sql)->fetchAll();
		
	// 	$data = array();
 //        $data['0']='不限';
	// 	foreach ( $list as $key => $value ) {
	// 		$data [$value['id']] = $value['task_name'];
	// 	}		
	// 	return $data;
	// }
	
	public function getTaskById($id) {
		if (! $id || ! is_numeric ( $id )) {
			return false;
		}	
		$sub_condition ["id"] = $id;
		$list = $this->where($sub_condition)->select();
		//$list = $db->select ( self::getTableName(), self::$columns, $condition );
		if ($list) {
			return $list[0];
		}
		return array ();
	}
	
	public function getTaskByName($task_name) {
		if (! $task_name ) {
			return false;
		}
	
		$sub_condition ["task_name"] = $task_name;
        $list = $this->where($sub_condition)->select();
		//$list = $db->select ( self::getTableName(), self::$columns, $condition );
		if ($list) {
			return $list;
		}
		return array ();
	}
	
	public function addTask($task_data) {
		if (! $task_data || ! is_array ( $task_data )) {
			return false;
		}
        $id = $this->data($task_data)->add();
		//$id = $db->insert ( self::getTableName(), $task_data );
		return $id;
	}
	
	public function delTask($id) {
		if (! $id || ! is_numeric ( $id )) {
			return false;
		}
		$condition = array("id" => $id);
		$result = $this->where($condition)->delete();
		//$result = $db->delete ( self::getTableName(), $condition );
		return $result;
	}
	
	public function updateTaskInfo($id,$task_data) {
		if (! $task_data || ! is_array ( $task_data )) {
			return false;
		}
		$condition=array("id"=>$id);
		$id = $this->where($condition)->save($task_data);
		//$id = $db->update ( self::getTableName(), $task_data,$condition );
		return $id;
	}


 //    public function getTasksByPage($start ,$page_size) {
	// 	$db=self::__instance();
	
	// 	$condition=array();
	
	// 	$condition["ORDER"]=" id desc";
	// 	$condition['LIMIT']=array($start,$page_size);
	
	// 	$list = $db->select ( self::getTableName(), self::$columns, $condition);
	
	// 	if ($list) {
	// 		return $list;
	// 	}
	// 	return array ();
	// }

    //分页
    public function search(){
        $perPage = 25;
        $count = $this->where($where)->count();
        $pageObj = new \Think\Page($count,$perPage);
        // 设置样式
        $pageObj->setConfig('first','首页');
        $pageObj->setConfig('prev', '上一页');
        $pageObj->setConfig('next', '下一页');
        $pageObj->setConfig('last','共%TOTAL_PAGE%页');
        $pageObj->setConfig('theme','%FIRST% %UP_PAGE%  &nbsp;%LINK_PAGE%&nbsp; %DOWN_PAGE% %END% <span class="rows">共 %TOTAL_ROW% 条</span>');
        $pageString = $pageObj->show();
        /************** 取某一页的数据 ***************/
        $data = $this 
        ->order("id desc")                    // 排序    
        ->limit($pageObj->firstRow.','.$pageObj->listRows)// 翻页
        ->select();       
        /************** 返回数据 ******************/
        return array(
            'data' => $data,  // 数据
            'page' => $pageString,  // 翻页字符串
        );
    }

}

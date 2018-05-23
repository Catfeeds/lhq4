<?php
namespace Common\Model;
use Think\Model;
class AdtypeModel extends Model {
	 protected $tableName = 'ad_type';
	// public function getAdTypes() {
	
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
	
	public function getAdTypesArray() {

		$list = $this->select();
		
		$data = array();
        $data['0']='不限';
		foreach ( $list as $key => $value ) {
			$data [$value['task_type']] = $value['task_name'];
		}		
		return $data;
	}
	
	public function getAdTypeById($id) {
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
	
	public function getAdTypeByName($task_name) {
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
	
	public function addAdType($adType_data) {

		if (! $adType_data || ! is_array ( $adType_data )) {
			return false;
		}
        $id = $this->data($adType_data)->add();
		//$id = $db->insert ( self::getTableName(), $adType_data );

		return $id;
	}
	
	public function delAdType($id) {
		if (! $id || ! is_numeric ( $id )) {
			return false;
		}
		$condition = array("id" => $id);
		$result = $this->where($condition)->delete();
		//$result = $db->delete ( self::getTableName(), $condition );
		return $result;
	}
	
	public function updateAdTypeInfo($id,$ad_data) {
		if (! $ad_data || ! is_array ( $ad_data )) {
			return false;
		}
		$condition=array("id"=>$id);
		$id = $this->where($condition)->save($ad_data);
		//$id = $db->update ( self::getTableName(), $ad_data,$condition );
        //var_dump($id);die;
		return $id;
	}


 //    public function getAdTypesByPage($start ,$page_size) {
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
        $pageObj->setConfig('next', '下一页');
        $pageObj->setConfig('prev', '上一页');
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

<?php
namespace Common\Model;
use Think\Model;
class MsgTypeModel extends Model {
	 protected $tableName = 'msg_type';

	
	public function getMsgTypesArray() {

		$list = $this->select();
		
		$data = array();

		foreach ( $list as $key => $value ) {
			$data [$value['id']] = $value['type_name'];
		}		
		return $data;
	}
	
	public function getMsgTypeById($id) {
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
	
	public function getMsgTypeByName($type_name) {
		if (! $type_name ) {
			return false;
		}	
		$sub_condition ["type_name"] = $type_name;
		$list = $this->where($sub_condition)->select();
		//$list = $db->select ( self::getTableName(), self::$columns, $condition );
		if ($list) {
			return $list;
		}
		return array ();
	}
	
	public function addMsgType($msgType_data) {

		if (! $msgType_data || ! is_array ( $msgType_data )) {
			return false;
		}
        $id = $this->data($msgType_data)->add();
		//$id = $db->insert ( self::getTableName(), $adType_data );

		return $id;
	}
	
	public function delMsgType($id) {
		if (! $id || ! is_numeric ( $id )) {
			return false;
		}
		$condition = array("id" => $id);
		$result = $this->where($condition)->delete();
		//$result = $db->delete ( self::getTableName(), $condition );
		return $result;
	}
	
	public function updateMsgTypeInfo($id,$msg_data) {
		if (! $msg_data || ! is_array ( $msg_data )) {
			return false;
		}
		$condition=array("id"=>$id);
		$id = $this->where($condition)->save($msg_data);
		//$id = $db->update ( self::getTableName(), $ad_data,$condition );
        //var_dump($id);die;
		return $id;
	}
	public function search(){
		$perPage = 25;
		$count = $this->count();
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

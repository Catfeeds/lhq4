<?php
namespace Common\Model;
use Think\Model;

class Channel_configModel extends Model {
	
	 
	public function getChannelConfigs() {
		
		$list = $this->select();
		if ($list) {
			return $list;
		}
		return array ();		
	}
	
	public function getChannelConfigById($config_id) {
		if (! $config_id || ! is_numeric ( $config_id )) {
			return false;
		}
		
		$sub_condition ["config_id"] = $config_id;
	
        $list = $this->where($sub_condition)->select();
		//$list = $db->select ( self::getTableName(), self::$columns, $condition );
		if ($list) {
			return $list[0];
		}
		return array ();
	}
	
	public function getChannelConfigByName($config_name) {
		if (! $config_name ) {
			return false;
		}	
		$sub_condition ["config_name"] = $config_name;
	    $list = $this->where($sub_condition)->select();
		//$list = $db->select ( self::getTableName(), self::$columns, $condition );
		if ($list) {
			return $list;
		}
		return array ();
	}
	
	public function addChannelConfig($config_data) {
		if (! $config_data || ! is_array ( $config_data )) {
			return false;
		}
		$id = $this->data($config_data)->add();
		//$id = $db->insert ( self::getTableName(), $config_data );
		return $id;
	}
	
	public function delChannelConfig($config_id) {
		if (! $config_id || ! is_numeric ( $config_id )) {
			return false;
		}
		$condition = array("config_id" => $config_id);
		$result = $this->where($condition)->delete();
		//$result = $db->delete ( self::getTableName(), $condition );
		return $result;
	}
	
	public function updateChannelConfigInfo($config_id,$config_data) {
		if (! $config_data || ! is_array ( $config_data )) {
			return false;
		}
		$condition=array("config_id"=>$config_id);
		$id = $this->where($condition)->save($config_data);
		//$id = $db->update ( self::getTableName(), $config_data,$condition );
	
		return $id;
	}
	
	// public function getChannelConfigsByPage($start ,$page_size) {
	// 	$db=self::__instance();
	
	// 	$condition=array();
	
	// 	$condition["ORDER"]=" config_id desc";
	// 	$condition['LIMIT']=array($start,$page_size);
	
	// 	$list = $db->select ( self::getTableName(), self::$columns, $condition);
	
	// 	if ($list) {
	// 		return $list;
	// 	}
	// 	return array ();
	// }
	public function search($perPage = 25){
		$count = $this->count();
		$pageObj = new \Think\Page($count,$perPage);
        $pageObj->setConfig('first','首页');
        $pageObj->setConfig('prev', '上一页');
        $pageObj->setConfig('next', '下一页');
        $pageObj->setConfig('last','共%TOTAL_PAGE%页');
        $pageObj->setConfig('theme','%FIRST% %UP_PAGE%  &nbsp;%LINK_PAGE%&nbsp; %DOWN_PAGE% %END% <span class="rows">共 %TOTAL_ROW% 条</span>');
		$pageString = $pageObj->show();
		// 设置样式
		/************** 取某一页的数据 ***************/
		$data = $this->order("config_id desc")                    // 排序
		->limit($pageObj->firstRow.','.$pageObj->listRows)            // 翻页
		->select();
		
		/************** 返回数据 ******************/
		return array(
			'data' => $data,  // 数据
			'page' => $pageString,  // 翻页字符串
		);
	}
}

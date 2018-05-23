<?php
namespace Common\Model;
use Think\Model;
class ChannelResModel extends Model {
	/*private static $table_name = 'channel_res';
 
	private static $columns = 'id,owner,type,media_name,channel_type,max,company,contact';

	public static function getTableName(){
		return self::$table_name;
	}*/
	 
	public function getChannelRess() {
		/*$db=self::__instance();
		$sql="select ".self::$columns." from ".self::getTableName();
		$list = $db->query($sql)->fetchAll();*/
		$list=$this->select();
      //  dump($list);die;
		if ($list) {
			return $list;
		}
		return array ();
	}
	
/*	public static function getChannelRessArray() {
	
		//�������ַ�ʽ����Է���sample��DB
		$db=self::__instance();
		//$db=self::__instance(SAMPLE_DB_ID);
	
		$sql="select ".self::$columns." from ".self::getTableName();
		$list = $db->query($sql)->fetchAll();
		
		$data = array();
		
		foreach ( $list as $key => $value ) {
			$data [$value['id']] = $value['channel_name'];
		}		
		return $data;
	}*/
	
	public function getChannelById($id) {
		if (! $id || ! is_numeric ( $id )) {
			return false;
		}
	
		$sub_condition ["id"] = $id;
		//$condition = array("AND" => $sub_condition);

		$list=$this->where($sub_condition)->select();
		if ($list) {
			return $list[0];
		}
		return array ();
	}
	/*
	public static function getChannelNameById($channel_id) {
		if (! $channel_id || ! is_numeric ( $channel_id )) {
			return false;
		}
	
		$sub_condition ["channel_id"] = $channel_id;
		$condition = array("AND" => $sub_condition);
		$db=self::__instance();
		$list = $db->select ( self::getTableName(), self::$columns, $condition );
		if ($list) {
			return $list[0]["channel_name"];
		}
		return array ();
	}
	
	public static function getChannelByName($channel_name) {
		if (! $channel_name ) {
			return false;
		}
	
		$sub_condition ["channel_name"] = $channel_name;
		$condition = array("AND" => $sub_condition);
		$db=self::__instance();
		$list = $db->select ( self::getTableName(), self::$columns, $condition );
		if ($list) {
			return $list;
		}
		return array ();
	}*/
	
	public function addChannel($channel_data) {
		if (! $channel_data || ! is_array ( $channel_data )) {
			return false;
		}
	/*	$db=self::__instance();
		$id = $db->insert ( self::getTableName(), $channel_data );*/
        $id = $this->data($channel_data)->add();
		return $id;
	}
	
	public function delChannel($id) {
		if (! $id || ! is_numeric ( $id )) {
			return false;
		}

		$condition = array("id" => $id);
		//$result = $db->delete ( self::getTableName(), $condition );
        $result = $this->where($condition)->delete();
		return $result;
	}
	
	public function updateChannelInfo($channel_id,$channel_data) {
		if (! $channel_data || ! is_array ( $channel_data )) {
			return false;
		}
		//$db=self::__instance();
		$condition=array("id"=>$channel_id);
		//$id = $db->update ( self::getTableName(), $channel_data,$condition );
        $id = $this->where($condition)->save($channel_data);
		return $id;
	}
	
	/*public static function getChannelsByPage($start ,$page_size) {
		$db=self::__instance();
	
		$condition=array();
	
		$condition["ORDER"]=" id desc";
		$condition['LIMIT']=array($start,$page_size);
	
		$list = $db->select ( self::getTableName(), self::$columns, $condition);
	
		if ($list) {
			return $list;
		}
		return array ();
	}*/
    //分页
    public function search(){
        $perPage = 25;

        $count = $this->count();
        //   dump($count);
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
            // ->join($join)
            ->order("id desc")                    // 排序
            //  ->where($where)            // 翻页
            ->limit($pageObj->firstRow.','.$pageObj->listRows)
            ->select();

        /************** 返回数据 ******************/
        return array(
            'data' => $data,  // 数据
            'page' => $pageString,  // 翻页字符串
        );
    }

	
}

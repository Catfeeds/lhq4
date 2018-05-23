<?php
namespace Common\Model;
use Think\Model;
class ChannelModel extends Model {
	 
	function getChannels() {

		$list = M('channel')->order("channel_id desc")->select();

		if ($list) {
			return $list;
		}
		return array ();		
	}

	public function getChannelsArray() {
	
		$list = $this->field('channel_id,channel_name')->select();
		
		$data = array();
		$data['0']='不限';
		$data['-1']='零花钱';
		foreach ( $list as $key => $value ) {
			$data [$value['channel_id']] = $value['channel_name'];
		}		
		return $data;
	}

	//查询标签似的渠道
	public function getChannelsArrays() {
		$data = $this->field('channel_id,channel_name')->select();;
		//var_dump($data);
		$count=count($data);
		$data[$count]['channel_id']='-1';
		$data[$count]['channel_name']='零花钱';
		return $data;
	}
	function getChannelsSelectedArray() {
		
	    $list = M('channel')->field('channel_id,channel_name')->select();
		$data = array();
		$data['0']='不限';
		$data['-1']='零花钱';
		foreach ( $list as $key => $value ) {
			$data [$value['channel_id']] = $value['channel_name'];
		}
		return $data;
	}

	//查询该用户的渠道
	public  function getChannelsSelectedArrayByUserid($user_id) {
		$condition['user_id']=$user_id;
		// $sql="select ".self::$columns." from ".self::getTableName();
		// $list = $db->query($sql)->fetchAll();
		$list = $this->field('channel_id,channel_name')->where($condition)->select ();
		$data = array();
		$data['0']='不限';
		foreach ( $list as $key => $value ) {
			$data [$value['channel_id']] = $value['channel_name'];
		}
		return $data;
	}

	public function getChannelById($channel_id) {
		if (! $channel_id || ! is_numeric ( $channel_id )) {
			return false;
		}
	
		$sub_condition ["channel_id"] = $channel_id;
		$list = $this->where($sub_condition)->select();
		//$list = $db->select ( self::getTableName(), self::$columns, $condition );
		if ($list) {
			return $list[0];
		}
		return array ();
	}

	//查找媒介自己拥有的渠道
	public  function getMjChannelsArray($user_id){
		$condition['user_id'] = $user_id;
		$list = $this->field('channel_id,channel_name')->where($condition)->select ();
		$data = array();
		$data['0']='不限';
		foreach ( $list as $key => $value ) {
			$data [$value['channel_id']] = $value['channel_name'];
		}
		return $data;
	}

	//获得用户对用的所有chanid
	public  function getUserChanid($user_id) {
		if ($user_id != "" && $user_id != 1) {
			$condition['user_id'] = $user_id;
		}
		$list = $this->field('channel_id')->where($condition)->select();


		foreach ($list as $k => $v) {
			$arr[] = $v['channel_id'];
		}
		if ($arr) {
			return $arr;
		}
		return array ();
	}

	public  function getChannelByChanid($chanid) {

			//�������ַ�ʽ����Է���sample��DB
			
			$condition['channel_id']=array('in',$chanid);
			// $sql="select ".self::$columns." from ".self::getTableName();
			// $list = $db->query($sql)->fetchAll();
			$list = $this->field('channel_id,channel_name')->where($condition)->select();
			$data = array();
			$data['0'] = '不限';
			foreach ($list as $key => $value) {
				$data [$value['channel_id']] = $value['channel_name'];
			}
			return $data;

	}

	public function getChannelNameById($channel_id) {
		if (! $channel_id || ! is_numeric ( $channel_id )) {
			return false;
		}
	
		$sub_condition ["channel_id"] = $channel_id;
		

		$list = M('channel')->where($sub_condition)->select();
		if ($list) {
			return $list[0]["channel_name"];
		}
		return array ();
	}
	
	public function getChannelByName($channel_name) {
		if (! $channel_name ) {
			return false;
		}
	
		$sub_condition ["channel_name"] = $channel_name;
		$list = $this->where($sub_condition)->select();
		//$list = $db->select ( self::getTableName(), self::$columns, $condition );
		if ($list) {
			return $list;
		}
		return array ();
	}
	
	public function addChannel($channel_data) {
		if (! $channel_data || ! is_array ( $channel_data )) {
			return false;
		}
		$id = $this->data($channel_data)->add();
		//$id = $db->insert ( self::getTableName(), $channel_data );
		return $id;
	}
	
	public function delChannel($channel_id) {
		if (! $channel_id || ! is_numeric ( $channel_id )) {
			return false;
		}
		$condition['channel_id']=$channel_id;
        $result = $this->where($condition)->delete();
		//$result = $db->delete ( self::getTableName(), $condition );
		return $result;
	}
	
	public function updateChannelInfo($channel_id,$channel_data) {
		if (! $channel_data || ! is_array ( $channel_data )) {
			return false;
		}
		$condition['channel_id']=$channel_id;
        $id = $this->where($condition)->save($channel_data);
		//$id = $db->update ( self::getTableName(), $channel_data,$condition );
	
		return $id;
	}

	public  function getChannelName() {
		$list = $this->field('channel_id,channel_name')->select ();
		$data = array();
		$data['0']="不限";
		foreach ( $list as $key => $value ) {
			$data [$value['channel_id']] = $value['channel_name'];
		}
		return $data;
	}
	// public function getChannelsByPage($start ,$page_size) {
	// 	$db=self::__instance();
	
	// 	$condition=array();
	
	// 	$condition["ORDER"]=" channel_id desc";
	// 	$condition['LIMIT']=array($start,$page_size);
	
	// 	$list = $db->select ( self::getTableName(), self::$columns, $condition);
	
	// 	if ($list) {
	// 		return $list;
	// 	}
	// 	return array ();
	// }
 //    //检索
    public function countSearch($channel_name,$user_id,$userGroupId) {
		if($channel_name!=""){
		  	$condition['channel_name']=array("like","%$channel_name%");
         }
		if ($user_id != "" && $userGroupId == '4') {
			$condition['user_id'] = $user_id;
		}
         //var_dump($condition);
         $num = $this->where($condition)->count();
         //var_dump($num);die;
        return $num;
     }
     //查询
    public function search($channel_name,$user_id,$userGroupId, $start ='' ,$page_size='' ) {
         $limit ="";
         $where = "";
         if($page_size){
             $limit =" limit $start,$page_size ";
         }
        // echo $channel_name;die;
         if($channel_name!=""){
             $where = " where channel_name like '%$channel_name%' ";
         }
		if ($channel_name!="" && $user_id != "" && $userGroupId == '4') {
			$where .= " and user_id = ".$user_id;
		}else if($channel_name =="" && $user_id != "" && $userGroupId == '4'){
			$where = " where user_id = ".$user_id;
		}
         $sql = "select * from ".$this->getTableName()." $where order by channel.channel_id desc $limit";
		//var_dump($sql);die;
		$Model = new \Think\Model();
         $list=$Model->query($sql);
         //var_dump($list);die;
         if ($list) {
             return $list;
        }
         return array ();
     }

     public function getAllChannel( $user_id,$userGroupId,$start ='' ,$page_size='' ) {
         $limit ="";
         if($page_size){
             $limit =" limit $start,$page_size ";
         }
		 if ($user_id != "" && $userGroupId == '4') {
			 $where = " where user_id = ".$user_id;
		 }
         $sql = "select * from ".$this->getTableName().$where."  order by channel.channel_id desc $limit";
	//	var_dump($sql);die;
		 $Model = new \Think\Model();
		 $list=$Model->query($sql);
         //var_dump($list);die;
         if ($list) {
             return $list;
         }
         return array ();
     }
    //分页
  /*  public function search($channel_name,$search,$user_id,$userGroupId){
        $perPage = 25;
        if ($search) {
            $where['channel_name'] = array('like', "%$channel_name%");

        }

        $count = $this->where($where)->count();
        $pageObj = new \Think\Page($count,$perPage);
        // 设置样式
        $pageObj->setConfig('first','首页');
        $pageObj->setConfig('prev', '上一页');
        $pageObj->setConfig('next', '下一页');
        $pageObj -> setConfig('header','<span class="rows">共 %TOTAL_ROW% 条记录</span>');
        $pageObj->setConfig('theme','%FIRST% %UP_PAGE%  &nbsp;%LINK_PAGE%&nbsp; %DOWN_PAGE% %END% %HEADER% ');
        $pageString = $pageObj->show();
        /************** 取某一页的数据 ***************/
  /*      $data = $this->order("channel_id desc")                    // 排序
        ->limit($pageObj->firstRow.','.$pageObj->listRows)
        ->where($where)            // 翻页
        ->select();
        
        /************** 返回数据 ******************/
  /*      return array(
            'data' => $data,  // 数据
            'page' => $pageString,  // 翻页字符串
        );
    }
*/
	//通过几个chan_id查找对应的channel_name
	public  function getAppsName($chan_id){
		$chanid_arr = explode(',', $chan_id);
		$lhq = in_array("-1",$chanid_arr);  //零花钱
		$condition['channel_id'] = array('in',$chanid_arr);
		//$sql="select GROUP_CONCAT(channel_name) as channel_name from".$this->getTableName()." where
		$list = $this->field('GROUP_CONCAT(channel_name) as channel_name')->where($condition)->select();
		if ($list[0]['channel_name'] == null  && $lhq) {
			return '零花钱';
		}else if($list[0]['channel_name']){
			if ($lhq) {
				return $list[0]['channel_name'].','.'零花钱';
			}else{
				return $list[0]['channel_name'];
			}
		}
		return '';
	}


	public  function getAppsNameByChanid($chan_id){
		$lhq = in_array("-1",$chan_id);  //零花钱
		$condition['channel_id'] = array('in',$chan_id);
		$list = $this->field('GROUP_CONCAT(channel_name) as channel_name')->where($condition)->select();
//var_dump($list);die;
		if ($list[0]['channel_name'] == null  && $lhq) {
			return '零花钱';
		}else if($list[0]['channel_name']){
			if ($lhq) {
				return $list[0]['channel_name'].','.'零花钱';
			}else{
				return $list[0]['channel_name'];
			}
		}
		return $list;
	}


	//通过几个chan_id查找对应的channel_name(编辑专用)
	public function getChansName($chan_id){
	//	$db=self::__instance();
		//echo 1212;
		//dump($chan_id);
		$chanid_arr = explode(',', $chan_id);
		$lhq = in_array("-1",$chanid_arr);  //零花钱
		$condition['channel_id'] = array('in',$chanid_arr);
		$list =$this->field('channel_name,group_concat(channel_name) as channel_name')->where($condition)->select();
		if ($list[0]['channel_name'] == null  && $lhq) {
			return '零花钱';
		}else if($list[0]['channel_name']){
			if ($lhq) {
				return $list[0]['channel_name'].','.'零花钱';
			}else{
				return $list[0]['channel_name'];
			}
		}
		return $list;
	}

}

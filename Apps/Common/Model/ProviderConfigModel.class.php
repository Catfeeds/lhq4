<?php
namespace Common\Model;
use Think\Model;

class ProviderConfigModel extends Model{
 
	public function getProviderConfigs() {
		
		//$sql="select ".self::$columns." from ".self::getTableName();
		$list = $this->select();
		//$list = $db->query($sql)->fetchAll();
		if ($list) {
			return $list;
		}
		return array ();		
	}
	
	public function getProviderConfigById($sample_id) {
		if (! $sample_id || ! is_numeric ( $sample_id )) {
			return false;
		}
	
		$sub_condition ["config_id"] = $sample_id;
        $list = $this->where($sub_condition)->select();
		//$list = $db->select ( self::getTableName(), self::$columns, $condition );
		if ($list) {
			return $list[0];
		}
		return array ();
	}

	public function getProviderConfigByAppid($app_id) {
		if (! $app_id || ! is_numeric ( $app_id )) {
			return false;
		}

		$sub_condition['app_id'] =  $app_id;
		$list = $this->where($sub_condition)->select();
		//$list = $db->select ( self::getTableName(), self::$columns, $condition );
		if ($list) {
			return $list;
		}
		return array ();
	}


	public function getProviderConfigByName($sample_name) {
		if (! $sample_name ) {
			return false;
		}
	
		$sub_condition ["config_name"] = $sample_name;
		$list = $this->where($sub_condition)->select();
		//$list = $db->select ( self::getTableName(), self::$columns, $condition );
		if ($list) {
			return $list;
		}
		return array ();
	}
	
	//根据app_id查询
	public  function getConfigConByAppid($appids) {
	
		if(!empty($appids)){
			$condition['app_id']=array('in',$appids);
		}
		
		//var_dump($condition);die;
		$list = $this->where($condition)->group('config_content')->order('config_id desc')->limit(5)->select();
		//var_dump($list);die;
		if ($list) {
			return $list;
		}
		return array ();
	}

	//根据app_id查询
	public  function getConfigIfByAppid($appids) {

		if(!empty($appids)){
			$condition['app_id']=array('in',$appids);
		}

		//var_dump($condition);die;
		$list = $this->where($condition)->group('config_if')->order('config_id desc')->limit(5)->select();
		//var_dump($list);die;
		if ($list) {
			return $list;
		}
		return array ();
	}

	public function addProviderConfig($sample_data) {
		if (! $sample_data || ! is_array ( $sample_data )) {
			return false;
		}
		$id = $this->data($sample_data)->add();
		//$id = $db->insert ( self::getTableName(), $sample_data );
		return $id;
	}
	
	public function delProviderConfig($sample_id) {
		if (! $sample_id || ! is_numeric ( $sample_id )) {
			return false;
		}
		$condition = array("config_id" => $sample_id);
		$result = $this->where($condition)->delete();
		//$result = $db->delete ( self::getTableName(), $condition );
		return $result;
	}

	public function delProviderConfigByAppid($app_id) {
		if (! $app_id || ! is_numeric ( $app_id )) {
			return false;
		}
		$condition['app_id'] =  $app_id;
		$result = $this->where($condition)->delete();
		//$result = $db->delete ( self::getTableName(), $condition );
		return $result;
	}

	public function updateProviderConfigInfo($sample_id,$sample_data) {
		if (! $sample_data || ! is_array ( $sample_data )) {
			return false;
		}
		$condition=array("config_id"=>$sample_id);
		$id = $this->where($condition)->save($sample_data);
		//$id = $db->update ( self::getTableName(), $sample_data,$condition );
		return $id;
	}
	
	// public function getProviderConfigsByPage($start ,$page_size) {
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
 //    //检索部分
 //    public function countSearch($config_name) {
 //        $db=self::__instance();
 //        $condition = array();
 //        if($config_name!=""){
 //            $condition['LIKE']=array("config_name"=>$config_name);
 //        }
 //        $num = $db->count( self::getTableName(), $condition);
 //        return $num;
 //    }
 //    //查询
 //    public function search($config_name, $start ='' ,$page_size='' ) {
 //        $db=self::__instance();
 //        $limit ="";
 //        $where = "";
 //        if($page_size){
 //            $limit =" limit $start,$page_size ";
 //        }
 //        //echo $provider_name;die;
 //        if($config_name!=""){
 //            $where = " where config_name like '%$config_name%' ";
 //        }

 //        $sql = "select * from ".self::getTableName()." $where order by provider_config.config_id desc $limit";
 //        $list=$db->query($sql)->fetchAll();
 //        //var_dump($list);
 //        if ($list) {
 //            return $list;
 //        }
 //        return array ();
 //    }

 //    public function getAllConfig( $start ='' ,$page_size='' ) {
 //        $db=self::__instance();
 //        $limit ="";
 //        if($page_size){
 //            $limit =" limit $start,$page_size ";
 //        }
 //        $sql = "select * from ".self::getTableName()."  order by provider_config.config_id desc $limit";

 //        $list=$db->query($sql)->fetchAll();
 //        //var_dump($list);
 //        if ($list) {
 //            return $list;
 //        }
 //        return array ();
 //    }

    //通过app_id查找config_content
    public function getProviderConfigByApp_id($appid) {
        $sub_condition = array();
        if($appid != ''){
            $sub_condition['app_id']=$appid;
        }
        $list = $this->field('config_content,config_if')->where($sub_condition)->select();
        if ($list) {
            return $list;
        }
        return array ();
    }
    //分页
    public function search($config_name,$search){
    	$perPage = 25;
		$provider_id=$_GET['provider_id'];
		//var_dump($provider_id,$config_name);
		if(!empty($provider_id)){
			$appArr=D('App')->getAppByPid($provider_id);
			foreach($appArr as $v){
				$appids[]=$v['app_id'];
			}
		}

    	if ($search) {
    		$where['config_name'] = array('like', "%$config_name%");
			if(!empty($appids)){
				$where['app_id']=array('in',$appids);
			}

    	}

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
		$data = $this->order("config_id desc")                    // 排序
		->limit($pageObj->firstRow.','.$pageObj->listRows)
		->where($where)            // 翻页
		->select();
		
		/************** 返回数据 ******************/
		return array(
			'data' => $data,  // 数据
			'page' => $pageString,  // 翻页字符串
		);
	}
}

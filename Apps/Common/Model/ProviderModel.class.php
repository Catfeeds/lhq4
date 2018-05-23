<?php
namespace Common\Model;
use Think\Model;

class ProviderModel extends Model {
	 
	public function getProviders() {
		
        $list = $this->order('provider_id desc')->select();
		//$list = $db->query($sql)->fetchAll();
		if ($list) {
			return $list;
		}
		return array ();		
	}
	
	public function getProvidersArray() {
	
		//$list = $db->select ( self::getTableName(), 'provider_id,provider_name');
        $list = $this->field('provider_id,provider_name')->select();
		$data = array();
        $data['0']='不限';
		foreach ( $list as $key => $value ) {
			$data [$value['provider_id']] = $value['provider_name'];
		}		
		return $data;
	}
	public function getProviderById($provider_id) {
		if (! $provider_id || ! is_numeric ( $provider_id )) {
			return false;
		}	
		$sub_condition ["provider_id"] = $provider_id;
		$list = $this->where($sub_condition)->select();
		//$list = $db->select ( self::getTableName(), self::$columns, $condition );
		if ($list) {
			return $list[0];
		}
		return array ();
	}

	public function getProvidersArrayById($providerid) {
		$sub_condition ["provider_id"] =array('in',$providerid);
		//$list = $db->select ( self::getTableName(), 'provider_id,provider_name');
		$list = $this->field('provider_id,provider_name')->where($sub_condition)->select();
		$data = array();
		$data['0']='不限';
		foreach ( $list as $key => $value ) {
			$data [$value['provider_id']] = $value['provider_name'];
		}
		return $data;
	}
//查询该用户的广告商
	public function getProvidersArrayByUserid($user_id) {
		$condition['user_id']=$user_id;
		//$list = $db->select ( self::getTableName(), 'provider_id,provider_name');
		$list = $this->field('provider_id,provider_name')->where($condition)->select();
		$data = array();
		$data['0']='不限';
		foreach ( $list as $key => $value ) {
			$data [$value['provider_id']] = $value['provider_name'];
		}
		return $data;
	}
	
	public function getProviderByName($provider_name) {
		if (! $provider_name ) {
			return false;
		}
		$sub_condition ["provider_name"] = $provider_name;
		$list = $this->where($sub_condition)->select();
		//$list = $db->select ( self::getTableName(), self::$columns, $condition );
		if ($list) {
			return $list;
		}
		return array ();
	}
	
	public function addProvider($provider_data) {
		if (! $provider_data || ! is_array ( $provider_data )) {
			return false;
		}
		$id = $this->data($provider_data)->add();
		//$id = $db->insert ( self::getTableName(), $provider_data );
		return $id;
	}
	
// 	public function delProvider($provider_id) {
// 		if (! $provider_id || ! is_numeric ( $provider_id )) {
// 			return false;
// 		}
// 		$db=self::__instance();
// 		$condition = array("provider_id" => $provider_id);
// 		$result = $db->delete ( self::getTableName(), $condition );
// 		return $result;
// 	}
	
	public function updateProviderInfo($provider_id,$provider_data) {
		if (! $provider_data || ! is_array ( $provider_data )) {
			return false;
		}
		$condition=array("provider_id"=>$provider_id);
		$id = $this->where($condition)->save($provider_data);
		//$id = $db->update ( self::getTableName(), $provider_data,$condition );
	
		return $id;
	}


//     public function getProvidersByPage($start ,$page_size) {
// 		$db=self::__instance();
	
// 		$condition=array();
	
// 		$condition["ORDER"]=" provider_id desc";
// 		$condition['LIMIT']=array($start,$page_size);
	
// 		$list = $db->select ( self::getTableName(), self::$columns, $condition);
	
// 		if ($list) {
// 			return $list;
// 		}
// 		return array ();
// 	}

     public function countSearch($provider_name) {
             if($provider_name!=""){
                 $condition['provider_name']=array("LIKE","%$provider_name%");
             }
         //var_dump($condition);
         $num = $this->where($condition)->count();
         //var_dump($num);die;
         return $num;
     }

	//查询该操作者的广告商
	public  function searchByUserid($provider_name, $user_id, $start = '', $page_size = ''){

		$limit = "";
		$where = "";
		if ($page_size) {
			$limit = " limit $start,$page_size ";
		}
		//echo $provider_name;die;
		if ($provider_name != "") {
			$where = "where provider_name like '%$provider_name%' and user_id= $user_id";
		}else{
			$where="where user_id=".$user_id;
		}
		$sql = "select * from ".$this->getTableName().   "  $where order by provider.provider_id desc $limit";
//var_dump($sql);die;
		$Model=new \Think\Model();
		$list = $Model->query($sql);
		//var_dump($list);
		if ($list) {
			return $list;
		}
		return array();
	}

	public  function countSearchByUserid($provider_name,$user_id){
		$condition ["user_id"] = $user_id;
		if ($provider_name != "") {
			$condition['provider_name'] = array("like" ,"%$provider_name%");
		}
		//	var_dump($condition);die;
		$num = $this->where($condition)->count();
		//var_dump($num);die;
		return $num;
	}

	//查询该操作者的所有广告商

	public  function getAllProviderByUserid($user_id,$start = '', $page_size = ''){
		$limit = "";
		if ($page_size) {
			$limit = " limit $start,$page_size ";
		}
		$sql = "select * from " .$this->getTableName() . " where user_id=".$user_id." order by provider.provider_id desc $limit";
		//var_dump($sql);die;
		$Model=new \Think\Model();
		$list=$Model->query($sql);
		//var_dump($list);
		if ($list) {
			return $list;
		}
		return array();
	}

//     //查询
     public function search($provider_name, $start ='' ,$page_size='' ) {
         $limit ="";
         $where = "";
         if($page_size){
             $limit =" limit $start,$page_size ";
         }
         //echo $provider_name;die;
             if($provider_name!=""){
                 $where = " where provider_name like '%$provider_name%' ";
             }

        $sql = "select * from ".$this->getTableName()." $where order by provider.provider_id desc $limit";
 //var_dump($sql);die;
		 $Model=new \Think\Model();
        $list=$Model->query($sql);
         //var_dump($list);
         if ($list) {
             return $list;
         }
         return array ();
     }

     public function getAllProvider( $start ='' ,$page_size='' ) {
         $limit ="";
         if($page_size){
             $limit =" limit $start,$page_size ";
         }
         $sql = "select * from ".$this->getTableName()."  order by provider.provider_id desc $limit";
		//var_dump($sql);die;
		 $Model=new \Think\Model();
		 $list=$Model->query($sql);
         //var_dump($list);
         if ($list) {
             return $list;
         }
         return array ();
     }

	//查询商务自己的广告主id
	public  function getSwProviderByUserid($user_id){
		$condition['user_id'] = $user_id ;
		$list = $this->field('provider_id')->where($condition)->select();
		foreach ($list as $k => $v) {
			$data[] = $v['provider_id'];
		}
		if ($data) {
			return $data;
		}

		return array();
	}

	//分页
 /*   public function search($provider_name,$search){
        $perPage = 25;
        if ($search) {
            $where['provider_name'] = array('like', "%$provider_name%");
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
   /*     $data = $this->order("provider_id desc")                    // 排序
        ->limit($pageObj->firstRow.','.$pageObj->listRows)
        ->where($where)            // 翻页
        ->select();
        
        /************** 返回数据 ******************/
 /*       return array(
            'data' => $data,  // 数据
            'page' => $pageString,  // 翻页字符串
        );
    }
*/
}

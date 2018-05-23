<?php
namespace Common\Model;
use Think\Model;
class AppModel extends Model{
//class Sample extends Base {
// 	private $table_name = 'app';
 
// 	private $columns = 'app_id,app_name,appstore_url,callback_url,provider_id,cutoff,adsid,chan_id,adtype_id,is_repeat,img,url_scheme';
 
	
// 	public function getTableName(){
// 		return self::$table_name;
// 	}
	 
// 	public function getApps() {

// 		$db=self::__instance();
		
// 		$sql="select ".self::$columns." from ".self::getTableName()." order by app_id desc";
// 		$list = $db->query($sql)->fetchAll();
// 		if ($list) {
// 			return $list;
// 		}
//         //var_dump($db->query($sql)->fetchAll());die;
// 		return array ();		
// 	}
	public function getAppsArray() {
        $list = $this->select();

		$data = array();
        $data['0']='不限';
		foreach ( $list as $key => $value ) {
			$data [$value['app_id']] = $value['app_name'];
            //$data [$value['app_id']] = $value['adtype_id'];
		}
		return $data;
	}
    //通过provider_id查找
    public function getAppByPid($provider_id) {

        if (! $provider_id || ! is_numeric ( $provider_id )) {
            return false;
        }
        
        $sub_condition ["provider_id"] = $provider_id;
        $list= $this->where($sub_condition)->select();

        if ($list) {
            return $list;
        }
        //var_dump($db->query($sql)->fetchAll());die;
        return array ();
    }
    //查询APP名
    public function getAppArray() {
        $list = $this->select();

        $data = array();
        $data['0']='不限';
        foreach ( $list as $key => $value ) {
            $data [$value['app_id']] = $value['app_name'];
            //$data [$value['app_id']] = $value['adtype_id'];
        }
        return $data;
    }
    //查询app_id，provider_id对应
    public function getProviders() {
        $list = $this->select();

        $data = array();
        $data['0']='不限';
        foreach ( $list as $key => $value ) {
            $data [$value['app_id']] = $value['provider_id'];
            //$data [$value['app_id']] = $value['adtype_id'];
        }
        return $data;
    }

 
//通过广告商id查询app
    public  function getappid($prividerId) {
        
        if($prividerId != ''){
            $sub_condition['provider_id']=$prividerId;
        }

        $callback = $this->field('app_id,app_name')->where($sub_condition)->select(); 
     
//        var_dump($condition["AND"]);//die;
        return $callback;
    }

    //查询所有provider_id
    public  function getPid() {

       $list= $this->field('provider_id')->group('provider_id')->select();

        if ($list) {
            return $list;
        }
        //var_dump($db->query($sql)->fetchAll());die;
        return array ();
    }

    //通过app_id查询provider_id
    public  function getPidByAppid($app_id) {
        if (! $app_id || ! is_numeric ( $app_id )) {
            return false;
        }
        $sub_condition ["app_id"] = $app_id;
        $list= $this->field('provider_id')->where($sub_condition)->select();

        if ($list) {
            return $list;
        }
        //var_dump($db->query($sql)->fetchAll());die;
        return array ();
    }

       //通过app_id查找对应的provider_id
    public  function getPidsByAppid($app_id) {
        $sub_condition ["app_id"] = array('in',$app_id);
        
        $list = $this->field('provider_id')->where($sub_condition)->select();
       
        if ($list) {
            return $list;
        }
        return array ();
    }

    //通过app_id查找img
    public function getAppimgArray() {
        $list = $this->select();
        $data = array();

        foreach ( $list as $key => $value ) {
            $data [$value['app_id']] = $value['img'];
            //$data [$value['app_id']] = $value['adtype_id'];
        }
        return $data;
    }

 //查找adtype_id
     public function getAdtypeIdArray() {
       /*  $db=self::__instance();

         $sql="select ".self::$columns." from ".self::getTableName();
         $list = $db->query($sql)->fetchAll();*/
         $list = $this->select();
         $data = array();
         //$data['0']='不限';
         foreach ( $list as $key => $value ) {
             //$data [$value['app_id']] = $value['app_name'];
             $data [$value['app_id']] = $value['adtype_id'];
         }
         return $data;
     }
    function getChannelAppsArray() {

    	$list = M('App')->field('app_id,app_name')->select();
    	$data = array();
    	$data['0']="不限";
    	foreach ( $list as $key => $value ) {
    		$data [$value['app_id']] = $value['app_name'];
    	}
    	return $data;
    }
	
	public function getAppById($app_id) {
		if (! $app_id || ! is_numeric ( $app_id )) {
			return false;
		}
	
		$sub_condition ["app_id"] = $app_id;
		$list = $this->where($sub_condition)->select();
		//$list = $db->select ( self::getTableName(), self::$columns, $condition );
		if ($list) {
			return $list[0];
		}
		return array ();
	}
// 	public function getAppNameByAdsid($adsid) {
// 		if (! $adsid || ! is_numeric ( $adsid )) {
// 			return false;
// 		}
	
// 		$sub_condition ["adsid"] = $adsid;
// 		$condition = array("AND" => $sub_condition);
// 		$db=self::__instance();
// 		$list = $db->select ( self::getTableName(), self::$columns, $condition );
// 		if ($list) {
// 			return $list[0]['app_name'];
// 		}
// 		return array ();
// 	}
	
	public function getAppByName($app_name) {
		if (! $app_name ) {
			return false;
		}	
		$sub_condition ["app_name"] = $app_name;
	    $list = $this->where($sub_condition)->select();
		//$list = $db->select ( self::getTableName(), self::$columns, $condition );
		if ($list) {
			return $list;
		}
		return array ();
	}
//     //搜索广告应用
    public function SelectAppByName($app_name) {

         if( $app_name!=""){
             $condition['app_name']=array('like', "%$app_name%");
         }
         $list = $this->field("app_id")->where($condition)->select();
         if ($list) {
             return $list;
         }
         return array ();
     }

       //根据appname和provider_id搜索广告应用
    public  function SelectAppByNamePid($app_name,$provider_id) {
        $condition ["provider_id"] = $provider_id;
        if( $app_name!=""){
            $condition['app_name']=array("like","%$app_name%");
            // var_dump($condition);//die;
        }
        //var_dump($condition);
        $list = $this->field('app_id')->where($condition)->select();
        
        if ($list) {
            return $list;
        }
        return array ();
    }

     ////     //搜索广告应用是否需要排重
    function SelectAppRepate($appid) {

        $sub_condition ["app_id"] = $appid;
        $list = $this->where($sub_condition)->Field("is_repeat,chan_id,provider_id,adtype_id")->find();
        if ($list) {
            return $list;
        }
        return array ();
    }

    //    ////     //搜索广告应用是否需要排重
    function SelectAdType($appid) {

        $sub_condition ["app_id"] = $appid;
        $list = $this->where($sub_condition)->Field("adtype_id,chan_id")->find();
        if ($list) {
            return $list;
        }
        return array ();
    }

	//添加数据
	public function addApp($app_data) {
		if (! $app_data || ! is_array ( $app_data )) {
			return false;
		}
		$id = $this->data($app_data)->add();
		//$id = $db->insert ( self::getTableName(), $app_data );
		return $id;
	}
	//删除
	public function delApp($app_id) {
		if (! $app_id || ! is_numeric ( $app_id )) {
			return false;
		}
		$condition = array("app_id" => $app_id);
        $result = $this->where($condition)->delete();
		//$result = $db->delete ( self::getTableName(), $condition );
		return $result;
	}
	//修改
	public function updateAppInfo($app_id,$app_data) {
		if (! $app_data || ! is_array ( $app_data )) {
			return false;
		}
		$condition=array("app_id"=>$app_id);
        $id = $this->where($condition)->save($app_data);
		//$id = $db->update ( self::getTableName(), $app_data,$condition );
	
		return $id;
	}

    //获取auto_increment值
    public  function getID() {

       // $where['TABLE_SCHEMA']=SAMPLE_DB_ID;
     //   $where['TABLE_NAME']=$this->getTableName();
        $Model = new \Think\Model();

       // $sql="SELECT auto_increment FROM information_schema.`TABLES` WHERE ". TABLE_SCHEMA."=".SAMPLE_DB_ID." AND ". TABLE_NAME."=".$this->getTableName();
        $sql="show table status like '".$this->getTableName()."'";
        $result=$Model->query($sql);
        $id=$result[0]['auto_increment'];
        return $id;
    }

// 	public function getAppIdByAdsId($adsid) {
//     if (! $adsid || ! is_numeric ( $adsid )) {
//         return false;
//     }

//     $sub_condition ["adsid"] = $adsid;
//     $condition = array("AND" => $sub_condition);
//     $db=self::__instance();
//     $list = $db->select ( self::getTableName(), self::$columns, $condition );
//     if ($list) {
//         return $list[0];
//     }
//     return array ();
// }
     public function countSearch($app_name,$provider_id) {

         if($provider_id >0  && $app_name!=""){
             $condition['provider_id']=$provider_id;
             $condition['app_name']=array("like","%$app_name%");
            // var_dump($condition);//die;
         }else{
             if($provider_id>0){
                 $condition['provider_id']=$provider_id;
            }
             if($app_name!=""){
                 $condition['app_name']=array("like","%$app_name%");
             }
         }

         //var_dump($condition);
         $num = $this->where($condition)->count();
         //var_dump($num);
         return $num;
     }

    public function countSearchByProid($app_name, $providerIds) {
       if(!empty($providerIds)){
           $condition['provider_id']=array('in',$providerIds);
       }else{
           $condition['provider_id']=$providerIds;
       }
        if($app_name!=""){
            $condition['app_name']=array("like","%$app_name%");
        }
        $num = $this->where($condition)->count();
        //var_dump($num);
        return $num;
    }

    public  function countSearchByChanid($app_name,$chanIds) {
        for($i=0;$i<count($chanIds);$i++){
            if (!empty($lian)) {
                $lian.=" or FIND_IN_SET(".$chanIds[$i].",chan_id)";
            }else{
                $lian = " where ( FIND_IN_SET(".$chanIds[$i].",chan_id)";
            }
        }
        if($app_name!=""){
            $lian1 = " and app_name  like '%$app_name%'" ;
        }
        $sql = "select count(*) as num from ".$this->getTableName().$lian.")".$lian1."order by app_id desc";
        //var_dump($sql);die;
        $Model=new \Think\Model();
        $list=$Model->query($sql);
        foreach($list as $v){
            $num =$v['num'];
        }
        return $num;
    }

     //查询
     public function search($app_name,$provider_id, $start ='' ,$page_size='' ) {
         $limit ="";
         $where = "";
         if($page_size){
             $limit =" limit $start,$page_size ";
         }
         //echo $provider_id;die;
         if($provider_id >0  && $app_name!=""){
             $where = " where provider.provider_id=$provider_id and app_name like '%$app_name%'";
             //var_dump($where);die;
         }else{
             if($provider_id>0){
                 $where = " where provider.provider_id=$provider_id ";
             }
            // echo $where;
             if($app_name!=""){
                 $where = " where app_name like '%$app_name%' ";
             }
         }
         $sql = "select * from ".$this->getTableName()." left join ".D('Provider')->getTableName()." on app.provider_id = provider.provider_id $where order by app.app_id desc $limit";
// var_dump($sql);die;
         $Model=new \Think\Model();
         $list=$Model->query($sql);
         //var_dump($list);
         if ($list) {
             return $list;
         }
         return array ();
     }

    public  function searchByProid($app_name,$providerIds, $start ='' ,$page_size='' ) {
        $condition['LIMIT']=array($start,$page_size);
        if($app_name!=""){
            $condition['app_name']=array("like","%$app_name%");
        }
        $order=" app_id desc";
        $condition['provider_id']=array('in',$providerIds);
        $list = $this->where($condition)->order($order)->limit($start,$page_size)->select();
        //  var_dump($list);die;
        if ($list) {
            return $list;
        }
        return array ();
    }

    public  function searchByChanid($app_name,$chanIds, $start ='' ,$page_size='' ) {
        $limit =" limit $start,$page_size ";
        for($i=0;$i<count($chanIds);$i++){
            if (!empty($lian)) {
                $lian.=" or FIND_IN_SET(".$chanIds[$i].",chan_id)";
            }else{
                $lian = " where ( FIND_IN_SET(".$chanIds[$i].",chan_id)";
            }
        }
        if($app_name!=""){
            $lian1 = " and app_name  like '%$app_name%'" ;
        }
        $sql = "select * from ".$this->getTableName().$lian.")".$lian1."order by app_id desc".$limit;
        //var_dump($sql);die;
        $Model=new \Think\Model();
        $list=$Model->query($sql);
        // var_dump($list);die;
        if ($list) {
            return $list;
        }
        return array ();
    }

     public function getAllApp( $start ='' ,$page_size='' ) {
         $limit ="";
         if($page_size){
            $limit =" limit $start,$page_size ";
         }
         $sql = "select * from ".$this->getTableName()." a left join ".D('Provider')->getTableName()." p on a.provider_id = p.provider_id order by a.app_id desc $limit";
         $Model=new \Think\Model();
         $list=$Model->query($sql);
         //var_dump($sql);//die;
         $list=$Model->query($sql);
         //var_dump($list);die;
         if ($list) {
             return $list;
         }
         return array ();
     }

    public  function getAllAppProid( $providerIds,$start ='' ,$page_size='' ) {
        $order=" app_id desc";
        if(!empty($providerIds)){
            $condition['provider_id']=array('in',$providerIds);
        }else{
            $condition['provider_id']=$providerIds;
        }

        $list = $this->where($condition)->order($order)->limit($start,$page_size)->select();
        //  $lists=array_merge($list);
        // var_dump($list);die;
        if ($list) {
            return $list;
        }
        return array ();
    }

   /* public function getCountByDate($app_name,$provider_id) {
        $db=self::__instance();
        //var_dump($db);die;
        $condition=array();
        //var_dump($condition);die;
        if($app_name != ''){
            //$sub_condition['app_name']=$app_name;
            $sub_condition['LIKE']=array("app_name"=>$app_name);
        }
        if($provider_id != ''){
            $sub_condition['provider_id']=$provider_id;
        }

        //$sub_condition["time[<>]"] =array($start_date,$end_date);
        $condition["OR"] = $sub_condition;
        //var_dump($condition);//die;

        $num = $db->count ( self::getTableName(),$condition );
        //var_dump($num);die;
        return $num;
    }

    public function count($app_name='',$provider_id='') {
        $db=self::__instance();

        $sub_condition = array();
        if($app_name != ''){
            $sub_condition['LIKE']=array("app_name"=>$app_name);
            //$sub_condition['app_name']=$app_name;
        }
        if($provider_id != ''){
            $sub_condition['provider_id']=$provider_id;
        }

        if(empty($sub_condition)){
            $condition = array();
        }else{
            $condition["OR"] = $sub_condition;
        }

        $num = $db->count ( self::getTableName(),$condition);
        return $num;
    }*/

// 	public function getAppsByPage($start ,$page_size) {
// 		$db=self::__instance();
	
// 		$condition=array();
	
// 		$condition["ORDER"]=" app_id desc";
// 		$condition['LIMIT']=array($start,$page_size);
	
// 		$list = $db->select ( self::getTableName(), self::$columns, $condition);
	
// 		if ($list) {
// 			return $list;
// 		}
// 		return array ();
// 	}
//     //查询广告应用列表
//     public function getChannelListById($channel_id) {
//         if (! $channel_id || ! is_numeric ( $channel_id )) {
//             return false;
//         }
//         $sub_condition ["chan_id"] = $channel_id;
//         $condition = array("AND" => $sub_condition);
//         $db=self::__instance();
//         $list = $db->select ( self::getTableName(), self::$columns, $condition );
//         //var_dump($list);die;
//         if ($list) {
//             return $list;
//         }
//         return array ();
//     }

//     public function getAppByImg() {

//         //
//         $db=self::__instance();
//         //$db=self::__instance(SAMPLE_DB_ID);
//         $sql="select ".self::$columns." from ".self::getTableName();
//         // var_dump($sql);die;
//         $list = $db->query($sql)->fetchAll();
//         //var_dump($list);//die;
//         $data = array();
//         foreach ( $list as $key => $value ) {
//             $data [$value['app_id']] = $value['img'];
//         }
//         return $data;
//     }
    //通过app_id查找img
    public function getUrlscheme() {
        $list = $this->select();
        $data = array();
        foreach ( $list as $key => $value ) {
            $data [$value['app_id']] = $value['url_scheme'];
        }
        return $data;
    }

    //appid获得'adsid','provider_id'
    public function getAdsidByAppid($appid) {
         if (!$appid) {
            return false;
        }
        $sub_condition = array();
        if($appid != ''){
            $sub_condition['app_id']=$appid;
        }
        $list = $this->field('adsid,provider_id,adtype_id')->where($sub_condition)->select();
        if ($list) {
            return $list;
        }
        return array ();
    }

    //appid获得'adsid','provider_id'
    public function getAppidByAdsid3($appid) {

        $sub_condition = array();
        if($appid != ''){
            $sub_condition['app_id']=$appid;
        }

        $list = $this->field('adsid,cutoff,provider_id')->where($sub_condition)->select();
       
        if ($list) {
            return $list;
        }
        return array ();
    }
//     //appid获得'adsid','provider_id'
//     public function getAppidByAdsid4($app) {
//         $db=self::__instance();
//         $condition=array();
//         $sub_condition = array();
//         if($app != ''){
//             $sub_condition['app_id']=$app;
//         }
//         if(empty($sub_condition)){
//             $condition = array();
//         }else{
//             $condition["AND"] = $sub_condition;
//         }
// //        $ad = $database->select('app', ['adsid','provider_id'],["app_id[=]"=>$appid]);
//         $list = $db->select ( self::getTableName(), "adsid,cutoff,provider_id", $condition);
//         if ($list) {
//             return $list;
//         }
//         return array ();
//     }

    //通过appid获取任务类型id
    public function getAdTypeId($appid) {
        if (! $appid || ! is_numeric ( $appid )) {
            return false;
        }
        $sub_condition ["app_id"] = $appid;
        $list = $this->field('adtype_id')->where($sub_condition)->select();
      
        if ($list) {
            return $list[0];
        }
        return array ();
    }
        //搜索广告应用
    public function SelectAppByNameChanid($app_name,$chan_id) {

        /*if ($chan_id != "") {
            $condition['chan_id']=$chan_id;
        }

        if( $app_name!=""){
            $condition['app_name'] = array('like', "%$app_name%");
        
        }

        $list = $this->field("app_id")->where($condition)->select();
        */
        if (!empty($chan_id) && !is_array($chan_id)) {
            $sql = "select app_id from ".$this->getTableName()." where app_name like '%".$app_name."%' and FIND_IN_SET(".$chan_id.",chan_id)";
        }else{
            $sql = "select app_id from ".$this->getTableName()." where app_name like '%".$app_name."%'";
        }
        $Model = new \Think\Model();
        $list=$Model->query($sql);
        //$list = $db->query($sql)->fetchAll();
        //$list = $db->select ( self::getTableName(), "app_id", $condition );
        if ($list) {
            return $list;
        }
        return array ();
    }



  
     //通过广告主广告id查询渠道
     public function getchanid($adsid) {
      //   $db=self::__instance();
       //  $condition=array();
         if($adsid != ''){
             $sub_condition['adsid']=$adsid;
         }

         //$condition["AND"] = $sub_condition;
        // $callback =  $db->select ( self::getTableName(), "chan_id", $condition);
         $callback = $this->field('chan_id')->where($sub_condition)->select();
 //        var_dump($condition["AND"]);//die;
         return $callback;
     }
//     //通过adsid，privider_id.chan_id 获取appid;

     public function getAPP($chan_id,$provider_id) {
       /*  $condition=array();
         if($adsid != ''){
             $sub_condition['adsid']=$adsid;
         }
         if($chan_id != ''){
             $sub_condition['chan_id']=$chan_id;
         }
         if($provider_id != ''){
             $sub_condition['provider_id']=$provider_id;
         }
*/
         $lian = "";
         if($chan_id != ''){
             $lian = " where FIND_IN_SET(".$chan_id.",chan_id)";
         }
         // if($chan_id != ''){
         //     if (!empty($lian)) {
         //         $lian.=" and FIND_IN_SET(".$chan_id.",chan_id)";
         //     }else{
         //         $lian = " where FIND_IN_SET(".$chan_id.",chan_id)";
         //     }
         // }
         if($provider_id != ''){
             if (!empty($lian)) {
                 $lian.=" and provider_id = ".$provider_id;
             }else{
                 $lian = " where provider_id=".$provider_id;
             }
         }
         $sql = "select app_id from ".$this->getTableName().$lian;
         //var_dump($sql);die;
         // if(empty($sub_condition)){
         //     $condition = array();
         // }else{
         //     $condition["AND"] = $sub_condition;
         // }

         //var_dump($condition);
         // var_dump($condition);
         $Model = new \Think\Model();
         $list=$Model->query($sql);
        // $list = M('app')->where($sub_condition)->field("app_id")->select();
        // var_dump($list);die;
         if ($list) {
             return $list;
         }
         return array ();
     }

    public  function getAPPByProid($providerIds) {

        if(!empty($providerIds)){
            $sub_condition['provider_id']=array('in',$providerIds);
        }else{
            $sub_condition['provider_id']=$providerIds;
        }

        $list =  $this->field('app_id')->where($sub_condition)->select ();
        // var_dump($list);die;
        if ($list) {
            return $list;
        }
        return array ();
    }

    //渠道不限时查询appid
    public  function getAPPByChanid($chanIds) {
        $lian = "";
        for($i=0;$i<count($chanIds);$i++){
            if (!empty($lian)) {
                $lian.=" or FIND_IN_SET(".$chanIds[$i].",chan_id)";
            }else{
                $lian = " where ( FIND_IN_SET(".$chanIds[$i].",chan_id)";
            }
        }

        $sql = "select app_id from ".$this->getTableName().$lian.")".$lian1." group by app_id";
        // var_dump($sql);die;
        $Model=new \Think\Model();
        $list= $Model->query($sql);
       // var_dump($list);die;
        // $list = $db->select ( self::getTableName(), "app_id", $condition );
        if ($list) {
            return $list;
        }
        return array ();

    }

    //通过appid查找
    public  function getCutoff($app_id){
        if($app_id != ''){
            $condition['app_id']=$app_id;
        }
        $list =  $this->field('cutoff')->where($condition)->select ();
      //  var_dump($list);die;
        return $list;
    }
 /*   //分页
    public function search($channel_id,$app_name,$provider_id,$search){
        $perPage = 25;
        if ($search) {
            if ($provider_id > 0) {    
                $where['app.provider_id'] = $provider_id;
            }
            if ($app_name != "") {
                $where['app.app_name'] = array('like', "%$app_name%");
            }
            $join = "LEFT JOIN provider ON app.provider_id =provider.provider_id";
        }
        if ($channel_id != "") {
            $where['chan_id'] = $channel_id;
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
   /*     $data = $this
        ->join($join)
        ->order("app_id desc")                    // 排序
        ->where($where)            
        ->limit($pageObj->firstRow.','.$pageObj->listRows)// 翻页
        ->select();       
        /************** 返回数据 ******************/
  /*      return array(
            'data' => $data,  // 数据
            'page' => $pageString,  // 翻页字符串
        );
    }
*/
}

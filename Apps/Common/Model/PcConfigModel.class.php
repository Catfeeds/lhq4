<?php
namespace Common\Model;
use Think\Model;
class PcConfigModel extends Model{

	// private  $table_name = 'pc_config';

	// private  $columns = 'config_id,app_id,request,repeat_url,config_if,config_name';

	// public  function getTableName(){
	// 	return self::$table_name;
	// }

	 //用$app_id获取repeat_url
    public function getRepeat_urlByappid($appid) {
        if (!$appid) {
            return false;
        }  
        if($appid != ''){
        	$sub_condition['app_id']=$appid;
        }    
        $pcconfig = $this->field('request,repeat_url,config_if')->where($sub_condition)->select();  
        return $pcconfig;
    }
    //查询排重配置类型
    //用$app_id获取repeat_url
    public function getRepeat_type($appid) {
        if (!$appid) {
            return false;
        }
        if($appid != ''){
            $sub_condition['app_id']=$appid;
        }
        $pcconfig = $this->field('config_type')->where($sub_condition)->select();
        return $pcconfig;
    }

 //    public  function getPcConfigs() {
    
 //        $db=self::__instance();
 //        //$db=self::__instance(SAMPLE_DB_ID);
        
 //        $sql="select ".self::$columns." from ".self::getTableName();
 //        $list = $db->query($sql)->fetchAll();
 //        if ($list) {
 //            return $list;
 //        }
 //        return array ();        
 //    }

 //    public  function getPcConfigsByPage($start ,$page_size) {
 //        $db=self::__instance();
    
 //        $condition=array();
    
 //        $condition["ORDER"]=" config_id desc";
 //        $condition['LIMIT']=array($start,$page_size);
    
 //        $list = $db->select ( self::getTableName(), self::$columns, $condition);
    
 //        if ($list) {
 //            return $list;
 //        }
 //        return array ();
 //    }

    public  function addPcConfig($sample_data) {
        if (! $sample_data || ! is_array ( $sample_data )) {
            return false;
        }
        $id = $this->data($sample_data)->add();
        //$id = $db->insert ( self::getTableName(), $sample_data );
        return $id;
    }
    
    public  function delPcConfig($sample_id) {
        if (! $sample_id || ! is_numeric ( $sample_id )) {
            return false;
        }
        $condition = array("config_id" => $sample_id);
        $result = $this->where($condition)->delete();
        //$result = $db->delete ( self::getTableName(), $condition );
        return $result;
    }

    public  function delPcConfigByAppid($app_id) {
        if (! $app_id || ! is_numeric ( $app_id )) {
            return false;
        }
        $condition['app_id'] =  $app_id;
        $result = $this->where($condition)->delete();
        //$result = $db->delete ( self::getTableName(), $condition );
        return $result;
    }
    
    public  function updatePcConfigInfo($sample_id,$sample_data) {
        if (! $sample_data || ! is_array ( $sample_data )) {
            return false;
        }
        $condition=array("config_id"=>$sample_id);
        $id = $this->where($condition)->save($sample_data);
        //$id = $db->update ( self::getTableName(), $sample_data,$condition );
    
        return $id;
    }

    public  function getPcConfigByName($sample_name) {
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

    //根据app_id查询
    public  function getConfigConByAppid($appids) {

        if(!empty($appids)){
            $condition['app_id']=array('in',$appids);
        }
        //var_dump($condition);die;
        $list = $this->where($condition)->group('repeat_url')->order('config_id desc')->limit(5)->select();
        //var_dump($list);die;
        if ($list) {
            return $list;
        }
        return array ();
    }

    public  function getPcConfigById($sample_id) {
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

    public  function getPcConfigByAppid($app_id) {
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

    //通过appId查询是否有排重
    public function getCongigIdArray() {
        $list = $this->select();
        $data = array();

        foreach ( $list as $key => $value ) {
            $data [$value['app_id']] = $value['config_id'];
            //$data [$value['app_id']] = $value['adtype_id'];
        }
        return $data;
    }

 //    //检索部分
 //    public  function countSearch($config_name) {
 //        $db=self::__instance();
 //        $condition = array();
 //        if($config_name!=""){
 //            $condition['LIKE']=array("config_name"=>$config_name);
 //        }
 //        $num = $db->count( self::getTableName(), $condition);
 //        return $num;
 //    }
 //    //查询
 //    public  function search($config_name, $start ='' ,$page_size='' ) {
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

 //        $sql = "select * from ".self::getTableName()." $where order by pc_config.config_id desc $limit";
 //        $list=$db->query($sql)->fetchAll();
 //        //var_dump($list);
 //        if ($list) {
 //            return $list;
 //        }
 //        return array ();
 //    }

 //    public  function getAllConfig( $start ='' ,$page_size='' ) {
 //        $db=self::__instance();
 //        $limit ="";
 //        if($page_size){
 //            $limit =" limit $start,$page_size ";
 //        }
 //        $sql = "select * from ".self::getTableName()."  order by pc_config.config_id desc $limit";

 //        $list=$db->query($sql)->fetchAll();
 //        //var_dump($list);
 //        if ($list) {
 //            return $list;
 //        }
 //        return array ();
 //    }
     //分页
    public function search($config_name,$search){
        $perPage = 25;
        $provider_id=$_GET['provider_id'];
        if(!empty($provider_id)){
            $appArr=D('App')->getAppByPid($provider_id);
            foreach ($appArr as $v) {
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
?>
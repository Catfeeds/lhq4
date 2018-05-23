<?php
namespace Common\Model;
use Think\Model;

class ChannelCallbackLogModel extends Model {

	 public function getChannelLogs() {
	 	$sql="select * from channel_callback_log order by id desc";
	 	$list = $this->query($sql);
         //var_dump($list);die;
	 	if ($list) {
	 		return $list;
	 	}
	 	return array ();		
	 }


	 public function getCountByDate($app_id,$chan_id,$provider_id,$idfa,$start_date,$end_date) {
	 	if($app_id != ''){
	 		$sub_condition['app_id']=array("in",$app_id);
	    }
	 	if($chan_id != ''){
	 		$sub_condition['chan_id']=$chan_id;
	 	}
        if($provider_id!=''){
            $sub_condition['provider_id']=$provider_id;
        }

	 	if($start_date != "" && $end_date == ""){
            $sub_condition["time"] =array("egt",$start_date);
        }
        if($start_date == "" && $end_date != ""){
            $sub_condition["time"] =array("elt",$end_date);
        }
        if($start_date != "" && $end_date != ""){
            $sub_condition["time"] =array(array("egt",$start_date),array("elt",$end_date),'and');
        }
        if($idfa != ''){
            $sub_condition['idfa']=$idfa;
        }
	 	$num = $this->where($sub_condition)->count();
	 	return $num;
	 }
	
	 public function getCount($app_id='',$chan_id,$provider_id,$idfa) {
	 	$sub_condition = array();
	 	if($app_id != ''){
	 		$sub_condition['app_id']=array("in",$app_id);
	 	}
	 	if($chan_id != ''){
	 		$sub_condition['chan_id']=$chan_id;
	 	}
        if($provider_id!=''){
            $sub_condition['provider_id']=$provider_id;
        }
        if($idfa != ''){
            $sub_condition['idfa']=$idfa;
        }
	 	$num = $this->where($sub_condition)->count();
	 	return $num;
	 }
	
	//获得渠道激活数
	public function getChannelCallbackByTime($app_name, $chan_id,$start_date,$end_date,$search) {
         if ($search) {
           if (!empty($app_name)) {
                $appName = D('App')->SelectAppByNameChanid($app_name,$chan_id);

                $aName=array();
                foreach ($appName as $k => $v) {
                    foreach ($v as $key => $value) {
                        $aName[] = $value;
                    }
                }
                $app_id = $aName;  

                if (empty($app_id)) {
                 	$where['app_id'] = '';
                }else{
                    $where['app_id'] = array('in',$app_id);
                } 

            }
            if ($chan_id !='') {
                $where['chan_id'] = $chan_id;
            }
            if ($start_date == '' && $end_date != '') {
                $where['time'] = array('elt',$end_date);
            }
            if ($start_date != '' && $end_date == '') {
                $where['time'] = array('egt',$start_date);;
            }
            if ($start_date != '' && $end_date != '') {
               $where["time"] = array(array('egt',$start_date),array('elt',$end_date));
            }
            if( $chan_id == '' && $app_id != ''){
                $group= "chan_id";
            } else if ($app_id == '' && $chan_id != ''){
                $group="app_id";
            } else if ($app_id == '' && $chan_id == '') {
                $group="app_id,chan_id";
            }
        }else{
            $group = 'app_id,chan_id';
        }
                   
		$list = $this 
        ->field('count(*) as bcount,app_id,chan_id')
        ->where($where)
        ->group($group)                  // 排序    
        ->select(); 

        if ($list['0']["count"] == '0') {
        	return array ();
		}else{
			return $list;
		}
		
	}

    //获得广告商激活回调数  商务数据
    public  function getChannelCallbackProviderId($app_id,$provider_id,$start_date,$end_date) {
        $where = "";
        if(!empty($provider_id)){
            for($i=0;$i<count($provider_id);$i++){
                $uname=$uname."'".$provider_id[$i]."',";
            }
            $uname = rtrim($uname, ",");
            $where =" where provider_id in(".$uname.")";

        }
        if($start_date !='' && $end_date !=''){
            if (!empty($where)) {
                $where = $where." and time >='$start_date' and time <='$end_date' ";
            }else{
                $where =" where time >='$start_date' and time <='$end_date' ";
            }
        }
        $group=" group by app_id";
        $order = " order by app_id desc";
        $sql = "select count(*) as bcount,app_id,chan_id,provider_id from ".$this->getTableName().$where.$group.$order;
        $Model = new \Think\Model();
        $list=$Model->query($sql);
        if ($list) {
            return $list;
        }
        return array ();
    }

    //获得广告商激活回调数  媒介数据
    public  function getChannelCallbackByChanId($app_id,$chanid,$start_date,$end_date) {

        $where = "";
        if(!empty($provider_id)){
            for($i=0;$i<count($chanid);$i++){
                $uname=$uname."'".$chanid[$i]."',";
            }
            $uname = rtrim($uname, ",");
            $where =" where chan_id in(".$uname.")";

        }
        if($start_date !='' && $end_date !=''){
            if (!empty($where)) {
                $where = $where." and time >='$start_date' and time <='$end_date' ";
            }else{
                $where =" where time >='$start_date' and time <='$end_date' ";
            }
        }
        $group=" group by app_id,chan_id";
        $order = " order by app_id desc";
        $sql = "select count(*) as bcount,app_id,chan_id,provider_id from ".$this->getTableName().$where.$group.$order;
        $Model = new \Think\Model();
        $list=$Model->query($sql);
        if ($list) {
            return $list;
        }
        return array ();
    }

    //获得广告商激活回调数  商务数据
    public  function getChannelCallbackForSw($app_id, $provider_id,$start_date='',$end_date='') {

        $where = "";
        if($app_id != ''){
            $where =" where app_id = ".$app_id;
        }
        if(!empty($provider_id)){
            if(is_array($provider_id)){
                for($i=0;$i<count($provider_id);$i++){
                    $uname=$uname."'".$provider_id[$i]."',";
                }
                $uname = rtrim($uname, ",");
            }
            if (!empty($where)) {
                if (is_array($provider_id)) {

                    $where = $where." and provider_id in(".$uname.")";
                }else{
                    $where = $where." and provider_id=".$provider_id;
                }
            }else{
                if (is_array($provider_id)) {
                    $where =" where provider_id in(".$uname.")";
                }else{
                    $where =" where provider_id = ".$provider_id;
                }
            }
        }
        if($start_date !=''){
            if (!empty($where)) {
                $where = $where." and time >='$start_date'";
            }else{
                $where =" where time >='$start_date'";
            }
        }
        if($end_date !=''){
            if (!empty($where)) {
                $where = $where." and time <='$end_date'";
            }else{
                $where =" where time <= '$end_date'";
            }
        }


        $group=" group by app_id";

        $order = " order by app_id desc";
        // $list = $db->select ( self::getTableName(), "count(*) as count,app_id,chan_id", $condition);
        $sql = "select count(*) as bcount,app_id,chan_id from ".$this->getTableName().$where.$group.$order;
        // var_dump($sql);
        $Model = new \Think\Model();
        $list=$Model->query($sql);
        if ($list) {
            return $list;
        }
        return array ();
    }

    //获取pid
    public function getPids(){
        $list=$this->field('provider_id')->group('provider_id')->select();
        if($list){
            return $list;
        }
        return array();
    }

	// //获得渠道点击数
	// public function getChannelCallbackCount($app_id, $chanid) {
	// 	$db=self::__instance();
	
	// 	$condition=array();
	// 	$sub_condition = array();
	// 	if($app_id != ''){
	// 		$sub_condition['app_id']=$app_id;
	// 	}
	// 	if($chanid != '' ){
	// 		$sub_condition['chan_id']=$chanid;
	// 	}
	// 	if(empty($sub_condition)){
	// 		$condition = array();
	// 	}else{
	// 		$condition["AND"] = $sub_condition;
	// 	}
	// 	if( $chanid == '' && $app_id != '' ){
	// 		$condition["GROUP"]=" chan_id";
	// 	} else if($app_id == '' && $chanid != '' ){
	// 		$condition["GROUP"]=" app_id";
	// 	} else if($app_id == '' && $chanid == '') {
	// 		$condition["GROUP"]=" app_id,chan_id";
	// 	}
	
	// 	$list = $db->select ( self::getTableName(), "count(*) as count,app_id,chan_id,provider_id", $condition);
	// 	if ($list) {
	// 		return $list;
	// 	}
	// 	return array ();
	// }
	
	 public function getLogs($app_id,$chanid,$provider_id,$idfa,$start ,$page_size,$start_date='',$end_date='') {
	 	$sub_condition = array();
	 	if($app_id != ''){
	 		$sub_condition['app_id']=array("in",$app_id);
	 	}
	 	if($chanid != ''){
	 		$sub_condition['chan_id']=$chanid;
	 	}
        if($provider_id!=''){
            $sub_condition['provider_id']=$provider_id;
        }

	 	if($start_date != "" && $end_date == ""){
            $sub_condition["time"] =array("egt",$start_date);
        }
        if($start_date == "" && $end_date != ""){
            $sub_condition["time"] =array("elt",$end_date);
        }
        if($start_date != "" && $end_date != ""){
            $sub_condition["time"] =array(array("egt",$start_date),array("elt",$end_date),'and');
        }
        if($idfa != ''){
            $sub_condition['idfa']=$idfa;
        }
//	 	$condition["ORDER"]=" id desc";
//	 	$condition['LIMIT']=array($start,$page_size);
 //        //var_dump($condition);die;

	 	$list = M('channel_callback_log')->where($sub_condition)->order("id desc")->limit($start,$page_size)->select();
	
	 	if ($list) {
	 		return $list;
	 	}
	 	return array ();
	 }

     //渠道实际回调量
     public function channelCalllogCount($app_id) {
       //  $db=self::__instance();
         $condition=array();
         if($app_id){
             $condition['app_id']=$app_id;
         }
         //echo $id;die;
        // $list=$db->select ( self::getTableName(), "*",$condition);
         $list = $this->where($condition)->select();
         //var_dump($list);die;
         return $list;
     }
    //添加
    public function addChannelCallbackrLog($plog_data) {
        if (! $plog_data || ! is_array ( $plog_data )) {
            return false;
        }
        $id = $this->data($plog_data )->add();
        
        return $id;
    }

    //查询所有的数据
    public function getChanLog($start, $page_size){
        $list = $this->limit($start, $page_size)->order('id desc')->select();

        if ($list) {
            return $list;
        }
        return array();
    }
    //查询所有的数据的条数
    public function CountgetLog(){
        $num = $this->count();
        // dump($num);//die;
        return $num;
    }

    //特殊情况查询
    public function getCountByDateLog($chan_id,$provider_id,$idfa,$start_date,$end_date) {

        if($chan_id != ''){
            $sub_condition['chan_id']=$chan_id;
        }
        if($provider_id!=''){
            $sub_condition['provider_id']=$provider_id;
        }

        if($idfa != ''){
            $sub_condition['idfa']=$idfa;
        }
        if($start_date != "" && $end_date == ""){
            $sub_condition["time"] =array("egt",$start_date);
        }
        if($start_date == "" && $end_date != ""){
            $sub_condition["time"] =array("elt",$end_date);
        }
        if($start_date != "" && $end_date != ""){
            $sub_condition["time"] =array(array("egt",$start_date),array("elt",$end_date),'and');
        }

        $num = $this->where($sub_condition)->count();
        return $num;
    }

    public function getCountLog($chanid='',$provider_id="",$idfa='') {
        $sub_condition = array();

        if($chanid != ''){
            $sub_condition['chan_id']=$chanid;
        }
        if($provider_id!=''){
            $sub_condition['provider_id']=$provider_id;
        }
        if($idfa != ''){
            $sub_condition['idfa']=$idfa;
        }
//dump($sub_condition);
        $num = $this->where($sub_condition)->count();
        //   dump($num);
        return $num;
    }
    public function getLogsLog($chan_id,$provider_id,$idfa,$start ,$page_size,$start_date='',$end_date='') {
        $sub_condition = array();

        if($chan_id != ''){
            $sub_condition['chan_id']=$chan_id;
        }
        if($provider_id!=''){
            $sub_condition['provider_id']=$provider_id;
        }
        if($start_date != "" && $end_date == ""){
            $sub_condition["time"] =array("egt",$start_date);
        }
        if($start_date == "" && $end_date != ""){
            $sub_condition["time"] =array("elt",$end_date);
        }
        if($start_date != "" && $end_date != ""){
            $sub_condition["time"] =array(array("egt",$start_date),array("elt",$end_date),'and');
        }
        if($idfa != ''){
            $sub_condition['idfa']=$idfa;
        }

        $list =$this->where($sub_condition)->order("id desc")->limit($start,$page_size)->select();
        // var_dump($chan_id);die;

        if ($list) {
            return $list;
        }
        return array ();
    }
    //查询显示的导出数据
    public function getOutPutChannelCallback($app_id, $start_time, $end_time){
        $sub_condition = array();
        if($app_id != ''){
            $sub_condition['app_id']=array("in",$app_id);
        }
        if($start_time !='' && $end_time !=''){
            $sub_condition["time"] =array(array("egt",$start_time),array("elt",$end_time),"and");
        }elseif($start_time !='' && $end_time ==''){
            $sub_condition['time'] = array("egt",$start_time);
        }elseif($start_time =='' && $end_time !=''){
            $sub_condition['time'] = array("elt",$end_time);
        }
        $condition=" app_id";

        $list = $this->field("count(*) as count,app_id,chan_id")->where($sub_condition)->group($condition)->select();
        if ($list) {
            return $list;
        }
        return array ();
    }
        //导出数据
    public function getOutputLogs($appid,$start_time, $end_time){
        if ($appid != '') {
            $condition['app_id'] = array("in",$appid);
        }
        if($start_time !='' && $end_time !=''){
            $condition["time"] =array(array("egt",$start_time),array("elt",$end_time),"and");
        }elseif($start_time !='' && $end_time ==''){
            $condition['time'] = array("egt",$start_time);
        }elseif($start_time =='' && $end_time !=''){
            $condition['time'] = array("elt",$end_time);
        }
        $list = $this->field("idfa,time")->where($condition)->order("id desc")->select();
        if ($list) {
            return $list;
        }
        return array ();
    }
}

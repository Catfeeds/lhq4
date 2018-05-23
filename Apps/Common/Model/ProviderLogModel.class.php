<?php
namespace Common\Model;
use Think\Model;
class ProviderLogModel extends Model {

 	public function getProviderLogs() {
		
 		$sql="select * from provider_log order by id desc";
 		$list = M("provider_log")->query($sql);
 		if ($list) {
 			return $list;
 		}
 		return array ();		
 	}
    //添加
	public function addProviderLog($plog_data) {
		if (! $plog_data || ! is_array ( $plog_data )) {
			return false;
		}
		$id = $this->data($plog_data)->add();
		
		return $id;
	}
// 	//获得渠道激活数
// 	public function getProviderActiveCount($app_id, $chanid) {
// 		//select count(*) from provider_log where app_id ='5'  and chan_id='2';
// 		//select count(*),app_id,chan_id from channel_log GROUP BY app_id,chan_id;
// 		//var_dump($v);
// 		$db=self::__instance();
// 		$condition=array();
// 		if($app_id != ''){
// 			$sub_condition['app_id']=$app_id;
// 		}
// 		if($chanid != ''){
// 			$sub_condition['chan_id']=$chanid;
// 		}

// 		$condition["AND"] = $sub_condition;
		
// 		if( $chanid == '' && $app_id != '' ){
// 			$condition["GROUP"]=" chan_id";
// 		} else if($app_id == '' && $chanid != '' ){
// 			$condition["GROUP"]=" app_id";
// 		} else if($app_id == '' && $chanid == '') {
// 			$condition["GROUP"]=" app_id,chan_id";
// 		}
//        // var_dump($condition);
// 		$list =  $db->select ( self::getTableName(), "count(*) as count,app_id,chan_id", $condition);
// 		//var_dump($list);//die;
//         return $list;
// 	}
	
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
	
 	public function getCount($app_id='',$provider_id="",$chanid='',$idfa='') {
 		$sub_condition = array();
 		if($app_id != ''){
             $sub_condition['app_id']=array("in",$app_id);
 		}
 		if($chanid != ''){
 			$sub_condition['chan_id']=$chanid;
 		}
        if($provider_id != ''){
            $sub_condition['provider_id']=$provider_id;
        }
 		if($idfa != ''){
 			$sub_condition['idfa']=$idfa;
 		}
       // var_dump($sub_condition);die;
 		$num = $this->where($sub_condition)->count();

         return $num;
 	}
//获取pid
    public function getPids(){
        $list=$this->field('provider_id')->group('provider_id')->select();
        if($list){
            return $list;
        }
        return array();
    } 
//     //方法分离
//     public function getCountByDate1($adsid,$chan_id,$provider_id,$start_date,$end_date) {
//         $db=self::__instance();
//         $condition=array();

//         if($chan_id != ''){
//             $sub_condition['chan_id']=$chan_id;
//         }
//         if($provider_id != ''){
//             $sub_condition['provider_id']=$provider_id;
//         }
//         if($adsid != ''){
//             $sub_condition['adsid']=$adsid;
//         }
//         //echo $idfa;die;
//         $sub_condition["time[<>]"] =array($start_date,$end_date);
//         $condition["AND"] = $sub_condition;
//         var_dump($condition);
//         $num = $db->count ( self::getTableName(),$condition );
//         //var_dump($num);die;
//         return $num;
//     }
//     public function count1($adsid='',$chan_id='',$provider_id='') {
//         $db=self::__instance();

//         $sub_condition = array();

//         if($chan_id != ''){
//             $sub_condition['chan_id']=$chan_id;
//         }

//         if($adsid != ''){
//             $sub_condition['adsid']=$adsid;
//         }
//         if($provider_id != ''){
//             $sub_condition['provider_id']=$provider_id;
//         }
//         if(empty($sub_condition)){
//             $condition = array();
//         }else{
//             $condition["AND"] = $sub_condition;
//         }

//         //var_dump($condition);
//         $num = $db->count ( self::getTableName(),$condition);
//         //var_dump($db->count ( self::getTableName(),$condition));die;
//         return $num;
//     }
	
 	public function getLogs($app_id,$chan_id,$provider_id,$idfa,$start ,$page_size,$start_date='',$end_date='') {
 		$sub_condition = array();
 		if($app_id != ''){
 			$sub_condition['app_id']=array("in",$app_id);
 		}
 		if($chan_id != ''){
 			$sub_condition['chan_id']=$chan_id;
 		}
        if($provider_id != ''){
            $sub_condition['provider_id']=$provider_id;
        }
         /*if($adsid != ''){
             //$sub_condition['idfa']=$idfa;
             $sub_condition['adsid']=$adsid;
         }*/

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

 		$list = M('provider_log')->where($sub_condition)->order("id desc")->limit($start,$page_size)->select();
        // var_dump($chan_id);die;

 		if ($list) {
 			return $list;
 		}
 		return array ();
 	}
     public function getOutputLogs($appid,$start_date='',$end_date='') {

         $sub_condition = array();
         if ($appid != '') {
            $sub_condition['app_id'] = array("in",$appid);
        }
         if ($start_date == '' && $end_date != '') {
             $sub_condition['time'] = array('elt',$end_date);
         }
         if ($start_date != '' && $end_date == '') {
             $sub_condition['time'] = array('egt',$start_date);;
         }
         if ($start_date != '' && $end_date != '') {
             $sub_condition["time"] = array(array('egt',$start_date),array('elt',$end_date));
         }
      /*   if(empty($sub_condition)){
             $condition = array();
         }else{
             $condition["AND"] = $sub_condition;
         }*/

        // var_dump($condition);
         $list = M('provider_log')->field("idfa,time")->where($sub_condition)->order("id desc")->select();
         //var_dump($list);die;

         if ($list) {
             return $list;
         }
         return array ();
     }


    public function getTrueOutputLogs($appid,$chan_id,$start_date='',$end_date='') {

        $sub_condition = array();
        if ($appid != '') {
            $sub_condition['app_id'] = array("in",$appid);
        }
        if($chan_id != ''){
            $sub_condition['chan_id']=$chan_id;
        }
        if ($start_date == '' && $end_date != '') {
            $sub_condition['time'] = array('elt',$end_date);
        }
        if ($start_date != '' && $end_date == '') {
            $sub_condition['time'] = array('egt',$start_date);;
        }
        if ($start_date != '' && $end_date != '') {
            $sub_condition["time"] = array(array('egt',$start_date),array('elt',$end_date));
        }
        /*   if(empty($sub_condition)){
               $condition = array();
           }else{
               $condition["AND"] = $sub_condition;
           }*/

        // var_dump($condition);
        $list = M('provider_log')->field("idfa,time")->where($sub_condition)->order("id desc")->select();
        //var_dump($list);die;

        if ($list) {
            return $list;
        }
        return array ();
    }

    //获得广告商激活回调成功数
    public  function getSuccessProviderActiveByTime($app_id, $chanid,$start_date='',$end_date='') {
        //select count(*) from provider_log where app_id ='5'  and chan_id='2';
        //select count(*),app_id,chan_id,provider_id from channel_log GROUP BY app_id,chan_id;

        $where = "";
        if($app_id != ''){
            $where =" where app_id = ".$app_id;
        }

        if(!empty($chanid)){
            if(is_array($chanid)){
                for($i=0;$i<count($chanid);$i++){
                    $uname=$uname."'".$chanid[$i]."',";
                }
                $uname = rtrim($uname, ",");
            }
            if (!empty($where)) {
                if (is_array($chanid)) {

                    $where = $where." and chan_id in(".$uname.")";
                }else{
                    $where = $where." and chan_id=".$chanid;
                }
            }else{
                if (is_array($chanid)) {
                    $where =" where chan_id in(".$uname.")";
                }else{
                    $where =" where chan_id = ".$chanid;
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

        if (!empty($where)) {
            $where = $where." and own_result ='{\"message\":\"success\",\"success\":true}'";
        }else{
            $where =" where own_result = '{\"message\":\"success\",\"success\":true}'";
        }

        if(is_array($chanid)){
            $group=" group by app_id,chan_id";
        }else{
            if( $chanid == '' && $app_id != ''){
                $group=" group by chan_id";
            } else if($app_id == '' && $chanid != ''){
                $group=" group by app_id";
            } else if($app_id == '' && $chanid == '') {
                $group=" group by app_id,chan_id";
            }
        }
        $order = " order by app_id desc,chan_id desc";
        // $list = $db->select ( self::getTableName(), "count(*) as count,app_id,chan_id", $condition);
        $sql = "select count(*) as pcount,app_id,chan_id from ".$this->getTableName().$where.$group.$order;
        // var_dump($sql);
        $Model = new \Think\Model();
        $list=$Model->query($sql);
        if ($list) {
            return $list;
        }
        return array ();
    }

    //获得广告商激活回调成功数  商务数据
    public function getSuccessProviderCallBackForSw($app_id, $provider_id,$start_date='',$end_date='') {
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
        if (!empty($where)) {
            $where = $where." and own_result ='{\"message\":\"success\",\"success\":true}'";
        }else{
            $where =" where own_result = '{\"message\":\"success\",\"success\":true}'";
        }

        $group=" group by app_id";

        $order = " order by app_id desc";
        // $list = $db->select ( self::getTableName(), "count(*) as count,app_id,chan_id", $condition);
        $sql = "select count(*) as pcount,app_id,chan_id from ".$this->getTableName().$where.$group.$order;
        // var_dump($sql);
        $Model = new \Think\Model();
        $list=$Model->query($sql);
        if ($list) {
            return $list;
        }
        return array ();
    }

    //获得广告商激活回调成功数  商务数据
    public  function getSuccessProviderActiveByProviderId($app_id,$provider_id,$start_date,$end_date) {
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
        if (!empty($where)) {
            $where = $where." and own_result ='{\"message\":\"success\",\"success\":true}'";
        }else{
            $where =" where own_result = '{\"message\":\"success\",\"success\":true}'";
        }
        $group=" group by app_id";
        $order = " order by app_id desc";
        $sql = "select count(*) as pcount,app_id,chan_id,provider_id from ".$this->getTableName().$where.$group.$order;
        $Model = new \Think\Model();
        $list=$Model->query($sql);
        if ($list) {
            return $list;
        }
        return array ();
    }

    //获得广告商激活回调成功数  媒介数据
    public  function getSuccessProviderActiveByChanId($app_id,$chanid,$start_date,$end_date) {

        $where = "";
        if(!empty($chanid)){
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
        if (!empty($where)) {
            $where = $where." and own_result ='{\"message\":\"success\",\"success\":true}'";
        }else{
            $where =" where own_result = '{\"message\":\"success\",\"success\":true}'";
        }
        $group=" group by app_id,chan_id";
        $order = " order by app_id desc";
        $sql = "select count(*) as pcount,app_id,chan_id,provider_id from ".$this->getTableName().$where.$group.$order;
        $Model = new \Think\Model();
        $list=$Model->query($sql);
        if ($list) {
            return $list;
        }
        return array ();
    }

// 获得渠道激活数
    public function getProviderActiveByTime($app_name,$chan_id,$start_date='',$end_date='',$search) {
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
            // if ($chan_id !='') {
            //     $where['chan_id'] = $chan_id;
            // }
            if ($start_date == '' && $end_date != '') {
                $where['time'] = array('elt',$end_date);
            }
            if ($start_date != '' && $end_date == '') {
                $where['time'] = array('egt',$start_date);;
            }
            if ($start_date != '' && $end_date != '') {
               $where["time"] = array(array('egt',$start_date),array('elt',$end_date));
            }
            if (!empty($where)) {
                $where ['own_result'] ='{"message":"success","success":true}';
            }

            if( $chan_id == '' && $app_id != ''){
                $group= "chan_id";
            } else if ($app_id == '' && $chan_id != ''){
                $group="app_id";
            } else if ($app_id == '' && $chan_id == '') {
                $group="app_id,chan_id";
            }

        $order = " app_id desc,chan_id desc";
        $list = $this 
        ->field('count(*) as count,app_id,chan_id')
        ->where($where)
        ->group($group)                  // 排序
        ->order($order)
        ->select();  
        if ($list['0']["count"] == '0') {
            return array ();
        }else{
            return $list;
        }
    }
//     //广告财务查看详情

//     public function getDetailsData($app_id) {

//         $db=self::__instance();
//         $condition=array();
//         if($app_id){
//             $condition['app_id']=$app_id;
//         }
//         //echo $id;die;
//         $list=$db->select ( self::getTableName(), "*",$condition);
//         //var_dump($list);die;
//         return $list;
//     }

//     //广告主实际回调量
     public function providerlogCount($app_id) {
        // $db=self::__instance();
         $condition=array();
         if($app_id){
             $condition['app_id']=$app_id;
         }
         //echo $id;die;
       /*  $list=$db->select ( self::getTableName(), "*",$condition);*/
         $list = $this->where($condition)->select();
         //var_dump($list);die;
         return $list;
     }

    //获得id
    public function getExist_idByid($appid,$idfa) {
      
        $condition=array();
        if($appid != ''){
            $sub_condition['app_id']=$appid;
        }
        if($idfa != ''){
            $sub_condition['idfa']=$idfa;
        }
        $id = $this->field('id')->where($sub_condition)->select();
        
        return $id;
    }

    //查询数据是否存在
    public function SelectData($appid,$idfa) {

        //$condition=array();
        if($appid != ''){
            $sub_condition['app_id']=$appid;
        }
        if($idfa != ''){
            $sub_condition['idfa']=$idfa;
        }
        $list = $this->where($sub_condition)->select();

        return $list;
    }
//     //分页功能
//     public function getLists($app_id,$start ,$page_size) {
//         $db=self::__instance();
// //var_dump($db);

//         $condition = array();

//         if($app_id != ''){
//             $sub_condition['app_id']=$app_id;
//             //$condition['LIKE']=array("app_id"=>$app_id);
//             //var_dump($sub_condition);die;
//         }
//         $condition["AND"] = $sub_condition;
//         // var_dump($condition);
//         $condition["ORDER"]=" id desc";
//         $condition['LIMIT']=array($start,$page_size);
//        // var_dump($condition);
//         $list = $db->select ( self::getTableName(), self::$columns, $condition);
//         //var_dump($list);die;

//         if ($list) {
//             return $list;
//         }
//         return array ();
//     }
//     //count
//     public function Appcount($app_id) {
//         $db=self::__instance();

//         $condition = array();

//         if($app_id != ''){
//             $sub_condition['app_id']=$app_id;
//             //$condition['LIKE']=array("app_id"=>$app_id);
//             //var_dump($sub_condition);die;
//         }
//         $condition["AND"] = $sub_condition;
//         //var_dump($condition);
//         $num = $db->count ( self::getTableName(),$condition);
//         //var_dump($db->count ( self::getTableName(),$condition));die;

//         return $num;
//     }
     //导出数据获得渠道激活数
     public function getOutPutActiveCount($app_id,$chan_id) {
         //select count(*) from provider_log where app_id ='5'  and chan_id='2';
         //select count(*),app_id,chan_id from channel_log GROUP BY app_id,chan_id;
         //var_dump($v);

         if($app_id != ''){
             $sub_condition['app_id']=array("in",$app_id);
         }
         if($chan_id != ''){
             $sub_condition['chan_id']=$chan_id;
         }
         if( $chan_id == '' && $app_id != ''){
             $condition=" chan_id";
         } else if($app_id == '' && $chan_id != ''){
             $condition=" app_id";
         } else if($app_id == '' && $chan_id == '') {
             $condition=" app_id,chan_id";
         }
         // var_dump($sub_condition);
         $list =  $this->field("count(*) as count,app_id")->where($sub_condition)->group($condition)->select();
         return $list;
     }
     //数据导出点击数
     //获得渠道激活数
     public function getOutPutActiveByTime($app_id,$chan_id,$start_time='',$end_time='') {

         $sub_condition = array();
         if($app_id != ''){
             $sub_condition['app_id']=array("in",$app_id);
         }
         if($chan_id != ''){
             $sub_condition['chan_id']=$chan_id;
         }
         if($start_time !='' && $end_time !=''){
            $sub_condition["time"] =array(array("egt",$start_time),array("elt",$end_time),"and");
        }elseif($start_time !='' && $end_time ==''){
            $sub_condition['time'] = array("egt",$start_time);
        }elseif($start_time =='' && $end_time !=''){
            $sub_condition['time'] = array("elt",$end_time);
        }

         if( $chan_id == '' && $app_id != ''){
             $condition = " chan_id";
         } else if($app_id == '' && $chan_id != ''){
             $condition = " app_id";
         } else if($app_id == '' && $chan_id == '') {
             $condition = " app_id,chan_id";
         }
          $list = $this->field("count(*) as count,app_id,chan_id")->where($sub_condition)->group($condition)->select();
         //$sql = "select count(*) as count,app_id,chan_id from (select app_id,chan_id,time from ".self::getTableName()." order by app_id) as tmp ".$where.$group;
         //$list=$db->query($sql)->fetchAll();
         if ($list) {
             return $list;
         }
         return array ();
     }
//获得渠道激活成功数
    public function getTrueOutPutActiveByTime($app_id,$chan_id,$start_time='',$end_time='') {

        $sub_condition = array();
        $sub_condition['own_result']='{"message":"success","success":true}';
        if($app_id != ''){
            $sub_condition['app_id']=array("in",$app_id);
        }
        if($chan_id != ''){
            $sub_condition['chan_id']=$chan_id;
        }
        if($start_time !='' && $end_time !=''){
            $sub_condition["time"] =array(array("egt",$start_time),array("elt",$end_time),"and");
        }elseif($start_time !='' && $end_time ==''){
            $sub_condition['time'] = array("egt",$start_time);
        }elseif($start_time =='' && $end_time !=''){
            $sub_condition['time'] = array("elt",$end_time);
        }

        if( $chan_id == '' && $app_id != ''){
            $condition = " chan_id";
        } else if($app_id == '' && $chan_id != ''){
            $condition = " app_id";
        } else if($app_id == '' && $chan_id == '') {
            $condition = " app_id,chan_id";
        }
        $list = $this->field("count(*) as count,app_id,chan_id")->where($sub_condition)->group($condition)->select();
        //$sql = "select count(*) as count,app_id,chan_id from (select app_id,chan_id,time from ".self::getTableName()." order by app_id) as tmp ".$where.$group;
        //$list=$db->query($sql)->fetchAll();
        if ($list) {
            return $list;
        }
        return array ();
    }
//分页
    public function search_log($app_id){
        $perPage = 25;
        if ($app_id != "") {
            $where['app_id'] = $app_id;
        }
        //dump($where);
        $count = $this->where($where)->count();
       // dump($count);
        $pageObj = new \Think\Page($count,$perPage);
        $pageString = $pageObj->show();
        // 设置样式
        $pageObj->setConfig('first','首页');
        $pageObj->setConfig('prev', '上一页');
        $pageObj->setConfig('next', '下一页');
        $pageObj->setConfig('last','共%TOTAL_PAGE%页');
        $pageObj->setConfig('theme','%FIRST% %UP_PAGE%  &nbsp;%LINK_PAGE%&nbsp; %DOWN_PAGE% %END% <span class="rows">共 %TOTAL_ROW% 条</span>');
        /************** 取某一页的数据 ***************/
        $data = $this->order("id desc")
            ->where($where)// 排序
            ->limit($pageObj->firstRow.','.$pageObj->listRows)            // 翻页
            ->select();

        /************** 返回数据 ******************/
        return array(
            'data' => $data,  // 数据
            'page' => $pageString,  // 翻页字符串
        );
    }
    //更新字段own_result 和 info_explain
    public function update_state($pid,$own_result,$info_explain){
        $data['own_result'] = $own_result;
        $data['info_explain'] = $info_explain;
        $this->where(array('id'=>$pid))->save($data);
    }

    //特殊情况查询
    public function getCountByDateLog($chan_id,$provider_id,$idfa,$start_date,$end_date) {

        if($chan_id != ''){
            $sub_condition['chan_id']=$chan_id;
        }
        if($provider_id != ''){
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

    public function getCountLog($chanid='',$idfa='',$provider_id="") {
        $sub_condition = array();

        if($chanid != ''){
            $sub_condition['chan_id']=$chanid;
        }
        if($idfa != ''){
            $sub_condition['idfa']=$idfa;
        }
        if($provider_id!=""){
            $sub_condition['provider_id']=$provider_id;
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
        if($provider_id != ''){
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

        $list = M('provider_log')->where($sub_condition)->order("id desc")->limit($start,$page_size)->select();
        // var_dump($chan_id);die;

        if ($list) {
            return $list;
        }
        return array ();
    }
    //查询所有的数据
    public function getProLog($start, $page_size){
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
}

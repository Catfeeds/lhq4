<?php
namespace Common\Model;
use Think\Model;

class ChannelLogModel extends Model {

	 
// 	public function getChannelLogs() {
	
// 		$db=self::__instance();
		
// 		$sql="select ".self::$columns." from ".self::getTableName()." order by id desc";
// 		$list = $db->query($sql)->fetchAll();
// 		if ($list) {
// 			return $list;
// 		}
// 		return array ();		
// 	}
    //添加
    public function addChannellog($channel_data) {
        if (! $channel_data || ! is_array ( $channel_data )) {
            return false;
        }
        $id = $this->data($channel_data)->add();
        //$id = $db->insert ( self::getTableName(), $channel_data );
        return $id;
    }

    //获取pid
    public function getPids(){
        $list=$this->field('provider_id')->group('provider_id')->select();
        if($list){
            return $list;
        }
        return array();
    }
// 	//获得渠道激活数
// 	public function getChannelClickByTime($app_id, $chanid,$start_date='',$end_date='') {
// 		$db=self::__instance();
//         // $where = "";
//         // if($app_id != ''){
//         //     $where ="where app_id = ".$app_id;
//         // }
//         // if($chanid != '' ){
//         //     if (!empty($where)) {
//         //         $where = $where." and chan_id=".$chanid;
//         //     }else{
//         //         $where ="where chan_id = ".$chanid;
//         //     }
//         // }
//         // if($start_date !=''){
//         //     if (!empty($where)) {
//         //         $where = $where." and time >='$start_date'";
//         //     }else{
//         //         $where ="where time >='$start_date'";
//         //     }
//         // }
//         // if($end_date !=''){
//         //     if (!empty($where)) {
//         //         $where = $where." and time <='$end_date'";
//         //     }else{
//         //         $where ="where time <= '$end_date'";
//         //     }
//         // }
//         // if( $chanid == '' && $app_id != ''){
//         //     $group=" group by chan_id";
//         // } else if($app_id == '' && $chanid != ''){
//         //     $group=" group by app_id";
//         // } else if($app_id == '' && $chanid == '') {
//         //     $group=" group by app_id,chan_id";
//         // }
//         // $sql = "select count(*) as count,app_id,chan_id,provider_id from (select app_id,chan_id,provider_id,time from ".self::getTableName()." order by app_id) as tmp ".$where.$group;
//         // //var_dump($sql);
//         // $list=$db->query($sql)->fetchAll();
// 		$condition=array();
// 		$sub_condition = array();
// 		if($app_id != "" ){
// 			$sub_condition['app_id']=$app_id;
// 		}
// 		if($chanid != "" ){
// 			$sub_condition['chan_id']=$chanid;
// 		}
// 		if($start_date !='' && $end_date !=''){
// 			$sub_condition["time[<>]"] =array($start_date,$end_date);
// 		}
// 		if(empty($sub_condition)){
// 			$condition = array();
// 		}else{
// 			$condition["AND"] = $sub_condition;

// 		}
// 		if($app_id == "" && $chanid != ''){
// 			$condition["GROUP"]=" app_id";
// 		} else if($chanid == "" && $app_id != ''){
// 			$condition["GROUP"]=" chan_id";
// 		} else if($chanid == "" && $app_id == '') {
// 			$condition["GROUP"]=" app_id,chan_id";
// 		}
// 		$condition["ORDER"]=" id desc";
// 		$list = $db->select ( self::getTableName(), "count(*) as count,app_id,chan_id,provider_id", $condition);
// 		if ($list) {
// 			return $list;
// 		}
// 		return array ();
// 	}
	
// 	//获得渠道点击数
// 	public function getChannelClickCount($app_id, $chanid,$start_time,$end_time) {
// 		//select count(*) from provider_log where app_id ='5'  and chan_id='2';
// 		//select count(*),app_id,chan_id from channel_log GROUP BY app_id,chan_id;
// 		$db=self::__instance();
// 		$condition=array();
// 		$sub_condition = array();
// 		if($app_id != ''){
// 			$sub_condition['app_id']=$app_id;
// 		}
// 		if($chanid != '' ){
// 			$sub_condition['chan_id']=$chanid;
// 		}
//         if($start_time !='' && $end_time !='') {
//             $sub_condition["time[<>]"] = array($start_time, $end_time);
//         }
// 		if(empty($sub_condition)){
// 			$condition = array();
// 		}else{
// 			$condition["AND"] = $sub_condition;
// 		}
// 		if( $chanid == '' && $app_id != ''){
// 			$condition["GROUP"]=" chan_id";
// 		} else if($app_id == '' && $chanid != ''){
// 			$condition["GROUP"]=" app_id";
// 		} else if($app_id == '' && $chanid == '') {
// 			$condition["GROUP"]=" app_id,chan_id";
// 		}
// 		$condition["ORDER"]=" id desc";
// 		$list = $db->select ( self::getTableName(), "app_id,chan_id", $condition);
//         if ($list) {
// 			return $list;
// 		}
// 		return array ();
// 	}

 	function getCountByDate($app_id,$chan_id,$provider_id,$idfa,$start_date,$end_date,$kid) {
 		$condition=array();
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
            //$sub_condition['idfa']=$idfa;
            $sub_condition['idfa']=$idfa;
        }
        if($kid != ''){
            $sub_condition['kid']=array('like',"%$kid%");
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
 		//$condition["AND"] = $sub_condition;
 		//dump(M('channel_log')->where($condition)->select());
 		$num = M('channel_log')->where($sub_condition)->count();
 		return $num;
 	}
	
 	function getCount($app_id='',$chan_id='',$provider_id="",$idfa='',$kid) {
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
 			//$sub_condition['idfa']=$idfa;
 			$sub_condition['idfa']=$idfa;
 		}
        if($kid != ''){
            $sub_condition['kid']=array('like',"%$kid%");
        }
 		$num = M('channel_log')->where($sub_condition)->count();
 		return $num;
 	}

// 	//数据导出方法
//     public function getCountByDate1($adsid,$chan_id,$provider_id,$start_date,$end_date) {
//         $db=self::__instance();
//         $condition=array();


//         if($adsid != ''){
//             $sub_condition['adsid[=]']=$adsid;
//         }
//         if($chan_id != ''){
//             $sub_condition['chan_id']=$chan_id;
//         }
//         if($provider_id != ''){
//             $sub_condition['provider_id']=$provider_id;
//         }
//         $sub_condition["time[<>]"] =array($start_date,$end_date);
//         $condition["AND"] = $sub_condition;
//        // var_dump($condition);//die;
//         $num = $db->count ( self::getTableName(),$condition );
//         //var_dump($num);die;
//         return $num;
//     }

//     public function count1($adsid='',$chan_id='',$provider_id='') {
//         $db=self::__instance();
// //var_dump($chan_id);
//         $sub_condition = array();
//         if($adsid != ''){
//             $sub_condition['adsid']=$adsid;
//         }

//         if($chan_id != ''){
//             $sub_condition['chan_id']=$chan_id;
//             //var_dump($sub_condition);
//         }
//         if($provider_id != ''){
//             $sub_condition['provider_id']=$provider_id;
//         }
//         if(empty($sub_condition)){
//             $condition = array();
//         }else{
//             $condition["AND"] = $sub_condition;
//         }



//        // var_dump($condition);
//         $num = $db->count ( self::getTableName(),$condition);
//         //var_dump($num);
//         return $num;
//     }
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
        $list = $this->field('time,idfa')->where($condition)->order("id desc")->select();
        if ($list) {
            return $list;
        }
        return array ();
    }
//导出成功数据
    public function getTrueOutputLogs($appid,$chan_id,$start_time, $end_time){
        $condition['own_result']='{"message":"success","success":true}';
        if ($appid != '') {
            $condition['app_id'] = array("in",$appid);
        }
        if($chan_id != ''){
            $condition['chan_id']=$chan_id;
        }
        if($start_time !='' && $end_time !=''){
            $condition["time"] =array(array("egt",$start_time),array("elt",$end_time),"and");
        }elseif($start_time !='' && $end_time ==''){
            $condition['time'] = array("egt",$start_time);
        }elseif($start_time =='' && $end_time !=''){
            $condition['time'] = array("elt",$end_time);
        }
        //var_dump($condition);die;
        $list = $this->field('time,idfa,own_result')->where($condition)->order("id desc")->select();
       // var_dump($list);die;
        if ($list) {
            return $list;
        }
        return array ();
    }
     function getLogs($app_id,$chan_id,$provider_id,$idfa,$start,$page_size,$start_date='',$end_date='',$kid) {

 		$condition=array();
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
 			//$sub_condition['idfa']=$idfa;
 			//$sub_condition['LIKE']=array("idfa"=>$idfa);
 			$sub_condition['idfa']=$idfa;
 		}
        if($kid != ''){
            $sub_condition['kid']=array('like',"%$kid%");
        }
//		$condition["ORDER"]=" id desc";
// 		$condition['LIMIT']=array($start,$page_size);
 		//var_dump($sub_condition);
        // var_dump($condition);//die;
 		$list = M('channel_log')->where($sub_condition)->order(" id desc")->limit($start,$page_size)->select();

 		if ($list) {
 			return $list;
 		}
 		return array ();
 	}

    //获得渠道激活成功数
    public  function getSuccessChannelClickByTime($app_id, $chanid,$start_date,$end_date) {

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

        $sql = "select count(*) as ccount,app_id,chan_id,provider_id from ".$this->getTableName().$where.$group.$order;
        // var_dump($sql);
        $Model = new \Think\Model();
        $list=$Model->query($sql);
        if ($list) {
            return $list;
        }
        return array ();
    }

    //获得渠道激活成功数  商务查询数据
    public  function getSuccessChannelClickForSw($app_id, $provider_id,$start_date,$end_date) {
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

        $sql = "select count(*) as ccount,app_id,chan_id,provider_id from ".$this->getTableName().$where.$group.$order;
        $Model = new \Think\Model();
        $list=$Model->query($sql);
        if ($list) {
            return $list;
        }
        return array ();
    }

    //获得渠道激活成功数  商务数据
    public  function getSuccessChannelClickByProviderId($app_id,$provider_id,$start_date,$end_date) {
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
        $sql = "select count(*) as ccount,app_id,chan_id,provider_id from ".$this->getTableName().$where.$group.$order;
        $Model  = new \Think\Model();
        $list=$Model->query($sql);
        if ($list) {
            return $list;
        }
        return array ();
    }

    //获得渠道激活成功数  媒介数据
    public  function getSuccessChannelClickByChanId($app_id,$chanid,$start_date,$end_date) {

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
        $order = " order by app_id desc,chan_id desc";
        $sql = "select count(*) as ccount,app_id,chan_id,provider_id from ".$this->getTableName().$where.$group.$order;
        $Model = new \Think\Model();
        $list=$Model->query($sql);
        if ($list) {
            return $list;
        }
        return array ();
    }

//     //广告主报表查询
//      public function getCLogs($app_id,$chanid,$start ,$page_size,$start_date='',$end_date='') {
//         $db=self::__instance();

//        $where = "";
//         if($app_id != ''){
//             $where ="where app_id = ".$app_id;
//         }
//         if($chanid != '' ){
//             if (!empty($where)) {
//                 $where = $where." and chan_id=".$chanid;
//             }else{
//                 $where ="where chan_id = ".$chanid;
//             }
//         }
//          if($start_date !=''){
//              if (!empty($where)) {
//                  $where = $where." and time >='$start_date'";
//              }else{
//                  $where ="where time >='$start_date'";
//              }
//          }
//          if($end_date !=''){
//              if (!empty($where)) {
//                  $where = $where." and time <='$end_date'";
//              }else{
//                  $where ="where time <= '$end_date'";
//              }
//          }

//         if( $chanid == '' && $app_id != ''){
//             $group=" group by chan_id";
//         } else if($app_id == '' && $chanid != ''){
//             $group=" group by app_id";
//         } else if($app_id == '' && $chanid == '') {
//             $group=" group by app_id,chan_id";
//         }
//         /* $condition=array();
//          $sub_condition = array();
//          $condition["ORDER"]=" id desc";
//          $condition['LIMIT']=array($start,$page_size);*/
//          //$list = $db->select ( self::getTableName(), "count(*) as count,app_id,chan_id,provider_id", $condition);
//         $sql = "select count(*) as count,app_id,chan_id,provider_id from (select app_id,chan_id,provider_id,time from ".self::getTableName()." order by app_id) as tmp ".$where.$group." limit $start,$page_size";
//          //var_dump($sql);//die;
//         $list=$db->query($sql)->fetchAll();
//         // var_dump($list);die;
//         if ($list) {
//             return $list;
//         }
//         return array ();
//     }

//     //广告财务查看详情
//     public function getDetailsData($app_id) {

//             $db=self::__instance();
//             $condition=array();
//             if($app_id){
//                 $condition['app_id']=$app_id;
//             }
//             //echo $id;die;
//             $list=$db->select ( self::getTableName(), "*",$condition);
//             //var_dump($list);die;
//             return $list;
//     }

//     //获取实际投放量
     public function getNumberCount($app_id) {
       //  $db=self::__instance();

       //  $condition=array();
         $sub_condition = array();
         if($app_id != ''){
             $sub_condition['app_id']=$app_id;
         }

         /*if(empty($sub_condition)){
             $condition = array();
         }else{
             $condition["AND"] = $sub_condition;
         }*/
          if($app_id == ''){
             $condition=" app_id";
         }

        // $list = $db->select ( self::getTableName(), "count(*) as count,app_id", $condition);
         $list=$this->field('count(*) as count,app_id')->where($sub_condition)->group($condition)->select();
         $data = array();

         foreach ( $list as $key => $value ) {
             $data [$value['app_id']] = $value['count'];
         }

         return $data;
     }
    //用$app_id,$idfa获取id
    public function getExist_idByid($appid,$idfa) {
        if (! $idfa||!$appid ) {
            return false;
        }
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
//     //获得callback
//     public function getCallback_urlBycallback($app,$idfa) {
//         $db=self::__instance();
//         $condition=array();
//         if($app != ''){
//             $sub_condition['app_id']=$app;
//         }
//         if($idfa != ''){
//             $sub_condition['idfa']=$idfa;
//         }
//         $condition["AND"] = $sub_condition;
//         $callback =  $db->select ( self::getTableName(), "callback", $condition);
// //        var_dump($condition["AND"]);//die;
//         return $callback;
//     }
    //获得callback
    public function getCallback_urlBycallback1($appid,$idfa) {
      
        if($appid != ''){
            $sub_condition['app_id']=$appid;
        }
        if($idfa != ''){
            $sub_condition['idfa']=$idfa;
        }
        $callback =  $this->field('callback,chan_id')->where($sub_condition)->select();
       
        return $callback;
    }


    /**
    * 修改点击上报时间
    * @param int $appid   
    * @param int $idfa   
    * @param array $channel_data   
    * @return boolean  成功或失败 
    */
    public function updateTime($appid,$idfa,$channel_data) {
        if (! $channel_data || ! is_array ( $channel_data ) || ! $idfa || !$appid) {
			return false;
		}
		if($appid != ''){
        	$sub_condition['app_id']=$appid;
        }
        if($idfa != ''){
        	$sub_condition['idfa']=$idfa;
        }
        $id = $this->where($sub_condition)->save($channel_data);
		//$id = $db->update ( self::getTableName(), $channel_data,$condition );
		return $id;
    }
//     //count
//     public function Appcount($app_id) {
//         $db=self::__instance();

//        // $sub_condition = array();
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
     //分页功能
     public function getLists($app_id,$start ,$page_size) {
       //  $db=self::__instance();
 //var_dump($db);
         $condition=array();
      /*   $sub_condition = array();
         if(empty($sub_condition)){
             $condition = array();
         }else{
             $condition["AND"] = $sub_condition;
         }*/

         if($app_id != ''){
             $sub_condition['app_id']=$app_id;
              //$condition['LIKE']=array("app_id"=>$app_id);
         }
         $condition["AND"] = $sub_condition;
         //var_dump($condition);
         $condition["ORDER"]=" id desc";
      //   $condition['LIMIT']=array($start,$page_size);
         //var_dump($condition);
         /*$list = $db->select ( self::getTableName(), self::$columns, $condition);*/
         $list =  $this->where($condition)->select();
        // var_dump($list);die;

         if ($list) {
             return $list;
         }
         return array ();
     }

//     //数据导出点击量查询

     public function getOutPutClickCount($app_id,$chan_id) {
         $sub_condition = array();
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
        // $condition["ORDER"]=" id desc";
         $list = $this->field( "app_id,chan_id")->where($sub_condition)->order(" id desc")->group($condition)->select();
         // var_dump($list);
         if ($list) {
             return $list;
         }
         return array ();
     }
     ////据导出获得渠道激活数
     public function getOutPutClickByTime($app_id,$chan_id,$start_time='',$end_time='') {

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
        // var_dump($sub_condition,$condition);die;
         $list = $this->field("count(*) as count,app_id,chan_id")->where($sub_condition)->group($condition)->order(" id desc")->select();
         //var_dump($list);
         if ($list) {
             return $list;
         }
         return array ();
     }

    ////据导出获得渠道激活成功数
    public function getTrueOutPutClickByTime($app_id,$chan_id,$start_time='',$end_time='') {

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
        $list = $this->field("count(*) as count,app_id,chan_id")->where($sub_condition)->group($condition)->order(" id desc")->select();
        // var_dump($list);
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
       // dump($where);
        $count = count($this->where($where)->select());
        //dump($count);
        $pageObj = new \Think\Page($count,$perPage);
        $pageString = $pageObj->show();
        // 设置样式
        $pageObj->setConfig('next', '下一页');
        $pageObj->setConfig('prev', '上一页');
        /************** 取某一页的数据 ***************/
        $data = $this->order("id desc")
            ->where($where)// 排序
            ->limit($pageObj->firstRow.','.$pageObj->listRows)            // 翻页
            ->select();
        //dump($data);
        /************** 返回数据 ******************/
        return array(
            'data' => $data,  // 数据
            'page' => $pageString,  // 翻页字符串
        );
    }


    //分页
    public function search($app_name,$chan_id,$start_date,$end_date){
        $perPage = 25;
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
            if (!empty($where)) {
                $where['own_result'] ='{"message":"success","success":true}';
            }
          //  var_dump($where);die;
            if( $chan_id == '' && $app_id != ''){
                $group= "chan_id";
            } else if ($app_id == '' && $chan_id != ''){
                $group="app_id";
            } else if ($app_id == '' && $chan_id == '') {
                $group="app_id,chan_id";
            }
            $order="app_id desc,chan_id desc";
        //$count = $this->where($wher)->group($groups)->count();
        $counts = $this->field('count(*) as count')->where($where)->group($group)->select();
    //    var_dump($counts);die;
        $count = count($counts);
        $actives = D('ChannelActiveLog')->field('count(*) as count,app_id,chan_id,provider_id')->where($where)->group($group)->select();
       // dump($actives);die;
        $pageObj = new \Think\Page($count,$perPage);
        // 设置样式
        $pageObj->setConfig('first','首页');
        $pageObj->setConfig('prev', '上一页');
        $pageObj->setConfig('next', '下一页');
        $pageObj->setConfig('last','共%TOTAL_PAGE%页');
        $pageObj -> setConfig('header','<span class="rows">共 %TOTAL_ROW% 条记录</span>');
        //$pageObj -> setConfig('theme','%FIRST% %LINK_PAGE% %DOWN_PAGE% %UP_PAGE% %HEADER% ');
        $pageObj->setConfig('theme','%FIRST% %UP_PAGE%  &nbsp;%LINK_PAGE%&nbsp; %DOWN_PAGE% %END% %HEADER% ');
        $pageString = $pageObj->show();
        /************** 取某一页的数据 ***************/
        $data = $this 
        ->field('count(*) as count,app_id,chan_id,provider_id')
        ->where($where) 
        ->order("id desc")  
        ->group($group)                // 排序    
        ->limit($pageObj->firstRow.','.$pageObj->listRows)// 翻页
        ->select();  
        /************** 返回数据 ******************/
        return array(
            'data' => $data,  // 数据
            'page' => $pageString,
            'active' => $actives,// 翻页字符串
        );
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
    public function getCountByDateLog($chan_id,$provider_id,$idfa,$start_date,$end_date,$kid) {

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
        if($kid != ''){
            $sub_condition['kid']=array('like',"%$kid%");
        }
        $num = $this->where($sub_condition)->count();
        return $num;
    }

    public function getCountLog($chanid='',$provider_id='',$idfa='',$kid) {
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
        if($kid != ''){
            $sub_condition['kid']=array('like',"%$kid%");
        }
//dump($sub_condition);
        $num = $this->where($sub_condition)->count();
        //   dump($num);
        return $num;
    }
    public function getLogsLog($chan_id,$provider_id,$idfa,$start ,$page_size,$start_date='',$end_date='',$kid) {
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
        if($kid != ''){
            $sub_condition['kid']=array('like',"%$kid%");
        }

        $list =$this->where($sub_condition)->order("id desc")->limit($start,$page_size)->select();
        // var_dump($chan_id);die;

        if ($list) {
            return $list;
        }
        return array ();
    }

    //根据idfa查找chanid
    public  function getChanId($appid,$idfa){

        $sub_condition["idfa"]=strtoupper($idfa);
        $sub_condition["app_id"]=$appid;
        $condition["AND"] = $sub_condition;
        
        $list = $this->field('chan_id')->where($condition)->select();
        return $list[0]['chan_id'];

    }

}

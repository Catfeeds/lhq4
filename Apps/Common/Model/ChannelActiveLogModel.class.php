<?php
namespace Common\Model;
use Think\Model;

class ChannelActiveLogModel extends Model
{

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
    public function addChannelActivelog($channel_data)
    {
        if (!$channel_data || !is_array($channel_data)) {
            return false;
        }
        $id = $this->data($channel_data)->add();
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
// 	public function getChannelActiveClickByTime($app_id, $chanid,$start_date='',$end_date='') {
// 		$db=self::__instance();

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
    //获得渠道激活数
    public static function getChannelActiveClickByTime($app_id, $chanid,$start_date='',$end_date='') {
        $db=self::__instance();

        $condition=array();
        $sub_condition = array();
        if($app_id != "" ){
            $sub_condition['app_id']=$app_id;
        }
        if($chanid != "" ){
            $sub_condition['chan_id']=$chanid;
        }
        if($start_date !='' && $end_date !=''){
            $sub_condition["time[<>]"] =array($start_date,$end_date);
        }
        if(empty($sub_condition)){
            $condition = array();
        }else{
            $condition["AND"] = $sub_condition;

        }
        if($app_id == "" && $chanid != ''){
            $condition["GROUP"]=" app_id";
        } else if($chanid == "" && $app_id != ''){
            $condition["GROUP"]=" chan_id";
        } else if($chanid == "" && $app_id == '') {
            $condition["GROUP"]=" app_id,chan_id";
        }
        $condition["ORDER"]=" id desc";
        $list = $db->select ( self::getTableName(), "count(*) as count,app_id,chan_id,provider_id", $condition);
        if ($list) {
            return $list;
        }
        return array ();
    }

    //获得渠道激活成功数
    public  function getSuccessChannelActiveClickByTime($app_id, $chanid,$start_date='',$end_date='') {

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
        $sql = "select count(*) as acount,app_id,chan_id,provider_id from ".$this->getTableName().$where.$group.$order;
        // var_dump($sql);
        $Model = new \Think\Model();
        $list=$Model->query($sql);

        if ($list) {
            return $list;
        }
        return array ();
    }

    //获得渠道激活数
    public static function getProviderActiveByTime($app_id, $chanid,$start_date='',$end_date='') {


        $db=self::__instance();
        $where = "";
        if($app_id != ''){
            $where ="where app_id = ".$app_id;
        }
        if($chanid != '' ){
            if (!empty($where)) {
                $where = $where." and chan_id=".$chanid;
            }else{
                $where ="where chan_id = ".$chanid;
            }
        }
        if($start_date !=''){
            if (!empty($where)) {
                $where = $where." and time >='$start_date'";
            }else{
                $where ="where time >='$start_date'";
            }
        }
        if($end_date !=''){
            if (!empty($where)) {
                $where = $where." and time <='$end_date'";
            }else{
                $where ="where time <= '$end_date'";
            }
        }

        if( $chanid == '' && $app_id != ''){
            $group=" group by chan_id";
        } else if($app_id == '' && $chanid != ''){
            $group=" group by app_id";
        } else if($app_id == '' && $chanid == '') {
            $group=" group by app_id,chan_id";
        }
        // //var_dump($condition);
        // $list = $db->select ( self::getTableName(), "count(*) as count,app_id,chan_id", $condition);
        $sql = "select count(*) as count,app_id,chan_id from (select app_id,chan_id,time from ".self::getTableName()." order by app_id) as tmp ".$where.$group;
        $list=$db->query($sql)->fetchAll();

        if ($list) {
            return $list;
        }
        return array ();
    }



// 	//获得渠道点击数
// 	public function getChannelActiveClickCount($app_id, $chanid) {
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
// 		$list = $db->select ( self::getTableName(), "count(*) as count,app_id,chan_id,provider_id", $condition);
// 		if ($list) {
// 			return $list;
// 		}
// 		return array ();
// 	}

    //获得渠道激活成功数  商务查询
    public  function getSuccessChannelActiveClickForSw($app_id, $provider_id,$start_date='',$end_date='') {
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
        $sql = "select count(*) as acount,app_id,chan_id,provider_id from ".$this->getTableName().$where.$group.$order;
        // var_dump($sql);
        $Model=new \Think\Model();
        $list=$Model->query($sql);

        if ($list) {
            return $list;
        }
        return array ();
    }

    //获得渠道激活成功数  商务数据
    public  function getSuccessChannelActiveClickByProviderId($app_id,$provider_id,$start_date,$end_date) {
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
        $sql = "select count(*) as acount,app_id,chan_id,provider_id from ".$this->getTableName().$where.$group.$order;
        $Model = new \Think\Model();
        $list=$Model->query($sql);
        if ($list) {
            return $list;
        }
        return array ();
    }

    //获得渠道激活数  媒介数据
    public  function getSuccessChannelActiveClickByChanId($app_id,$chanid,$start_date,$end_date) {

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
        $sql = "select count(*) as acount,app_id,chan_id,provider_id from ".$this->getTableName().$where.$group.$order;
        $Model = new \Think\Model();
        $list=$Model->query($sql);
        if ($list) {
            return $list;
        }
        return array ();
    }

    public function getCountByDate($app_id, $chan_id,$provider_id, $idfa, $start_date, $end_date,$kid)
    {
        if($kid != ''){
            $sub_condition['kid']=array('like',"%$kid%");
        }
        if ($app_id != '') {
            $sub_condition['app_id'] = array("in", $app_id);
        }
        if ($chan_id != '') {
            $sub_condition['chan_id'] = $chan_id;
        }
        if ($provider_id != '') {
            $sub_condition['provider_id'] = $provider_id;
        }
        if ($idfa != '') {
            $sub_condition['idfa'] =  $idfa ;
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

    public function getCount($app_id = '', $chan_id = '',$provider_id='', $idfa = '',$kid)
    {

        $sub_condition = array();
        if($kid != ''){
            $sub_condition['kid']=array('like',"%$kid%");
        }
        if ($app_id != '') {
            $sub_condition['app_id'] = array("in", $app_id);
        }
        if ($chan_id != '') {
            $sub_condition['chan_id'] = $chan_id;
        }
        if ($provider_id != '') {
            $sub_condition['provider_id'] = $provider_id;
        }
        if ($idfa != '') {
            //$sub_condition['idfa']=$idfa;
            $sub_condition['idfa'] =  $idfa ;
        }
        $num = $this->where($sub_condition)->count();
        return $num;
    }
// 	//数据导出方法
//     public function getCountByDate1($chan_id,$adsid,$idfa,$start_date,$end_date) {
//         $db=self::__instance();
//         $condition=array();

//         if($chan_id != ''){
//             $sub_condition['chan_id']=$chan_id;
//         }
//         if($chan_id != ''){
//             $sub_condition['adsid']=$adsid;
//         }
//         if($idfa != ''){
//             //$sub_condition['idfa']=$idfa;
//             $condition['LIKE']=array("idfa"=>$idfa);
//         }
//         $sub_condition["time[<>]"] =array($start_date,$end_date);
//         $condition["AND"] = $sub_condition;
//         //var_dump($condition);//die;
//         $num = $db->count ( self::getTableName(),$condition );
//         //var_dump($num);
//         return $num;
//     }

//     public function count1($chan_id='',$adsid='',$idfa='') {
//         $db=self::__instance();

//         $sub_condition = array();

//         if($chan_id != ''){
//             $sub_condition['chan_id']=$chan_id;
//         }
//         if($chan_id != ''){
//             $sub_condition['adsid']=$adsid;
//         }

//         if(empty($sub_condition)){
//             $condition = array();
//         }else{
//             $condition["AND"] = $sub_condition;
//         }
//         if($idfa != ''){
//             //$sub_condition['idfa']=$idfa;
//             $condition['LIKE']=array("idfa"=>$idfa);
//         }
//         $num = $db->count ( self::getTableName(),$condition);
//         return $num;
//     }

    public function getLogs($app_id, $chanid,$provider_id, $idfa, $start, $page_size, $start_date , $end_date ,$kid)
    {

        if($kid != ''){
            $sub_condition['kid']=array('like',"%$kid%");
        }
       // $sub_condition = array();
        if ($app_id != '') {
            $sub_condition['app_id'] = array("in", $app_id);
        }
        if ($chanid != '') {
            $sub_condition['chan_id'] = $chanid;
        }
        if ($provider_id != '') {
            $sub_condition['provider_id'] = $provider_id;
        }

        if ($start_date == '' && $end_date != '') {
            $sub_condition['time'] = array('elt', $end_date);
        }
        if ($start_date != '' && $end_date == '') {
            $sub_condition['time'] = array('egt', $start_date);;
        }
        if ($start_date != '' && $end_date != '') {
            $sub_condition["time"] = array(array('egt', $start_date), array('elt', $end_date));
        }
        if ($idfa != '') {
            //$sub_condition['idfa']=$idfa;
            $sub_condition['idfa'] =  $idfa ;
        }
        $list = M('channel_active_log')->where($sub_condition)->order(" id desc")->limit($start, $page_size)->select();
/*        dump($sub_condition);
dump($list);*/
        if ($list) {
            return $list;
        }
        return array();
    }

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
//     public function getNumberCount($app_id) {
//         $db=self::__instance();

//         $condition=array();
//         $sub_condition = array();
//         if($app_id != ''){
//             $sub_condition['app_id']=$app_id;
//         }

//         if(empty($sub_condition)){
//             $condition = array();
//         }else{
//             $condition["AND"] = $sub_condition;
//         }
//          if($app_id == ''){
//             $condition["GROUP"]=" app_id";
//         }

//         $list = $db->select ( self::getTableName(), "count(*) as count,app_id", $condition);

//         $data = array();

//         foreach ( $list as $key => $value ) {
//             $data [$value['app_id']] = $value['count'];
//         }

//         return $data;
//     }
    //用$app_id,$idfa获取id
    public function getExist_idByid($appid, $idfa)
    {
        if (!$idfa || !$appid) {
            return false;
        }
        if ($appid != '') {
            $sub_condition['app_id'] = $appid;
        }
        if ($idfa != '') {
            $sub_condition['idfa'] = $idfa;
        }
        $id = $this->field("time")->where($sub_condition)->select();

        return $id;
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
//     //获得callback
//     public function getCallback_urlBycallback1($appid,$idfa) {
//         $db=self::__instance();
//         $condition=array();
//         if($appid != ''){
//             $sub_condition['app_id']=$appid;
//         }
//         if($idfa != ''){
//             $sub_condition['idfa']=$idfa;
//         }
//         $condition["AND"] = $sub_condition;
//         $callback =  $db->select ( self::getTableName(), "callback,chan_id", $condition);
//         return $callback;
//     }


    /**
     * 修改点击上报时间
     * @param int $appid
     * @param int $idfa
     * @param array $channel_data
     * @return boolean  成功或失败
     */
    public function updateTime($appid, $idfa, $channel_data)
    {
        if (!$channel_data || !is_array($channel_data) || !$idfa || !$appid) {
            return false;
        }
        if ($appid != '') {
            $sub_condition['app_id'] = $appid;
        }
        if ($idfa != '') {
            $sub_condition['idfa'] = $idfa;
        }
        $id = $this->where($sub_condition)->save($channel_data);

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
//     //分页功能
//     public function getLists($app_id,$start ,$page_size) {
//         $db=self::__instance();
// //var_dump($db);
//         $condition=array();
//      /*   $sub_condition = array();
//         if(empty($sub_condition)){
//             $condition = array();
//         }else{
//             $condition["AND"] = $sub_condition;
//         }*/

//         if($app_id != ''){
//             $sub_condition['app_id']=$app_id;
//              //$condition['LIKE']=array("app_id"=>$app_id);
//         }
//         $condition["AND"] = $sub_condition;
//         //var_dump($condition);
//         $condition["ORDER"]=" id desc";
//         $condition['LIMIT']=array($start,$page_size);
//         //var_dump($condition);
//         $list = $db->select ( self::getTableName(), self::$columns, $condition);
//        // var_dump($list);die;

//         if ($list) {
//             return $list;
//         }
//         return array ();
//     }

//回去激活数
    public function getChannelActiveByTime($app_name, $chan_id, $start_date, $end_date)
    {
            if (!empty($app_name)) {
                $appName = D('App')->SelectAppByNameChanid($app_name, $chan_id);

                $aName = array();
                foreach ($appName as $k => $v) {
                    foreach ($v as $key => $value) {
                        $aName[] = $value;
                    }
                }
                $app_id = $aName;

                if (empty($app_id)) {
                    $where['app_id'] = '';
                } else {
                    $where['app_id'] = array('in', $app_id);
                }

            }
            if ($chan_id != '') {
                $where['chan_id'] = $chan_id;
            }
            if ($start_date == '' && $end_date != '') {
                $where['time'] = array('elt', $end_date);
            }
            if ($start_date != '' && $end_date == '') {
                $where['time'] = array('egt', $start_date);;
            }
            if ($start_date != '' && $end_date != '') {
                $where["time"] = array(array('egt', $start_date), array('elt', $end_date));
            }
            if (!empty($where)) {
                $where ['own_result']  ='{"message":"success","success":true}';
            }
           // var_dump($where);die;
            if ($chan_id == '' && $app_id != '') {
                $group = "chan_id";
            } else if ($app_id == '' && $chan_id != '') {
                $group = "app_id";
            } else if ($app_id == '' && $chan_id == '') {
                $group = "app_id,chan_id";
            }
        $order = "  app_id desc,chan_id desc";

        $list = $this
            ->field('count(*) as count,app_id,chan_id')
            ->where($where)
            ->group($group)// 排序
            ->order($order)
            ->select();
        //var_dump($list);die;
        if ($list['0']["count"] == '0') {
            return array();
        } else {
            return $list;
        }

    }

    //获取渠道激活上报日志
    public function getOutPutChannelActive($app_id,$chan_id, $start_time, $end_time){
        $sub_condition = array();
        if($app_id != ''){
            $sub_condition['app_id']=array("in",$app_id);
        }
        if($chan_id != ''){
            $sub_condition['chan_id']=$chan_id;
        }
        //var_dump($sub_condition);die;
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

        if ($list) {
            return $list;
        }
        return array ();
    }

    //只获取渠道激活上报成功的日志
    public function getTrueOutPutChannelActive($app_id,$chan_id, $start_time, $end_time){
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

        if ($list) {
            return $list;
        }
        return array ();
    }


    //数据查询导出的
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
// 导出成功数据
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
        $list = $this->field('time,idfa')->where($condition)->order("id desc")->select();
        if ($list) {
            return $list;
        }
        return array ();
    }


    //查询所有的数据
    public function getChanLog($start, $page_size){
        $list = $this->limit($start, $page_size)->order(' id desc')->select();

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
        if($kid != ''){
            $sub_condition['kid']=array('like',"%$kid%");
        }
        if($chan_id != ''){
            $sub_condition['chan_id']=$chan_id;
        }
        if ($provider_id != '') {
            $sub_condition['provider_id'] = $provider_id;
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

    public function getCountLog($chanid='',$provider_id='',$idfa='',$kid) {
        $sub_condition = array();
        if($kid != ''){
            $sub_condition['kid']=array('like',"%$kid%");
        } 
        if($chanid != ''){
            $sub_condition['chan_id']=$chanid;
        }
        if ($provider_id != '') {
            $sub_condition['provider_id'] = $provider_id;
        }

        if($idfa != ''){
            $sub_condition['idfa']=$idfa;
        }
//dump($sub_condition);
        $num = $this->where($sub_condition)->count();
        //   dump($num);
        return $num;
    }
    public function getLogsLog($chan_id,$provider_id,$idfa,$start ,$page_size,$start_date='',$end_date='',$kid) {
        $sub_condition = array();
        if($kid != ''){
            $sub_condition['kid']=array('like',"%$kid%");
        }
        if($chan_id != ''){
            $sub_condition['chan_id']=$chan_id;
        }
        if ($provider_id != '') {
            $sub_condition['provider_id'] = $provider_id;
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

}
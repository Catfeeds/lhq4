<?php

namespace Common\Model;

use Think\Model;

class RowRepeatModel extends Model{

     

    // public function getRowRepeats() {

    

    //     //�������ַ�ʽ����Է���sample��DB

    //     $db=self::__instance();

    //     //$db=self::__instance(SAMPLE_DB_ID);

        

    //     $sql="select ".self::$columns." from ".self::getTableName()." order by id desc";

    //     $list = $db->query($sql)->fetchAll();

    //     if ($list) {

    //         return $list;

    //     }

    //     return array ();        

    // }



    public function addRowRepeat($row_data) {

        if (! $row_data || ! is_array ( $row_data )) {

            return false;

        }

        $id = $this->data($row_data)->add();

        //$id = $db->insert ( self::getTableName(), $row_data );

        return $id;

    }



    // public function getCountByDate($app_id,$idfa,$start_date,$end_date) {

    //     $db=self::__instance();

    //     $condition=array();

    //     if($app_id != ''){

    //         $sub_condition['app_id']=$app_id;

    //     }

    //     if($idfa != ''){

    //         //$sub_condition['idfa']=$idfa;

    //         //$sub_condition['idfa']=array('like','%'.$idfa.'%');

    //         $condition['LIKE']=array("idfa"=>$idfa);

    //     }

    //     //echo $idfa;die;

    //     $sub_condition["rtime[<>]"] =array($start_date,$end_date);

    //     $condition["AND"] = $sub_condition;

    //     //var_dump($condition);

    //     //$num = $db->select ( self::getTableName(), self::$columns, $condition);



    //     $num = $db->count ( self::getTableName(),$condition );

    //    // var_dump($num);die;

    //     return $num;

    // }



    // public function count($app_id='',$idfa='') {

    //     $db=self::__instance();



    //     $sub_condition = array();

    //     if($app_id != ''){

    //         //$condition['app_id[=]']=$app_id;

    //         $sub_condition['app_id']=$app_id;

    //         //var_dump($sub_condition);die;

    //     }

    //     if(empty($sub_condition)){

    //         $condition = array();

    //     }else{

    //         $condition["AND"] = $sub_condition;

    //     }

    //     if($idfa != ''){

    //         $condition['LIKE']=array("idfa"=>$idfa);

    //     }



    //     //var_dump($condition);

    //     $num = $db->count ( self::getTableName(),$condition);

    //     //var_dump($condition);

    //     //var_dump($num);die;

    //     return $num;

    // }



    // public function getLogs($app_id,$idfa,$start ,$page_size,$start_date='',$end_date='') {

    //     $db=self::__instance();



    //     $condition=array();

    //     $sub_condition = array();

    //     if($app_id != ''){

    //         $sub_condition['app_id']=$app_id;

    //     }

    //     if($start_date !='' && $end_date !=''){

    //         $sub_condition["rtime[<>]"] =array($start_date,$end_date);

    //     }

    //     if(empty($sub_condition)){

    //         $condition = array();

    //     }else{

    //         $condition["AND"] = $sub_condition;

    //     }

    //     if($idfa != ''){

    //         //$sub_condition['idfa']=$idfa;

    //         $condition['LIKE']=array("idfa"=>$idfa);

    //     }

    //     $condition["ORDER"]=" id desc";

    //     $condition['LIMIT']=array($start,$page_size);

    //     //var_dump($condition);

    //     $list = $db->select ( self::getTableName(), self::$columns, $condition);

    //     //var_dump($list);die;

    //     if ($list) {

    //         return $list;

    //     }

    //     return array ();

    // }
    //�ϱ����ز�ѯ
    public function getOutPutQueryIdfa($app_id,$chan_id, $start_time, $end_time){
        $sub_condition = array();
        if($app_id != ''){
            $sub_condition['app_id']=array("in",$app_id);
        }
        if($chan_id != ''){
            $sub_condition['chan_id']=$chan_id;
        }
        if($start_time !='' && $end_time !=''){
            $sub_condition["rtime"] =array(array("egt",$start_time),array("elt",$end_time),"and");
        }elseif($start_time !='' && $end_time ==''){
            $sub_condition['rtime'] = array("egt",$start_time);
        }elseif($start_time =='' && $end_time !=''){
            $sub_condition['rtime'] = array("elt",$end_time);
        }
        // if( $chan_id == '' && $app_id != ''){
        //     $condition=" chan_id";
        // } else if($app_id == '' && $chan_id != ''){
            $condition=" app_id";
        // } else if($app_id == '' && $chan_id == '') {
        //     $condition=" app_id,chan_id";
        // }

        $list = $this->field("count(*) as count,app_id,chanid")->where($sub_condition)->group($condition)->select();
        //$sql = "select count(*) as count,app_id,chan_id from (select app_id,chan_id,time from ".self::getTableName()." order by app_id) as tmp ".$where.$group;
        //$list=$db->query($sql)->fetchAll();
        if ($list) {
            return $list;
        }
        return array ();
    }
    
    //��������
    public function getOutputLogs($appid,$chan_id,$start_time, $end_time){

        if ($appid != '') {
            $condition['app_id'] = array("in",$appid);
        }
        if($chan_id != ''){
            $condition['chan_id']=$chan_id;
        }
        if($start_time !='' && $end_time !=''){
            $condition["rtime"] =array(array("egt",$start_time),array("elt",$end_time),"and");
        }elseif($start_time !='' && $end_time ==''){
            $condition['rtime'] = array("egt",$start_time);
        }elseif($start_time =='' && $end_time !=''){
            $condition['rtime'] = array("elt",$end_time);
        }
        $list = $this->field("idfa,rtime as time")->where($condition)->order("id desc")->select();
        if ($list) {
            return $list;
        }
        return array ();
    }


}


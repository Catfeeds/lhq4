<?php
namespace Common\Model;
use Think\Model;

class MemMisModel extends Model{

    public function getid(){
    	return $this -> select();
    }
    //查询当前是否有任务
    public function getMsidMid($member_id,$mission_id) {
        $sub_condition ["member_id"] = $member_id;
        $sub_condition ["mission_id"] = $mission_id;
        $list = $this->where($sub_condition) ->select();
        if ($list) {
            return $list[0];
        }
        return array ();
    }
    //查询以前是否有作废的任务
    public function getStaMsidMid($member_id,$mission_id) {
        $sub_condition ["member_id"] = $member_id;
        $sub_condition ["mission_id"] = $mission_id;
        $sub_condition ["status"] = 5;
        $list = $this->where($sub_condition)->select();
        if ($list) {
            return $list[0];
        }
        return array ();
    }
    //判断状态
    public function getMsidMidststus($member_id) {
        $sub_condition ["member_id"] = $member_id;    
        $sub_condition ["status"] = 2;
        $list = $this->where($sub_condition)->select();
        if ($list) {
            return $list[0];
        }
        return array ();
    }
    //通过member_id查询，然后ｍission_id进行索引
    public function getMissionStatus($member_id) {
        if (! $member_id || ! is_numeric ( $member_id )) {
            return false;
        }
        $sub_condition = array('member_id'=>$member_id);
        $list = $this->where($sub_condition)->select();
        $data = array();
        foreach ( $list as $key => $value ) {
            $data [$value['mission_id']] = $value['status'];
        }    
        return $data;
    }
    //取消任务
    public function getqxStatus($mmid,$msid,$val) {
        $sub_condition ["member_id"] = $mmid;
        $sub_condition ["mission_id"] = $msid;
        if($val==3){
            $tj=array('status'=>'3');
        }
        $list = $this->where($sub_condition)->save($tj);
        if ($list) {
            return $list;
        }
        return array ();
    }
    //审核任务
    public function getshStatus($mmid,$msid,$val) {
        $sub_condition ["member_id"] = $mmid;
        $sub_condition ["mission_id"] = $msid; 
        if($val==2){
            $tj=array('status'=>'4');
        }
        $list = $this->where($sub_condition)->save($tj);    
        if ($list) {
            return $list;
        }
        return array ();
    }
    //快速完成任务
    public function getshSuccesStatus($mmid,$msid,$val) {
        $sub_condition ["member_id"] = $mmid;
        $sub_condition ["mission_id"] = $msid;
        if($val==2){
            $tj=array('status'=>'1');
        }
        $list = $this->where($sub_condition)->save($tj);
        if ($list) {
            return $list;
        }
        return array ();
    }
    //任务做费
    public function getzfStatus($mmid,$msid) {
        $sub_condition ["member_id"] = $mmid;
        $sub_condition ["mission_id"] = $msid;
        $tj=array('status'=>'5');
        $list = $this->where($sub_condition)->save($tj);
        if ($list) {
            return $list;
        }
        return array ();
    }
    //查询第一步第二步是否以作
    public function SelectSteps($mmid,$msid) {
        $sub_condition ["member_id"] = $mmid;
        $sub_condition ["mission_id"] = $msid;
        $list = $this->where($sub_condition)->select();
        if ($list) {
            return $list[0];
        }
        return array ();
    }
    //完成任务
    public function updatefinishStatus($memberid,$missionId) {
        $sub_condition['member_id'] = $memberid;
        $sub_condition["mission_id"] = $missionId;
        $tj=array('status'=>'1');
        $list = $this->where($sub_condition)->save($tj);
        if ($list) {
            return $list;
        }
        return array ();
    }
    public function addMemMis($input_data) {
        if (! $input_data || ! is_array ( $input_data )) {
            return false;
        }
        $id = $this->data($input_data)->add();
        return $id;
    }
    public function delMemMis($status) {
        if (! $status || ! is_numeric ( $status )) {
            return false;
        }
        $condition = array("status" => $status);
        $result = $this->where($condition)->delete();
        return $result;
    }
    //删除作废的任务
    public function deleteMemMis($member_id,$memmionid){
        $condition['member_id'] = $member_id;
        $condition['mission_id'] = $memmionid;
        $condition['status'] = 2;
        $result = $this->where($condition)->delete();
        return $result;
    }
    //查询这个任务的状态
    public function getmisId($id){
        if (! $id || ! is_numeric ( $id )) {
            return false;
        }
        $sub_condition ["id"] = $id;
        $list=$this->where($sub_condition)->select();
        if ($list) {
            return $list[0];
        }
     }
    //查询做过的任务
    public function getWcMission($member_id){
        $Finish['status']=array(array('eq',1),array('eq',6), 'or');
        $Finish['ctime'] = array('between',array(date("Y-m-d H:i:s",strtotime("last month")),date("Y-m-d H:i:s",time())));
        $Finish['member_id'] = array('eq',$member_id);
		$list=$this->where($Finish)->select();
        if ($list) {
            return $list;
        }
    }
	public function getCountByDate($mission_id,$member_id,$status,$start_date,$end_date) {
        $sub_condition=array();
        if($member_id != ''){
            $sub_condition['member_id']=array("in",$member_id);
        }
        if($mission_id != ''){
            $sub_condition['mission_id']=array("in",$mission_id);
        }
        if($status != ''){
            $sub_condition['status']=$status;
        }
        $sub_condition["ctime"] =array(array("egt",$start_date),array("elt",$end_date),"and");
        $num = $this->where($sub_condition )->count();
        return $num;
    }
    public function getCount($mission_id='',$member_id='',$status='') {
        $sub_condition = array();
         if(!empty($member_id) || $member_id != ''){
            $sub_condition['member_id']=array("in",$member_id);
        }
        if(!empty($mission_id) || $mission_id != ''){
            $sub_condition['mission_id']=array("in",$mission_id);
        }
        if($status != ''){
            $sub_condition['status']=$status;
        }
        $num = $this->where($sub_condition)->count();
        return $num;
    }

    public function getMms($mission_id,$member_id,$status,$start ,$page_size,$start_date='',$end_date='') {
     	$sub_condition = array();
        if($member_id != '' || $member_id != ''){
     		$sub_condition['member_id']=array("in",$member_id);;
     	}
     	if($mission_id != '' || $mission_id != ''){
     		$sub_condition['mission_id']=array("in",$mission_id);
     	}
     	if($start_date !='' && $end_date !=''){
     		$sub_condition["ctime"] =array(array("egt",$start_date),array("elt",$end_date),"and");
     	}
     	if($status != '' && $status != '0'){
     		$sub_condition['status']=$status;
     	}
        $list = $this->where($sub_condition)->order("id desc")->limit($start, $page_size)->select();
        if ($list) {
            return $list;
        }
        return array ();
     }

    //任务审核状态修改
    public function StatusChanges($id,$val) {
        $condition =array('id'=>$id);
        if($val==2){
            $tj=array('status'=>'1');
        }
        if($val == 3) {
            $tj=array('status'=>'6');
        }
        $list=$this->where($condition)->save($tj);
        if ($list) {
            return $list;
        }
        return array ();
     }
    //通过任务id查找此任务完成数量
    public function endCount($mission_id){
        $sub_condition ["mission_id"] = $mission_id;
        $sub_condition['status'] = 1;
        $list = $this->where($sub_condition)->select();
        return $list;
    }
    //通过member_id查找完成任务的数量
    public function getFinishCount($member_id){
        $sub_condition ["member_id"] = $member_id;
        $sub_condition ['status'] = 1;
        $list = $this->where($sub_condition)->select();
        return $list;
    }

    //查询倒计时时间
    public function getCdTime($mmid,$msid) {
        $sub_condition ["member_id"] = $mmid;
        $sub_condition ["mission_id"] = $msid;
        $list = $this->field('cd_time')->where($sub_condition)->select();
        if ($list) {
            return $list[0];
        }
        return array ();
    }
    public function selectMid($msid){     
        $sub_condition ["mission_id"] = $msid;
        $list=$this->where($sub_condition)->field('member_id')->select();
        if ($list) {
            return $list[0];
        }
        return array ();
    }
    //添加操作步骤
    public function updateFirstSteps($mmid,$msid) {
        $sub_condition ["member_id"] = $mmid;
        $sub_condition ["mission_id"] = $msid;
        $tj=array('first_step'=>'1');
        $list = $this->where($sub_condition)->save($tj);
        if ($list) {
            return $list;
        }
        return array ();
    }
    public function updateSecondSteps($mmid,$msid) {
        $sub_condition ["member_id"] = $mmid;
        $sub_condition ["mission_id"] = $msid;
        $tj=array('second_step'=>'1');
        $list = $this->where($sub_condition)->save($tj);
        if ($list) {
            return $list;
        }
        return array ();
    }
    //查找步骤
    public function findSteps($mmid,$msid)
    {
        $sub_condition ["member_id"] = $mmid;
        $sub_condition ["mission_id"] = $msid;
        $list = $this->field('first_step')->where($sub_condition)->select();
        if ($list) {
            return $list[0];
        }
        return array();
    }
    //  下架商品数据的修改；
    public function delData($msid) {
        $sub_condition ["mission_id"] = $msid;
        $sub_condition ["status"] = '2';
        $result = $this->where($sub_condition)->delete();
        return $result;
    }
    //通过任务id查询任务状态
    public function getMsidststus($mission_id) {
        $sub_condition ["mission_id"] = $mission_id;
        $sub_condition ["status"] = '2';
        $list = $this->where($sub_condition)->select();
        if ($list) {
            return $list;
        }
        return array ();
    }
    /**
    *  获取mission_id
    *  @param $member_id 用户id
    *  @return array 
    **/
    public function getMssionId($member_id) {
        $list = $this->field('mission_id,cd_time')->where(array('member_id'=>$member_id,'status'=>2))->find();
        if ($list) {
            return $list;
        }
        return array ();
    }
    /**
    *  如果任务下线修改状态为5
    *  @param $member_id 用户id
    *  @return int 
    **/
    public  function updateStatus($member_id) {
        $list = $this->where(array('member_id'=>$member_id,'status'=>2))->save(array('status'=>'5'));
        if ($list) {
            return $list;
        }
        return 0;
    }
    /**
    *  如果任务下线后又上线把没做完任务状态为5又一次做任务状态修改为2
    *  @param $member_id 用户id
    *  @return int 
    **/
    public function ChangeAgainStatus($member_id,$mission_id,$ctime) {
        $sub_condition ["member_id"] = $member_id;
        $sub_condition ["mission_id"] = $mission_id;
        $sub_condition ["status"] = 5;
        $tj=array('status'=>'2','ctime'=>$ctime);
        $list = $this->where($sub_condition)->save($tj);
        if ($list) {
            return $list;
        }
        return array ();
    }

    /**
    *  查找用户提交审核的任务是否完成了第一二步
    *  @param $mid 用户id
    *  @param $msid 任务id
    *  @return int 
    **/
    public function getSteps($mid,$msid)
    {
        $sub_condition ["member_id"] = $mid;
        $sub_condition ["mission_id"] = $msid;
        $sub_condition ["first_step"] = '1';
        $sub_condition ["second_step"] = '1';
        $list = $this->where($sub_condition)->select();
        if ($list) {
            return $list[0];
        }
        return array();
    }

    //查询所有的数据
    public function getMmsLog($start, $page_size){
        $list = $this->limit($start, $page_size)->select();
        if ($list) {
            return $list;
        }
        return array();
    }
    //查询所有的数据的条数
    public function CountgetMmsLog(){
        $num = $this->count();
        return $num;
    }
    public function getCountByDateLog($status,$start_date,$end_date) {
        $sub_condition=array();
        if($status != ''){
            $sub_condition['status']=$status;
        }
        $sub_condition["ctime"] =array(array("egt",$start_date),array("elt",$end_date),"and");
        $num = $this->where($sub_condition )->count();
        return $num;
    }

    public function getCountLog($status='') {
        if($status != ''){
            $sub_condition['status']=$status;
        }
        $num = $this->where($sub_condition)->count();
        return $num;
    }

    public function getsMms($status,$start ,$page_size,$start_date='',$end_date='') {
        $sub_condition = array();
        if($start_date !='' && $end_date !=''){
            $sub_condition["ctime"] =array(array("egt",$start_date),array("elt",$end_date),"and");
        }
        if($status != '' && $status != '0'){
            $sub_condition['status']=$status;
        }
        $list = $this->where($sub_condition)->order("id desc")->limit($start, $page_size)->select();
        if ($list) {
            return $list;
        }
        return array ();
    }

}

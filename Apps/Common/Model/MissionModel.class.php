<?php
namespace Common\Model;
use Think\Model;

class MissionModel extends Model{
	 
 	public function getMissions() {

        $list=$this->order('mission_id desc')->select();
 		if ($list) {
 			return $list;
 		}
 		return array ();
 	}
    //获取上线任务的所有信息
    public function getsMissions() {
        $mission['start_time'] =array('elt',date("Y-m-d H:i:s",time()));
        $mission['end_time'] = array('egt',date("Y-m-d H:i:s",time()));
        $mission['status'] = array('eq',2);
        $mission['re_num'] = array('gt',0);
        $list=$this->where($mission)->order("adtype_id DESC,mission_id DESC")->select();

        if ($list) {
            return $list;
        }
        return array ();
    }
    //获取待上线任务的所有信息
    public function getsWaitMissions() {
        $Waitsmission['status'] = array('eq',2);
        $Waitsmission['start_time'] =array('egt',date("Y-m-d H:i:s",time()));
        $Waitsmission['re_num'] = array('gt',0);       
        $list=$this->where($Waitsmission)->order('adtype_id,mission_id desc')->select();
        if ($list) {
            return $list;
        }
        return array ();
    }
    //查询已下线的任务；
    public function getEndingMissions() {
        $Endmission['status'] = array('eq',3);
        $Endmission['end_time'] = array(array('egt',date("Y-m-d H:i:s",strtotime("-1 day"))),array('elt',date("Y-m-d H:i:s",time())),'and');
        $list=$this->where($Endmission)->order('mission_id desc')->select();
        $sql = $this->_sql();
        if ($list) {
            return $list;
        }
        return array ();
    }
    //查询剩余量为0的任务；
    public function getRnumMissions() {
        $Rnummission['end_time'] =array('egt',date("Y-m-d H:i:s",time()));
        $Rnummission['re_num'] = array('eq',0);     
        $list=$this->where($Rnummission)->order('mission_id desc')->select();
        if ($list) {
            return $list;
        }
        return array ();
    }
    //查询两个条件同时到的任务；
    public function getRnumEndMissions() {
        $RnummEnd['end_time'] = array(array('egt',date("Y-m-d H:i:s",strtotime("-1 day"))),array('elt',date("Y-m-d H:i:s",time())));
        $RnummEnd['re_num'] = array('eq',0);    
        $list=$this->where($RnummEnd)->order('mission_id desc')->select();
        if ($list) {
            return $list;
        }
        return array ();
    }

	public function getMissionsArray() {
		$list = $this->select();
		$data = array();
        $data['0']="不限";
		foreach ( $list as $key => $value ) {
			$data [$value['mission_id']] = $value['mission_name'];
		}		
		return $data;
	}
    public function getLabelArray() {
        $list = $this->select();
        $data = array();
        foreach ( $list as $key => $value ) {
            $data [$value['mission_id']] = $value['label'];
        }
        return $data;
    }
     //通过查询app_id获取单价
     public function getPricesArray() {

         $list =$this->field('app_id,price')->select();
         $data = array();

         foreach ( $list as $key => $value ) {
             $data [$value['app_id']] = $value['price'];
         }
         return $data;
     }

    //通过查询mission_id获取app_id
    public function getappIdArray() {
       
        $list = $this->select();

        $data = array();

        foreach ( $list as $key => $value ) {
            $data [$value['mission_id']] = $value['app_id'];
        }
        return $data;
    }
     //获取实际总量
     public function getAmountsArray() {

         $list = $this->field('app_id,amount')->select();
         $data = array();

         foreach ( $list as $key => $value ) {
             $data [$value['app_id']] = $value['amount'];
         }
         return $data;
     }
     public function getmoneysArray() {

         $list = $this->field('mission_id,amount,price')->select();
         $data = array();

         foreach ( $list as $key => $value ) {
             $data[$value['mission_id']]= $value['amount']*$value['price'];
         }
         return $data;
     }
	//获取一条任务信息
	public function getMissionById($mission_id) {
		if (! $mission_id || ! is_numeric ( $mission_id )) {
			return false;
		}	
		$sub_condition ["mission_id"] = $mission_id;
	    $list = $this->where($sub_condition)->find();
        //var_dump($list);die;
		if ($list) {
			return $list;
		}
		return array ();
	}

    //连表查询获取一条任务信息
    public function getMissionkwdById($mission_id) {
        if (! $mission_id || ! is_numeric ( $mission_id )) {
            return false;
        }   
        $sub_condition ["mission_id"] = $mission_id;
        $list = $this->join('left join kwd_mount on mission.mission_id = kwd_mount.mid')->where($sub_condition)->order('id desc')->select();
        //var_dump($list);die;
        if ($list) {
            return $list[0];
        }
        return array ();
    }

//修改状态
   public function getMById($id,$val) {

        $condition =array('mission_id'=>$id);
       $status = $this->where($condition)->find();
        if($val==1 || $val == 2){
            $tj=array('status'=>'3');
        }else{
            if ($status['end_time'] < date('Y-m-d H:i:s',time())) {
                $time = date('Y-m-d',time());
                $start_time = $time.' 15:00:00';
                $end_time = $time.' 23:59:59';
                $tj=array('status'=>'2','start_time'=>$start_time,'end_time'=>$end_time);
            } else {
                $tj = array('status'=>2);
            }

        }
        $list = $this->where($condition)->save($tj);
        if ($list) {
           return $list;
        }
       return array ();
    }

    public function addMission($mission_data) {
        if (! $mission_data || ! is_array ( $mission_data )) {
            return false;
        }
        $id = $this->data($mission_data)->add();
        return $id;
    }

    public function delMission($mission_id) {
        if (! $mission_id || ! is_numeric ( $mission_id )) {
            return false;
        }
        $condition = array("mission_id" => $mission_id);
        $result = $this->where($condition)->delete();
        return $result;
    }

    public function updateMissionInfo($mission_id,$mission_data) {
        if (! $mission_data || ! is_array ( $mission_data )) {
            return false;
        }
        $condition=array("mission_id"=>$mission_id);
        $id = $this->where($condition)->save($mission_data);

        return $id;
    }

    //通过任务id获取app_id
    public function getappId($mission_id) {

        $sub_condition ["mission_id"] = $mission_id;
        $list = $this->field('app_id,kwd_qx')->where($sub_condition)->select();
        if ($list) {
            return $list[0]['app_id'];
        }
        return array ();
    }

    public function getKwdqx($mission_id)
    {
        $sub_condition ["mission_id"] = $mission_id;
        $list = $this->field('kwd_qx')->where($sub_condition)->find();
        if ($list) {
            return $list['kwd_qx'];
        }
        return array ();
    }
    //通过id查找此价格
    public function getPrice($mission_id) {
        if (! $mission_id || ! is_numeric ( $mission_id )) {
            return false;
        }
        $sub_condition ["mission_id"] = $mission_id;
        $list = $this->field('mission_id,price')->select();
        //  var_dump($list);die;
        $data = array();
        foreach ( $list as $key => $value ) {
            $data [$value['mission_id']] = $value['price'];
        }
        return $data;
    }

    /**
    *  获取end_time
    *  @param $memmionid 任务id
    *  @return array 
    **/
    public function getEndtime($memmionid) {
        $list = $this->field('end_time,re_num')->where(array("mission_id"=>$memmionid))->find();    
        if ($list) {
            return $list;
        }
        return array ();
    }
    //获取标签
    public function getlabel($mission_id) {
        $list = $this->field('label_name')->where(array("mission_id"=>$mission_id))->find();
        if ($list) {
            return $list;
        }
        return array ();
    }

    /**
    *  获取 re_num
    *  @param $mission_id 任务id
    *  @return array 
    **/
    public function getMisReNum($mission_id) {
        $sub_condition ["mission_id"] = $mission_id;
        $list = $this->field('re_num')->where($sub_condition) ->select();
        if ($list) {
            return $list[0];
        }
        return array ();
    }

 
    /**
    *  如果任务以下线  则修改状态（status）为下线3
    *  @param $member_id 用户id
    *  @return int 
    **/
    public function updateStatus($mission_id) {

        $sub_condition ["mission_id"] = $mission_id; 
        $tj=array('status'=>'3');
        $list = $this->where($sub_condition)->save($tj);
        if ($list) {
            return $list;
        }
        return 0;
    }
    /**
    *  进入任务列表后对自动下线任务修改状态为3 
    *  @return int 
    **/
    public function updateMisStatus() {
        // $end_time = array('between',date("Y-m-d H:i:s",time()-3600*24*365),date("Y-m-d H:i:s",time()));
        $end_time =array(array('egt',date("Y-m-d H:i:s",time()-3600*24*365)),array('elt',date("Y-m-d H:i:s",time())));
        $list = $this->where(array('status'=>'2','end_time'=>$end_time))->save(array('status'=>'3'));
        if ($list) {
            return $list;
        }
        return 0;
    }

    //查找mission_id
    public function getMissionId() {
        $list = $this->select();
        $data = array();
        $data['0']='不限';
        foreach ( $list as $key => $value ) {
            $data [$value['app_id']] = $value['mission_id'];
        }
        return $data;
     }
    
    /**
    *  每次做一个任务  对应的任务数量-1
    *  @param $msid   任务的id
    *  @return int 
    **/
    public function updateRuNum($mission_id) {
        $sub_condition ["mission_id"] = $mission_id;
        $list = $this->where($sub_condition)->setDec('re_num');
        if ($list) {
            return $list;
        }
        return 0;
    }

    /**
    *  每次取消一个任务  对应的任务数量+1
    *  @param $msid   任务的id
    *  @return int 
    **/
    public function updateRuNumAdd($msid) {
        $sub_condition ["mission_id"] = $msid;
        $list = $this->where($sub_condition)->setInc('re_num');
        if ($list) {
            return $list;
        }
        return 0;
    }
    /**
    *  每次取消一个任务  对应的任务数量+1
    *  @param $msid   任务的id
    *  @return int 
    **/
    public function update_adtypeid($app_id,$adtype_id){
        $sub_condition ["app_id"] = $app_id;
        $tj=array('adtype_id'=>$adtype_id);
        $list = $this->where($sub_condition)->save($tj);
        if ($list) {
         return $list;
        }
        return 0;
    }
    /**
    *  取用户可以做的任务
    *  @param $msid   任务的id
    *  @return int 
    **/
    public function taskAll($member_id){
        $sub_condition ["member_id"] = $app_id;
        $sub_condition ["status"] = $app_id;

        $list = $this->where($sub_condition)->save($tj);
        if ($list) {
         return $list;
        }
        return 0;
    }

    //查询任务名称
    //搜索广告应用
    public function SelectMissionByName($mission_name) {

        if( $mission_name!=""){
         $condition['mission_name']=array('like',"%$mission_name%");
        }
        $list= $this->where($condition)->field("mission_id")->select();
        if ($list) {
         return $list;
        }
        return array ();
    }

    //分页和检索
    public function search($app_name,$search,$start_date,$end_date,$status){
        if($start_date != "" && $end_date != "") {
            $start_time = ($start_date." 00:00:00");
            $end_time = ($end_date." 23:59:59");
        }
        $perPage = 25;
        if ($search) {
            if (!empty($app_name)) {
                $appName = D('App')->SelectAppByName($app_name);
                $aName = array();
                foreach ($appName as $k => $v) {
                    foreach ($v as $key => $value) {
                        $aName[] = $value;

                    }
                }
                $app_id = $aName;
                if(!empty($app_id)){
                    $where['app_id'] = array('in',$app_id);
                }
            }

            if($status == "0") {
                $_GET["status"] = "";
                $status = "";
            }

            if ($start_date != "") {    
                $where['start_time'] = array('egt',$start_date);
            }
            if ($end_date != "") {
                $where['end_time'] = array('elt',$end_date);
            }
            if($status != ''){
                $where['status']=$status;
            }

        }
        $count = $this->where($where)->count();

        $pageObj = new \Think\Page($count,$perPage);
        // 设置样式
        $pageObj->setConfig('next', '下一页');
        $pageObj->setConfig('prev', '上一页');
        $pageString = $pageObj->show();
        /************** 取某一页的数据 ***************/
     /*   $result=D('mission')
        ->field('mission.*,group_concat(kwd_mount.kkwd)')
        ->join(' left join kwd_mount ON mission.mission_id = kwd_mount.mid') 
        ->where($where) 
        ->order("mission.mission_id desc,kwd_mount.time desc")                    // 排序     
        ->buildSql();
       */
        $time=date('Y-m-d',time());
        $stime=$time." 00:00:00";
        $etime=$time." 23:59:59";
        $sub_condition['time']=array(array('egt',$stime),array('elt',$etime),'and');
        $result=D('kwd_mount')
        ->field('mid, kkwd')
        //->where($sub_condition)
        ->group('mid')
        ->table('kwd_mount')
        ->buildSql();
        //var_dump($result);  die;
        $data = $this
        ->table($result.' a')
        ->join('right join mission on mid = mission.mission_id')
        ->where($where)  
        ->order('mission_id desc')     
        ->limit($pageObj->firstRow.','.$pageObj->listRows)// 翻页
        ->select();
        /************** 返回数据 ******************/
        return array(
            'data' => $data,  // 数据
            'page' => $pageString,  // 翻页字符串
        );
    }
    public function getMissionByAppid($app_id) {
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
    public function delMissionByAppid($app_id) {
        if (! $app_id || ! is_numeric ( $app_id )) {
            return false;
        }
        $condition['app_id'] =  $app_id;
        $result = $this->where($condition)->delete();
        //$result = $db->delete ( self::getTableName(), $condition );
        return $result;
    }

    //取任务类型
    public function getAdtype($mission_id)
    {
        if (!empty(mission_id)) {
            $where['mission_id'] = $mission_id;
            $result = $this->where($where)->find();
            if ($result) {
                return $result['adtype_id'];
            }
        }
    }


}

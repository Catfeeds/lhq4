<?php
namespace Mobile\Controller;
use Think\Controller;
use Common\Common\UserSession;
class ApiajaxController extends CommonController{
    //提示是否取消任务
	public function qxStatusAjax(){
	    $mmid = I('request.mmid');
	    $msid = I('request.msid');
	    $val = I('request.val');
		$start_time=date('Y-m-d H:i:s', time());
		$user_info = UserSession::getSessionInfo();
		$mission_name=D('Mission')->getMissionById($msid);
	    if ($val == '2') {
	        $step = D('MemMis')->SelectSteps($mmid, $msid);
	        if ($step['first_step'] == 1 && $step['second_step'] == 1) {
                //mission用mission_id取app_id
                $app_id = D('Mission')->getappIdArray($msid);
                //用app_id到app表查是否是回调任务（$adtype_id==1）
                $adtype_id= D('App')->getAdtypeIdArray($app_id);
                if ($adtype_id['id'] == 1) {
                    $sj = 0;
                    $sj = D('MemMis')->getshStatus($mmid, $msid, $val);
                    $Detailid = D('TaskDetails')->getMsidMeid($mmid, $msid);
                    if ($Detailid) {
                        $endtime = date('Y-m-d H:i:s', time());
                        $update_data = array('status' => 4, 'end_time' => $endtime);
                        $result = D('TaskDetails')->updateTaskDetail($Detailid, $update_data);
                    }
                }else{
					//修改状态
					$sj = 0;
					$sj = D('MemMis')->getshSuccesStatus($mmid, $msid, $val);
					$sj = 0;
					$Detailid = D('TaskDetails')->getMsidMeid($mmid, $msid);
					if ($Detailid) {
						$endtime = date('Y-m-d H:i:s', time());
						$update_data = array('status' => 1, 'end_time' => $endtime);
						$result = D('TaskDetails')->updateTaskDetail($Detailid, $update_data);
					}
                    $Id= D('MemMis')-> getMsidMid($mmid, $msid);
                    if($Id['status']=='1'){
                        // Mission::updateRuNum($msid);
                        //$meid=D('MemMis')->selectMid($msid);
                        UpdateMemberdata($Id['member_id'],$msid);
						//任务成功消息
						$m_name=D('MsgType')->getMsgTypesArray();
						foreach($m_name as $key=>$value){
							if($value=='任务完成通知')
							{
								$module=D('MsgModule')->getMsgMByTypeid($key);
								$label=explode('?',$module['m_content']);
                                dump($label);
								$in_data = array('title' => $module['m_name'], 'content' => $label[0].$mission_name['mission_name'].$label[1].$label[2], 'msg_from' =>'admin', 'msg_to' => $Id['member_id'],
									'display' => '1', 'create_time' => $start_time, 'status' => 0,'m_type'=>$module['m_types']);
								D('Message')->addMessage($in_data);
							}
						}
                    }else if($Id['status']=='6'){
                        $Detailid= D('TaskDetails')->updateMsidMeid($Id['member_id'],$msid);
                        D('Mission')->updateRuNumAdd($msid);     //审核失败后将剩余量加1
                        // $update_data = array ('status' => 6, 'start_time' => $nowTime);
						$m_name=D('MsgType')->getMsgTypesArray();
						foreach($m_name as $key=>$value){
							if($value=='任务失败通知')
							{
								$module=D('MsgModule')->getMsgMByTypeid($key);
								$label=explode('?',$module['m_content']);
								$in_data = array('title' => $module['m_name'], 'content' => $label[0].$mission_name['mission_name'].$label[1].$label[2], 'msg_from' =>'admin', 'msg_to' => $Id['member_id'],
									'display' => '1', 'create_time' => $start_time, 'status' => 0,'m_type'=>$module['m_types']);
								D('Message')->addMessage($in_data);
							}
						}
                    }
                }
                 $this->ajaxReturn(2);
	            die;
	        } else {
				 $this->ajaxReturn(3);
	            die;
	        }
	    } elseif ($val == '3') {
			//mission用mission_id取app_id
			$sj = D('MemMis')->getqxStatus($mmid, $msid, $val);
			$Detailid = D('TaskDetails')->getMsidMeid($mmid, $msid);
			D('Mission')->updateRuNumAdd($msid);     //取消后将剩余量加1
			if ($Detailid) {
				$endtime = date('Y-m-d H:i:s', time());
				$update_data = array('status' => 3, 'end_time' => $endtime);
				$result = D('TaskDetails')->updateTaskDetail($Detailid, $update_data);
				$_SESSION[$msid . "_" . $mmid . "time"] = '';
			}

		}
	    if ($sj == 1) {
	        $result = D('MemMis')->delMemMis(3);
	    }
	    echo json_encode($sj);
	}
     
    public function stepsAjax(){
    	$mmid=$_POST['mmid'];
		$msid=$_POST['msid'];
		$val=$_POST['val'];
		
		if($mmid==''){
			$this->ajaxReturn(4);
		}elseif ($msid=='') {
			$this->ajaxReturn(5);
		} elseif ($val=='') {
			$this->ajaxReturn(6);
		} else {
		    if($val=='0'){
		        $first=D('MemMis')->findSteps($mmid,$msid);
		        if ($first['first_step']!='1') {
		        	$steps=D('MemMis')->updateFirstSteps($mmid,$msid);
		        	if($steps==array()){
		        	    $this->ajaxReturn(1);
		            }
		        }else if($first['first_step']=='1'){
		        	 $this->ajaxReturn(2);
		        }
		    }else if($val=='1'){
		        $first=D('MemMis')->findSteps($mmid,$msid);
		        if($first['first_step']=='1'){
		            $steps=D('MemMis')->updateSecondSteps($mmid,$msid);
		            if($steps==''){
		               $this->ajaxReturn(2);
		            }else{
		               $this->ajaxReturn(4);
		            }
		        }else{
		            $this->ajaxReturn(3);
		        }

		    }
		}
	}
    //任务详情倒计时
    public function sjajax(){
		$msid=$_POST['msid'];
		$mmid=$_POST['mmid'];
		$stime=D('MemMis')->getCdTime($mmid,$msid);
		if($stime['cd_time']){
		    $ctime=$stime['cd_time']+1800-time();
		    if($ctime<=0){        
		    	$ctime=0;
		        $zf=0;
		        $zf=D('MemMis')->getzfStatus($mmid,$msid);
		        $Detailid= D('TaskDetails')->getMsidMeid($mmid,$msid);
		        if(!empty($Detailid)){
		            $endtime=date('Y-m-d H:i:s',time());
		            $update_data = array ('status' =>5, 'end_time' => $endtime);
		            $result = D('TaskDetails')->updateTaskDetail($Detailid, $update_data );
		        }
		        if($zf==1){
		            $result=D('MemMis')->delMemMis(5);
		        }
		        $_SESSION[$msid."_".$mmid."time"] ='';
		    }
		    $mt=floor($ctime/60);
		    if($mt<10){
		        $mt="0".$mt;
		    }
		    $st=$ctime%60;
		    if($st<10 && $st>=0){
		        $st="0".$st;
		    }
		    $mytime=$mt.":".$st;
		}else{
		    $_SESSION[$msid."_".$mmid."time"]=time();
		    $ctime=1800;
		    $mt=floor($ctime/60);
		    if($mt<10 && $mt>0){
		        $mt="0".$mt;
		    }
		    $st=$ctime%60;
		    if($st<10 && $st>=0){
		        $st="0".$st;
		    }
		    $mytime=$mt.":".$st;
		}
		echo $mytime;
    }


    public function ReadStatus(){
        //  $current_user_info = UserSession::getSessionInfo();
        //$current_user_info= D('UserSession')->getSessionInfo( );
      //  $message = $_POST['message'];
        $msg = $_POST['msg'];
        $mid = $_POST['mid'];
        $zt = $_POST['zt'];

        $ctime=date('Y-m-d H:i:s',time());
        $list=D('Message')->getMessageById($msg);
        //var_dump($list);die;
        if ($list['msg_to']=='-1') {
            $data= D('MsgLog')->StatusChanges($msg, $mid);
        }else{
            $list=D('MsgLog')->getMsg($msg,$mid);
			//dump($list);
            if(empty($list)){
                $input_data = array ('member_id' => $mid, 'status' => '1','message_id' =>$msg, 'create_time' => $ctime );
				//dump($input_data);//die;
                $data = D('MsgLog')->addMsgLog( $input_data );
            }else{
                $data= D('MsgLog')->StatusChanges($msg, $mid);
            }

        }
        echo json_encode($data);//die;
    }
    //后台点击上下线任务图片
    public function statusAjax(){
		$id=$_POST['mid'];
		$val=$_POST['val'];
		$missionInfo = D('Mission')->getMissionById($id);
		$smount = $missionInfo['smount'] ;//总量
		$smount_re = $missionInfo['smount_re'] ; //总量剩余量
		if ($smount == '0' || empty($smount)) {
			$sj = 3;
		}
		elseif($smount_re == '0' || empty($smount_re)){
			$sj = 4;
		}
		else{
			$sj = D('Mission')->getMById($id,$val);    //修改状态
		}
			if ($val == 2) {
				$list=D('MemMis')->getMsidststus($id);   //对下线任务删除
				if($list){
					$lst=D('MemMis')->delData($id);
				}
			}
		echo json_encode($sj);
    }
    //广告任务分类
    public function adtypeAjax(){
        if(IS_GET){
		    $appid = $_GET['appid'];
		    $adtyid = D('App')->getAdTypeId($appid);
	    }
		echo json_encode($adtyid['adtype_id']);
    }

    //查询msg表中的记录
    public function SelectMsg(){
        $msg = $_POST['msg'];
        $mid = $_POST['mid'];
        $list=D('MsgLog')->getMsg($msg,$mid);
        if($list){
            echo 1;
        }else{
            echo 0;
        }
    }


}


?>
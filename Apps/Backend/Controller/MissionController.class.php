<?php
namespace Backend\Controller;
use Think\Controller;
use Common\Common\UserSession;
class MissionController extends ComController{
	//计划任务列表
	public function mission(){
        $method = $mission_id= $app_name=$app_id= $mission_name= $kwd=$status= $start= $page_no = $start_date = $end_date =$start_time=$end_time= $search=$mount=$smount=$kwd_qx=$mount_re=$smount_re='';
		extract ( $_GET, EXTR_IF_EXISTS );
		extract ( $_POST, EXTR_IF_EXISTS );
		if ($method == 'del' && ! empty ( $mission_id )) {

			$missionId = D('Mission')->getMissionById($mission_id);
			$kwdId = D('Kwd_mount')->getKwdByMid($mission_id);
			if (intval($mission_id) <= 0) {
				$this->alert("error", '参数不正确');
			} else {
				$result = D('Mission')->delMission($mission_id);
				if ($result) {

					D('SysLog')->addLog(UserSession::getUserName(), 'DELETE', '配置信息', $mission_id, json_encode($missionId));
					$k = D('Kwd_mount')->delKwd($mission_id);
					if ($k) {
						D('SysLog')->addLog(UserSession::getUserName(), 'DELETE', '配置信息', $mission_id, json_encode($kwdId));
					}
					$this->exitWithSuccess('删除成功', U('Backend/Mission/mission'), 1);
				} else {
					$this->alert("error");

			}
		}
		}
		$data = D('Mission')->search($app_name,$search,$start_date,$end_date,$status);
		$missions = D('Mission')->getMissions();
		$adtypes =D('Adtype')->getAdTypesArray();
		$apps = D('App')->getChannelAppsArray();
        $this->assign('logs', $data['data']);
		$this->assign('page', $data['page']);
		$this->assign ( '_GET', $_GET );
		$this->assign ('adtypes',$adtypes);
		$this->assign ('missions',$missions);
		$this->assign ('apps',$apps);
		$this->display ();
	}
    //添加计划任务

	public function missionAdd(){ 
		$mission_id = $mission_name = $kwd = $app_id =$des =$label= $price = $channel_id = $start_time=$end_time=$scale=$amount =$status=$adtype_id=$mount = $mount_re='';
		extract ( $_POST, EXTR_IF_EXISTS );
		//var_dump($_POST);die;
		if (IS_POST) {
			//$exist = Mission::getMissionByName($mission_name);
		    $stat='1';
			$nowTime = date('Y-m-d H:i:s',time());
		    /*if($exist){
				OSAdmin::alert("error",ErrorMessage::NAME_CONFLICT);
			}else{*/
				$input_data = array ('mission_name' => $mission_name, 'mkwd' => $kwd, 'price' => $price,  'scale' => $scale, 'app_id' => $app_id,'amount' => $amount, 'des' => $des,'start_time' => $start_time, 'end_time' => $end_time, 'channel_id' => $channel_id, 'status'=>$stat,'re_num' => $amount,'adtype_id'=>$adtype_id,'label_name'=>$label);
		        //var_dump($input_data);die;
		        $mission_id = D('Mission')->addMission($input_data);
		        if($mission_id){
					D('SysLog')->addLog(UserSession::getUserName(), 'ADD', '配置信息', $mission_id, json_encode($input_data));
					$this->exitWithSuccess('添加成功', U('Backend/Mission/mission'), 1);
						
				}
		}
		$adtypes =D('Adtype')->getAdTypesArray();
		$apps = D('App')->getAppsArray();
		$channels = D('Channel')->getChannelsArray();
        $label_name = D('Label')->label_name();

        $this->assign('labels', $label_name);
		$this->assign("channels", $channels);
		$this->assign("apps",$apps);
		$this->assign("adtypes",$adtypes);
		$this->assign("_POST" ,$_POST);
		$this->display();
	}
	//修改计划任务
	public function missionModify(){
		header('Content-type: text/html; charset=UTF8');
        $httpref = $mission_id = $mission_name=$mkwd = $kwd = $price = $app_id = $amount = $des = $scale =$channel_id =$start_time =$end_time = $adtype_id=$label=$mount=$smount=$kwd_qx= $mount_re=$smount_re=$channels=$id=$lmount=$lrmount=$cmount='';
		extract ( $_REQUEST, EXTR_IF_EXISTS );
		$missionInfo = D('Mission')->getMissionById($mission_id);  //查看该任务所有信息
		//判断是否有此任务
		if(empty($missionInfo)){   
			$this->exitWithError(ErrorMessage::SAMPLE_NOT_EXIST,U('Backend/Mission/mission'),1);
		}
		$nowTime = date('Y-m-d H:i:s',time());  //此刻时间
		if (IS_POST) {
			//此任务处于上线状态
			if ($missionInfo['status'] == '2' && $nowTime >= $missionInfo['start_time'] && $nowTime < $missionInfo['end_time']) {
				//关键词关闭  
				if ($kwd_qx == '0') {
					//mission表的更新  剩余量字段(+-)
					$smount_re = $missionInfo['smount_re'] + ($smount - $missionInfo['smount']  );//总量剩余量
					$re_num = $missionInfo['re_num'] + ( $amount - $missionInfo['amount'] );      //用户剩余量
					$cmount_re = $missionInfo['cmount_re'] + ($cmount - $missionInfo['cmount']  );//渠道剩余量
					
					$updateMission = array('mission_name' => $mission_name, 'mkwd'=>$mkwd,'re_num' => $re_num, 'price' => $price, 'amount' => $amount, 'scale' => $scale, 'channel_id' => $channel_id,'smount'=>$smount,'smount_re'=>$smount_re,'kwd_qx'=>$kwd_qx,  'app_id' => $app_id, 'start_time' => $start_time, 'end_time' => $end_time, 'des' => $des, 'adtype_id' => $adtype_id, 'label_name' => $label,'cmount' => $cmount,'cmount_re' => $cmount_re);
					$result = D('Mission')->updateMissionInfo($mission_id, $updateMission);

					if ($result >= 0) {
						D('SysLog')->addLog(UserSession::getUserName(), 'MODIFY', '配置信息', $mission_id, json_encode($updateMission));
						$this->exitWithSuccess('修改成功',$httpref, 1);
					} else {
						$this->alert("error");
					}
				}
				//关键词开启
				if ($kwd_qx == '1') {
					//mission表的更新  剩余量字段(+-)
					$smount_re = $missionInfo['smount_re'] + ($smount - $missionInfo['smount']);//总量剩余量
					$re_num = $missionInfo['re_num'] + ( $amount - $missionInfo['amount'] );    //用户剩余量
					$cmount_re = $missionInfo['cmount_re'] + ($cmount - $missionInfo['cmount']);//渠道剩余量
					$updateMission = array('mission_name' => $mission_name,'mkwd'=>$mkwd, 're_num' => $re_num, 'price' => $price, 'amount' => $amount, 'scale' => $scale, 'channel_id' => $channel_id,'smount'=>$smount,'smount_re'=>$smount_re,'kwd_qx'=>$kwd_qx,  'app_id' => $app_id, 'start_time' => $start_time, 'end_time' => $end_time, 'des' => $des, 'adtype_id' => $adtype_id, 'label_name' => $label,'cmount' => $cmount,'cmount_re' => $cmount_re);
					$result = D('Mission')->updateMissionInfo($mission_id, $updateMission);
					//查询此任务当天添加的关键词信息
					$kkwds=D('Kwd_mount')->getKwd($mission_id);
					$kwdArr = array();
					//获取当天添加的关键词
					foreach ($kkwds as $k => $v) {
						$kwdArr[] = $v['kkwd'];
					}
                  	//比较两个数组的键值，并返回交集
                    $result = array_intersect($kwd,$kwdArr);

					foreach ($kwd as $k => $v) {
						if (in_array($v,$result)) { //对旧关键词更新  剩余量字段(+-)
							foreach ($kkwds as $key => $val) {
								if ($val['kkwd'] == $v) {
									//渠道关键词剩余量
									$mount_re = $kkwds[$key]['mount'] + ($mount[$k] -  $kkwds[$key]['mount']);
									//零花钱关键词剩余量
									$lmount_re = $kkwds[$key]['lmount'] + ($lmount[$k] -  $kkwds[$key]['lmount']);
									$kwd_data=array('mid'=>$mission_id,'kkwd'=>$kwd[$k],'mount'=>$mount[$k],'mount_re'=>$mount_re,'lmount'=>$lmount[$k],'lmount_re'=>$lmount_re,'time'=>$nowTime);
									$kwd_id=D('Kwd_mount')->updateKwd($mission_id,$kwd[$k],$kwd_data);
								}
							}
						}else{   //对新关键词添加  
							$kwd_data=array('mid'=>$mission_id,'kkwd'=>$kwd[$k],'mount'=>$mount[$k],'mount_re'=>$mount[$k],'lmount'=>$lmount[$k],'lmount_re'=>$lmount[$k],'time'=>$nowTime);
							$tj = $kwd_id=D('Kwd_mount')->addKwd($kwd_data);
						}
					}
					foreach ($kwdArr as $k => $v) {
						if (!in_array($v, $result)) {//删除当天修改后删除掉的关键词
							$del = D('Kwd_mount')->delKkwd($mission_id,$kwdArr[$k]);
						}
					}
					if ($result >= 0) {
						D('SysLog')->addLog(UserSession::getUserName(), 'MODIFY', '配置信息', $mission_id, json_encode($updateMission));
						$this->exitWithSuccess('修改成功',$httpref, 1);
					} else {
						$this->alert("error");
					}
				}
			}else{
				//任务不处于上线状态
				//关键词关闭  
				if ($kwd_qx == '0') {
					//mission表的更新
					$updateMission = array(
						'mission_name' => $mission_name,
						'mkwd'         => $mkwd,
						're_num'       => $amount,
						'price'        => $price,
						'amount'       => $amount,
						'scale'        => $scale,
						'channel_id'   => $channel_id,
						'smount'       => $smount,
						'smount_re'    => $smount,
						'kwd_qx'       => $kwd_qx,
						'app_id'       => $app_id,
						'start_time'   => $start_time,
						'end_time'     => $end_time,
						'des'          => $des,
						'adtype_id'    => $adtype_id,
						'label_name'   => $label,
						'cmount'       => $cmount,
						'cmount_re'    => $cmount
					);
					$result = D('Mission')->updateMissionInfo($mission_id, $updateMission);
					if ($result >= 0) {
						D('SysLog')->addLog(UserSession::getUserName(), 'MODIFY', '配置信息', $mission_id, json_encode($updateMission));
						$this->exitWithSuccess('修改成功',$httpref, 1);
					} else {
						$this->alert("error");
					}
				}
				//关键词开启
				if ($kwd_qx == '1') {
					//mission表的更新
					$updateMission = array(
						'mission_name' => $mission_name,
						'mkwd'         => $mkwd,
						're_num'       => $amount,
						'price'        => $price,
						'amount'       => $amount,
						'scale'        => $scale,
						'channel_id'   => $channel_id,
						'smount'       => $smount,
						'smount_re'    => $smount,
						'kwd_qx'       => $kwd_qx,
						'app_id'       => $app_id,
						'start_time'   => $start_time,
						'end_time'     => $end_time,
						'des'          => $des,
						'adtype_id'    => $adtype_id,
						'label_name'   => $label,
						'cmount'       => $cmount,
						'cmount_re'    => $cmount
					);
					$str = D('Mission')->updateMissionInfo($mission_id, $updateMission);
					//查询此任务当天添加的关键词
					$kkwds=D('Kwd_mount')->getKwd($mission_id);
					$kwdArr = array();
					foreach ($kkwds as $k => $v) {
						$kwdArr[] = $v['kkwd'];
					}
                  	//比较两个数组的键值，并返回交集
                    $result = array_intersect($kwd,$kwdArr);
					foreach ($kwd as $k => $v) {
						if (in_array($v,$result)) { //对旧关键词更新
							$kwd_data=array(
								'mid'      =>$mission_id,
								'kkwd'     =>$kwd[$k],
								'mount'    =>$mount[$k],
								'mount_re' =>$mount[$k],
								'lmount'   =>$lmount[$k],
								'lmount_re'=>$lmount[$k],
								'time'     =>$nowTime
							);
							$kwd_id=D('Kwd_mount')->updateKwd($mission_id,$kwd[$k],$kwd_data);
						}else{   //对新关键词添加
							$kwd_data=array('mid'=>$mission_id,'kkwd'=>$kwd[$k],'mount'=>$mount[$k],'mount_re'=>$mount[$k],'lmount'=>$lmount[$k],'lmount_re'=>$lmount[$k],'time'=>$nowTime);
							$tj = $kwd_id=D('Kwd_mount')->addKwd($kwd_data);
						}
					}
					foreach ($kwdArr as $k => $v) {
						if (!in_array($v, $result)) {//删除当天修改后删除掉的关键词
							$del = D('Kwd_mount')->delKkwd($mission_id,$kwdArr[$k]);
						}
					}
					if ($str >= 0) {
						D('SysLog')->addLog(UserSession::getUserName(), 'MODIFY', '配置信息', $mission_id, json_encode($updateMission));
						$this->exitWithSuccess('修改成功',$httpref, 1);
					} else {
						$this->alert("error");
					}
				}
			}
		}
		$apps = D('App')->getAppsArray();
		$adtypes =D('Adtype')->getAdTypesArray();
		$channels = D('Channel')->getChannelsArray();
		$kwdArrs=D('Kwd_mount')->getKwdByMid($mission_id);
        $label_name = D('Label')->label_name();    //标签

        $this->assign('labels', $label_name);
		$this->assign("kwdArrs",$kwdArrs);
		$this->assign ( 'mission', $missionInfo );
		$this->assign("channels", $channels);
		$this->assign("apps",$apps);
		$this->assign("adtypes",$adtypes);
		$this->display ();
	}

	public function appidAjax()
	{
		$method = $name = '';
		extract ( $_POST, EXTR_IF_EXISTS );
		$res = D('App')->SelectAppByName($name);
		return json_encode($res) ;
	}

	//删除关键词
//	public function delKwd()
//	{
//		$mid = I('post.id');
//		$kwd = I('post.ked');
//		dump($mid);
//		dump($kwd);
//		if (!empty($msid) && !empty($kwd)) {
//			$result = D('Kwd_mount')->delKwds($mid,$kwd);
//			if ($result) {
//				$sj = 1;
//				return json_encode($sj);
//			} else {
//				$sj = 2;
//				return json_encode($sj);
//			}
//		}
//	}


}
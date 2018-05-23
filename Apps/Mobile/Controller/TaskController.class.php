<?php
namespace Mobile\Controller;
use Think\Controller;        
class TaskController extends CommonController{
	public function index(){
		$this->display();
	}
	/**
	* 任务中心
	*/
    public function task(){
    	$member_id = session('member_id');
		//更改用户对于没做完但是已经下线的任务的状态为5
        $memmionid = D('MemMis')->getMssionId($member_id);
		$cd_time = (int)$memmionid['cd_time'];
		$nowTime = date('Y-m-d H:i:s',time());
		if($memmionid != NULL){
		    $memmionid = (int)$memmionid['mission_id'];
		    $endTime = D('Mission')->getEndtime($memmionid);

            //修改下线任务状态为5
		    if ($nowTime >= $endTime['end_time']) {
		    	D('MemMis')->updateStatus($member_id);
		        $_SESSION[$memmionid."_".$member_id."time"] ='';
		    }
		    //对于在任务详情页面直接退出并且下次登录在30分钟以后的用户
		    $ctime=$cd_time+1800-time();
		    if($ctime<=0){ 
                $zf=D('MemMis')->deleteMemMis($member_id,$memmionid);
		        $Detailid= D('TaskDetails')->getMsidMeid($member_id,$memmionid);
		        if(!empty($Detailid)){
		            $endtime=date('Y-m-d H:i:s',time());
		            $update_data = array ('status' =>5, 'end_time' => $endtime);
		            $result = D('TaskDetails')->updateTaskDetail($Detailid, $update_data );
		        }
		    }
		}
		//修改下线任务的状态为3
        $MisStatus = D('Mission')->updateMisStatus();
		//获取任务列表中可以显示的任务 
		$map = array() ;
		$map['start_time'] =array('elt',date("Y-m-d H:i:s",time()));
        $map['end_time'] = array('egt',date("Y-m-d H:i:s",time()));
        $map['status'] = array('eq',2);
        $map['re_num'] = array('gt',0);
		$field = "a.mission_id, a.adtype_id,a.app_id,a.mission_name, a.price, a.label_name, a.end_time, b.img, b.is_repeat" ;
		$missions = M( 'Mission' ) -> field( $field ) 
			-> alias( 'a' ) 
			-> join( 'LEFT JOIN app b ON a.app_id = b.app_id' )
			-> where( $map ) 
			-> order( "adtype_id DESC,mission_id DESC" ) 
			-> select( ) ;

		//获取待上线任务的所有信息       
		$Waitsmissions=D('Mission')->getsWaitMissions();
        //查询已下线的任务   
		$Endmissions=D('Mission')->getEndingMissions();
        //查询剩余量为0的任务 	
		$Rnummissions=D('Mission')->getRnumMissions();
        //查询两个条件同时到的任务
        $RnummEnds=D('Mission')->getRnumEndMissions();
		//查询做过的任务 
		$Finishs=D('MemMis')->getWcMission($member_id);  
        $missionName=D('Mission')->getMissionsArray();
		$appIds=D('Mission')->getappIdArray();

		$imgs=D('App')->getAppimgArray();
		$status=D('MemMis')->getMissionStatus($member_id);//查询个人任务和该任务对应的状态

		$zt=1;
		if($status){
			foreach($status as $k=>$v){
				if($v=='2'){
				    $zt=2;break;
				}else{
				    $zt=1;
				}
			}
		}
        //在线的任务
        foreach ($missions as $key=>$mission){
            $label=explode(',',$mission['label_name']);
            $missions[$key]['label_name']=$label;
        }
        //已抢光任务
        foreach ($Rnummissions as $key=>$Rnummission){
            $label=explode(',',$Rnummission['label_name']);
            $Rnummissions[$key]['label_name']=$label;
        }
        //已完成任务
		foreach ($Finishs as $key => $Finish) {
			$lab = D('Mission')->getlabel($Finish['mission_id']);
			$label = explode(',', $lab['label_name']);
			$Finishs[$key]['label_name'] = $label;
		}
        //已下线任务
        foreach ($Endmissions as $key=>$Endmission){
            $label=explode(',',$Endmission['label_name']);
            $Endmissions[$key]['label_name']=$label;
        }

		//关键词
		foreach ($missions as $key=>$id) {
			$label=explode(',',$id['label_name']);
			$missions[$key]['label_name']=$label;
		}
		//查询此任务当天添加的关键词信息
		foreach($missions as $key=>$id){
		$kkwds[$key]=D('Kwd_mount')->getKwd($id['mission_id']);
		//取剩余量不为零的
		foreach($kkwds[$key] as $v) {
			if ($v['mount_re'] > '0' && $v['lmount_re'] > '0') {
				$kwdarr[] = $v['kkwd'];
			}
		}
		//数组的键值
		$k =array_rand($kwdarr);
		$missions[$key]['kwd'] = $kwdarr[$k];
		}
		
		$this->assign ( 'missions', $missions );
		$this->assign ( 'Finishs', $Finishs );
		$this->assign ( 'RnummEnds', $RnummEnds );
		$this->assign ( 'member_id', $member_id );
		$this->assign ( 'imgs', $imgs );
		$this->assign ( 'appIds', $appIds );
		$this->assign ( 'status', $status );
		$this->assign ( 'zt', $zt );
		$this->assign ( 'Waitsmissions', $Waitsmissions );
		$this->assign ( 'Endmissions', $Endmissions );
		$this->assign ( 'Rnummissions', $Rnummissions );
		$this->assign ( 'missionName', $missionName );

        $this->display( );
    }


    //任务详情页
    public function details(){
	    $mission_id = I('get.mission_id');

	    $member_id =session('member_id');
	    $midmsid = D('MemMis')->getMsidMid($member_id,$mission_id);
	    $stamsimid = D('MemMis')->getStaMsidMid($member_id,$mission_id);
        //判断
        if(empty($midmsid) || !empty($stamsimid)){  
            $cd_ime=time();
            $ctime=date('Y-m-d H:i:s',$cd_ime);
            if (empty($midmsid)) {  
                $input_data = array ('member_id' =>$member_id,'mission_id' =>$mission_id,'status' => 2,'ctime'=>$ctime,'first_step'=>0,'second_step'=>0,'cd_time'=>$cd_ime);
                $id= D('MemMis')->addMemMis($input_data);
            }
            if (!empty($stamsimid)) {
                $id= D('MemMis')->ChangeAgainStatus($member_id,$mission_id,$ctime);
            } 
            D('Mission')->updateRuNum($mission_id);     //进来后将任务数量减一     
            $listData=D('Mission')->getMissionById($mission_id);
            $Detailid= D('TaskDetails')->getMsidMeid($member_id,$mission_id);

            if(!empty($Detailid)){
                $update_data = array ('status' => 2, 'start_time' => $ctime);
                $result = D('TaskDetails')->updateTaskDetail($Detailid, $update_data );
            }else{
                $input_data = array ('member_id' =>$member_id,'mission_id' =>$mission_id,'status' => '2','start_time'=>$ctime,'app_id' => $listData['app_id'], 'price' => $listData['price']);
                $Detailid= D('TaskDetails')->addTaskDetail($input_data);
            }
        }
		//获取任务详情 
		$map = array() ;
        $map['a.mission_id'] = array('eq',$mission_id);
		$field = "a.mission_id,a.app_id,a.mission_name, a.price, a.des,a.kwd_qx,a.mkwd, a.re_num,b.img, b.url_scheme, b.bundleId,b.adtype_id";
		$missionIds = M( 'Mission' ) -> field( $field ) 
			-> alias( 'a' ) 
			-> join( 'LEFT JOIN app b ON a.app_id = b.app_id' )
			-> where( $map ) 
			-> select( );
		//var_dump($missionIds);die;
		if($missionIds['0']['kwd_qx'] == 0){
			if($missionIds['0']['re_num'] > 0){
				$kwd=$missionIds['0']['mkwd'];
			}
			
		}elseif($missionIds['0']['kwd_qx'] == 1){
//			$kwdArrs=D('Kwd_mount')->getKwdByLMid($mission_id);
//			foreach ($kwdArrs as $k => $v) {
//				$kwdArr[]=$v['kkwd'];
//			}
//			//var_dump($kwdArr);die;
//			$m=mt_rand(0,count($kwdArr)-1);
//			$kwd=$kwdArr[$m];
			$kwd = I('get.kwd');
		}
	    $step = D('MemMis')->SelectSteps($member_id, $mission_id);
		//$this->assign('kwd1',$kwd1);
	    $this->assign ( 'missionIds', $missionIds['0'] );
	    $this->assign ( 'member_id', $member_id );
	    $this->assign ( 'step', $step );
	    $this->assign ( 'kwd', $kwd );
	    $this->display();
    }
    /**
	* 判断任务是否已下线和任务是否已被抢光
	*/
    public function taskDownAjax(){
	    $mission_id = I('get.mission_id');
    	$a = 1;
    	//判断任务是否已经被抢光
    	if(!empty($mission_id)){
		    $ReNum = D('Mission')->getMisReNum($mission_id);
		    if ($ReNum['re_num'] == 0) {
		    	$a = 3;
		    }
		}
	    //如果任务以下线  则修改状态（status）为下线3
	    $nowTime = date('Y-m-d H:i:s',time());
		$endTime = D('Mission')->getEndtime($mission_id);   //获取任务结束时间
		if ($nowTime>$endTime['end_time']) {
		    $memmisStatus = D('Mission')->updateStatus($mission_id);
		    $a = 2;
		}
        $this->ajaxReturn($a);
    }

	public function callBack()
	{
		$mission_id=I('get.msid');
		$member_id = I('get.mid');
		if (empty($mission_id) || empty($member_id)) {
			echo json_encode(array("code"=>501,"message"=>"缺少关键词"),JSON_UNESCAPED_UNICODE);
			exit;
		}
		$idfa = D('Member')->getIdfa($member_id);
		$app_id = D('Mission')->getappId($mission_id);
		$result = D('ChannelLog')->getCallback_urlBycallback1($app_id,$idfa);
		$callback = $result['0']['callback'];
		$data = $this->httpGet($callback);
		echo $data;
	}

	public function httpGet($request_url){
		/* 初始化并执行curl请求 */
		$ch = curl_init();
		//设置选项
		curl_setopt($ch, CURLOPT_URL, $request_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		//执行
		$data = curl_exec($ch);
		//关闭curl
		curl_close($ch);
		return $data ;
	}

}
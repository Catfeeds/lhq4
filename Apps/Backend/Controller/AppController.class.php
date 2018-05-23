<?php
namespace Backend\Controller;
use Think\Controller;
use Common\Common\UserSession;
use Common\Common\Pagination;
class AppController extends ComController{
	//广告应用管理
	public function app(){
		$method = $app_id = $app_name= $provider_id = $page_no =$channel_id= $search=$row_count='';
		extract ( $_GET, EXTR_IF_EXISTS );
		$user_id=UserSession::getUserId();
		$user_group=UserSession::getUserGroup();
		if ($method == 'del' && ! empty ( $app_id )) {
			$app = D('App')->getAppById($app_id);
			if(intval($app_id) <= 0){
                $this->alert("error",'系统菜单不能被删除');//ErrorMessage::CAN_NOT_DELETE_SYSTEM_MENU
				//OSAdmin::alert("error",ErrorMessage::CAN_NOT_DELETE_SYSTEM_MENU);
			}else{
				$result = D('App')->delApp ( $app_id );
				if ($result) {
					D('SysLog')->addLog ( UserSession::getUserName(), 'DELETE', '配置信息' ,$app_id, json_encode($app) );
					$pconfigs = D('ProviderConfig')->getProviderConfigByAppid($app_id);
					$presult=D('ProviderConfig')->delProviderConfigByAppid($app_id);

					if($presult){
						foreach($pconfigs as $v){
							D('SysLog')->addLog ( UserSession::getUserName(), 'DELETE', '配置信息' ,$app_id, json_encode($v) );
						}
					}
					$pcconfigs = D('PcConfig')->getPcConfigByAppid($app_id);
					$pcresult=D('PcConfig')->delPcConfigByAppid($app_id);
					if($pcresult){
						foreach($pcconfigs as $v){
							D('SysLog')->addLog ( UserSession::getUserName(), 'DELETE', '配置信息' ,$app_id, json_encode($v) );
						}
					}
					$aconfigs = D('ActiveConfig')->getActiveConfigByAppid($app_id);
					$aresult=D('ActiveConfig')->delActiveConfigByAppid($app_id);
					if($aresult){
						foreach($aconfigs as $v){
							D('SysLog')->addLog ( UserSession::getUserName(), 'DELETE', '配置信息' ,$app_id, json_encode($v) );
						}
					}

					$missoins=D('Mission')->getMissionByAppid($app_id);
					$mission_id=$missoins[0]['mission_id'];
					$kwdresult=D('Kwd_mount')->delKwd($mission_id);
					//var_dump($missoins);die;
					$misresult=D('Mission')->delMissionByAppid($app_id);
                  //  D('SysLog')->addLog ( UserSession::getUserName(), 'DELETE', '配置信息' ,$app_id, json_encode($result) );
                    $this->exitWithSuccess('已将配置信息删除', U('Backend/App/app'),1);

					//Common::exitWithSuccess ('已将配置信息删除','backend/app.php');
				}else{
                    $this->alert("error");
					//OSAdmin::alert("error");
				}
			}
		}
		if($user_group==1||$user_group==5){
			$providers = D('Provider')->getProvidersArray();
		}else{
			$providers = D('Provider')->getProvidersArrayByUserid($user_id);
		}
//var_dump($apps);die;
		$adtypes =D('Adtype')->getAdTypesArray();
		$channels = D('Channel')->getChannelsArray();

		$page_size = PAGE_SIZE;
		$page_no=$page_no<1?1:$page_no;

		if($user_group==1||$user_group==5){
			$row_count =D('App')->countSearch($app_name,$provider_id);
			//var_dump($row_count);die;
			$total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
			$total_page=$total_page<1?1:$total_page;
			$page_no=$page_no>($total_page)?($total_page):$page_no;
			$start = ($page_no - 1) * $page_size;
			if($search){
				$apps = D('App')->search($app_name,$provider_id,$start , $page_size);
			}else{
				$apps = D('App')->getAllApp ( $start , $page_size );
				//var_dump($apps);die;
			}
		}elseif($user_group==3) {
			$arr = D('Provider')->getAllProviderByUserid($user_id);

			foreach ($arr as $v) {
				$providerIds[] = $v['provider_id'];
			}
			//var_dump($providerIds);die;
			if ($provider_id == 0) {
				$row_count +=D('App')->countSearchByProid($app_name, $providerIds);
				//var_dump($row_count);die;
			} else {
				$row_count = D('App')->countSearchByProid($app_name, $provider_id);
			}
			$total_page = $row_count % $page_size == 0 ? $row_count / $page_size : ceil($row_count / $page_size);
			$total_page = $total_page < 1 ? 1 : $total_page;
			$page_no = $page_no > ($total_page) ? ($total_page) : $page_no;
			$start = ($page_no - 1) * $page_size;
			if ($search) {
				if ($provider_id == 0) {
					$apps = D('App')->searchByProid($app_name, $providerIds, $start, $page_size);
				}else{
					$apps = D('App')->searchByProid($app_name, $provider_id, $start, $page_size);
				}
			} else {
				$apps = D('App')->getAllAppProid($providerIds, $start, $page_size);

			}
		}elseif($user_group==4){
			$chanIds=D('Channel')->getUserChanid($user_id);
			if($chanIds) {
				$row_count = D('App')->countSearchByChanid($app_name, $chanIds);
				$total_page = $row_count % $page_size == 0 ? $row_count / $page_size : ceil($row_count / $page_size);
				$total_page = $total_page < 1 ? 1 : $total_page;
				$page_no = $page_no > ($total_page) ? ($total_page) : $page_no;
				$start = ($page_no - 1) * $page_size;
				$apps = D('App')->searchByChanid($app_name, $chanIds, $start, $page_size);
			}
		}
		if($apps){
			if($user_group==4){
				$chanIds=D('Channel')->getUserChanid($user_id);

				foreach($apps as $k=>$v){
					$chanid[]=$v['chan_id'];
				}
				// var_dump($chanid);
				// var_dump(count($chanid));die;
				for($i=0;$i<count($chanid);$i++) {
					$chanidarr = explode(",", $chanid[$i]);
					$num = count($chanidarr);
					//var_dump($chanidarr);
					for($j=0;$j<$num;$j++){
						$chan_id=in_array($chanidarr[$j],$chanIds)?$chanidarr[$j]:'';
						$arr[$i][$j]=$chan_id;
					}
				}
				//var_dump($arr);die;
				for($n=0;$n<count($arr);$n++){
					$chan_name[] = D('Channel')->getAppsNameByChanid($arr[$n]);
				}

				foreach($apps as $k=>$v){
					$apps[$k]['chan_name'] = $chan_name[$k];
				}
			}else{
				//var_dump($apps);die;
				foreach ($apps as $k=> $v) {
					//var_dump($apps);
					$chan_name = D('Channel')->getAppsName($v['chan_id']);
					$apps[$k]['chan_name'] = $chan_name;
				}
			}

		}
//die;

//var_dump($apps);die;
//END
// 显示分页栏

		$page_html=Pagination::showPager("app.php?app_name=$app_name&provider_id=$provider_id",$page_no,PAGE_SIZE,$row_count);
//echo $page_html;die;
//$apps = App::getAppsByPage($app_name,$provider_id,$start, $page_size);

//var_dump($apps);die;
		$this->assign ( 'page_no', $page_no );
		$this->assign( 'page_size', PAGE_SIZE );
		$this->assign ( 'row_count', $row_count );
		$this->assign ( 'page_html', $page_html );
		$this->assign('user_group',$user_group);
		$this->assign ('apps',$apps);
		$this->assign ( '_GET', $_GET );
		$this->assign ('adtypes',$adtypes);
		$this->assign("channels",$channels);
		$this->assign("providers",$providers);
		$this->display ();
	}
    //添加 广告应用
    public function appAdd(){
    $app_id = $app_name = $appstore_url = $remark = $cutoff = $adsid = $provider_id =$channel_id =$adtype_id = $is_repeat = $url_scheme= $goods_img= $appid = $bundleid ='';
		$max_file_size=10*1024*1024;
		extract ( $_POST, EXTR_IF_EXISTS );
		if (!empty($cutoff)) {
			$cutoff = implode(',', $cutoff);
		}
		//var_dump($_POST);die;
		$img = "/Public/app_logo/default_logo.png";
		$path=$_POST[img];
		if (IS_POST) {
			$path=strstr($path,'/Public');
			$gpath=($_SERVER['DOCUMENT_ROOT']);
			$xpath=__ROOT__;
			$spath=strstr($path,'/app_logo');
			$truePath=$gpath.$xpath.$path;
		//	var_dump($gpath,$path,$truePath);die;

			$size=filesize($truePath);
			if($max_file_size < $size) {
				$this->exitWithError ( '文件太大!',U('backend/app_add.php') ,2);
				//OSAdmin::alert("error",ErrorMessage::file_too_large);
				// return false;
				exit;
			}
			$id=D('App')->getID();
			//var_dump($id);die;
			$napp_name=$app_name."($id)";

			$exist = D('App')->getAppByName($napp_name);

			if($exist){
                $this->alert("error",'已存在，请不要重复添加');//error('已存在，请不要重复添加', U('Backend/App/app'),1);
				//OSAdmin::alert("error",ErrorMessage::NAME_CONFLICT);
			}else{
		//		var_dump($cutoff);die;
				$input_data = array ('app_name' => $napp_name, 'appstore_url' => $appstore_url, 'remark' => $remark, 'cutoff' => $cutoff, 'adsid' => $adsid,
		            'provider_id' => $provider_id, 'chan_id' => $channel_id,'adtype_id' => $adtype_id,'is_repeat' => $is_repeat,'url_scheme' => $url_scheme,'img' => $spath , 'bundleid'=> $bundleid);
		
				$app_id = D('App')->addApp( $input_data );

	    		if($adtype_id == 1 || $adtype_id == 3){     //回调任务或上报点击任务,自动添加广告主点击上报配置。
            		$config_content='';
           			$config_if='';
            		$proconfig_data = array ('config_name' => $napp_name, 'config_content' => $config_content, 'app_id' => $app_id ,'config_if' => $config_if);
            		$proconfig_id = D('ProviderConfig')->addProviderConfig( $proconfig_data );

        		}elseif($adtype_id == 2){   //排重,回调任务,自动添加广告主点击上报配置、排重配置信息。
            		$config_content='';
            		$config_if='';
            		$repeat_url='';
            		$request='get';
            		//自动添加广告主点击上报配置
            		$proconfig_data = array ('config_name' => $napp_name, 'config_content' => $config_content, 'app_id' => $app_id ,'config_if' => $config_if);
            		$proconfig_id = D('ProviderConfig')->addProviderConfig( $proconfig_data );
            		//自动排重配置信息
            		$pcconfig_data = array ('config_name' => $napp_name, 'repeat_url' => $repeat_url, 'app_id' => $app_id ,'config_if' => $config_if ,'request' => $request);
            		$pcconfig_id = D('PcConfig')->addPcConfig( $pcconfig_data);

        		}elseif($adtype_id == 4){   //上报激活任务,自动添加广告主激活上报配置。
            		$config_content='';
            		$config_if='';
            		//自动添加广告主激活上报配置
            		$actconfig_data = array ('config_name' => $napp_name, 'config_content' => $config_content, 'app_id' => $app_id ,'config_if' => $config_if);
            		$actconfig_id = D('ActiveConfig')->addActiveConfig($actconfig_data );

        		}elseif($adtype_id == 5){   //排重任务,自动添加排重配置信息。
            		$repeat_url='';
            		$config_if='';
            		$request='get';
            		//自动排重配置信息
            		$pcconfig_data = array ('config_name' => $napp_name, 'repeat_url' => $repeat_url, 'app_id' => $app_id ,'config_if' => $config_if ,'request' => $request);
            		$pcconfig_id = D('PcConfig')->addPcConfig( $pcconfig_data);

        		}elseif($adtype_id == 6){   //排重,上报点击任务,自动添加排重配置信息、广告主点击上报配置。
           		 	$config_content='';
            		$config_if='';
            		$repeat_url='';
            		$request='get';
            		//自动排重配置信息
            		$pcconfig_data = array ('config_name' => $napp_name, 'repeat_url' => $repeat_url, 'app_id' => $app_id ,'config_if' => $config_if ,'request' => $request);
            		$pcconfig_id = D('PcConfig')->addPcConfig( $pcconfig_data);
            		//自动添加广告主点击上报配置
            		$proconfig_data = array ('config_name' => $napp_name, 'config_content' => $config_content, 'app_id' => $app_id ,'config_if' => $config_if);
            		$proconfig_id = D('ProviderConfig')->addProviderConfig( $proconfig_data );

        		}elseif($adtype_id == 7){   //排重,上报激活任务,自动添加排重配置信息、广告主激活上报配置。
            		$config_content='';
            		$config_if='';
            		$repeat_url='';
            		$request='get';
            //自动排重配置信息
            		$pcconfig_data = array ('config_name' => $napp_name, 'repeat_url' => $repeat_url, 'app_id' => $app_id ,'config_if' => $config_if ,'request' => $request);
            		$pcconfig_id = D('PcConfig')->addPcConfig( $pcconfig_data);
            //自动添加广告主激活上报配置
            		$actconfig_data = array ('config_name' => $napp_name, 'config_content' => $config_content, 'app_id' => $app_id ,'config_if' =>    $config_if);
            		$actconfig_id = D('ActiveConfig')->addActiveConfig($actconfig_data );

        		}elseif($adtype_id == 8){   //上报点击,上报激活任务,自动添加广告主点击上报配置、广告主激活上报配置。
        			$config_content='';
        			$config_if='';
            //自动添加广告主点击上报配置
        			$proconfig_data = array ('config_name' => $napp_name, 'config_content' => $config_content, 'app_id' => $app_id ,'config_if' => $config_if);
        			$proconfig_id = D('ProviderConfig')->addProviderConfig( $proconfig_data );
            //自动添加广告主激活上报配置
        			$actconfig_data = array ('config_name' => $napp_name, 'config_content' => $config_content, 'app_id' => $app_id ,'config_if' => $config_if);
        			$actconfig_id = D('ActiveConfig')->addActiveConfig($actconfig_data );

        		}elseif($adtype_id == 9){   
        		//排重,上报点击,上报激活任务,自动添加排重配置信息、广告主点击上报配置、广告主激活上报配置。
        			$config_content='';
        			$config_if='';
        			$repeat_url='';
        			$request='get';
            //自动排重配置信息
        			$pcconfig_data = array ('config_name' => $napp_name, 'repeat_url' => $repeat_url, 'app_id' => $app_id ,'config_if' => $config_if ,'request' => $request);
        			$pcconfig_id = D('PcConfig')->addPcConfig( $pcconfig_data);
            //自动添加广告主点击上报配置
        			$proconfig_data = array ('config_name' => $napp_name, 'config_content' => $config_content, 'app_id' => $app_id ,'config_if' => $config_if);
        			$proconfig_id = D('ProviderConfig')->addProviderConfig( $proconfig_data );
            //自动添加广告主激活上报配置
        			$actconfig_data = array ('config_name' => $napp_name, 'config_content' => $config_content, 'app_id' => $app_id ,'config_if' => $config_if);
        			$actconfig_id = D('ActiveConfig')->addActiveConfig($actconfig_data );
        		}
       			$time=date('Y-m-d',time());
        		$start_time=$time." 00:00:00";
        		$end_time=$time." 23:59:59";
        		$mission_data = array ('mission_name' => $app_name, 'price' => '',  'scale' => '', 'app_id' => $app_id,'amount' => '', 'des' => '','start_time' => $start_time, 'end_time' => $end_time, 'channel_id' => -1, 'status'=>1,'re_num' => '','adtype_id'=>$adtype_id,'label_name'=>'','smount'=>'','smount_re'=>'','kwd_qx'=>'0');
        //  var_dump($input_data);die;

        		$mission_id = D('Mission')->addMission( $mission_data);
      /*  $chanArr=explode(',', $channel_id);
        //var_Dump($chanArr);
        $ctime=date('Y-m-d H:i:s',time());
        for($i=0;$i<count($chanArr);$i++){
        	$kwd_data=array('mid'=>$mission_id,'kwd'=>'','kwd_qx'=>0,'mount'=>'','mount_re'=>'','time'=>$ctime,'chan_id'=>$chanArr[$i]);
        	$kwd_id=D('Kwd_mount')->addKwd($kwd_data);
        }
      */  
                //添加信息到关键词表
        		// $ctime=date('Y-m-d H:i:s',time());
        		// // $kwd_data=array('mid'=>$mission_id,'kwd'=>'','mount'=>'','mount_re'=>'','time'=>$ctime);
        		// // $kwd_id=D('Kwd_mount')->addKwd($kwd_data);
				
				if ($app_id) {
                    D('SysLog')->addLog ( UserSession::getUserName(), 'ADD', '配置信息' ,$app_id, json_encode($input_data) );
                    if($pcconfig_id){
                    	D('SysLog')->addLog ( UserSession::getUserName(), 'ADD', '配置信息' ,$pcconfig_id, json_encode($pcconfig_data) );
                    }
                    if($proconfig_id){
                    	D('SysLog')->addLog ( UserSession::getUserName(), 'ADD', '配置信息' ,$proconfig_id, json_encode($proconfig_data) );
                    }
                    if($actconfig_id){
                    	D('SysLog')->addLog ( UserSession::getUserName(), 'ADD', '配置信息' ,$actconfig_id, json_encode($actconfig_data) );
                    }
                    if($mission_id){
                    	D('SysLog')->addLog ( UserSession::getUserName(), 'ADD', '配置信息' ,$mission_id, json_encode($mission_data) );
                    }

					$this->exitWithSuccess('广告应用添加完成', U('Backend/App/app'),1);

					//$this->redirect( U('Backend/App/app'), '', 1, '广告应用添加完成...');
					//
					//Common::exitWithSuccess ('配置信息添加完成','backend/app.php');
				}
			}
		}
		$providers = D('Provider')->getProvidersArray();
		$channels = D('Channel')->getChannelsArrays();
	//	dump($channels);die;
		$adtypes =D('Adtype')->getAdTypesArray();
		$this->assign("channels", $channels);
		$this->assign("providers",$providers);
		$this->assign("adtypes",$adtypes);
		$this->assign("_POST" ,$_POST);
		$this->display();
    }

    //修改广告应用
    public function appModify(){
        $app_id = $app_name = $appstore_url = $remark = $cutoff = $is_repeat = $adsid =$provider_id =$chan_id =$adtype_id = $url_scheme= $goods_img= $httpref= '';
		extract ( $_REQUEST, EXTR_IF_EXISTS );
		$max_file_size=10*1024*1024;
		$path=$_POST['img'];
		if (!empty($cutoff)) {
			$cutoff = implode(',', $cutoff);
		}
		$app =D('App')->getAppById($app_id);
		//var_dump($app);
		if(empty($app)){
			$this->exitWithError(ErrorMessage::SAMPLE_NOT_EXIST,U('Backend/App/app'),1);//error('缺少参数', U('Backend/App/app'),1);
		}
		if (IS_POST) {
			$path=strstr($path,'/Public');
			$gpath=$_SERVER['DOCUMENT_ROOT'];
			$xpath=__ROOT__;
			$spath=strstr($path,'/app_logo');
			$truePath=$gpath.$xpath.$path;
			$size=filesize($truePath);
			if($max_file_size < $size) {
				$this->exitWithError ( '文件太大!',U('backend/app_add.php') ,2);
				//OSAdmin::alert("error",ErrorMessage::file_too_large);
				// return false;
				exit;
			}
			//var_dump($gpath,$path,$xpath,$truePath);die;
		/*	//判断是否有图片上传  $_FILES['goods_img']['error'] == 0 时有图片上传
            if ($_FILES['goods_img']['error'] == 0) {             
	            //文件上传
	            $upload = new \Think\Upload();                     // 实例化上传类
	            $upload->maxSize = 10*1024*1024 ;                       // 设置附件上传大小
	            $upload->exts = array('jpg', 'gif', 'png', 'jpeg','pjpeg','bmp','x-png');// 设置附件上传类型
	            $upload->rootPath = './Public/app_logo/';                   // 设置附件上传根目录
	            // 上传文件
	            $info = $upload->upload();
	            if(!$info) {// 上传错误提示错误信息
	                 $this->error($upload->getError());
	            }else{// 上传成功
	                //获取图片路径
	                $goods_img = '/app_logo/'.$info['goods_img']['savepath'].$info['goods_img']['savename']; 
	                //删除旧的图片
	                $oldLogo = D('App')->field('img')->find($app_id); 
	                unlink('./Public/'.$oldLogo['img']);
	            }
            }else{
            	$goods_img = $app['img'];
            }
			*/
			if($app_name == "" ){
				$this->alert("error",'缺少参数');//error('缺少参数', U('Backend/App/app'),1);
				//OSAdmin::alert("error",ErrorMessage::NEED_PARAM);
			}else{
				if(strpos($app_name,"($app_id)") !==false){
					$napp_name=$app_name;
				}else{
					$napp_name=$app_name."(".$app_id.")";
				}
				//var_dump($napp_name);die;
				$update_data = array ('app_name' => $napp_name, 'appstore_url' => $appstore_url, 'remark' => $remark, 'cutoff' => $cutoff,
		            'adsid' => $adsid,'provider_id' => $provider_id, 'chan_id'=> $chan_id,'adtype_id'=>$adtype_id,'is_repeat' => $is_repeat,'url_scheme'=>$url_scheme,'img'=>$spath, 'bundleid'=> $bundleId);
				$result = D('App')->updateAppInfo($app_id, $update_data );
              // var_dump($result);die;
				if ($result>=0) {
					 D('Mission')->update_adtypeid($app_id,$adtype_id);
                    D('SysLog')->addLog ( UserSession::getUserName(), 'MODIFY', '配置信息' ,$app_id, json_encode($update_data) );
					 $this->exitWithSuccess('配置信息修改完成', $httpref,1);

					//Common::exitWithSuccess ( '配置信息修改完成','backend/app.php' );
				} else {
					 $this->alert("error");
					//OSAdmin::alert("error");
				}
			}
		}
		$providers = D('Provider')->getProvidersArray();
		$channels = D('Channel')->getChannelsArrays();
		$adtypes =D('Adtype')->getAdTypesArray();
		//var_dump($adtypes);die;
		$chan_name = D('Channel')->getChansName($app['chan_id']);
	//dump($chan_name);die;
		$app['chan_name'] = $chan_name;
		$chanids=explode(',',$app['chan_id']);
		$cutoff = D('App')->getCutoff($app_id);
		//var_dump($cutoff);die;
		$chanName_Arr = explode(',', $chan_name);
		$cutoof = explode(',', $cutoff['0']['cutoff']);
		if (count($cutoof) == count($chanName_Arr)) {
			$chanName_cutoff = array_combine($chanName_Arr,$cutoof);
		}

		$this->assign('chanName_cutoff', $chanName_cutoff);
		$this->assign("chanids", $chanids);
		$this->assign("channels", $channels);
		$this->assign("providers",$providers);
		$this->assign("adtypes",$adtypes);
		$this->assign ( 'app', $app );
		$this->display();
    }

	//获取bundleid
	public function getBundle()
	{
		$appid = I('post.appid');
		$url="http://itunes.apple.com/cn/lookup?id=".$appid;
		$html = file_get_contents($url);
		$gameArr=json_decode($html,true);
		$bundleId = $gameArr['results'][0]['bundleId'];
		if ($bundleId) {
			echo json_encode($bundleId,true);
		}
	}


}
?>
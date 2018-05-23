<?php
namespace Backend\Controller;
use Common\Model\UserModel;
use Think\Controller;
use Common\Common\UserSession;
use Common\Common\Pagination;
class ProviderReportFormController extends ComController{
	//广告主报表
	public function providerReportForm(){
		$method = $app_name = $page_no = $chan_id = $channel_name = $start_date = $end_date = $search = '';
		extract ( $_GET, EXTR_IF_EXISTS );
		$user_id = UserSession::getUserId();
		$userModel=new UserModel();
		$userinfo = $userModel->getUserById($user_id);
		$userGroupId = $userinfo['user_group'];

		if($chan_id == "0") {
			$_GET["chan_id"] = "";
			$chan_id ='';
		}

		switch ($userGroupId) {
			case '3':
				$provider_id =D('Provider')->getSwProviderByUserid($user_id); //商务自己的广告主id
				break;
			case '4':  //媒介所拥有的渠道
				if (empty($chan_id )) {
					$chan_id = D('Channel')->getUserChanid ($user_id);    //媒介自己的渠道id
				}
				$chan_arr = D('Channel')->getMjChannelsArray($user_id);  //没接自己的渠道id对应渠道名的数组
				$this->assign ('chan_arr',$chan_arr);
				break;

			default:

				break;
		}

//var_dump($userGroupId);die;
		if (empty($_GET)) {
			$time = date('Y-m-d',time());
			$start_date = $time.' 00:00 00';  //获取单天时间
			$end_date = $time.' 23:59:59';  //获取单天时间
			$_GET['start_date'] = $time.' 00:00:00';  //获取单天时间
			$_GET['end_date'] = $time.' 23:59:59';  //获取单天时间
		}

		$plogs =array();
		$clogs =array();
		$alogs =array();
		if ($search) {
			$app_name = trim($app_name);
			if (!empty($app_name)) {
				//查询广告应用对应的ID(app_id)
				$appName = D('App')->SelectAppByNameChanid($app_name,$chan_id);

				foreach ($appName as $k => $v) {
					$appid[] = $v['app_id'];
				}

				if (empty($appid)) {

				}else{
					//商务查找数据
					if ($userGroupId == '3') {
						if (empty($provider_id)) {

						}else{
							foreach ($appid as $k => $v) {
								//查询广告主激活回调日志
								$plogss = D('ProviderLog')->getSuccessProviderCallBackForSw($v, $provider_id,$start_date,$end_date);

								//查询渠道商点击上报日志
								$clogss = D('ChannelLog')->getSuccessChannelClickForSw($v, $provider_id,$start_date,$end_date);

								//查询渠道激活上报日志
								$alogss = D('ChannelActiveLog')->getSuccessChannelActiveClickForSw($v, $provider_id,$start_date,$end_date);


								if($clogss){
									foreach($clogss  as $value ){
										if ($value['ccount']>0) {
											$clogs[]=$value;
										}
									}
								}
								if($plogss){
									foreach($plogss  as $value ){
										if ($value['pcount']>0) {
											$plogs[]=$value;
										}
									}
								}
								if($alogss){
									foreach($alogss  as $value ){
										if ($value['acount']>0) {
											$alogs[]=$value;
										}
									}
								}
							}
						}
					}else{
						if (is_array($chan_id) && empty($chan_id)) {
							# code...
						}else{

							//出了商务 其他角色走的
							foreach ($appid as $k => $v) {
								//查询广告主激活回调日志
								$plogss = D('ProviderLog')->getSuccessProviderActiveByTime($v, $chan_id,$start_date,$end_date);

								//var_dump($plogss);
								//查询渠道商点击上报日志
								$clogss =D('ChannelLog')->getSuccessChannelClickByTime($v, $chan_id,$start_date,$end_date);

								//var_dump($clogss);
								//查询渠道激活上报日志
								$alogss = D('ChannelActiveLog')->getSuccessChannelActiveClickByTime($v, $chan_id,$start_date,$end_date);

								// var_dump($alogss);
								if($clogss){
									foreach($clogss  as $value ){
										if ($value['ccount']>0) {
											$clogs[]=$value;
										}
									}
								}
								if($plogss){
									foreach($plogss  as $value ){
										if ($value['pcount']>0) {
											$plogs[]=$value;
										}
									}
								}
								if($alogss){
									foreach($alogss  as $value ){
										if ($value['acount']>0) {
											$alogs[]=$value;
										}
									}
								}
							}
						}
					}

				}
				// $ccount=count($clogs);

			}else{

				switch ($userGroupId) {
					case '3':  //商务自己的
						if (empty($provider_id)) {

						}else{
							//查询广告主激活回调日志
							$plogs = D('ProviderLog')->getSuccessProviderCallBackForSw($app_id, $provider_id,$start_date,$end_date);

							//查询渠道商点击上报日志
							$clogs =D('ChannelLog')->getSuccessChannelClickForSw($app_id, $provider_id,$start_date,$end_date);

							//查询渠道激活上报日志
							$alogs  =D('ChannelActiveLog')->getSuccessChannelActiveClickForSw($app_id, $provider_id,$start_date,$end_date);

							// $ccount = count($clogs);
						}
						break;

					default:
						if (is_array($chan_id) && empty($chan_id)) {
							# code...
						}else{

							$clogs = D('ChannelLog')->getSuccessChannelClickByTime($app_id, $chan_id,$start_date,$end_date);
							 //var_dump($clogs);die;

							$plogs =D('ProviderLog')->getSuccessProviderActiveByTime($app_id, $chan_id,$start_date,$end_date);
							//var_dump($plogs);

							$alogs = D('ChannelActiveLog')->getSuccessChannelActiveClickByTime($app_id, $chan_id,$start_date,$end_date);

							// $ccount = count($clogs);
						}
						break;
				}
			}
		}else{
			//查询当天的记录
			switch ($userGroupId) {
				case '3':      //商务数据
					if (empty($provider_id)) {

					}else{

						$clogs = D('ChannelLog')->getSuccessChannelClickByProviderId($app_id,$provider_id,$start_date,$end_date);

						$plogs =D('ProviderLog')->getSuccessProviderActiveByProviderId($app_id,$provider_id,$start_date,$end_date);

						$alogs = D('ChannelActiveLog')->getSuccessChannelActiveClickByProviderId($app_id,$provider_id,$start_date,$end_date);
					}

					// $ccount = count($clogs);

					break;

				case '4':      //媒介数据

					if (empty($chan_id)) {
						# code...
					}else{
						$clogs = D('ChannelLog')->getSuccessChannelClickByChanId($app_id,$chan_id,$start_date,$end_date);

						$plogs = D('ProviderLog')->getSuccessProviderActiveByChanId($app_id,$chan_id,$start_date,$end_date);

						$alogs = D('ChannelActiveLog')->getSuccessChannelActiveClickByChanId($app_id,$chan_id,$start_date,$end_date);

						// $ccount = count($clogs);
					}

					break;

				default:
					$clogs = D('ChannelLog')->getSuccessChannelClickByTime($app_id, $chan_id,$start_date,$end_date);

					$plogs = D('ProviderLog')->getSuccessProviderActiveByTime($app_id, $chan_id,$start_date,$end_date);

					$alogs = D('ChannelActiveLog')->getSuccessChannelActiveClickByTime($app_id, $chan_id,$start_date,$end_date);
//var_dump($alogs);die;
					// $ccount = count($clogs);


					break;
			}
		}

		$arr = array();
		if (empty($clogs)) {
			$clogs = $alogs;
		}else{
			switch ($userGroupId) {
				case '3':  //商务数据
					foreach ($clogs as $key => $val) {
						if (!empty($plogs)) {
							foreach ($plogs as $k => $v) {
								if ($val['app_id'] == $v['app_id']) {
									$clogs[$key]['pcount'] = $v['pcount'];
								}
							}
						}
					}

					$clength = count($clogs);
					$alength = count($alogs);
					if (!empty($alogs)) {
						$k = 0;
						for ($i=0; $i < $clength ; $i++) {
							if ($k == $alength) {
								$arr[] = $clogs[$i];
							}else{
								for ($j=$k; $j < $alength; $j++) {

									if ($clogs[$i]['app_id'] > $alogs[$j]['app_id']) {
										$arr[] = $clogs[$i];
										break;
									}
									if ($clogs[$i]['app_id'] == $alogs[$j]['app_id']) {
										$clogs[$i]['acount'] = $alogs[$j]['acount'];
										$arr[] = $clogs[$i];
										$k = $j+1;
										break;
									}
									if ($clogs[$i]['app_id'] < $alogs[$j]['app_id']) {
										$arr[] = $alogs[$j];
										$k = $j+1;
									}
								}
							}
						}
					}
					break;

				default:

					foreach ($clogs as $key => $val) {
						if (!empty($plogs)) {
							foreach ($plogs as $k => $v) {
								if ($val['app_id'] == $v['app_id'] && $val['chan_id'] == $v['chan_id']) {
									$clogs[$key]['pcount'] = $v['pcount'];
								}
							}
						}
					}

					$clength = count($clogs);
					$alength = count($alogs);
					if (!empty($alogs)) {
						$k = 0;
						for ($i=0; $i < $clength ; $i++) {
							if ($k == $alength) {
								$arr[] = $clogs[$i];
							}else{
								for ($j=$k; $j < $alength; $j++) {

									if ($clogs[$i]['app_id'] > $alogs[$j]['app_id']) {
										$arr[] = $clogs[$i];
										break;
									}
									if ($clogs[$i]['app_id'] == $alogs[$j]['app_id']) {
										if ($clogs[$i]['chan_id'] == $alogs[$j]['chan_id']) {
											$clogs[$i]['acount'] = $alogs[$j]['acount'];
											$arr[] = $clogs[$i];
											$k = $j+1;
											break;
										}else{
	                                        if ($clogs[$i+1]['app_id'] == $alogs[$j]['app_id']) {
	                                            $arr[] = $clogs[$i];
	                                        }else{
	                                            $arr[] = $clogs[$i];
	                                            $arr[] = $alogs[$j];
	                                            $k = $j+1;
	                                        }
	                                        break;
										}
									}
									if ($clogs[$i]['app_id'] < $alogs[$j]['app_id']) {
										$arr[] = $alogs[$j];
										$k = $j+1;
									}
								}
							}
						}
					}
					break;
			}

		}
		//var_dump($arr);die;
		if (!empty($arr)) {
			$clogs = $arr;
		}
		$row_count = count($clogs);
		$page_size = PAGE_SIZE;
		$page_no=$page_no<1?1:$page_no; //页码
		$total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size); //总页数
		$total_page=$total_page<1?1:$total_page;
		$page_no=$page_no>($total_page)?($total_page):$page_no;
		$start = ($page_no - 1) * $page_size;
// 显示分页栏
		if (is_array($chan_id)) {
			$chan_id = '';
		}
		$page_html=Pagination::showPager("providerReportForm.php?app_name=$app_name&chan_id=$chan_id&start_date=$start_date&end_date=$end_date&search=1",$page_no,PAGE_SIZE,$row_count);

		//实现每页25行
		if( ($start + $page_size) < $row_count){
			for ($i=$start; $i <$start + $page_size ; $i++) {
				$clogsa[]=$clogs[$i];
			}
		}else{
			for ($i=$start; $i <$row_count ; $i++) {
				$clogsa[]=$clogs[$i];
			}
		}

		$apps = D('App')->getChannelAppsArray();
		$channels =D('Channel')->getChannelsSelectedArray();
		$providers = D('Provider')->getProvidersArray();
		$appAdtypeId = D('App')->getAdtypeIdArray();
		$adtypeId = D('Adtype')->getAdTypesArray();
		$this->assign ( 'page_no', $page_no );
		$this->assign ( 'page_size', PAGE_SIZE );
		$this->assign ( 'row_count', $row_count );
		$this->assign ( 'page_html', $page_html );
		$this->assign ( '_GET', $_GET );
		$this->assign ('providers',$providers);
		$this->assign ('apps',$apps);
		$this->assign ('channels',$channels);
		$this->assign ('plogs',$plogs);
		$this->assign('clogsa', $clogsa);
		$this->assign ('alogs',$alogs);
		$this->assign ('appAdtypeId',$appAdtypeId );
		$this->assign ('adtypeId',$adtypeId );
		$this->assign ('userGroupId',$userGroupId );

		$this->display ();
	}
}
?>
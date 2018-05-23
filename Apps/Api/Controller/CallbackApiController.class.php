<?php
namespace Api\Controller;
use Think\Controller;
class CallbackApiController  extends Controller{
	public function index(){
		$idfa = $mac = $appid = $chanid = $ip = $plog_id = $callback_time = $openudid = $missionId=   $body = $kid = '';
		extract ( $_GET, EXTR_IF_EXISTS );
		extract ( $_POST, EXTR_IF_EXISTS );
		//判断idfa和appid是否存在
		if (empty($appid)){
		    echo json_encode(array("code"=>501,"message"=>"缺少参数"),JSON_UNESCAPED_UNICODE);
		    return;
		}
		if($appid == '1758'){
		    $obj = json_decode($body);
		    $idfa = $obj->openId;
		    $chanid = ChannelLog::getChanId($appid,$idfa);	                  
		}
		if($appid == '1907'){
		    $chanid = ChannelLog::getChanId($appid,$idfa);
		}
		if (IS_POST || IS_GET) {
		    $callback_time = date('Y-m-d H:i:s',time());
		    //通过appid 查询任务类型；
		    $AdTypeId= D('App')->getAdTypeId($appid);
		    //provide_log 基本需要通过adsid和idfa来锁定唯一的provider_log id
		    $exist_id= D('ProviderLog')->getExist_idByid($appid,$idfa);
		    if(!count($exist_id)) {
		    	// 目前有个问题是 加入 adsid为相同时，则又要考虑到如何甄选的问题
		        $exist=D('App')->getAppidByAdsid3($appid);
		    	//如果没有重复的记录
		        $plog_data = array ('app_id'=>$appid,'adsid'=>$exist[0]['adsid'],'chan_id'=>$chanid,'idfa'=>$idfa,'mac'=>$mac,'openudid'=>$openudid,
		             'ip'=>$ip,'time'=>$callback_time,'provider_id'=>$exist[0]['provider_id']);
		        $pid=D('ProviderLog')->addProviderLog($plog_data);
		        $own_result;
		        $info_explain;
		        //对自媒体产品进行处理
		        if($chanid=='-1'){
                    $plog_data = array ('idfa'=>$idfa,'mac'=>$mac,'app_id'=>$appid,'adsid'=>$exist[0]['adsid'],'ip'=>$ip,'time'=>$callback_time,
                         'chan_id'=>$chanid,'openudid'=>$openudid,'provider_id'=>$exist[0]['provider_id'] );
                    $id=D('ChannelCallbackLog')->addChannelCallbackrLog($plog_data);
                    if($AdTypeId['adtype_id']=='1'){
                        $memberid= D('Member')->getMemId($idfa);
                        if($id){
                            $finishStatus= D('MemMis')->updatefinishStatus($memberid,$missionId);		                         
                            if($finishStatus){
                                UpdateMemberdata($memberid,$missionId);
                            }
							//chanid为-1时 总剩减一 用户余减一
							$map = array();
							$map['mission_id'] = $memberid;
							D('mission')->where($map)->setDec('smount_re');//总剩余量减一
							D('mission')->where($map)->setDec('re_num');//用户剩余量减一
                        }
                    }

                    $info_explain = '自媒体产品,不回调渠道';
		        }
		        //按回调比例回调api
				if(isDo($exist[0]['cutoff'])) {
				 	//渠道强制要求激活callback 功能
		            $callback_url=D('ChannelLog')->getCallback_urlBycallback1($appid,$idfa);
					dump($callback_url[0]['callback']);
		           	$map = array();
	            	$map['app_id'] = $appid;
		            $missionInfo = D('mission')->where($map)->find();//任务信息
		            if ($missionInfo['cmount_re'] > '0') { //剩余量大于0  给渠道回调          	
						if($callback_url[0]['callback']) {
		                    //初始化 请求获取返回结果 
		                    $ch = curl_init();
		                    curl_setopt($ch, CURLOPT_URL, $callback_url[0]['callback']);
		                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		                    curl_setopt($ch, CURLOPT_HEADER, 0);
		                    //执行
		                    $re = curl_exec($ch);
		                    $curl_errno = curl_errno($ch);  
	                        $curl_error = curl_error($ch);     
	                        curl_close($ch);
	                        if($curl_errno){ 
		                        $file  = 'C:/wamp/www/curl_log.txt';//如果文件不存在，将会创建一个
		                        $content = '-------------------------------------------------------'."\r\n";
		                        $content .= '时间  '.$callback_time."\r\n";
		                        $content .= "错误码为  ".$curl_errno."\r\n";
		                        $content .= "错误内容为  ".$curl_error."\r\n";
		                        $content .= "------------------------------------------------------"."\r\n";
		                        file_put_contents($file, $content,FILE_APPEND);
		                    }
		                    $plog_data = array ('idfa'=>$idfa,'mac'=>$mac,'app_id'=>$appid,'adsid'=>$exist[0]['adsid'],'ip'=>$ip,'time'=>$callback_time,
		                         'chan_id'=>$chanid,'openudid'=>$openudid,'provider_id'=>$exist[0]['provider_id'],'chan_result'=>$re);
		                    $id=D('ChannelCallbackLog')->addChannelCallbackrLog($plog_data);
							if ($id) {
								//总量减一  渠道减一
								$map = array();
								$map['mission_id'] = $memberid;
								D('mission')->where($map)->setDec('smount_re');//总剩余量减一
								D('mission')->where($map)->setDec('cmount_re');//渠道剩余量减一
							}
		                    $info_explain = '渠道产品,有渠道回调';
			                if($body){
			                    $rs = json_encode(array("isSuccess"=>'Y',"failReason"=>''));
			                    echo $rs;
			                    $own_result = json_encode(array("message"=>"success","success"=>true));
			                }else{
			                    $own_result = json_encode(array("message"=>"success","success"=>true));
	            				echo json_encode(array("code"=>200,"message"=>"成功"),JSON_UNESCAPED_UNICODE);
			                }
							//更新字段
		                    D('ProviderLog')->update_state($pid,$own_result,$info_explain);					
						}else{
							$info_explain = '没有第三方回调地址';
						 	if($body){
			                    $rs = json_encode(array("isSuccess"=>'Y',"failReason"=>''));
			                    echo $rs;
			                    $own_result = json_encode(array("message"=>"success","success"=>true));
			                }else{
			                    $own_result = json_encode(array("message"=>"success","success"=>true));
	            				echo json_encode(array("code"=>200,"message"=>"成功"),JSON_UNESCAPED_UNICODE);
			                }
						    D('ProviderLog')->update_state($pid,$own_result,$info_explain);	
						}
		            }else{
		            	$info_explain = '超出的量，没有给渠道回调';
					 	if($body){
			                $rs = json_encode(array("isSuccess"=>'Y',"failReason"=>''));
			                echo $rs;
			                $own_result = json_encode(array("message"=>"success","success"=>true));
			            }else{
			                $own_result = json_encode(array("message"=>"success","success"=>true));
	            			echo json_encode(array("code"=>200,"message"=>"成功"),JSON_UNESCAPED_UNICODE);
			            }
					 	D('ProviderLog')->update_state($pid,$own_result,$info_explain);
		            }
				}else{
                    $info_explain = '比例之外,没有给渠道回调';
				 	if($body){
		                $rs = json_encode(array("isSuccess"=>'Y',"failReason"=>''));
		                echo $rs;
		                $own_result = json_encode(array("message"=>"success","success"=>true));
		            }else{
		                $own_result = json_encode(array("message"=>"success","success"=>true));
	            		echo json_encode(array("code"=>200,"message"=>"成功"),JSON_UNESCAPED_UNICODE);
		            }
				 	D('ProviderLog')->update_state($pid,$own_result,$info_explain);	
				}
		    }else{
				//如果记录重复的话
				if($body){
		            $rs = json_encode(array("isSuccess"=>'Y',"failReason"=>''));
		            echo $rs;
		        }else{
		            echo json_encode(array("code"=>201,"message"=>"失败"),JSON_UNESCAPED_UNICODE);
		        }
		    }
        }
	}

}
?>
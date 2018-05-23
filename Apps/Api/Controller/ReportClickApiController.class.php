<?php
namespace Api\Controller;
use Think\Controller;
class ReportClickApiController extends Controller{
	public function index(){
		$idfa = $mac = $appid =$chanid= $ip = $plog_id = $callback_time = $callback = $openudid = $os =$devicemodel= $kid = '';
		extract ( $_GET, EXTR_IF_EXISTS );
		if($appid == '2044'){
			$timestamp = time();
			$callback_time = date('Y-m-d H:i:s',$timestamp);
			$ad=D('App')->getAdsidByAppid($appid);
			echo $own_result = json_encode(array("message"=>"success","success"=>true));
			$channel_data = array ('idfa'=>$idfa,'mac'=>$mac,'app_id'=>$appid,'adsid'=>$ad[0]['adsid'],'ip'=>$ip,'time'=>$callback_time,'chan_id'=>$chanid,
	            'callback'=>$callback,'openudid'=>$openudid,'provider_id'=>$ad[0]['provider_id'],'result'=>html_entity_decode($re),'own_result'=>$own_result,'os'=>$os,'devicemodel'=>$devicemodel,'kid'=>$kid);
	        D('ChannelLog')->addChannellog($channel_data);	
			exit;
	    }
		//判断idfa和appid是否存在
		if (empty($appid) || empty($idfa) || empty($ip)){
		    echo json_encode(array("code"=>501,"message"=>"缺少参数"),JSON_UNESCAPED_UNICODE);
		    return;
		}
		$timestamp = time();
	    $callback_time = date('Y-m-d H:i:s',$timestamp);
		$ad=D('App')->getAdsidByAppid($appid);
		//查询任务信息
		$renum = D('mission')->field('mission_id,cmount_re,kwd_qx,status')
		->where(array('app_id'=> $appid))
		->find();

		//总量如果为0  则不上报广告主 给渠道返回提示信息  或者当任务状态为3（任务下线）给取到返回信息
		if ($renum['cmount_re'] <= 0 || $renum['status'] == 3) {
			echo json_encode(array("code"=>203,"message"=>"任务已下线"),JSON_UNESCAPED_UNICODE);
			return;
		}
		//关键词的判断
		if ($renum['kwd_qx']) {
			if (empty($kid)) {  //判断渠道是否传关键词
				echo json_encode(array("code"=>501,"message"=>"缺少参数"),JSON_UNESCAPED_UNICODE);
				return;	
			}
	        //查询此任务当天添加的关键词信息
	        $kkwds=D('Kwd_mount')->getKwd($renum['mission_id']);
	        $kwdArr = array();
	        //获取当天添加的关键词
	        foreach ($kkwds as $k => $v) {
	            $kwdArr[] = $v['kkwd'];
	        }	
			if (!in_array($kid, $kwdArr)) { //关键词不符合要求
				echo json_encode(array("code"=>401,"message"=>"参数错误"),JSON_UNESCAPED_UNICODE);
				return;
			}
	        $mount_re = '';
	        $mount_re_id = '1';
	        //查询关键词剩余量
	        foreach ($kkwds as $k => $v) {
	            if ($v['kkwd'] == $kid) {
	                $mount_re = $v['mount_re'];
	                $mount_re_id = $v['id'];
	            }
	        }
			if ($mount_re <= '0') {
				echo json_encode(array("code"=>203,"message"=>"任务已下线"),JSON_UNESCAPED_UNICODE);
				return;
			}
		}
	    $config=D('ProviderConfig')->getProviderConfigByApp_id($appid);
	    $configUrl = $config[0]['config_content'];
	    $configIf = $config[0]['config_if'];   
	    $cif = explode('||',$configIf);
		$tokUrl = explode(';', $configUrl);
		if ($tokUrl[1] != '') {
			$urltoken = $tokUrl[1];
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $urltoken);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$gettoken = curl_exec($ch);
			curl_close($ch);
			$jsontoken = json_decode($gettoken,true);
			$diantoken = $jsontoken['Data']['RepDataToken']['0']['token'];
		}
		$configUrl = $tokUrl[0];
	    $ht = explode(':',$configUrl);      
	    $dian_callback = ADMIN_URL."/api/callbackApi?appid=".$appid."&chanid=".$chanid."&idfa=".$idfa."&mac=".$mac."&openudid=".$openudid."&ip=".$ip;
		dump($dian_callback );
	    //解析参数
	    $a=explode('?',$configUrl);
	    $url_f=$a[0];
	    $query=$a[1];
	    parse_str($query,$arr);
	    foreach($arr as $key => $value) {
	        if($value == "{dian_idfa}") {
	            $arr[$key] = $idfa;
	        }
	        if($value == "{dian_mac}") {
	            $arr[$key] = $mac;
	        }
	        if($value == "{dian_appid}") {
	            $arr[$key] = $appid;
	        }
	        if($value == "{dian_ip}") {
	            $arr[$key] = $ip;
	        }
	        if($value == "{dian_chanid}") {
	            $arr[$key] = $chanid;
	        }
	        if($value == "{dian_openudid}") {
	            $arr[$key] = $openudid;
	        }
	        if($value == "{dian_os}") {
	            $arr[$key] = $os;
	        }
	        if($value == "{dian_callback}") {
	            $arr[$key] = $dian_callback;
	        }
			if($value == "{dian_keyword}") {
				$arr[$key] = $keyword;
			}
			if($value == "{dian_token}") {
				$arr[$key] = $diantoken;
			}
			if($value == "{dian_devicemodel}") {
				$arr[$key] = $devicemodel;
			}
			if($value == "{dian_kid}") {
	        	$arr[$key] = $kid;
	    	}
	    	if($value == "{dian_timestamp}") {
	        	$arr[$key] = $timestamp;
	    	}
	    }
	    if($appid == "1245" || $appid == "1246" || $appid == "1247" ) {
	        $salt = "26376CC3F40545C9853CD8C80F3EC9CD";
	        $str_sa = "appid=1030675668&callbackaddress=http://101.200.129.217/api/provider_callback_api.php?appid=".$appid."&chanid=".$chanid."&idfa=".$idfa."&mac=".$mac."&openudid=".$openudid."&ip=".$ip."&idfa=".$idfa."&source=点点时代&version=3.0.1".$salt;
	        $sign = md5($str_sa);
	        $arr['sign']=$sign;
	    }
	    if($appid == "1570"||$appid =="1579" || $appid == "1581" || $appid == "1583" || $appid=="1646") {
	        $data1= array('idfa'=>$idfa,'source'=>"diandian");
	        ksort($data1);
	        $signStr="";
	        foreach($data1 as $key=>$val){
	            $signStr .= "$key=$val";
	        }
	        $arr['keyt']=md5($signStr."PuPaZVBhh2W9");
	    }
		if($appid == "1641"||$app_id=="1642") {
			$baidu_appid= "588287777";
			$baidu_source="d3ke";
			$baidu_sa=$baidu_appid.",".$idfa.",".$baidu_source.",wise_baidu_video_partner";
			$baidu_sign = md5($baidu_sa);
			$arr['sign']=$baidu_sign;
		}
		if($appid == "1643" || $appid == "1648" || $appid=="1673" || $appid=="1835") {
			$chuangli_adid= "8103";
			$chuangli_channel="72005";
			$chuangli_key="e53128c002402240468632b4f9b353c7";
			$chuangli_sa=$chuangli_adid."|".$chuangli_channel."|".$chuangli_key;
			$chuangli_sign = md5($chuangli_sa);
			$arr['sign']=$chuangli_sign;
		}
		//6596
		if($appid=="1878") {
            $chuangli_adid= "6596";
            $chuangli_channel="72005";
            $chuangli_key="e53128c002402240468632b4f9b353c7";
            $chuangli_sa=$chuangli_adid."|".$chuangli_channel."|".$chuangli_key;
            $chuangli_sign = md5($chuangli_sa);
            $arr['sign']=$chuangli_sign;
            }
		if($appid=="1891") {
            $chuangli_adid= "6706";
            $chuangli_channel="72005";
            $chuangli_key="e53128c002402240468632b4f9b353c7";
            $chuangli_sa=$chuangli_adid."|".$chuangli_channel."|".$chuangli_key;
            $chuangli_sign = md5($chuangli_sa);
             $arr['sign']=$chuangli_sign;
        }

		if($appid == "1644" || $appid == "1649") {
			$chuangli_adid= "6596";
			$chuangli_channel="72005";
			$chuangli_key="e53128c002402240468632b4f9b353c7";
			$chuangli_sa=$chuangli_adid."|".$chuangli_channel."|".$chuangli_key;
			$chuangli_sign = md5($chuangli_sa);
			$arr['sign']=$chuangli_sign;
		}

		if($appid=="1921") {
            $chuangli_adid= "2031";
            $chuangli_channel="72005";
            $chuangli_key="e53128c002402240468632b4f9b353c7";
            $chuangli_sa=$chuangli_adid."|".$chuangli_channel."|".$chuangli_key;
            $chuangli_sign = md5($chuangli_sa);
            $arr['sign']=$chuangli_sign;
        }	

		if ($appid == "1727") {
			//获取13位毫秒数
			list($s1, $s2) = explode(' ', microtime());
			$signTime =  (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
			$appkey = 'IDFA_ed0f83aeda6e77511a6665299945f6ea';
			$appsecret = '567860bc450444b470197f8a04a81916';
			$instr = $appkey.$signTime.$appsecret;
			$bdxd_sign = md5($instr);
			$arr['signTime'] = $signTime;
			$arr['sign']=$bdxd_sign;
		}
		if ($appid == '1758') {
	        $appsecret = '3ca5aa19c88cbd4bf0386e252dccc0f7';             
	        $str=$arr['sId'].@$arr['mac'].@$arr['udId'].@$arr['openId'].@$arr['mobileSystem'].@$arr['source'].@$arr['appId'];
	        $arr['sign'] = md5($str.$appsecret);
	        $http_buildss = '{';
	        foreach ($arr as $k => $v) {
	            $http_buildss .= '"'.$k.'"'.":".'"'.$v.'",';
	        }
	        $http_buildss = rtrim($http_buildss,',');
	        $http_buildss = $http_buildss.'}';
	    }
	    if($appid == '1907'){
	        $http_buildss = '{';
	        foreach ($arr as $k => $v) {
	            $http_buildss .= '"'.$k.'"'.":".'"'.$v.'",';
	        }
	        $http_buildss = rtrim($http_buildss,',');
	        $http_buildss = $http_buildss.'}';
	    }
	    if($appid == '1950' || $appid == '1952' || $appid == '1967' || $appid == '1969' || $appid == '2026' || $appid == '2038'){   //爱普优邦
	        $key = '4d3ef46ecd5710c2a31adab4bac15f19';
	        $arr['sign'] = md5($timestamp.$key);
	    }

	    if($appid == "2025") {
            $chuangli_adid= "5837";
            $chuangli_channel="72005";
            $chuangli_key="e53128c002402240468632b4f9b353c7";
            $chuangli_sa=$chuangli_adid."|".$chuangli_channel."|".$chuangli_key;
            $chuangli_sign = md5($chuangli_sa);
            $arr['sign']=$chuangli_sign;
            //var_dump($chuangli_sa);
            //var_dump($chuangli_sign);
		}
		if($appid == "2035") {
            $chuangli_adid= "5375";
            $chuangli_channel="72005";
            $chuangli_key="e53128c002402240468632b4f9b353c7";
            $chuangli_sa=$chuangli_adid."|".$chuangli_channel."|".$chuangli_key;
            $chuangli_sign = md5($chuangli_sa);
            $arr['sign']=$chuangli_sign;
            //var_dump($chuangli_sa);
            //var_dump($chuangli_sign);
		}

	    $request = 'get';
	    if($ad[0]['provider_id'] == '93'){
	        $request = 'post';
	    }
	    if ($appid == '1758') {
	        $request_url = $url_f.'?body='.$http_buildss;
	    }else if($appid == '1907'){
	        $request_url = $url_f.'?param='.$http_buildss;
	    }else{
	        if($request == 'get'){
	            $request_url = $url_f.'?'.http_build_query($arr);
	        }else if($request == 'post'){
	            $request_url = $url_f; 
	        }     
	    }
	    ini_set('user_agent','Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.95 Safari/537.36 SE 2.X MetaSr1.0'); 
	    //请求 获取返回结果
	    $ch = curl_init();
	    //设置选项
	    if($ht[0] == 'https') {
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //不验证证书
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //不验证证书
	    }
	    curl_setopt($ch, CURLOPT_URL, $request_url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    if($request == 'post'){
	        curl_setopt($ch, CURLOPT_POST, 1);
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $arr);
	    }
		if ($configIf == 'game302') { //重定向走的
			curl_setopt($ch, CURLOPT_NOBODY, 1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		}
	    //执行
	    $re = curl_exec($ch);
		if ($configIf == 'game302') {
			//获取302重新定向后的真实地址
			$httpUrl = curl_getinfo($ch,CURLINFO_EFFECTIVE_URL);
		}
	    //在执行curl_exec后,获取curl请求后的响应状态码
	    $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE); 
	    $obj = json_decode($re);
	    //对返回结果进行判断
	    $own_result='';
	    if ($cif[1] != NULL) {   
	        if( $obj->success == $cif[1] || $obj->$cif[0] == $cif[1]){ 
	            $result = true;
	            $own_result = json_encode(array("message"=>"success","success"=>true));
	            echo json_encode(array("code"=>200,"message"=>"成功"),JSON_UNESCAPED_UNICODE);
	        } else {
	            $own_result = json_encode(array("message"=>"fail","success"=>false));
	            echo json_encode(array("code"=>201,"message"=>"失败"),JSON_UNESCAPED_UNICODE);
	        }
	    }else if($cif['0'] == 'game302' && $httpCode == '302'){
	        $result = true;
			$own_result = json_encode(array("message"=>"success","success"=>true));
			header("Location:".$httpUrl);
		}else if($cif[1] == NULL && $cif['0'] != NULL){
	        if( $re == $configIf || $obj->$idfa == $configIf || $httpCode == $configIf)
	        {
	            $result = true;
	            $own_result = json_encode(array("message"=>"success","success"=>true));
	            echo json_encode(array("code"=>200,"message"=>"成功"),JSON_UNESCAPED_UNICODE);
	        } else {
	            $own_result = json_encode(array("message"=>"fail","success"=>false));
	            echo json_encode(array("code"=>201,"message"=>"失败"),JSON_UNESCAPED_UNICODE);
	        }
	    }else{
	    	$own_result = "配置信息为空";
		}
	    //关闭curl
	    curl_close($ch);  
	    //添加数据到channel_log
		if ($configIf == 'game302') {
			$channel_data = array ('idfa'=>$idfa,'mac'=>$mac,'app_id'=>$appid,'adsid'=>$ad[0]['adsid'],'ip'=>$ip,'time'=>$callback_time,'chan_id'=>$chanid,
				'callback'=>$callback,'openudid'=>$openudid,'provider_id'=>$ad[0]['provider_id'],'result'=>$httpUrl,'own_result'=>$own_result);
		}else{
			$channel_data = array ('idfa'=>$idfa,'mac'=>$mac,'app_id'=>$appid,'adsid'=>$ad[0]['adsid'],'ip'=>$ip,'time'=>$callback_time,'chan_id'=>$chanid,
				'callback'=>$callback,'openudid'=>$openudid,'provider_id'=>$ad[0]['provider_id'],'result'=>html_entity_decode($re),'own_result'=>$own_result,'os'=>$os,'devicemodel'=>$devicemodel,'kid'=>$kid);
		}
	    D('ChannelLog')->addChannellog($channel_data);
	    //此任务属于点击任务
	    if ($ad[0]['adtype_id'] == '3' || $ad[0]['adtype_id'] == '6') {
	        //此任务成功  给渠道剩余量减一
	        if ($result) {
	            $map = array();
	            $map['mission_id'] = $renum['mission_id'];
	            D('mission')->where($map)->setDec('cmount_re');//渠道剩余量减一
	            D('mission')->where($map)->setDec('smount_re');//总剩余量减一
	            if ($renum['kwd_qx']) {  //此任务有关键词  则给此任务对应的关键词剩余量减一
	                $map = array();
	                $map['id'] = $mount_re_id;
	                D('Kwd_mount')->where($map)->setDec('mount_re');
	            }
	        }
	    }
	}
}


?>
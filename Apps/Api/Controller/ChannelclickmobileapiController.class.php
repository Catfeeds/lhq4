<?php
namespace Api\Controller;
use Think\Controller;
class ChannelclickmobileapiController extends Controller{
    public function Channelclickmobileapi(){
        $kwd = $os = $devicemodel = $member_id = $mission_id = $mac=$openudid='';
        $mission_id=I('get.msid');
        $member_id=I('get.mid');
        $kwd = I('get.kwd');
        $os = I('get.os');
        $ip = getip();
        $devicemodel = I('get.devicemodel');
        $appid= D('Mission')->getappId($mission_id);
        $kwdstatus = D('Mission')->getKwdqx($mission_id);
        //去任务类型
        $adtype_id = D('Mission')->getAdtype($mission_id);
        $idfa= D('Member')->getIdfa($member_id);
        $timestamp = time();
        $callback_time = date('Y-m-d H:i:s',$timestamp);
        //查询是否有重复
        $chanid='-1';
        $ad=D('App')->getAdsidByAppid($appid);
        if(!empty($idfa) && !empty($appid)) {
            //获取provider click notification url
            $config=D('ProviderConfig')->getProviderConfigByApp_id($appid);
            $configUrl = $config[0]['config_content'];
            if ($configUrl == 'lhq') {
                $ip=getIp();
                $channel_data = array ('idfa'=>$idfa,'mac'=>'02:00:00:00:00:00','app_id'=>$appid,'adsid'=>$ad[0]['adsid'],'ip'=>$ip,'time'=>$callback_time,'chan_id'=>$chanid,
                    'provider_id'=>$ad[0]['provider_id'],'os'=>$os,'devicemodel'=>$devicemodel);
                ChannelLog::addChannellog($channel_data);
                echo json_encode(array("code"=>200,"message"=>"成功",JSON_UNESCAPED_UNICODE));
                exit;
            }

            //关键词的判断
            if ($kwdstatus) {
                //是否传关键词
                if (empty($kwd)) {
                    echo json_encode(array("code"=>501,"message"=>"缺少关键词"),JSON_UNESCAPED_UNICODE);
                    exit;
                }
                //查询此任务当天添加的关键词信息
                $kkwds=D('Kwd_mount')->getKwd($mission_id);
                $mount_re = '';
                $mount_re_id = '1';
                //查询关键词剩余量
                foreach ($kkwds as $k => $v) {
                    if ($v['kkwd'] == $kwd) {
                        $mount_re = $v['mount_re'];
                        $lmount_re = $v['lmount_re'];
                        $mount_re_id = $v['id'];
                    }
                }
                if ($mount_re <= '0' && $lmount_re <= '0') {
                    echo json_encode(array("code"=>203,"message"=>"任务已下线"),JSON_UNESCAPED_UNICODE);
                    return;
                }
            }
            $configIf = $config[0]['config_if'];
            $cif = explode('||',$configIf);
            $ht = explode(':',$configUrl);//判断地址是否是https访问
            $dian_callback = ADMIN_URL."/api/CallbackApi/index?appid=".$appid."&chanid=".$chanid."&idfa=".$idfa."&mac=".$mac."&openudid=".$openudid."&ip=".$ip."&missionId=".$mission_id;
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
                    if(empty($mac)){
                        $mac='02:00:00:00:00:00';
                    }
                    $arr[$key] = $mac;
                }
                if($value == "{dian_appid}") {
                    $arr[$key] = $appid;
                }
                if($value == "{dian_ip}") {
                    if(empty($ip)){
                        $ip=getIp();
                    }
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
                    $arr[$key] = $kwd;
                }
                if($value == "{dian_timestamp}") {
                    $arr[$key] = $timestamp;
                }
            }
            if($appid == "1245" || $appid == "1246" || $appid == "1247" ) {
                $salt = "26376CC3F40545C9853CD8C80F3EC9CD";
                $str_sa = "appid=1030675668&callbackaddress=http://101.200.129.217/api/provider_callback_api.php?appid=".$appid."&chanid=".$chanid."&idfa=".$idfa."&mac=".$mac."&ip=".$ip."&idfa=".$idfa."&source=点点时代&version=3.0.1".$salt;
                $sign = md5($str_sa);
                $arr['sign']=$sign;
            }

            if($appid == "1570"||$appid =="1579" || $appid == "1581"|| $appid == "1583" || $appid=="1646") {
                $data1 = array('idfa'=>$idfa,'source'=>"diandian");
                ksort($data1);
                $signStr="";
                foreach($data1 as $key=>$val){
                    $signStr .= "$key=$val";
                }
                $arr['keyt']=md5($signStr."PuPaZVBhh2W9");
            }
            if($appid == "1641"||$appid=="1642") {
                $baidu_appid= "588287777";
                $baidu_source="d3ke";
                $baidu_sa=$baidu_appid.",".$idfa.",".$baidu_source.",wise_baidu_video_partner";
                $baidu_sign = md5($baidu_sa);
                $arr['sign']=$baidu_sign;
            }
            if($appid == "1643" || $appid == "1648" || $appid=="1673") {
                $chuangli_adid= "8103";
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
            
            if($appid == '1950'){   //爱普优邦
                $key = '4d3ef46ecd5710c2a31adab4bac15f19';
                $arr['sign'] = md5($timestamp.$key);
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
            //执行
            $re = curl_exec($ch);
            //在执行curl_exec后,获取curl请求后的响应状态码
            $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
            $obj = json_decode($re);

            //对返回结果进行判断
            if ($cif[1] != NULL) {
                if( $obj->success == $cif[1] || $obj->$cif[0] == $cif[1] || $httpCode == $configIf)
                {
                    $result = true;
                    $own_result = json_encode(array("message"=>"success","success"=>true));
                    echo json_encode(array("code"=>200,"message"=>"成功"),JSON_UNESCAPED_UNICODE);

                } else {
                    $own_result = json_encode(array("message"=>"fail","success"=>false));
                        echo json_encode(array("code"=>201,"message"=>"失败"),JSON_UNESCAPED_UNICODE);
                }
            }else if($cif[1] == NULL && $cif['0'] != NULL){
                if( $re == $configIf  || $obj->$idfa == $configIf )
                {
                    $result = true;
                    $own_result = json_encode(array("message"=>"success","success"=>true));
                    if ($adtype_id == '2' || $adtype_id == '1') {
                        echo '0';
                    } else {
                        echo json_encode(array("code"=>200,"message"=>"成功"),JSON_UNESCAPED_UNICODE);
                    }
                } else {
                    $own_result = json_encode(array("message"=>"fail","success"=>false));
                    if ($adtype_id == '2' || $adtype_id == '1') {
                        echo '1';
                    } else {
                        echo json_encode(array("code"=>201,"message"=>"失败"),JSON_UNESCAPED_UNICODE);
                    }
                }
            }
            //关闭curl
            curl_close($ch);        
            //添加数据到channel log
            $channel_data = array ('idfa'=>$idfa,'mac'=>$mac,'app_id'=>$appid,'adsid'=>$ad[0]['adsid'],'ip'=>$ip,'time'=>$callback_time,'chan_id'=>$chanid,
                'callback'=>$dian_callback,'provider_id'=>$ad[0]['provider_id'],'result'=>$re,'own_result'=>$own_result,'kid'=>$kwd);
            D('ChannelLog')->addChannellog($channel_data);
            //此任务属于点击任务
            if ($ad[0]['adtype_id'] == '3' || $ad[0]['adtype_id'] == '6') {
                //此任务成功  给渠道剩余量减一
                if ($result) {
                    $map = array();
                    $map['mission_id'] = $mission_id;
                    D('mission')->where($map)->setDec('cmount_re');//渠道剩余量减一
                    D('mission')->where($map)->setDec('smount_re');//总剩余量减一
                    if ($kwdstatus) {  //此任务有关键词  则给此任务对应的关键词剩余量减一
                        $map = array();
                        $map['id'] = $mount_re_id;
                        D('Kwd_mount')->where($map)->setDec('mount_re');
                    }
                }
            }
        } else {
            if ($adtype_id == '2' || $adtype_id == '1') {
                echo '2';
            } else {
                echo json_encode(array("code"=>501,"message"=>"缺少参数"),JSON_UNESCAPED_UNICODE);
            }

            return;
        }
    }
}

?>

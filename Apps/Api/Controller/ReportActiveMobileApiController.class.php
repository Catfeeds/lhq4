<?php
namespace Api\Controller;
use Think\Controller;
class ReportActiveMobileApiController extends Controller{
	public function index(){
        $kwd = $os = $devicemodel = $member_id = $mission_id = $mac=$openudid=$idfa=$dian_callback='';
        $mission_id=I('get.msid');
        $member_id=I('get.mid');
        $kwd = I('get.kwd');
        $os = I('get.os');
        $mac = I('get.mac');
        $idfa = I('get.idfa');
        $ip = getip();
        $devicemodel = I('get.devicemodel');
        //取任务类型
        $adtype_id = D('Mission')->getAdtype($mission_id);
        $kwdstatus = D('Mission')->getKwdqx($mission_id);
        $appid= D('Mission')->getappId($mission_id);
       //$idfa= D('Member')->getIdfa($member_id);
        $timestamp = time();
        $callback_time = date('Y-m-d H:i:s',$timestamp);
		//判断idfa和appid是否存在
		if (empty($appid) || empty($idfa)){
		    echo json_encode(array("code"=>501,"message"=>"缺少参数"),JSON_UNESCAPED_UNICODE);
		    return;
		}
        $chanid='-1';
        $ad=D('App')->getAdsidByAppid($appid);
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
        //获取provider click notification url
        $config=D('ActiveConfig')->getActiveConfigByApp_id($appid);
        $configUrl = $config[0]['config_content'];
        $configIf = $config[0]['config_if'];   
        $cif = explode('||',$configIf);
        $ht = explode(':',$configUrl);      
        //$request_url = "http://t5.guomob.com/data/jfq_click_out.php?adsid=".$ad[0]['adsid']."&code=r7mdkb0g5jn8709&mac=".$mac."&idfa=".$idfa."&openudid=".$openudid;
        //分析参数的名称，并根据名称赋予对于的值。
        //另一个是PHP替换网址中query部分的某变量的值比如 ，我们要设$url中的key=321;
        //其实有几种情况：
        //$url='www.sina.com/a.php?key=330′;
        //或$url='www.sina.com/a.php;
        //或$url='www.sina.com/a.php?cat=2′;
        //等等。虽然情况很多，但PHP处理起来非常简单，如下：
        //复制代码 代码如下:
        /* 将URL中的某参数设为某值*/ //【这一段就挺好啊】

        $dian_callback = ADMIN_URL."/api/callbackApi?appid=".$appid."&chanid=".$chanid."&idfa=".$idfa."&mac=".$mac."&openudid=".$openudid."&ip=".$ip;
        //var_dump($dian_callback);
       // 当配置为空时
        if (!$config) {
            $re = '';
            $own_result = '配置信息为空';
            $channel_data = array ('idfa'=>$idfa,'mac'=>$mac,'app_id'=>$appid,'adsid'=>$ad[0]['adsid'],'ip'=>$ip,'time'=>$callback_time,'chan_id'=>$chanid,
                'callback'=>$dian_callback,'openudid'=>$openudid,'provider_id'=>$ad[0]['provider_id'],'result'=>$re,'own_result'=>$own_result,'kid'=>$kwd,'os'=>$os,'devicemodel'=>$devicemodel);
            $id = D('ChannelActiveLog')->addChannelActivelog($channel_data);
            if ($id) {
                $map = array();
                $map['mission_id'] = $mission_id;
                D('mission')->where($map)->setDec('cmount_re');//渠道剩余量减一
                D('mission')->where($map)->setDec('smount_re');//总剩余量减一
                if ($kwdstatus) {  //此任务有关键词  则给此任务对应的关键词剩余量减一
                    $map = array();
                    $map['id'] = $mount_re_id;
                    D('Kwd_mount')->where($map)->setDec('mount_re');
                }
                echo json_encode(array("code"=>200,"message"=>"成功"),JSON_UNESCAPED_UNICODE);
                exit;
            }
        }
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
            if($value == "{dian_devicemodel}") {
                $arr[$key] = $devicemodel;
            }
            if($value == "{dian_callback}") {
                $arr[$key] = $dian_callback;
            }
			if($value == "{dian_timestamp}") {
                $arr[$key] = $timestamp;
            }
            if($value == "{dian_kid}") {
                $arr[$key] = $kwd;
            }
        }
        if($appid == "1245" || $appid == "1246" || $appid == "1247" ) {
            $salt = "26376CC3F40545C9853CD8C80F3EC9CD";
            $str_sa = "appid=1030675668&callbackaddress=http://101.200.129.217/api/provider_callback_api.php?appid=".$appid."&chanid=".$chanid."&idfa=".$idfa."&mac=".$mac."&openudid=".$openudid."&ip=".$ip."&idfa=".$idfa."&source=点点时代&version=3.0.1".$salt;
            $sign = md5($str_sa);
            $arr['sign']=$sign;
        }
		if($appid == "1643" || $appid=="1648" || $appid=="1673") {
			$chuangli_adid= "8103";
			$chuangli_channel="72005";
			$chuangli_key="e53128c002402240468632b4f9b353c7";
			$chuangli_sa=$chuangli_adid."|".$chuangli_channel."|".$chuangli_key;
			$chuangli_sign = md5($chuangli_sa);
			$arr['sign']=$chuangli_sign;
		}
		if($appid == "1644" || $appid=="1649") {
			$chuangli_adid= "6596";
			$chuangli_channel="72005";
			$chuangli_key="e53128c002402240468632b4f9b353c7";
			$chuangli_sa=$chuangli_adid."|".$chuangli_channel."|".$chuangli_key;
			$chuangli_sign = md5($chuangli_sa);
			$arr['sign']=$chuangli_sign;
		}
		if($appid == '1950'){  //爱普优邦
            $key = '4d3ef46ecd5710c2a31adab4bac15f19';
            $arr['sign'] = md5($timestamp.$key);
        }
        $request = 'get';
        if($appid == '1924'){
            $request = 'post';
        }
        if($request == 'get'){
            $request_url = $url_f.'?'.http_build_query($arr);
        }else if($request == 'post'){
            $request_url = $url_f;
        } 
        //$request_url = $url_f.'?'.http_build_query($arr);
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
        //执行
        $re = curl_exec($ch);
        //在执行curl_exec后,获取curl请求后的响应状态码
        $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE); 
        $obj = json_decode($re);
        $own_result = '';
        //对返回结果进行判断
        if ($cif[1] != NULL) {   
            if( $obj->success == $cif[1] || $obj->$cif[0] == $cif[1] || $obj->$cif[0]->$idfa == $cif[1])
            {           
                $result = true;
                $own_result = json_encode(array("message"=>"success","success"=>true));
                echo json_encode(array("code"=>200,"message"=>"成功"),JSON_UNESCAPED_UNICODE);
            } else {
                $own_result = json_encode(array("message"=>"fail","success"=>false));
                echo json_encode(array("code"=>201,"message"=>"失败"),JSON_UNESCAPED_UNICODE);
            }
        }else if($cif[1] == NULL && $cif['0'] != NULL){
            if( $re == $configIf
                || $obj->$idfa == $configIf
                || $re == "{success:true,message:成功}"
                || $re == "{success:true}\n" 
                || $re == "{'message':'ok','success':'true'}"
                || $re == '{"code":0,"msg":"success"}'
                || $re == '{"status":"true","message":"success"}'
                || strstr("https://itunes.apple.com/cn/app/sui-xin-dai-yuan-xin-yong/id914686649?mt=8&channel_id=hj-ddsd_cpa_zjh",$re))
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
            echo json_encode(array("code"=>201,"message"=>"配置信息为空"),JSON_UNESCAPED_UNICODE);
        }
        //关闭curl
        curl_close($ch);
        //添加数据到channel log
        $channel_data = array ('idfa'=>$idfa,'mac'=>$mac,'app_id'=>$appid,'adsid'=>$ad[0]['adsid'],'ip'=>$ip,'time'=>$callback_time,'chan_id'=>$chanid,
        'callback'=>$dian_callback,'openudid'=>$openudid,'provider_id'=>$ad[0]['provider_id'],'result'=>$re,'own_result'=>$own_result,'kid'=>$kwd,'os'=>$os,'devicemodel'=>$devicemodel);
        D('ChannelActiveLog')->addChannelActivelog($channel_data);
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
	}
}

?>
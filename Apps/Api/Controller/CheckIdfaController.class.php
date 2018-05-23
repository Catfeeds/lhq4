<?php
namespace Api\Controller;
use Think\Controller;
class CheckIdfaController extends Controller{
    //排重接口
    public function index(){
        $idfa = $mac = $app = $ip = $plog_id = $callback_time = $appid = $chanid= $kid = '';
        extract ( $_GET, EXTR_IF_EXISTS );
        extract ( $_POST, EXTR_IF_EXISTS );

        if(empty($appid)){
           $appid = $app;
        }
        //判断idfa和appid是否存在
        if (empty($appid) || empty($idfa) || empty($ip)){
            echo json_encode(array("code"=>501,"message"=>"缺少参数"),JSON_UNESCAPED_UNICODE);
            return;
        }
        //查询任务信息
        $renum = D('mission')->field('mission_id,cmount_re,kwd_qx,status')
            ->where(array('app_id'=> $appid))
            ->find();
        //总量如果为0  则不上报广告主 给渠道返回提示信息  或者当任务状态为3（任务下线）给取到返回信息
        if ($renum['cmount_re'] <= 0 || $renum['status'] == 3) {
            echo json_encode(array("code"=>203,"message"=>"任务已下线"),JSON_UNESCAPED_UNICODE);
            return;
        }
        $post_data = array();
        $pcconfig = D('PcConfig')->getRepeat_urlByappid($appid);//通过appid获取排重配置信息
        $url = $pcconfig['0']['repeat_url'];
        $uu = explode(';', $url);
        if ($uu[1] != null) {
            $urltoken = $uu[1];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $urltoken);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            $gettoken = curl_exec($ch);
            curl_close($ch);
            $jsontoken = json_decode($gettoken,true);
            $diantoken = $jsontoken['Data']['RepDataToken']['0']['token'];
        }
        $url = $uu[0];
        $request = $pcconfig['0']['request'];
        $config_if = $pcconfig['0']['config_if'];
        $a = explode('?',$url);
        $b = explode(';',$config_if);
        $ht = explode(':',$url);
        $url_f=$a[0];
        $query=$a[1];
        parse_str($query,$post_data);
        foreach($post_data as $key => $value) {
            if($value == "{dian_idfa}") {
                $post_data[$key] = $idfa;
            }
            if ($value == "{dian_ip}") {
                $post_data[$key] = $ip;
            }
            if ($value == "{dian_token}") {
                $post_data[$key] = $diantoken;
            }
            if ($value == "{dian_kid}") {
                $post_data[$key] = $kid;
            }
        }

        if($appid == "1570"||$appid == "1579" || $appid == "1581" || $appid == "1583" ||$appid=="1646") {
            $data1= array('idfa'=>$idfa,'source'=>"diandian");
            ksort($data1);
            $signStr="";
            foreach($data1 as $key=>$val){
                $signStr .= "$key=$val";
            }
            $post_data['keyt']=md5($signStr."PuPaZVBhh2W9");
        }
        if($appid == "1643" || $appid=="1648" || $appid=="1673" || $appid=="1835") {
            $chuangli_adid= "8103";
            $chuangli_channel="72005";
            $chuangli_key="e53128c002402240468632b4f9b353c7";
            $chuangli_sa=$chuangli_adid."|".$chuangli_channel."|".$chuangli_key;
            $chuangli_sign = md5($chuangli_sa);
            $post_data['sign']=$chuangli_sign;
        }

        if($appid=="1878") {
            $chuangli_adid= "6596";
            $chuangli_channel="72005";
            $chuangli_key="e53128c002402240468632b4f9b353c7";
            $chuangli_sa=$chuangli_adid."|".$chuangli_channel."|".$chuangli_key;
            $chuangli_sign = md5($chuangli_sa);
            $post_data['sign']=$chuangli_sign;
            //var_dump($chuangli_sa);
            //var_dump($chuangli_sign);
        }

        if($appid == "1644" || $appid=="1649") {
            $chuangli_adid= "6596";
            $chuangli_channel="72005";
            $chuangli_key="e53128c002402240468632b4f9b353c7";
            $chuangli_sa=$chuangli_adid."|".$chuangli_channel."|".$chuangli_key;
            $chuangli_sign = md5($chuangli_sa);
            $post_data['sign']=$chuangli_sign;
        }

        if($appid == "1891" ) {
            $chuangli_adid= "6706";
            $chuangli_channel="72005";
            $chuangli_key="e53128c002402240468632b4f9b353c7";
            $chuangli_sa=$chuangli_adid."|".$chuangli_channel."|".$chuangli_key;
            $chuangli_sign = md5($chuangli_sa);
            $post_data['sign']=$chuangli_sign;
            //var_dump($chuangli_sa);
            //var_dump($chuangli_sign);
        }
        if($appid == "1921") {
            $chuangli_adid= "2031";
            $chuangli_channel="72005";
            $chuangli_key="e53128c002402240468632b4f9b353c7";
            $chuangli_sa=$chuangli_adid."|".$chuangli_channel."|".$chuangli_key;
            $chuangli_sign = md5($chuangli_sa);
            $post_data['sign']=$chuangli_sign;
            //var_dump($chuangli_sa);
            //var_dump($chuangli_sign);
        }
        if($appid == "2022" ) {
            $chuangli_adid= "4770";
            $chuangli_channel="72005";
            $chuangli_key="e53128c002402240468632b4f9b353c7";
            $chuangli_sa=$chuangli_adid."|".$chuangli_channel."|".$chuangli_key;
            $chuangli_sign = md5($chuangli_sa);
            $post_data['sign']=$chuangli_sign;
            //var_dump($chuangli_sa);
            //var_dump($chuangli_sign);
        }
        if($appid == "2025") {
            $chuangli_adid= "5837";
            $chuangli_channel="72005";
            $chuangli_key="e53128c002402240468632b4f9b353c7";
            $chuangli_sa=$chuangli_adid."|".$chuangli_channel."|".$chuangli_key;
            $chuangli_sign = md5($chuangli_sa);
            $post_data['sign']=$chuangli_sign;
            //var_dump($chuangli_sa);
            //var_dump($chuangli_sign);
        }
        if($appid == "2035") {
            $chuangli_adid= "5375";
            $chuangli_channel="72005";
            $chuangli_key="e53128c002402240468632b4f9b353c7";
            $chuangli_sa=$chuangli_adid."|".$chuangli_channel."|".$chuangli_key;
            $chuangli_sign = md5($chuangli_sa);
            $post_data['sign']=$chuangli_sign;
            //var_dump($chuangli_sa);
            //var_dump($chuangli_sign);
        }
        if ($appid == "1758") {
            $appsecret = '3ca5aa19c88cbd4bf0386e252dccc0f7'; 
            $post_data['sign'] = md5($post_data['sId'].$appsecret);
            $http_buildss = '{';
            foreach ($post_data as $k => $v) {
                if ($k == 'idfa') {
                    $http_buildss .= '"'.$k.'"'.":".'["'.$v.'"],';
                }else{
                    $http_buildss .= '"'.$k.'"'.":".'"'.$v.'",';
                }
            }
            $http_buildss = rtrim($http_buildss,',');
            $http_buildss = $http_buildss.'}';
        }
        //request为get请求
        if ($request == 'get') {
            $url_f=$url_f.'?'.http_build_query($post_data);
        }
        //初始化 请求获取返回结果
        $ch = curl_init();
        //https url handler    curl发起https请求  设定为不验证证书和host
        if($ht[0] == 'https') {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //不验证证书
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //不验证证书
        }
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL,$url_f);
        //为了支持cookie
        curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
        //get return result
        if($request == 'post') {
            curl_setopt($ch, CURLOPT_POST, 1);
            if ($appid == "1758") {
                $post_data = json_encode( $post_data );
            }
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        $redata = curl_multi_getcontent($ch);
        $obj = json_decode($redata);
        $arr = json_decode($redata,true);
        curl_close($ch);
        //添加排重信息
        $rtime = date('Y-m-d H:i:s',time());
        $row_data = array ('app_id' => $appid,'ip'=>$ip,'idfa'=>$idfa,'rtime'=>$rtime,'result'=>$redata,'chanid'=>$chanid,'kid'=>$kid);
        $Row_id = D('RowRepeat')->addRowRepeat($row_data);
        if($appid == '2044'){      //ceshixuyao
            echo '{"'.$idfa.'":"0"}';exit;
        }
        //对返回结果进行判断
        $jso = explode('->',$b[0]);
        if ($config_if != '') {
            if ($b[0] == 'dzh') {
                $coun = $arr['Data']['RepDataVerifyRsp'];
                $num =count($coun);
                for ($i=0; $i <$num ; $i++) {
                    if ($coun[$i]['Result'] == "YES") {
                        echo '{"'.$coun[$i]['Idfa'].'":"0"}';
                    }else if($coun[$i]['Result'] == "NO"){
                        echo '{"'.$coun[$i]['Idfa'].'":"1"}';
                    }else{
                        echo "error";
                    }
                }
            }else if($b[0] == 'guomei'){
                if ($arr['data'][0]['status'] == 0) {
                    echo '{"'.$idfa.'":"0"}';
                }else if($arr['data'][0]['status'] == 1){
                    echo '{"'.$idfa.'":"1"}';
                }else{
                    echo "error";
                }
                
            }else if($b[0] == 'dfsh'){
                if ($obj->data[0]->$idfa == 0) {
                    echo '{"'.$idfa.'":"0"}';
                }else if($obj->data[0]->$idfa == 1){
                    echo '{"'.$idfa.'":"1"}';
                }else{
                    echo "error";
                    
                }
            }else if($b[0] == 'ASOWind'){
                if ($obj->resState == 200) {
                    echo '{"'.$idfa.'":"0"}';
                }else if($obj->resState == 201){
                    echo '{"'.$idfa.'":"1"}';
                }else{
                    echo "error";
                }
            }else{
                if ($redata == $b[0]) {
                    echo '{"'.$idfa.'":"0"}';exit;
                }     
                if ($obj->$idfa == $b[1]) {
                    echo '{"'.$idfa.'":"0"}';exit;
                } 
                if ($obj->$b[0]->$idfa == $b[1]) {
                    echo '{"'.$idfa.'":"0"}';exit;
                }
                if (!empty($jso[1])) {
                    if($obj->$jso[0]->$jso[1]->$idfa == $b[1]){
                        echo '{"'.$idfa.'":"0"}';exit;
                    }
                }  
                if($redata == $b[1]){
                    echo '{"'.$idfa.'":"1"}';exit;
                }
                if($obj->$idfa === 1){
                    echo '{"'.$idfa.'":"1"}';exit;
                }
                if($obj->$b[0]->$idfa === 0){  //特殊情况  0和1  相反的
                    echo '{"'.$idfa.'":"1"}';exit;
                }
                if($obj->$b[0]->$idfa === 1){
                    echo '{"'.$idfa.'":"1"}';exit;
                }
                if (!empty($jso[1])) {
                    if($obj->$jso[0]->$jso[1]->$idfa == 1){
                        echo '{"'.$idfa.'":"1"}';exit;
                    }
                }
                echo $own_result = json_encode(array("code"=>201,"message"=>"失败"),JSON_UNESCAPED_UNICODE);exit;
            }
        }else{

            $_str =preg_replace("/\s/","",$redata);
            $_str = str_replace(':1', ':"1"',$_str);
            $_str = str_replace(',"1"', ':"1"',$_str);
            $_str = str_replace(',"0"', ':"0"',$_str);
            echo str_replace(':0', ':"0"', $_str);
        }
    }
}
?>

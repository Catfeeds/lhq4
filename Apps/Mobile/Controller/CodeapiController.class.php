<?php
/**
 * Created by PhpStorm.
 * User: z9944
 * Date: 2018/5/9
 * Time: 14:29
 */
namespace Mobile\Controller;
use Think\Controller;
class CodeapiController extends CommonController {

    public function index() {
        require (APP_PATH.'Api/Message/TopSdk.php');
        session_start();
        $phone=$_REQUEST['phone'];
        $identify = $_REQUEST['identify'];
        if (empty($phone) || empty($identify)) {
            echo json_encode(array('code'=>501,'message'=>'缺少参数'),JSON_UNESCAPED_UNICODE);
            exit;
        }
        $v_code = mt_rand(100000,999999);
        $_SESSION['yzm']=$v_code;
        $_SESSION['time']=time();
        //检测手机号
        $check = D('Member')->checkPhone($phone);
        //检测唯一初始标识
        $checkidfa = D('Member')->checkIdentify($identify);
        if ($check) {
            $code = D('Member')->upDataCode($v_code,$phone);
            if ($code) {
                $this->messAge($phone,$v_code);
            } else {
                echo  json_encode(array("code"=>203,"message"=>"验证失败"),JSON_UNESCAPED_UNICODE);
                exit;
            }

        } else {
            if ($checkidfa['identify']) {
                $result = D('Member')->updataPhone($phone,$identify,$v_code);
                if ($result) {
                    $this->messAge($phone,$v_code);
                } else {
                    echo  json_encode(array("code"=>203,"message"=>"验证失败"),JSON_UNESCAPED_UNICODE);
                    exit;
                }
            } else {
                //在数据库添加新数据
                $data = array();
                $name = '';
                $lenth = rand(5,6);
                $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                for ($i=0;$i<=$lenth;$i++) {
                    $name .= $chars[ mt_rand(0, strlen($chars) - 1) ];
                }
                if (!empty($phone)) {
                    $time = date('Y-m-d H:i:s',time());
                    $data['phone']       = $phone;
                    $data['creatDate']   = $time;
                    $data['yzm_code']    = $v_code;
                    $data['member_name'] = $name;
                    $data['nickname']    = $name;
                    $data['add_time']    = $time;
                    $data['login_time']  = $time;
                    $data['ip']          = getip();
                    $data['identify']    = $identify;
                    $result = D('Member')->add($data);
                    if ($result) {
                        $this->messAge($phone,$v_code);
                    }
                } else {
                    echo  json_encode(array("code"=>203,"message"=>"手机号为空"),JSON_UNESCAPED_UNICODE);
                    exit;
                }
            }

        }
    }

    /**
     * 发送验证码
     * @param $phone //手机号
     * @param $v_code //随机验证码
     */
    public function messAge($phone,$v_code)
    {
        $appkey="23436326";
        $secretKey="bbc5927acbfe786a27af52565838a4da";
        date_default_timezone_set('Asia/Shanghai');
        $c = new \TopClient;
        $c ->appkey = $appkey ;
        $c ->secretKey = $secretKey ;
        $req = new \AlibabaAliqinFcSmsNumSendRequest;
        $req ->setRecNum( $phone );
        $req ->setExtend( "trt" );
        $req ->setSmsType( "normal" );
        $req ->setSmsFreeSignName( "零花钱" );
        $req ->setSmsParam( "{code:'$v_code',product:'零花钱'}" );
        $req ->setSmsTemplateCode( "SMS_13190335" );
        $resp = $c->execute($req);
        $resp = json_encode($resp);
        if ($resp['result']['success']) {
            echo json_encode(array("code"=>200,"message"=>"发送成功"),JSON_UNESCAPED_UNICODE);
            exit;
        } else {
            echo  json_encode(array("code"=>203,"message"=>"发送失败"),JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

}
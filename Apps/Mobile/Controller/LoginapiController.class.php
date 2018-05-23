<?php
namespace Mobile\Controller;
use Think\Controller;
class LoginApiController extends CommonController {
    public function index()
    {
        $phone = $_REQUEST['phone'];
        $code  = $_REQUEST['code'];
        $identify  = $_REQUEST['identify'];
        $changeidfa = $_REQUEST['idfa'];
        //查找数据库有没有用户和通过手机号查找idfa
        $result = D('Member')->getPhoneInfo($phone);
        $idfa = $result['identify'];
        $memberid = $result['member_id'];
        if (empty($idfa)) {
            //在member表添加idfa
            $member = D('Member')->addMemberIdfa($identify,$phone);
            //在alertidfa表加入数据
            $alertidfa = D('AlterIdfa')->addAlterIdfa($identify,$phone);
            if ($member && $alertidfa) {
                $this->code($result['yzm_code'],$code,$memberid);
            } else {
                echo json_encode(array("code"=>203,"message"=>"系统错误 请重试！"),JSON_UNESCAPED_UNICODE);
                exit;
            }
        } else {
            if (!empty($changeidfa)) {

                if ($result['idfa'] == $changeidfa ) {


                    //改变标识idfa
                    $member1 = D('Member')->saveMemberIdfa($changeidfa,$phone);
                    //改变标识idfa
                    $alertidfa = D('AlterIdfa')->saveIdfa($changeidfa,$phone);
                    if ($member1 && $alertidfa) {
                        $this->code($result['yzm_code'],$code,$memberid);
                    } else {
                        echo json_encode(array("code"=>203,"message"=>"系统错误 请重试！"),JSON_UNESCAPED_UNICODE);
                        exit;
                    }
                } else {

                    //改变标识idfa
                    $member1 = D('Member')->saveMemberIdfa($changeidfa,$phone);
                    //改变标识idfa
                    $alertidfa = D('AlterIdfa')->saveIdfa($changeidfa,$phone);
//
                    if ( $member1 && $alertidfa) {
                        $this->code($result['yzm_code'],$code,$memberid);
                    }
                }

            } else {
				$this->code($result['yzm_code'],$code,$memberid);
                //改变标识idfa
                //$member1 = D('Member')->saveMemberIdfa($changeidfa,$phone);
                //改变标识idfa
                //$alertidfa = D('AlterIdfa')->saveIdfa($changeidfa,$phone);
                //if ($member1 && $alertidfa) {
                   // echo json_encode(array("code"=>200,"message"=>"操作成功"),JSON_UNESCAPED_UNICODE);
                //}
            }
        }
    }

    public function code($ycode,$code,$memberid)
    {
            $time=time();
            $stime=$_SESSION['time'];
            $ccode = $ycode;
            if (strtolower($code) !== strtolower($ccode)) {

                echo json_encode(array("code"=>203,"message"=>"验证码错误"),JSON_UNESCAPED_UNICODE);
                exit;
            } else {
                if ($time-$stime >= 600) {

                    echo json_encode(array("code"=>203,"message"=>"验证码已超时"),JSON_UNESCAPED_UNICODE);
                    exit;
                } else {
                    //登录成功跳转的页面
                    #$this->redirect('Mobile');
                    echo json_encode(array("code"=>200,"message"=>"验证成功","url"=>ADMIN_URL."/mobile/?member_id=$memberid","msid"=>$memberid),JSON_UNESCAPED_UNICODE);
                    exit;
                }
            }
    }
}
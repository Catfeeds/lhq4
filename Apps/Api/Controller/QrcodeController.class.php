<?php
namespace Api\Controller;
use Think\Controller;
class QrcodeController extends Controller {
    public function qrcode()
    {
//require ('../include/init.inc.php');
//require ('../mobile/checkDevice.php');

//checkDevice();
// $method = $appid = $secret= '1';
// $list=Member::getMembers();

//$appid = $WX_LIST['appid'];
//$secret=$WX_LIST['appsecret'];
        $appid = C('WX_LIST.appid');
        $secret = C('WX_LIST.appsecret');
// $member_id = $_SESSION['member_id'];
        $member_id = $_GET['member_id'];

        if (!empty($member_id)) {
            $status = '1';
            //$access_token = "YONQgSTY2LLhAaatAtKGJMe-h5eAgU4fDXKDN8SoI2UTqueJgLBsmyhT_7ArQBCnUVxcbGrAPKtt4bd7_ewzIe4199d8G6bzfNV1474KwpkCfO7s-EszzRqmjCdNl9EaJUHgACABMZ";
//    $access_token=Member::getsAccessToken($appid,$secret);
            $access_token = D('Member')->getsAccessToken($appid, $secret);
            //创建二维码ticket
            $qrcode = '{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id":' . $member_id . '}}}';
            $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=" . $access_token;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $qrcode);
            $output = curl_exec($ch);
            curl_close($ch);
            $result = json_decode($output);
            $result->ticket;
            //通过ticket换取二维码
            $url = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=" . urlencode($result->ticket) . "&member_id=" . $member_id;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            $output = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            //判断用ticket换取二维码图片是否成功
            if ($httpCode == 200) {
                //二维码图片下载
//        $imageInfo = Member::downloadImageFromWeiXin($url);
                $imageInfo = D('Member')->downloadImageFromWeiXin($url);
                //将二维码图片存入本地
                $qrcode = 'qrcode' . $member_id . ".jpg";
                $filename = "./Public/qrcode/" . $qrcode;
                $local_file = fopen($filename, 'w');
                if (false !== $local_file) {
                    if (false !== fwrite($local_file, $imageInfo["body"])) {
                        fclose($local_file);
                    }
                }
                //将二维码图片名称存入数据库
                $update_data = array('qrcode' => $qrcode);
//                $result = Member::updateMember($member_id, $update_data);
                $result = D('Member')->updateMember($member_id, $update_data);
                echo json_encode(array("message" => "生成二维码成功", "success" => true), JSON_UNESCAPED_UNICODE);
            } else {
                //获取二维码失败
                echo json_encode(array("message" => "生成二维码失败", "success" => false), JSON_UNESCAPED_UNICODE);
            }
        } else {
            //如果$member_id为空的话
            echo json_encode(array("message" => "参数部分信息为空", "success" => false), JSON_UNESCAPED_UNICODE);
        }

// if ($result>=0) {
//     SysLog::addLog ( UserSession::getUserName(), 'MODIFY', '配置信息' ,$member_id, json_encode($update_data) );
//     // Common::exitWithSuccess ( '配置信息修改完成','backend/mission.php' );
// } else {

//     OSAdmin::alert("error");
// }
    }
}
?>
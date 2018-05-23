<?php
namespace Api\Controller;
use Think\Controller;
//require ('../innamespace Api\Controller;
vendor('Wechat.wechat#class',"",'.php');
use Common\Common\GetCode;
class Oauth2Controller extends Controller
{
    public function oauth2()
    {
        // $weObj = new \Wechat(C('WX_LIST'));
        // //通过code换取网页授权access_token
        // $access_array = $weObj->getOauthAccessToken();
        // $access_token = $access_array["access_token"];
        // $refresh_token = $access_array["refresh_token"];
        // //刷新access token并续期
        // $new_access_array = $weObj->getOauthRefreshToken($refresh_token);
        // $refresh_token = $new_access_array["refresh_token"];
        // $expires_in = $new_access_array["expires_in"];
        // $new_access_token = $new_access_array['access_token'];
        // $openid = $new_access_array['openid'];
        // //获取授权后的用户资料
        // $member_info = $weObj->getOauthUserinfo($new_access_token,$openid);
        // $openid = $member_info['openid'];
        // $nickname = $member_info['nickname'];
        // $sex = $member_info['sex'];
        // $city = $member_info['city'];
        // $country = $member_info['country'];
        // $pic = $member_info['headimgurl'];

        $appid = C('WX_LIST.appid');
        if (isset($_GET['code'])) {
            //echo $_GET['code'];
            //2、通过code换取网页授权access_token
            //appid 是   公众号的唯一标识
            //secret    是   公众号的appsecret
            //code  是   填写第一步获取的code参数
            //grant_type    是   填写为authorization_code
            $code = $_GET['code'];
//            $secret = $WX_LIST['appsecret'];//    公众号的appsecret
            $secret = C('WX_LIST.appsecret');
            $get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $appid .
                '&secret=' . $secret . '&code=' . $code . '&grant_type=authorization_code';
            $res2 = file_get_contents($get_token_url);
            $json_obj2 = json_decode($res2, true);
//3、获取refresh_token拥有较长的有效期（7天、30天、60天、90天）
            $access_token = $json_obj2["access_token"];
            $refresh_token = $json_obj2["refresh_token"];
            $get_access_token_url = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=' . $appid .
                '&grant_type=refresh_token&refresh_token=' . $refresh_token;
            $res3 = file_get_contents($get_access_token_url);
            $json_obj3 = json_decode($res3, true);
            $refresh_token = $json_obj3["refresh_token"];
            $expires_in = $json_obj3["expires_in"];
//4、拉取用户信息(需scope为 snsapi_userinfo)
            $new_access_token = $json_obj3['access_token'];
            $openid = $json_obj3['openid'];
            $data = array("openid" => $openid, "refresh_token" => $refresh_token, "expires_in" => $expires_in,);
            //将数组存到指定的text文件中
//    file_put_contents("E:/weixindata.txt",json_encode($data));
            $get_user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $new_access_token .
                '&openid=' . $openid . '&lang=zh_CN';
            $res4 = file_get_contents($get_user_info_url);
            $json_obj4 = json_decode($res4, true);
            $openid = $json_obj4['openid'];
            $nickname = $json_obj4['nickname'];
            $sex = $json_obj4['sex'];
            $city = $json_obj4['city'];
            $country = $json_obj4['country'];
            $pic = $json_obj4['headimgurl'];
            //拼数据
            // 插入数据示例//通过openid查member表的内容分
            $exist = D('Member')->field("openid,member_id")->where(array('openid'=>$openid))->select();
            // var_dump($exist);
            if (!count($exist)) {
                //如果没有重复的记录添加
                $member_id = D('Member')->data( array(
                    'openid' => $openid,
                    'nickname' => $nickname,
                    'sex' => $sex,
                    'city' => $city,
                    'country' => $country,
                    'pic' => $pic,)
                )->add();

                // $no = intval($member_id);

                // if ($no == 0 || $no >= 10000000) {
                //     echo '';
                //     exit;
                // }
                // $card_no = GetCode::encodeID($no, 3);

                // //echo $code->decodeID('5YC1S');
                // //echo '<hr/>';

                // $card_pre = 'd6d';
                // $card_vc = substr(md5($card_pre . $card_no), 0, 2);
                // $card_vc = strtolower($card_vc);
                // D('Member')->where(array("member_id" => $member_id))->save(array('user_num' => $card_pre . $card_no . $card_vc,));
          
            } else {
                //修改
                $member_id = $exist['0']['member_id'];
                $openid = $exist['0']['openid'];
                D('Member')->where(array("member_id" => $member_id))->save(array( 'openid' => $openid,
                    'nickname' => $nickname,
                    'sex' => $sex,
                    'city' => $city,
                    'country' => $country,
                    'pic' => $pic,));
            }
            $url = 'https://itunes.apple.com/us/app/quan-min-yin-le/id1144881515?l=zh&ls=1&mt=8';
            $tieba = 'com.baidu.tieba://';
            $weibo = 'weibo://';
            $qq = 'mqq://';
            $weixin = 'weixin://';
            $target = 'ddsd://';
            $s = 'weixin';
            if ($s == "weixin") {
                //||strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')
                if (strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')) {
                    $open_url = $target . '?member_id=' . $member_id;
//            $res=file_get_contents($open_url);
//            var_dump($res);
//            die;
//            if($res ==false) {
                    header("Location:".ADMIN_URL."/Api/Oauth2?member_id=" . $member_id);
                    // }
                } else {
                    echo "仅支持iPhone用户";
                }
            } else {
            }
        } else {
            echo "请用微信打开";
        }
    }
    //授权通过后跳转页面
    public function index(){
        $member_id = I('get.member_id');
        $this->assign("member_id",$member_id);
        $this->display();
    }
}
?>
<?php
namespace Api\Controller;
use Think\Controller;
vendor('Wechat.wechat#class',"",'.php');
use Common\Common\GetCode;
class WechatController extends Controller
{
//    public function wechatJump(){
    public function wechatHandler(){

        $weObj = new \Wechat(C('WX_LIST'));
        //$weObj->valid();//明文或兼容模式可以在接口验证通过后注释此句，但加密模式一定不能注释，否则会验证失败
        $type = $weObj->getRev()->getRevType();
        //file_put_contents('C:/weObj.txt',$weObj->MSGTYPE_EVENT);
        switch ($type)
        {
            case $weObj::MSGTYPE_TEXT:
                $openid = $weObj->getRev()->getRevFrom();
                $timestamp = $weObj->getRev()->getRevCtime();
                $time_stamp = date('Y-m-d H:i:s', $timestamp);
                $message = $weObj->getRev()->getRevContent();
                $memberinfo=D('Member')->field('member_id,nickname')->where(array("openid" => $openid))->select();
                //$weObj->text("您的消息我们已经接收，会尽快回复!")->reply();
                $last_user_id=D('customer')->data(array(
                    'member_id' => $memberinfo[0]['member_id'],
                    'nickname' => $memberinfo[0]['nickname'],
                    'time_stamp' => $time_stamp,
                    'message' => $message,
                    'openid' => $openid))->add( );
                exit;
                break;
            case $weObj::MSGTYPE_EVENT;
                //拼数据
                $eventParam = $weObj->getRevEvent();
                $exist =D('Member')->field('openid', 'member_id', 'balance', 'user_num', 'pid', 'qrcode')->where(array(["openid" => $weObj->getRevFrom()]))->select();
                if ($eventParam['event'] == $weObj::EVENT_SCAN || $eventParam['event'] == $weObj::EVENT_SUBSCRIBE)
                {
                    if (!count($exist))
                    {
                        
                        //第一次关注subscribe
                        $memberInfo = $weObj->getUserInfo($weObj->getRevFrom());
                        //如果没有重复的记录添加
                        $member_id =D('Member')->data(array('openid' => $weObj->getRevFrom(),
                            'nickname'=>$memberInfo['nickname'],
                            'sex'=>$memberInfo['sex'],
                            'city'=>$memberInfo['city'],
                            'pic'=>$memberInfo['headimgurl'],
                            'country'=>$memberInfo['country'],
                            'pid' => $weObj->getRevSceneId(),
                            'add_time' => date('Y-m-d H:i:s', time()),
                            'total_task' => 0,
                            'total_invite' => 0,
                            'total_wd' => 0,
                            'finishs_task' => 0,
                            'invites' => 0,
                            'invitee_sum' => 0))->add();
                        if (is_numeric($weObj->getRevSceneId()) && $weObj->getRevSceneId() > 0)
                        {
                            D('Member')->where(array("member_id" => $weObj->getRevSceneId()))->setInc('invites');
                        }
                        $memberid =D('Member')->field('openid', 'member_id', 'balance', 'user_num', 'pid', 'qrcode')->where(array("openid" => $weObj->getRevFrom()))->select();
                        //访问qrcode.php生成二维码

                        $url = ADMIN_URL.'/Api/Qrcode/qrcode?member_id='.$memberid[0]['member_id'];

                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_HEADER, 0);
                        $output = curl_exec($ch);
                        curl_close($ch);
                        $obj = json_decode($output);
                        if ($obj->success) {
                            //如果获取二维码成功
                        } else {
                            //如果获取二维码失败
                        }
                        //生成用户编号
                        
                        $no = intval($member_id);
                        if ($no == 0 || $no >= 10000000) {
                            echo '';
                            exit;
                        }
                        $card_no = GetCode::encodeID($no, 5);
                        $card_vc = substr(md5($card_no), 0, 2);
                        $card_vc = strtolower($card_vc);
                        $card_no = strtolower($card_no);
                        D('Member')->where(array("member_id" => $member_id))->save(array('user_num' =>$card_no . $card_vc));
                        if ($weObj->getRevSceneId() != 0) {
                            //扫描的是场景二维码                          
                            //$txt = "欢迎关注应用帮公众平台!\n----------\n账户ID: " .$card_no . $card_vc . "\n您的邀请人id为" . $weObj->getRevSceneId() . "!\n  ----------\n开始赚钱吧!\n  <a href='https://itunes.apple.com/us/app/quan-min-yin-le/id1144881515?l=zh&ls=1&mt=8'>下载app</a> >>>>> <a href='".ADMIN_URL."/Api/Wechat/wechatJump'>打开app</a>";
                            $txt = "/:rose".$memberInfo['nickname']."\n你的好友说你会来，你果然出现了。如果你是苹果手机，赶快激活“应用帮”和好友一起赚钱呗！";
                        } else {
                            //扫描的是普通二维码或直接关注的
                            //$txt = "欢迎关注应用帮公众平台!\n----------\n账户ID: " .$card_no . $card_vc . "\n  ----------\n开始赚钱吧!\n  <a href='https://itunes.apple.com/us/app/quan-min-yin-le/id1144881515?l=zh&ls=1&mt=8'>下载app</a> >>>>> <a href='".ADMIN_URL."/Api/Wechat/wechatJump'>打开app</a>";
                            $txt = "/:rose".$memberInfo['nickname']."\n终于等到你啦，您如果是苹果手机就去激活【应用帮】，他会让您在空闲之余多一份收入。";
                        }
                    } else {
                        //scan  取消关注后在次关注
                        D('Member')->where(array("openid" => $weObj->getRevFrom()))->save(array("subscribe" => 1));
                        //以前关注后没有生成二维码的给他生成
                        if ($exist[0]['qrcode'] == NULL || $exist[0]['qrcode'] == '') {
                            //查询member_id
                            $memberid=  D('Member')->field('member_id')->where(array("openid" => $weObj->getRevFrom()))->select();
                            //访问qrcode.php生成二维码
                            $url = ADMIN_URL.'/Api/Qrcode/qrcode?member_id='.$memberid[0]['member_id'];
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, $url);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                            curl_setopt($ch, CURLOPT_HEADER, 0);
                            $output = curl_exec($ch);
                            curl_close($ch);
                            $obj = json_decode($output);
                            if ($obj->success) {
                                //如果获取二维码成功
                            } else {
                                //如果获取二维码失败
                            }
                        }
                        if ($exist[0]['pid'] == 0) {
                            //扫描的是普通二维码或直接关注的
                            $txt = "欢迎关注应用帮公众平台!\n----------\n账户ID: " . $exist[0]['user_num'] . "\n  ----------\n开始赚钱吧!\n  <a href='https://itunes.apple.com/us/app/quan-min-yin-le/id1144881515?l=zh&ls=1&mt=8'>下载app</a> >>>>> <a href='".ADMIN_URL."/Api/Wechat/wechatJump'>打开app</a>";
                        } else {
                            //扫描的是场景二维码
                            $txt = "欢迎关注应用帮公众平台!\n----------\n账户ID: " . $exist[0]['user_num'] . "\n您的邀请人id为" . $exist[0]['pid'] . "!\n  ----------\n开始赚钱吧!\n  <a href='https://itunes.apple.com/us/app/quan-min-yin-le/id1144881515?l=zh&ls=1&mt=8'>下载app</a> >>>>> <a href='".ADMIN_URL."/Api/Wechat/wechatJump'>打开app</a>";
                        }
                    }
                } 
                //菜单 - 点击菜单拉取消息
                if ($eventParam['event'] == $weObj::EVENT_MENU_CLICK) {
   /*                  if ($eventParam['key'] == "item1") {
                        if (!count($exist)) {
                            //subscribe
                            //如果没有重复的记录添加
                            $member_id=  D('Member')->where(array("openid" => $weObj->getRevFrom()))->select();
                            $no = intval($member_id);
                            if ($no == 0 || $no >= 10000000) {
                                echo '';
                                exit;
                            }
                            $card_no = GetCode::encodeID($no, 5); 
                            $card_vc = substr(md5($card_no), 0, 2);
                            $card_vc = strtolower($card_vc);
                            $card_no = strtolower($card_no);
                            D('Member')->where(array("member_id" => $member_id))->save(array("user_num" => $card_no . $card_vc));
                            $txt = "账户ID: " .$card_no . $card_vc . "\n----------\n账户余额: ". $exist['balance'] . "\n  ----------\n\n  <a href='https://itunes.apple.com/us/app/quan-min-yin-le/id1144881515?l=zh&ls=1&mt=8'>下载app</a> >>>>> <a href='".ADMIN_URL."/Api/Wechat/wechatJump'>打开app</a>";
                        } else {
                            if ($exist[0]['balance'] == "") {
                                $balance = 0;
                            } else {
                                $balance = $exist[0]['balance'];
                            }
                            $txt = "账户ID: " . $exist[0]['user_num'] . "\n----------\n账户余额: " . $balance . "\n  ----------\n\n  <a href='https://itunes.apple.com/us/app/quan-min-yin-le/id1144881515?l=zh&ls=1&mt=8'>下载app</a> >>>>> <a href='".ADMIN_URL."/Api/Wechat/wechatJump'>打开app</a>";
                        } 
                    }*/
                    if ($eventParam['key'] == "menu_1_1"){
                        $txt = "您好，请问有什么可以帮助您?";
                    }
                } 
                //菜单 - 点击菜单跳转链接
                if ($eventParam['event'] == $weObj::EVENT_MENU_VIEW) {
                    
                }
                //取消订阅
                if ($eventParam['event'] == $weObj::EVENT_UNSUBSCRIBE) {
                    if (count($exist)) {
                        D('Member')->where(array("openid" => $weObj->getRevFrom()))->save(array("subscribe" => 0));
                    }
                    $txt = "取消关注成功";
                }
                $weObj->text($txt)->reply();
                break;
            case $weObj::MSGTYPE_IMAGE:
                $weObj->text("请发送文字消息")->reply();
                break;
            default:
                $weObj->text("请发送文字消息!")->reply();
        }
        //获取菜单操作:
        //$menu = $weObj->getMenu();
        $data = array (
            'button' => array (
                0 => array (
                    'type' => 'view',
                    'name' => '激活应用帮',
                    'url' => ADMIN_URL.'/Api/Wechat/wechatJump',
                ),
                1 => array (
                   'name' => '服务大厅',
                   'sub_button' => array (
                        0 => array (
                            'type' => 'view',
                            'name' => '下载应用帮',
                            'url' => 'https://itunes.apple.com/us/app/quan-min-yin-le/id1144881515?l=zh&ls=1&mt=8'
                        ),
                        1 => array (
                            'type' => 'click',
                            'name' => '客服服务',
                            'key' => 'menu_1_1',
                        ),
                        2 => array (
                            'type' => 'view',
                            'name' => '商务合作',
                            'url' => ADMIN_URL.'/Api/Wechat/busniss',
                        )
                    ),
                ),

            ),
        );
        $weObj->createMenu($data);  

    }
    //授权页面
    public function wechatJump(){
        $appid= C('WX_LIST.appid');
        $redirect_uri= ADMIN_URL.'/Api/Oauth2/oauth2';
        $scope='snsapi_userinfo';//snsapi_userinfo （弹出授权页面，可通过openid拿到昵称、性别、所在地。并且，即使在未关注的情况下，只要用户授权，也能获取其信息）
        $weixinurl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri='.urlencode($redirect_uri).'&response_type=code&scope='.$scope.'&state=STATE#wechat_redirect';
        header("Location:".$weixinurl);
    }
    //商务合作
    public function busniss(){
        $this->display();
    }
}


?>
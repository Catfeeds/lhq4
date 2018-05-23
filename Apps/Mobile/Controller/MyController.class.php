<?php
namespace Mobile\Controller;
use Think\Controller;
class MyController extends CommonController {
    public function my(){
        $id=I('get.member_id');
        $model=D('Member');

        $members=$model-> where(array('member_id'=>$id))->field('pic,sex,alipay,weixin,phone,birthday,nickname')->find();
        $this->members=$members;
       // dump($members);die;
        //修改个人信息
      /*  if(IS_POST) {
            $data = I('post.');
            dump($data);die;
            $msave = $model->where(array('member_id' => $id))->save($data);
            if ($msave) {
                echo "<script>alert('个人信息修改成功')</script>";
                echo "<script language=JavaScript> location.replace(location.href);</script>";
            } else {
                echo "<script>alert('个人信息修改失败')</script>";
            }
        }*/
       /* dump($data);
        dump($a);die*/;
        $this->display();
    }
    public function bindmobile(){
        $id=I('get.member_id');
        $model=D('Member');
        $time=time();
        $stime=$_SESSION['time'];
        $A=$_SESSION['yzm'];
 /*       dump($time);
        dump($stime);
        dump($id);
         dump($A);
 $data = I('post.');
        dump($data);//die;*/
        if(IS_POST) {
            $data = I('post.');
            if(strtolower($data['v_code']) != strtolower($_SESSION['yzm'])){
                echo"<script>alert('验证码错误，请输入正确的验证码！')</script>";
              //  exit;
            }else{
                if( $time-$stime >=300){
                    echo"<script>alert('验证码已超时，请重新获取验证码！')</script>";
                   // exit;
                }else{
                   $phone=$model->where(array('member_id' => $id))->save(array('phone' => $data['phone']));
                    if($phone){
                        $this->redirect('My/my',array('member_id' => $id));
                    }
                    //$url='{:U('My/my')}';
                  //  header("Location:".{:U('My/my')});
                }
            }
        }
        $this->display();
    }

    public function bindalipay(){
        $id=I('get.member_id');
        $model=D('Member');
        $alipay= $member_name= $verify_code ='';
        extract ( $_REQUEST, EXTR_IF_EXISTS );
       // dump($data);//die;
        if(IS_POST) {


            if (strtolower($verify_code) != strtolower($_SESSION['osa_verify_code'])) {
                echo "<script>alert('验证码错误，请输入正确的验证码！')</script>";
            } else
               // $mave = $model->where(array('member_id' => $id))->save($data);
                $update_data = array('alipay' => $alipay,'member_name'=>$member_name);
            //var_dump($update_data);//die;

            $result = $model->updateMember($id, $update_data);
                if($result){
                    $this->redirect('My/my',array('member_id' => $id));
                }

        }
        $this->display();
    }
    public function sendvercode(){
        //import('Api.Message.TopSdk','APP_PATH','.php');
        require (APP_PATH.'Api/Message/TopSdk.php');
       // var_dump('../Api/Message/TopSdk.php');
       // $message=new\
        session_start();
        $phone=$_POST['tel'];
        $mobile=$phone;
        $appkey="23436326";
        $secretKey="bbc5927acbfe786a27af52565838a4da";
        //var_dump($mobile);DIE;
        //echo 1;die;
        date_default_timezone_set('Asia/Shanghai');
        $c = new \TopClient;
        $c ->appkey = $appkey ;
        $c ->secretKey = $secretKey ;
        $v_code = mt_rand(100000,999999);
        $_SESSION['yzm']=$v_code;
        $_SESSION['time']=time();
        $req = new \AlibabaAliqinFcSmsNumSendRequest;
        $req ->setExtend( "trt" );
        $req ->setSmsType( "normal" );
        $req ->setSmsFreeSignName( "身份验证" );
        $req ->setSmsParam( "{code:'$v_code',product:'应用帮'}" );
        //$req->setSmsParam("{\"code\":\"{$v_code}\",\"product\":\"零花钱1\"}");
        $req ->setRecNum( $mobile );
        $req ->setSmsTemplateCode( "SMS_13190337" );
        // $resp = $c ->execute( $req );
        $resp = $c->execute($req);
        echo 1;die;
        //var_dump($resq);die;
        $resp = obj2Arr($resp);
        //var_dump($req);
        if ($resp['result']['success']) {
            return true;
        } else {
            return false;
        }
        //将返回的对象装换成数组
        /*function obj2Arr ($obj) {
            $_arr = is_object($obj) ? get_object_vars($obj) : $obj;
            foreach ($_arr AS $k => $v) {
                $val = (is_object($v) ? obj2Arr($v) : $v);
                $arr[$k] = $val;
            }
            return $arr;
        }*/
        $this->display();
    }

    //验证码
    public function verify(){
        /*    $config =    array(
                'fontSize'    =>    15,    // 验证码字体大小
                'length'      =>    4,     // 验证码位数
                'useNoise'    =>    true, // 关闭验证码杂点
                'useImgBg' => true,
                'fontttf' => '5.ttf',
            );

            $verify = new \Think\Verify($config);
            //$verify->useZh = true; // 使用中文验证码
            $verify->entry();*/
        session_start();

        header("Content-type: image/png");

        $im = imagecreatetruecolor(80, 28);
        $english = array(2,3,4,5,6,7,8,9,'A','B','C','D','E','F','G','H','I','J','K','L','M','N','P','Q','R','S','T','U','V','W','X','Y','Z','a','b','c','d','e','f','g','h','j','k','m','n','p','q','r','s','t','u','v','w','x','y','z');
        $chinese= array();
//$chinese = array("人","出","来","友","学","孝","仁","义","礼","廉","忠","国","中","易","白","者","火 ","土","金","木","雷","风","龙","虎","天","地", "生","晕","菜","鸟","田","三","百","钱","福","爱","情","兽","虫","鱼","九","网","新","度","哎","唉","啊","哦","仪","老","少","日","月","星","于","文","琦","搜","狐","卓","望");
        $chars = array_merge($english,$chinese);
// 创建颜色
        $fontcolor = imagecolorallocate($im, 0x6c, 0x6c, 0x6c);
//$bg = imagecolorallocate($im, rand(0,85), rand(85,170), rand(170,255));
        $bg = imagecolorallocate($im, 0xfc, 0xfc, 0xfc);
        imagefill($im, 0, 0, $bg);
// 设置文字
        $text = "";
        for($i=0;$i<6;$i++) $text .= trim($chars[rand(0,count($chars)-1)]);

        $_SESSION['osa_verify_code'] = $text;

        $font = './Public/font/tahoma.ttf';

        $gray = ImageColorAllocate($im, 200,200,200);

// 添加文字
        imagettftext($im, 15, 0, 1, 23, $fontcolor, $font, $text);

//加入干扰象素
        $r = rand()%50;
        for($i=0;$i<150;$i++){
            $x=sqrt($i)*2+$r;
            imagesetpixel($im, abs(sin($i)*80) , abs(cos($i)*28) , $gray);
            imagesetpixel($im, $x , $i , $gray);
            imagesetpixel($im, rand()%80 , rand()%28 , $gray);

        }

// 输出图片
        imagepng($im);
        imagedestroy($im);
    }
    public function yzmAjax(){
        session_start();
        $verify=$_POST['verify'];

        $yzm=strtolower($verify);
        $yzm1=strtolower($_SESSION['osa_verify_code']);
        if($yzm==$yzm1){
            echo 1;die;
        }else{
            echo 0;die;
        }
    }
    //修改头像
    public function xgtx(){
      //  $id=I('get.member_id');
        $model=D("Member");
        $id=session('member_id');
    //$logo=$_FILES;
    $config=array(
        'maxSize'    =>    5*1024*1024,
        'savePath'   =>'aface/',
        'rootPath'=>'./pic/',
        'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),
        'autoSub'    =>    true,
        'subName'    =>    array('date','Ymd')
    );
        $data = I('post.');
        dump($id);
dump($data);//die;
        $a=$model->where(array('member_id'=>$id))->save($data);
    $upload = new \Think\Upload($config);// 实例化上传类
    if($_FILES['upfile']['error']==0){
      //  dump($_FILES['upfile']);
        $info=$upload->upload();
        if($info) {
          //  dump($info);//exit;
            $log=$info['upfile']['savepath'].$info['upfile']['savename'];
          //  dump($log);
            $smlog=$info['upfile']['savepath'].'sm_'.$info['upfile']['savename'];
            $image=new \Think\Image();
            $image->open('./pic/'.$log);
            $image->thumb(64,64,6)->save('./pic/'.$smlog);

            $list=$model->where(array('member_id'=>$id))->getField('pic');
           // dump($list);//DIE;
            if($list['pic']!='ht_touxiang.jpg'){
                $mylog=str_replace('sm_','',$list);
                unlink('./pic/'.$mylog);
                unlink('./pic/'.$list);
            }
           // $lj=$_SERVER['HTTP_ORIGIN'];
            $pic=ADMIN_URL."/".pic."/".$smlog;
            $model->where(array('member_id'=>$id))->save(array(
                'pic'=>$pic,
            ));

        }
    }

       // dump($a);die;
        $this->redirect('My/my',array('member_id' => $id));
  //  dump($logo);
    }

    public function bindweixin()
    {
        $this->display();
    }
}



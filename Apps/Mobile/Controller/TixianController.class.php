<?php
/*require ('../include/init.inc.php');
require ('../mobile/checkDevice.php');

checkDevice();
$member_id =$_SESSION['member_id'];

Template::assign ( 'member_id', $member_id );
Template::display ( 'mobile/about.tpl' );*/

namespace Mobile\Controller;
use Think\Controller;
class TixianController extends CommonController {
    public function tixian(){
        $id=I('get.member_id');
        $model=D('Member');
        $Dmodel=M('Drawing');
        //dump($members);die;
       // $verify = new \Think\Verify();
        $members=$model-> where(array('member_id'=>$id))->field('member_id,balance,alipay,member_name,phone,weixin,nickname')->find();

      /*  $data=I('post.');
        if(IS_POST){
            if(strtolower($data['verify_code']) != strtolower($_SESSION['osa_verify_code'])){
                echo "<script>alert('验证码错误请重新输入！')</script>";
              //  $this->redirect("Ti/tixian");
                //exit;
            }else{
                $now_time=date('Y-m-d',time());
                //插入提现金额

                $members=$model-> where(array('member_id'=>$id))->field('member_id,balance,alipay,member_name,phone,weixin,nickname')->find();

                $data=I('post.');
                //dump($_FILES);//die;
               // dump($data);//die;
                $data['member_id']=$members['member_id'];
                $data['member_name']=$members['member_name'];
                $data['alipy']=$members['alipay'];
                $data['phone']=$members['phone'];
                $data['wd_time']=$now_time;
                $data['status']='1';
                $data['weixin']=$members['weixin'];
                $data['nickname']=$members['nickname'];
                $did = $Dmodel -> add( $data ) ;
              //  dump($did);
                if($did){
                    echo "<script>alert('已提交审核,请注意查看收支明细！')</script>";
                    $balances = $members['balance'] - $data['wd_money'];
                   // dump($balances);
                    $input_date = array('balance' => $balances);
                  //  dump($input_date);//die;
                    $numberr = $model->updateBalance($id,$input_date);
                   // dump($numberr);die;

                    $this->success('Books/books',array('member_id' => $id));
                }else{
                    echo "<script>alert('提交审核失败,请重新提交！')</script>";
                    $this->redirect('Tixian/tixian',array('member_id' => $id));
                }
            }

        }*/



        $this->members=$members;
        $this->display( );
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
        ob_clean();
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
        //echo $verify;die;
        /*$yzm=strtolower($verify);
        $yzm1=strtolower($_SESSION['osa_verify_code']);
        if($yzm==$yzm1)*/
      //  $sj=Drawing::checkYzm($verify);
     /*   if($sj){
            echo 1;die;
        }else{
            echo 0;die;
        }*/
        $yzm=strtolower($verify);
        $yzm1=strtolower($_SESSION['osa_verify_code']);
        if($yzm==$yzm1){
            echo 1;die;
        }else{
            echo 0;die;
        }
    }

     public function TixianAjax(){

         $id=$_POST['id'];
         $money=$_POST['money'];
         $verify=$_POST['verify'];
         $way=$_POST['way'];
         $model=D('Member');
        // $Dmodel=D('Drawing');
         if(strtolower($verify) != strtolower($_SESSION['osa_verify_code'])){
             echo 0;die;
         }else{
             $now_time=date('Y-m-d',time());
             //插入提现金额
             $members=$model-> where(array('member_id'=>$id))->field('member_id,balance,alipay,member_name,phone,weixin,nickname')->find();

            // $data=I('post.');
            // $data['member_id']='1';
             $data['wd_money']=$money;
             $data['wd_way']=$way;
             $data['member_id']=$members['member_id'];
             $data['member_name']=$members['member_name'];
             $data['alipy']=$members['alipay'];
             $data['phone']=$members['phone'];
             $data['wd_time']=$now_time;
             $data['status']='1';
             $data['weixin']=$members['weixin'];
             $data['nickname']=$members['nickname'];
             $did = D('Drawing') -> addDrawing( $data ) ;
            //   dump($data);
             //dump($did);die;
            // echo $did;
             if($did){
                 //dump($did);die;
                 $balances = $members['balance'] - $money;
                 // dump($balances);
                 $input_date = array('balance' => $balances);
                 //  dump($input_date);//die;
                 $numberr = $model->updateBalance($id,$input_date);
                 // dump($numberr);die;
                 echo 1;die;
               //  $this->success('Books/books',array('member_id' => $id));
             }else{
                // dump($did);die;
                 echo 2;die;
                // $this->redirect('Tixian/tixian',array('member_id' => $id));
             }
         }
     }


}
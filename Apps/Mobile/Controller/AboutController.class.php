<?php
/*require ('../include/init.inc.php');
require ('../mobile/checkDevice.php');

checkDevice();
$member_id =$_SESSION['member_id'];

Template::assign ( 'member_id', $member_id );
Template::display ( 'mobile/about.tpl' );*/

namespace Mobile\Controller;
use Think\Controller;
class AboutController extends CommonController {

    public function more(){
        $id=I('get.member_id');
        $this->display( );
    }
    public function about(){

        $this->display( );
    }
    public function busniss(){

        $this->display( );
    }
    public function service(){
        $member_id = session('member_id');
        $memberInfo = D('member')->field('member_id,nickname,phone')->find($member_id);
        $this->assign('memberInfo',$memberInfo);
        $this->display( );
    }
    public function problem(){

        $this->display( );
    }
    public function news(){

        $this->display( );
    }
}
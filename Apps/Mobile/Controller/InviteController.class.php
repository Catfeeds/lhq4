<?php

namespace Mobile\Controller;
use Think\Controller;

class InviteController extends CommonController {
    public function invite(){
        $id=I('get.member_id');
        //累计邀请
        $pid= D('member')->where(array('pid'=>$id))->select();
        $pid = count($pid);
        $this->assign('pid',$pid);
        //累计收益
        $members=D('member')-> where(array('member_id'=>$id))->field('total_invite,qrcode')->find();
        $this->members=$members;
        $this->assign('member_id',$id);
        $this->display( );
    }
    public function cheats(){

        $this->display( );
    }
    //邀请好友详情
    public function inviteInfo(){
        $member_id=I('get.member_id');
        $qrcode = D('member')->field('qrcode')->find($member_id);
        $this->assign('qrcode',$qrcode);
        $this->display();
    }

    //检测有没有openID
//    public function checkOpenid()
//    {
//        $member_id = I('post.mid');
//        $result = D('Member')->getOpenid($member_id);
//    }


}
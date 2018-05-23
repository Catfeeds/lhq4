<?php
namespace Mobile\Controller;
use Think\Controller;
class IndexController extends CommonController {
    public function index(){
        session_start();
        $model=D('Member');
        $msmodel=D('MsgLog');
        $memodel=D('Message');
        $imodel=D('IncomeDetails');
        $member_id=I('get.member_id');
        $ip=getIp();
        #dump($ip);
        if(empty($_SESSION['member_id'])){
            if(is_numeric($member_id)&& $member_id>=1) {
                // $members=$model->getMemberById($member_id);
                $members=$model-> where(array('member_id'=>$member_id))->find();
                if($members['member_id'] != NULL){
                    $model->where(array('member_id' => $members['member_id']))->save(array('login_time' =>date('Y-m-d H:i:s',time()),'ip' =>$ip));
                    $_SESSION['member_id'] = $member_id;
                  // checkDevice();
                }else{
                    $_SESSION['member_id'] = '';
                  // checkDevice();
                }

            }
        }else{
            $member_id = $_SESSION['member_id'];
            $members=$model-> where(array('member_id'=>$member_id))->find();
           // checkDevice();
        }


    /*    if(($members['pic'] == NULL ||$members['pic'] == '')&&$members['sex']=='1'){
            $members['pic']='__PUBLIC__/img/1654509913107329972.jpg';
          //  $members['aa'] = 'pic';
        }elseif(($members['pic'] == NULL ||$members['pic'] == '')&&$members['sex']=='2'){
            $members['pic']='__PUBLIC__/img/a686c9177f3e6709d16cd4d23ac79f3df8dc55aa.jpg';
           // $members['aa'] = 'pic';
        }*/
        //头像问题
      //  dump($members['pic']);die;
        //消息点击数

      //  $list=$msmodel->where(array('member_id'=>$member_id,'status'=>'0'))->select();
        $sub_condition["a.member_id"] =$member_id;
        $sub_condition["a.status"] ='0';
        $sub_condition["m.display"] ='1';
        $list=D('MsgLog')-> alias('a')->field('a.*,m.display')
            ->join('left join message m on a.message_id=m.message_id')
            ->where($sub_condition)
           // ->order("a.message_id desc")
            ->select();

        if($members['click_time']){
            $sl=0;
            foreach ($list as $k => $v) {
                if ($v['create_time'] >$members['click_time']) {
                    ++$sl;
                }
            }
        }else {
            $sl = count($list);
        }
      // dump($sl);
        //公共消息
        $condition["msg_to"] =array(array("eq",$member_id),array("eq",0),'or');
        $condition["display"] ='1';
        $condition['create_time'] =array('lt',date("Y-m-d H:i:s",time()));
      //  dump($condition);
        $mlist=$memodel->where($condition)->select();
        //dump($mlist);die;
        if($members['click_time']){
            $mcount=0;
            foreach ($mlist as $k => $v1) {
                if ($v1['create_time'] >$members['click_time']) {
                    ++$mcount;
                }
            }
        }else {
            $mcount = count($mlist);
        }
      //  dump($mcount);
        $counts=$mcount+$sl;
      //  dump($mcount);DIE;
        //今日收益
        $num=$model-> where(array('pid'=>$member_id))->select();
        $c = 0;
        //var_dump($num);die;
        foreach ($num as $k => $v) {
            if (substr($v['add_time'], 0,10) == date("Y-m-d", time())) {
                $c += $v['invitee_sum'];
            }
        }
        //当天做任务收益
        $taskincome=$imodel->where(array('member_id'=>$member_id))->select();
        $data = 0;
        //var_dump($num);die;
        foreach ($taskincome as $k => $v) {
            if (substr($v['time'], 0, 10) == date("Y-m-d", time())) {
                $data += $v['income'];
            }
            //var_dump($list);die;
        }
    /*    var_dump($taskincome);
        var_dump($data);
        die;*/
    $income=$c+$data;
        //var_dump($members);DIE;
        $this->income=$income;
        $this->counts=$counts;
        $this->members=$members;


        $this->display();
    }
    public function index_index(){
        // echo '2134';
        $this->display( );
    }
    public function task(){
        // echo '2134';
        $id=I('get.member_id');
        $model=M('Mission');
        $this->display( );
    }
    //消息点击
    public function msgClickAjax(){
        $member_id=$_POST['mmid'];
        $type=$_POST['type'];
     /*   dump($member_id);
        dump($type);die;*/
        $list=D('Member')-> getMemberById($member_id);;
        if($type=="2"){
            $MessageCount=D('Message')->count($member_id,$list['click_time']);
            $MsgLogCount=D('MsgLog')->count($member_id,$list['click_time']);
            $counts=$MessageCount+$MsgLogCount;
            echo $counts;die;
        }else if($type=="1"){
            $time=date('Y-m-d H:i:s',time());
            $input_data = array ('click_time'=>$time);
            $id= D('Member')->updateMember($member_id ,$input_data);
        /*    $data['click_time'] =$time;
            $id= D('Member')->where(array('member_id'=>$member_id))->save($data);*/
            if($id=='1'){
                echo 0;die;
            }
        }
      /*  if($type==2){
           // $MessageCount=Message::count($mmid,$list['click_time']);
            $list=$model->where(array('msg_to'=>$mmid,'status'=>'0','display'=>'1'))->select();
            if($member['click_time']){
                $messagecount=0;
                foreach ($list as $k => $v) {
                    if ($v['create_time'] >$member['click_time']) {
                        ++$messagecount;
                    }
                }
            }else {
                $messagecount = count($list);
            }
            //公共消息
            $list=$msmodel->where(array('member_id'=>$mmid,'status'=>'0'))->select();
            if($member['click_time']){
                $MsgLogCount=0;
                foreach ($list as $k => $v) {
                    if ($v['create_time'] >$member['click_time']) {
                        ++$MsgLogCount;
                    }
                }
            }else {
                $MsgLogCount = count($list);
            }
            $counts=$messagecount+$MsgLogCount;
            echo $counts;die;
        }else if($type==1){
            $input_data = array ('click_time'=>date('Y-m-d H:i:s',time()));
            $id= $model->updateMember($mmid ,$input_data);
            echo 0;die;
        }*/
    }

    //判断用户有没有openid
    public function userMsg()
    {
        $member_id = I('post.mid');
        if (!empty($member_id)) {
            $openid = D('Member')->getOpenid($member_id);
            $openid = $openid['openid'];
            if ($openid) {
                echo '1';
            } else {
                echo '2';
            }
        }
    }
}
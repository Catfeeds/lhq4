<?php
/*require ('../include/init.inc.php');
require ('../mobile/checkDevice.php');

checkDevice();
$member_id =$_SESSION['member_id'];

Template::assign ( 'member_id', $member_id );
Template::display ( 'mobile/about.tpl' );*/

namespace Mobile\Controller;
use Think\Controller;
class BooksController extends CommonController {
    public function books(){
        $id=I('get.member_id');
        //查询所做任务
       // $imodel=D('IncomeDetails');
        $dmodel=D('Drawing');
        $list=D('IncomeDetails')->alias('i')->field('i.*,a.img,m.app_id,m.mission_name,m.price')->
        join('left join mission m on i.mission_id=m.mission_id')
            ->join('left join app a on a.app_id=m.app_id')
            ->where(array('income_type'=>'1','member_id'=>$id))
            ->order("income_id desc")
            ->select();
       // dump($list);die;
        //获取收徒数
 /*       $lists=D('IncomeDetails')->alias('b')->field('b.*,a.pic')->
        join('left join member a on b.invited_id=a.member_id')
            ->where(array('b.income_type'=>'2','b.member_id'=>$id))
            ->order("income_id desc")
            ->select();*/
        //提现
        $tixian=$dmodel->where(array('member_id'=>$id))->order("id desc")->select();
        //获取当天邀请人信息
  /*      $mlist=D('Member')->getMemberByMid($id);
      //  foreach ($mlist as $value){
        foreach ($mlist as $key=>$value){
          // $mid=$value['member_id'];
            $mid  =$value['member_id' ];
            $count=D('IncomeDetails')->countDetailByInviteId($mid);
                //  dump($count);
            $mlist[$key]['count']=$count[0]['count']*0.5;
        }*/

//查询个人总收益
//邀请时间
        $time=D('Member')->getMemberByMemberId($id);
       // dump($time);
        //截取处理
        foreach($time as $key=>$val){
            $addtime[]=substr($val['add_time'], 0, 10);
        }
       // 时间去重
        $time= array_unique($addtime);
        $arr=array();
        foreach($time as $key1=>$val) {

            $start_time = ($val . " 00:00:00");
            $end_time = ($val . " 23:59:59");
            //查找时间区间内的所有数据
            $lists = D('Member')->getMemberBytime($start_time, $end_time,$id);
           // dump($lists);
            foreach ($lists as $key2 => $value)
            {   //  dump($value);
                $mid = $value['member_id'];
                 //dump($mid);
                $count = D('IncomeDetails')->countDetailByInviteId($mid);
                //  dump($count);
               $lists[$key2]['money'] = $count[0]['count'] * 0.5;

            }
            $arr[]= $lists;
          // dump($arr);

        }
// dump($arr); die;
        $this->list=$list;
       // $this->lists=$lists;
        $this->tixian=$tixian;
      //  $this->mlist=$mlist;
        $this->time=$time;
        $this->lists=$lists;
        $this->arr=$arr;
       // $this->counts=$counts;
        $this->display( );
    }

    public function Detailed(){
        $id=I('get.invited_id');
        $mid=I('get.member_id');
        $name=D('Mission')->getMissionsArray();
        $img=D('App')->getAppimgArray();
        $appIds=D('Mission')->getappIdArray();
        $list=D('IncomeDetails')->getDetailByInviteId($id);
       // dump($id);
       // dump($mid);
        $this->appIds=$appIds;
        $this->name=$name;
        $this->img=$img;
        $this->list=$list;
      //  $this->mid=$mid;
        $this->display( );
    }

}
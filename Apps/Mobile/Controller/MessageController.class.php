<?php
namespace Mobile\Controller;
use Think\Controller;
class MessageController extends CommonController {
    public function message(){
        $id=I('get.member_id');

        $condition["msg_to"] =array(array("eq",$id),array("eq",0),'or');
        //$condition['member_id'] = $id;
        //$condition['_logic'] = 'OR';
        $subcondition["m.member_id"] =$id;
        $subcondition["a.msg_to"] ='-1';
        $condition['_complex'] = $subcondition;
        $condition['_logic'] = 'OR';
      //  dump($condition);
        $array['_complex']=$condition;
        $array['a.create_time'] =array('lt',date("Y-m-d H:i:s",time()));// array('between',array(date("Y-m-d H:i:s",time()),date("Y-m-d H:i:s",time()+3600*24*365)));
        $array['a.display']  = 1;
        $array["m_type"] =array(array("eq",3),array("eq",4),array("eq",5),array("eq",6),array("eq",7),'or');//01
       // $map['_complex'] = $condition;
        //dump($array);
        $msg_to=D('message')-> alias('a')->field('a.*,m.member_id,m.status')
            ->join('left join msg_log m on a.message_id=m.message_id')
            ->where($array)
            ->order("a.message_id desc")
            ->group('a.message_id')
            ->select();
       //
       // dump($msg_to);die;
        $this->msg_to=$msg_to;
//新闻公告23
        $array["m_type"] =array(array("eq",1),array("eq",2),'or');
        // $map['_complex'] = $condition;
        $news=D('message')-> alias('a')->field('a.*,m.member_id,m.status')
            ->join('left join msg_log m on a.message_id=m.message_id')
            ->where($array)
            ->order("a.message_id desc")
            ->group('a.message_id')
            ->select();
       // dump($news);die;
        $this->news=$news;

        $status=D('MsgLog')->getStatusdArray($id);

        //dump($status);die;
        $this ->assign('msg_to',$msg_to);
        $this ->assign('news',$news);
        $this ->assign('status',$status);
        $this ->assign('member_id',$id);
     //  dump($msg_to);//
        //die;
        $this->display();
    }
    public function information(){
       $message_id=I('get.message_id');
        $message_information=D('message')-> where(array('message_id'=>$message_id))->order("message_id desc")->find();
        $this->message_information=$message_information;
        $this->display();
    }
}

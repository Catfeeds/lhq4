<?php
namespace Backend\Controller;
use Think\Controller;
use Common\Common\UserSession;
class FinanceController extends ComController {
    public function drawings(){
        $mids = $method = $id= $member_id= $start= $page_no = $start_date = $end_date = $status ='';
        extract ( $_GET, EXTR_IF_EXISTS );
      /*  if($start_date != "" && $end_date != "") {
            $start_time = ($start_date." 00:00:00");
            $end_time = ($end_date." 23:59:59");
        }*/
/*        $id='82';
        $Id=D('Drawing')->getData($id);
        $mid=D('Drawing')->selectMid($id);

        $money=D('Member')->SelectMoney($mid['member_id']);
dump($money);
       die;*/
        $model=D('Drawing');
        $data =D('Drawing')-> search($start_date,$end_date,$member_id,$status);
        //判断是否是导出数据
        if (I('get.export')) {
            $this->export();
        }
        $this->assign('draws', $data['data']);
        $this->assign('page', $data['page']);
        $this->assign('data_total',$data['data_total']);
        $this->assign('_GET', $_GET );
        $this->display();
    }
    //导出数据
    public function export(){
        $start_date = I('get.start_date');
        $end_date = I('get.end_date');
        $status = I('get.status');
        $member_id = I('get.member_id');
        $mids = I('get.mids');
        if (mids != "") {
            $where['id']=array("in",$mids);
        }
        if ($member_id != "") {
            $where['member_id'] = $member_id;
        }
        if ($start_date == '' && $end_date != '') {
            $where['wd_time'] = array('elt',$end_date);
        }
        if ($start_date != '' && $end_date == '') {
            $where['wd_time'] = array('egt',$start_date);;
        }
        if ($start_date != '' && $end_date != '') {
           $where["wd_time"] = array(array('egt',$start_date),array('elt',$end_date));
        }
        if ($status != "") {
            $where["status"] = $status;
        }
        $data = D('drawing')->order("id desc")->where($where)->select();                       
        $statusArray = array('','待审核','审核成功','审核失败'); 
        $wd_way = array('','微信','支付宝','手机话费');

        header("Content-Type: application/force-download");
        header("Content-Disposition: attachment; filename=\""."提现列表".date('YmdHis') .".csv\"");

        echo iconv('utf-8', 'gb2312', '"ID","用户ID","用户昵称","微信号","手机号","支付宝号","真实姓名","提现金额","提现方式","提现时间","状态"');
        echo "\r\n";
        foreach($data as $v){
            echo '"';
            echo iconv('utf-8', 'gb2312', implode('","', array(
                    $v['id'],
                    "\t". $v['member_id'],
                    "\t".$v['nickname'],
                    "\t". $v['weixin'],
                    "\t". $v['phone'],
                    "\t". $v['alipy'],
                    "\t". $v['member_name'],
                    "\t". $v['wd_money'], 
                    @$wd_way[$v['wd_way']],
                    "\t". $v['wd_time'],
                    $statusArray[$v['status']],
            )));
            echo "\"\r\n";
        }

        die;

    }
    //审核
    public function drawingAjax(){
        $user_info = UserSession::getSessionInfo();
        $id=$_POST['mid'];//提现id
        $val=$_POST['val'];
       // $sj=D('Drawing')->getDrawById($id,$val);//更改审核状态*/
        if($val=='1'){
            $tj=array('status'=>'2');
        }else if($val=='2'){
            //$tj['status']='1';
            $tj=array('status'=>'3');
        }
        $sj=D('Drawing')->where(array('id'=>$id))->save($tj);

        //更新提现金额
        if($sj){
            $Id=D('Drawing')->getData($id);//获取状态

            if($Id['status']=='2'){
                //提现成功
                $mid=D('Drawing')->selectMid($id);
                $money=D('Member')->SelectMoney($mid['member_id']);
                $wd=$money['total_wd']+$Id['wd_money'];
                // $balanc=$money['balance']-$Id['wd_money'];
                // $input_data = array('total_wd' => $wd,'balance' => $balanc);
                $input_data = array('total_wd' => $wd);
                $id = D('Member')->updateMember($mid['member_id'], $input_data);
                if($id){
                    $m_name=D('MsgType')->getMsgTypesArray();
                    foreach($m_name as $key=>$value){
                        if($value=='提现成功通知')
                        {
                            $module=D('MsgModule')->getMsgMByTypeid($key);
                            $label=explode('?',$module['m_content']);
                            $start_time=date('Y-m-d H:i:s',time());
                            $in_data = array('title' => $module['m_name'], 'content' => $label[0].$label[1].$label[2], 'msg_from' =>$user_info['user_name'], 'msg_to' => $mid['member_id'],
                                'display' => '1', 'create_time' => $start_time, 'status' => 0,'m_type'=>$module['m_types']);
                            D('Message')->addMessage($in_data);
                        }
                    }

                }

            }else if($Id['status']=='3'){
                $mid=D('Drawing')->selectMid($id);
                $money=D('Member')->SelectMoney($mid['member_id']);
                $balanc=$money['balance']+$Id['wd_money'];
                $input_data = array('balance' => $balanc);
                $id = D('Member')->updateBalance($mid['member_id'], $input_data);
                if($id){
                    $m_name=D('MsgType')->getMsgTypesArray();
                    foreach($m_name as $key=>$value){
                        if($value=='提现失败通知')
                        {
                            $module=D('MsgModule')->getMsgMByTypeid($key);
                            $label=explode('?',$module['m_content']);
                            $start_time=date('Y-m-d H:i:s',time());
                            $in_data = array('title' => $module['m_name'], 'content' => $label[0].$label[1].$label[2], 'msg_from' =>$user_info['user_name'], 'msg_to' => $mid['member_id'],
                                'display' => '1', 'create_time' => $start_time, 'status' => 0,'m_type'=>$module['m_types']);
                            D('Message')->addMessage($in_data);
                        }
                    }

                }
            }
        }

//var_dump($sj);die;
        echo json_encode($sj);//die;
    }
    //财务审核
    public function audit(){
        $method = $id= $account= '';
        extract ( $_GET, EXTR_IF_EXISTS );
      /*  $finances = D('Finance')->getFinances();
        $this->assign ('finances',$finances);*/
        $data =D('Finance')-> search($account);
        $this->assign('finances', $data['data']);
        $this->assign('page', $data['page']);
       // $this->assign ('lists',$lists);
        $this->display();
    }
    //财务审核ajax
    public function statusAjax2(){
        $id=$_POST['mid'];
        $val=$_POST['val'];
        //var_dump($val);
        $sj=D('Finance')->getFinById($id,$val);
//var_dump($sj);die;
        echo json_encode($sj);die;
    }
    //财务列表
    public function financeList (){
        //$members =D('Member')->getMembers();
       // $this->assign ('members',$members);
        $method = $user_num =  $nickname = '';
        extract ( $_GET, EXTR_IF_EXISTS );
      //  dump($user_num);
        $data =D('Member')-> search_financeList($user_num,$nickname);
        $this->assign('members', $data['data']);
        $this->assign('page', $data['page']);
        // $this->assign ('lists',$lists);
        $this->display();
}
    public function fin_incomeDetails (){

        $method = $member_id =  $page_no = '';
        extract ( $_GET, EXTR_IF_EXISTS );

        $members = D('Member')->getMembersArray();
        $missions =D('Mission')->getMissionsArray();

        $this->assign ('missions',$missions);
        $this->assign ('members',$members);
        $data = D('IncomeDetails')->search($member_id);
        $this->assign('incomes', $data['data']);
        $this->assign('page', $data['page']);

        // $this->assign ('lists',$lists);
        $this->display();
    }
    //广告财务列表
    public function adFinancial (){
        $id=I('get.member_id');
        $app_id=I('get.app_id');



        $missions = D('Mission')->getMissions();
//var_dump($missions);die;
        $moneys = D('Mission')->getmoneysArray();
        $apps = D('App')->getAppsArray();
        $numbers = D('ChannelLog')->getNumberCount($app_id);
     /*   var_dump($missions);
        var_dump($numbers);die;*/
        $adids = D('App')->getAdtypeIdArray();
        $this->assign('missions',$missions);
        $this->assign ('moneys',$moneys);
        $this->assign ('apps',$apps);
       // var_dump($missions);die;
        $data = D('Mission')->search();
        $this->assign ('numbers',$numbers);
        $this->assign ('adids',$adids);
        $this->assign('missions', $data['data']);
        $this->assign('page', $data['page']);
        // $this->assign ('lists',$lists);
        $this->display();
    }
    //财务列表详情明显
    public function adFinancialList (){
        $app_id=I('get.app_id');
        $adtype_id=I('get.adtype_id');
      /*  dump($app_id);
        dump($adtype_id);*/
        if($adtype_id=='1'){
            $data= D('ProviderLog')->search_log($app_id);

        }else if($adtype_id=='3'){
            $data= D('ChannelLog')->search_log($app_id);
        }
        $apps =  D('App')->getAppsArray();
        $channels = D('Channel')->getChannelsArray();
        $amounts =D('Mission')->getAmountsArray();
        $prices = D('Mission')->getPricesArray();
       // $datas = ChannelLog::getLists($app_id);
        $this->assign('datas', $data['data']);
        $this->assign('page', $data['page']);
       // $this->assign ('datas',$datas);
        $this->assign('apps',$apps);
        $this->assign('channels',$channels);
        $this->assign ('amounts',$amounts);
        $this->assign('prices',$prices);
   
        $this->display();
    }
    //提现列表 支付宝打款用户信息
    function lists(){
        $mids = I('post.mids');
        if (mids != "") {
            $where['id']=array("in",$mids);
        }
        $time1 = date('YmdHis',time());
        $wd_no = '1000000';
        $data = D('drawing')->order("id desc")->where($where)->select();
        foreach ($data as $k => $v) {
            if (empty($v['wd_no'])) {
                $v['wd_no'] = $time1.($wd_no+$v['id']);
                D('drawing')->where(array('id'=>$v['id']))->save(array('wd_no'=>$v['wd_no']));
                $data[$k]['wd_no'] = $v['wd_no'];
            }
        }
        $this->ajaxReturn($data);
    }

}
?>
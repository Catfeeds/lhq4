<?php
namespace Backend\Controller;
use Think\Controller;
use Common\Common\Pagination;
use Api\Controller\UpdateMemberdata;
use Common\Common\UserSession;
class MessageController extends ComController{
    public function message(){

        $method = $message_id = $msg_from= $title = $start= $page_no = $start_time=$end_time=
            $start_date=$end_date= $page_size=$config= '';
        extract ( $_GET, EXTR_IF_EXISTS );
//dump($_GET);die;
        if ($method == 'del' && ! empty ( $message_id )) {
            $message = D('Message')->getMessageById($message_id);
          //  dump($msgTypes);//die;
            if(intval($message_id) <= 0){
                $this->alert("error",'系统菜单不能被删除');//\ErrorMessage::CAN_NOT_DELETE_SYSTEM_MENU
                // error('参数不正确', U('Backend/Adtype/adType'),1);
                //OSAdmin::alert("error",ErrorMessage::CAN_NOT_DELETE_SYSTEM_MENU);
            }else{
                $result = D('Message')->delMessage ( $message_id );
                $list=D('MsgLog')->getMsgLog($message_id);
              //  dump($list);
                foreach($list as $key=>$value){
                    $resultLog=D('MsgLog')->delMsgLogByMessage_id($value['message_id']);
                }
                if ($result) {
                    D('SysLog')->addLog ( UserSession::getUserName(), 'DELETE', '配置信息' ,$message_id, json_encode($message) );
                    $this->  exitWithSuccess ('已将配置信息删除',U('Backend/Message/message'), 1);
                }else{
                    $this-> alert("error");
                    // OSAdmin::alert("error");
                }
            }
        }


         //START 数据库查询及分页数据

        if($start_time != '' && $end_time !=''){
          //  $row_count =Message::getCountByDate($msg_from,$title,$start_time,$end_time);
         	$row_count= D('Message')->getCountByDate($msg_from,$title,$start_time,$end_time);
           // var_dump($row_count);
        }else{
           // $row_count = Message::counts ($msg_from,$title);
            $row_count= D('Message')->counts($msg_from,$title);
          // var_dump($row_count);
        }
		//END
		$apps = D('App')->getAppsArray();
        $name= D('Member')->getMembernameArray( );
		// 显示分页栏
        $page_size = 25;//PAGE_SIZE;
        $page_no=$page_no<1?1:$page_no;
        $total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
        $total_page=$total_page<1?1:$total_page;
        $page_no=$page_no>($total_page)?($total_page):$page_no;
        $start = ($page_no - 1) * $page_size;
        $page_html=Pagination::showPager("message?msg_from=$msg_from&title=$title&start_time=$start_time&end_time=$end_time",$page_no,PAGE_SIZE,$row_count);

        $messages= D('Message')->getLogs($msg_from,$title, $start, $page_size, $start_time, $end_time );
//dump($messages);
        $this->assign ( 'page_no', $page_no );
        $this->assign ( 'page_size', PAGE_SIZE );
        $this->assign ( 'row_count', $row_count );
        $this->assign ( 'page_html', $page_html );

        $this->assign('messages',$messages);
        $this->assign ('name',$name);

		$this->assign('apps', $apps);
		$this->assign ( 'config', $config );
		$this->display ();
	}
    public function message_add()
    {
        $message_id = $title = $content = $msg_from = $msg_to = $display = $m_type='';
        extract ( $_POST, EXTR_IF_EXISTS );

        if (IS_POST ) {
            if ($msg_to == '-1') {
              //  echo 1;
                $ctime = date('Y-m-d H:i:s', time());
                $input_data = array('title' => $title, 'content' => $content, 'msg_from' => $msg_from, 'msg_to' => $msg_to,
                    'display' => $display, 'create_time' => $ctime, 'status' => 0, 'm_type' => $m_type);
//die;
//                $message_id = Message::addMessage( $input_data );
                $message_id = D('Message')->addMessage($input_data);
              //  var_dump($message_id);die;
                if ($msg_to == '-1') {
                    //   Common::exitWithSuccess ('配置信息添加完成','Backend/Message/msg_add?message_id=$message_id');
                    $this->exitWithSuccess('请选择要发送的用户', U('Backend/Message/msg_add',array('message_id' => $message_id)), 1);
                } else {
                    if ($message_id) {
                        D('SysLog')->addLog ( UserSession::getUserName(), 'ADD', '配置信息' ,$message_id, json_encode($input_data) );

                        $this->exitWithSuccess ('用户选择完成',U('Backend/Message/message'), 1);
                      //  $this->success('配置信息添加完成', U('Backend/Message/message'), 1);
                    }
                }
            } else{
              //  die;
//                $memId=Member::selectId($msg_to);
                if($msg_to=='0'){
                    $ctime = date('Y-m-d H:i:s', time());
                    $input_data = array('title' => $title, 'content' => $content, 'msg_from' => $msg_from, 'msg_to' => $msg_to,
                        'display' => $display, 'create_time' => $ctime, 'status' => 0 ,'m_type' => $m_type);

                    //    $message_id = Message::addMessage( $input_data );
                    $message_id = D('Message')->addMessage($input_data);
                    //var_dump($message_id);die;
                        if ($message_id) {
                            D('SysLog')->addLog ( UserSession::getUserName(), 'ADD', '配置信息' ,$message_id, json_encode($input_data) );
                            $this->exitWithSuccess ('配置信息添加完成',U('Backend/Message/message'), 1);
                            //  $this->success('配置信息添加完成', U('Backend/Message/message'), 1);
                        }
                   // }
                }else{
                $memId = D('Member')->selectId($msg_to);
                //var_dump($memId);die;
                if (empty($memId)) {
                    //  OSAdmin::alert("error",ErrorMessage::MEMBER_NOT_EXIST);
                    $this->error('用户不存在', U('Backend/Message/message'), 1);
                } else {
                    $ctime = date('Y-m-d H:i:s', time());
                    $input_data = array('title' => $title, 'content' => $content, 'msg_from' => $msg_from, 'msg_to' => $msg_to,
                        'display' => $display, 'create_time' => $ctime, 'status' => 0 ,'m_type' => $m_type);

                    //    $message_id = Message::addMessage( $input_data );
                    $message_id = D('Message')->addMessage($input_data);
                    //var_dump($message_id);die;
                /*    if ($msg_to == '0') {
                        $this-> exitWithSuccess ('配置信息添加完成',U('Backend/Message/msg_add'), 1);
                       // $this->success('配置信息添加完成', U('Backend/Message/msg_add'), 1);
                    } else {*/
                        if ($message_id) {
                            D('SysLog')->addLog ( UserSession::getUserName(), 'ADD', '配置信息' ,$message_id, json_encode($input_data) );
                            $this->exitWithSuccess ('配置信息添加完成',U('Backend/Message/message'), 1);
                          //  $this->success('配置信息添加完成', U('Backend/Message/message'), 1);
                        }
                   // }
                }
                }
            }
//        }
//        Template::assign("_POST" ,$_POST);
        }
        $msgtypes =D('MsgType')->getMsgTypesArray();
        //  dump($msgtypes);
        $this->assign("msgtype" ,$msgtypes);
            $this->display();

    }
    public function message_modify(){
        $message_id = $title = $content = $msg_from= $msg_to = $display = $create_time =$m_type= '';
        extract ( $_REQUEST, EXTR_IF_EXISTS );
//dump($_REQUEST);//die;
        $message= D('Message')->getMessageById($message_id);
        if(empty($message)){
            $this->exitWithError(ErrorMessage::SAMPLE_NOT_EXIST,U('Backend/Message/message'),1);
             // $this->error('已存在，请不要重复添加', U('Backend/Message/message'),1);
        }

        if (IS_POST) {
            $update_data = array ('title' => $title,'content'=>$content,'msg_from'=>$msg_from,'msg_to'=>$msg_to,
                'display' => $display,'create_time' => $create_time,'m_type' => $m_type);
           // var_dump($update_data);die;
           // $result = Message::updateMessage($message_id,$update_data);
            $result= D('Message')->updateMessage($message_id,$update_data);
            if ($result>=0) {
                D('SysLog')->addLog ( UserSession::getUserName(), 'MODIFY', '配置信息' ,$message_id, json_encode($update_data) );
                $this->exitWithSuccess ( '配置信息修改完成',U('Backend/Message/message'),1 );
              //  $this->success('配置信息添加完成', U('Backend/Message/message'),1);
            } else {
                $this->alert("error");
              //  $this->success('配置信息添加完成', U('Backend/Message/message'),1);

            }

        }
     //   die;
        $msgtypes =D('MsgType')->getMsgTypesArray();
        //  dump($msgtypes);
        $this->assign("msgtype" ,$msgtypes);
      //  Template::assign("message" ,$message);
        $this->assign('message',$message);
        $this->display ();
    }
    public function message_shows(){
        $member_id=$nickname=$message=$time_stamp=$reply=$time_reply=$state=$id=
        $search ='';
        extract ( $_GET, EXTR_IF_EXISTS );
            $row_count = D('Customer')->search($nickname,$search);
            $this->assign('Customers',$row_count['data']);
            $this->assign('_GET', $_GET  );
            $this->assign('row_count', $row_count );
            $this->assign('page', $row_count['page']);
            $this->display ();
    }

    public function mem_mis( ){

        $method =  $mission_name= $nickname= $status= $start= $page_no = $start_date = $end_date ='';
        extract ( $_GET, EXTR_IF_EXISTS );
        /*Common::checkParam($id);*/
        if($status =="0") {
            $_GET["status"] = "";
            $status = "";
        }
        if($_GET){
            if ($mission_name != '' || $nickname != '') {
                    $memberName = D('Member')-> SelectMemberName($nickname);
                    $mName=array();
                    foreach ($memberName as $k => $v) {
                        foreach ($v as $key => $value) {
                            $mName[] = $value;
                        }
                    }
                    $member_id = $mName;
//            $missionName = Mission::SelectMissionByName($mission_name);
                    $missionName = D('Mission')-> SelectMissionByName($mission_name);
                    // var_dump($memberName);die;
                    $missName=array();
                    foreach ($missionName as $k => $v) {
                        foreach ($v as $key => $value) {
                            $missName[] = $value;
                        }
                    }
                    $mission_id = $missName;

                if(!empty($member_id) && !empty($mission_id) ){
                    if($start_date != '' && $end_date !=''){
                        $row_count = D('MemMis')-> getCountByDate( $mission_id,$member_id,$status,$start_date,$end_date );
                        //var_dump($row_count);die;
                    }else{
                        $row_count = D('MemMis')->getCount($mission_id,$member_id,$status );
                    }
                    $page_size = PAGE_SIZE;
                    $page_no=$page_no<1?1:$page_no;
                    $total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
                    $total_page=$total_page<1?1:$total_page;
                    $page_no=$page_no>($total_page)?($total_page):$page_no;
                    $start = ($page_no - 1) * $page_size;
//END// 显示分页栏
                    $page_html=Pagination::showPager("mem_mis?mission_name=$mission_name&nickname=$nickname&status=$status&start_date=$start_date&end_date=$end_date",$page_no,PAGE_SIZE,$row_count);
                    // $lists = MemMis::getMms($mission_id,$member_id,$status, $start, $page_size, $start_time, $end_time);
                    $lists = D('MemMis')->getMms($mission_id,$member_id,$status, $start,$page_size, $start_date, $end_date);

                }else{

                    $row_count =0;
                    $page_size = PAGE_SIZE;
                    $page_no=$page_no<1?1:$page_no;
                    $total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
                    $total_page=$total_page<1?1:$total_page;
                    $page_no=$page_no>($total_page)?($total_page):$page_no;
                    $start = ($page_no - 1) * $page_size;
                    $page_html=Pagination::showPager("mem_mis?mission_name=$mission_name&nickname=$nickname&status=$status&start_date=$start_date&end_date=$end_date",$page_no,PAGE_SIZE,$row_count);
                    // $lists = D('MemMis')->getsMms($status, $start,$page_size, $start_date, $end_date);
                }
            }else{
                echo 4;
                   if($start_date != '' && $end_date !=''){
                       $row_count = D('MemMis')-> getCountByDateLog($status,$start_date,$end_date );
                       //var_dump($row_count);die;
                   }else{
                       $row_count = D('MemMis')->getCountLog($status );
                   }

                $page_size = PAGE_SIZE;
                $page_no=$page_no<1?1:$page_no;
                $total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
                $total_page=$total_page<1?1:$total_page;
                $page_no=$page_no>($total_page)?($total_page):$page_no;
                $start = ($page_no - 1) * $page_size;
                $page_html=Pagination::showPager("mem_mis?status=$status&start_date=$start_date&end_date=$end_date",$page_no,PAGE_SIZE,$row_count);
                 $lists = D('MemMis')->getsMms($status, $start,$page_size, $start_date, $end_date);
            }

        }else{
            $row_count = D('MemMis')->CountgetMmsLog();
//dump($row_count);
            $page_size = PAGE_SIZE;
            $page_no=$page_no<1?1:$page_no;
            $total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
            $total_page=$total_page<1?1:$total_page;
            $page_no=$page_no>($total_page)?($total_page):$page_no;

            $start = ($page_no - 1) * $page_size;
            $page_html=Pagination::showPager("mem_mis?mission_name=$mission_name&nickname=$nickname&status=$status&start_date=$start_date&end_date=$end_date",$page_no,PAGE_SIZE,$row_count);
            $lists = D('MemMis')->getMmsLog($start, $page_size);
        }

//die;

        $memberName = D('Member')-> getMembersNicknameArray( );
        $missionName = D('Mission')-> getMissionsArray( );
        $app_AdtypeId = D('App')-> getAdtypeIdArray( );
        $mission_appId = D('Mission')-> getMissionId( );

        $data = array();

        foreach ($mission_appId as $k => $v) {
            foreach ($app_AdtypeId as $key => $value) {
                if ($k == $key) {
                    $data [$v] = $value;
                }
            }
        }

//将ad_typeid 赋值给数组$lists
        foreach ($lists as $key => $value) {
            foreach ($data as $k => $v) {
                if ($value['mission_id'] == $k)
                    $lists[$key]['adtype_id'] = $v;
            }
        }
        $this->assign ( 'page_no', $page_no );
        $this->assign ( 'page_size', PAGE_SIZE );
        $this->assign ( 'row_count', $row_count );
        $this->assign ( 'page_html', $page_html );

        $this->assign( 'row_count', $row_count );
        $this->assign( 'lists', $lists );
        $this->assign( 'memberName', $memberName );
        $this->assign( 'missionName', $missionName );

        $this->display ();
    }


    public function reply( ){
        vendor('Wechat.wechat#class',"",'.php');
        $id=$reply=$time_reply=$reply_status=$openid=$weixin='';
        extract ( $_REQUEST, EXTR_IF_EXISTS );
//$id=
//        $Customer = Customer::getCustomerById($id);
//        $openid=Customer::getCustomerByOpenid($id);
         $Customer = D('Customer')-> getCustomerById($id);
         $openid = D('Customer')-> getCustomerByOpenid($id);
// $appid='wx44e75a73a7b50d77';$appsecret='77756ee72d11921a4f561cc5237e7b63';$token='cndiandian';
// $options = array(
//     'token'=>$token, //填写你设定的key
//     'encodingaeskey'=>'KCg2Q3xQZ6m2peNSjNUEUAZIZtMs1b3v3zvBUzF3FwL', //填写加密用的EncodingAESKey，如接口为明文模式可忽略
//     'appid'=>$appid, //填写高级调用功能的app id
//     'appsecret'=>$appsecret,//填写高级调用功能的密钥
// );

        $weObj = new \Wechat(C('WX_LIST'));
        $access_token=$weObj->checkAuth(C('WX_LIST.appid'),C('WX_LIST.appsecret'));
        if(empty($Customer)){
//            Common::exitWithError(ErrorMessage::SAMPLE_NOT_EXIST,"backend/message_shows.php");
            $this->error('已存在，请不要重复添加', U('Backend/Message/message_shows'),1);
        }
        if (IS_POST) {
            if($reply =="" ){
//                OSAdmin::alert("error",ErrorMessage::NEED_PARAM);
                $this->error('参数不正确', U('Backend/Message/message_shows'),1);
            }else{
                $time_reply=date('Y-m-d H:i:s',time());
                $text=array( "content"=>$reply);
                $data =array('touser'=>$openid[0]['openid'],'msgtype'=>'text','text'=>$text);
//        var_dump($weObj->sendCustomMessage($data));
                $weixin=$weObj->sendCustomMessage($data);
//        $weixin=1;
                $update_data = array ('reply'=>$reply,'time_reply'=>$time_reply,'reply_status'=>'1');
//                $results = Customer::updateCustomerInfo($id,$update_data);
                $results = D('Customer')-> updateCustomerInfo($id,$update_data);
//        $results=-1;
                if ($results>=0 && $weixin==0) {
                    D('SysLog')->addLog ( UserSession::getUserName(), 'MODIFY', '客服回复' ,$id, json_encode($update_data) );
                    $this->exitWithSuccess ( '回复成功', U('Backend/Message/message_shows'),1 );
 //                   $this->success('回复成功', U('Backend/Message/message_shows'),1);
                } else {
                    if(!$weixin=0){
                        $this->exitWithSuccess ( '微信回复失败，请回复文本信息',U('Backend/Message/message_shows'),1 );
                     //   $this->error('微信回复失败，请回复文本信息', U('Backend/Message/message_shows'),1);
                    }else{
                        $this->exitWithSuccess ( '数据库存储失败，请重试',U('Backend/Message/message_shows'),1 );
                     ///   $this->error('数据库存储失败，请重试', U('Backend/Message/message_shows'),1);
                    }

                }
            }
        }
        //Template::assign ( 'Customer', $Customer );
        $this->assign( 'Customer', $Customer );
        $this->display ();
    }
    public function misStatusAjax(){
        $id=$_POST['mid'];
        $val=$_POST['val'];
        $msid=$_POST['msid'];

      //  $sj=D('MemMis')->StatusChanges($id,$val);
        if($val=='2'){
            $tj=array('status'=>'1');
        }else if($val=='3'){
            //$tj['status']='1';
            $tj=array('status'=>'6');
        }

        $sj=D('MemMis')->where(array('id'=>$id))->save($tj);
        $Id= D('MemMis')-> getmisId($id);
        if($Id['status']=='1'){
            // Mission::updateRuNum($msid);
            //$meid=D('MemMis')->selectMid($msid);
            UpdateMemberdata($Id['member_id'],$msid);

        }else if($Id['status']=='6'){
            $Detailid= D('TaskDetails')->updateMsidMeid($Id['member_id'],$msid);
           D('Mission')->updateRuNumAdd($msid);     //审核失败后将剩余量加1
            // $update_data = array ('status' => 6, 'start_time' => $nowTime);
        }
    }

    public function msg_add(){
        $mid = $message_id= $member_id=$user_num=$phone=$find=$st=$end= $page_no =$search= '';
        extract ( $_POST, EXTR_IF_EXISTS );
        extract ( $_GET, EXTR_IF_EXISTS );
/*var_dump($_POST);
        var_dump($_GET);die;*/
        if (IS_POST) {

            $msgTo='-1';
            $msg = D('Message')->getNewMsg($msgTo);
          //  dump($msg);die;
            $ctime=date('Y-m-d H:i:s',time());
            if($search=='1'){
                for($i=$st;$i<=$end;$i++) {
                    //var_dump($i);//die;
                    $list =D('MsgLog')->SelectMsg($i,$msg['message_id']);
                    if(empty($list)){
                        $input_data = array ('member_id' => $i, 'status' => '0','message_id' =>$msg['message_id'], 'create_time' => $ctime );
                        $id = D('MsgLog')->addMsgLog( $input_data );
                    }
                }
            }else {
                foreach ($mid as $k => $v) {
                    $list = D('MsgLog')->SelectMsg($i,$msg['message_id']);
                    //var_dump($list);//die;
                    if(empty($list)){
                        $input_data = array ('member_id' => $v, 'status' => '0','message_id' =>$msg['message_id'], 'create_time' => $ctime );
                        $id = D('MsgLog')->addMsgLog( $input_data );
                    }
                }
            }

            if ($id) {
                D('SysLog')->addLog ( UserSession::getUserName(), 'ADD', '消息通知' ,$id, json_encode($input_data) );
                $this->exitWithSuccess ('消息发送人选择完成',U('Backend/Message/message'),1 );
            }

        }
//检索部分
        $page_size = 25;
        $page_no=$page_no<1?1:$page_no;
        if($find){
            $row_count =D('Member')->countSearch($member_id, $phone, $user_num);
            $total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
            $total_page=$total_page<1?1:$total_page;
            $page_no=$page_no>($total_page)?($total_page):$page_no;
            $start = ($page_no - 1) * $page_size;
            //echo $provider_id;die;
            $members = D('Member')->searchmember($member_id, $phone, $user_num,$start , $page_size);
            //var_dump($apps);//die;
        }else{
            $row_count =D('Member')->countSearch($member_id, $phone, $user_num);
            $total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
            $total_page=$total_page<1?1:$total_page;
            $page_no=$page_no>($total_page)?($total_page):$page_no;
            $start = ($page_no - 1) * $page_size;
            $members = D('Member')->getAllMember ( $start , $page_size );
            // var_dump($row_count);
        }
//var_dump($row_count);die;

//END
// 显示分页栏

        $page_html=Pagination::showPager("msg_add?user_num=$user_num&phone=$phone&member_id=$member_id",$page_no,PAGE_SIZE,$row_count);
        $this->assign ( 'page_no', $page_no );
        $this->assign ( 'page_size', PAGE_SIZE );
        $this->assign ( 'row_count', $row_count );
        $this->assign ( 'page_html', $page_html );
        $this->assign ( '_GET', $_GET );
//var_dump($members);die;
        $this->assign("_POST" ,$_POST);
        $this->assign("members" ,$members);
      //  $this->display('backend/msg_add.tpl' );
        $this->display();
    }

    //消息模板区域：
    public function msgModule(){
        $method = $id = $type_name = $page_no = '';
        extract ( $_GET, EXTR_IF_EXISTS );
        if ($method == 'del' && ! empty ( $id )) {
            $msgTypes = D('MsgModule')->getMsgById($id);
            if(intval($id) <= 0){
                $this->alert("error",'系统菜单不能被删除');//\ErrorMessage::CAN_NOT_DELETE_SYSTEM_MENU
                // error('参数不正确', U('Backend/Adtype/adType'),1);
                //OSAdmin::alert("error",ErrorMessage::CAN_NOT_DELETE_SYSTEM_MENU);
            }else{
                $result = D('MsgModule')->delMsgModule ( $id );
                //var_dump($result);//die;
                if ($result) {
                    D('SysLog')->addLog ( UserSession::getUserName(), 'DELETE', '配置信息' ,$id, json_encode($msgTypes) );
                    $this->  exitWithSuccess ('已将配置信息删除',U('Backend/Message/msgModule'), 1);
                }else{
                    $this-> alert("error");
                    // OSAdmin::alert("error");
                }
            }
        }
        $data = D('MsgModule')->getMsgModule();

        $this->assign ( '_GET', $_GET );
        $this->assign('msgModule', $data);
       // $this->assign('page', $data['page']);

        $this->display();
    }

    public function msgModule_add(){
        $id = $m_name =  $m_content =$m_types= '';
        extract ( $_POST, EXTR_IF_EXISTS );
        $now_time=date('Y-m-d H:i:s',time());
      //  dump($_POST);die;
        if (IS_POST) {
        /*    $exist = D('MsgType')->getMsgTypeByName($type_name);
            $now_time=time();
            if($exist){
                $this->alert("error",'名称冲突');//ErrorMessage::NAME_CONFLICT
                //error('已存在，请不要重复添加', U('Backend/Adtype/adType'),1);
                //OSAdmin::alert("error",ErrorMessage::NAME_CONFLICT);
            }else{*/
                $input_data = array ('m_name' => $m_name,'m_content' => $m_content,'m_types' => $m_types, 'm_time' => $now_time );
                $id = D('MsgModule')->addMsgModule( $input_data );
                if ($id) {
                    D('SysLog')->addLog ( UserSession::getUserName(), 'ADD', '任务分类' ,$id, json_encode($input_data) );
                    $this->  exitWithSuccess ('配置信息添加完成',U('Backend/Message/msgModule'),1);
                }
            //}
        }
        $msgtypes =D('MsgType')->getMsgTypesArray();
//dump($msgtypes);
        $this->assign("_POST" ,$_POST);
        $this->assign("msgtype" ,$msgtypes);
        $this->display();

    }
    public function msgModule_modify(){
        $id = $m_name =  $m_content =$m_types= '';
        extract ( $_REQUEST, EXTR_IF_EXISTS );

        //Common::checkParam($id);
        $Msg = D('MsgModule')->getMsgById($id);
        if(empty($Msg)){
            $this->exitWithError(ErrorMessage::SAMPLE_NOT_EXIST,"Backend/Message/msgModule");//error('缺少参数', U('Backend/Adtype/adType'),1);
            //Common::exitWithError(ErrorMessage::SAMPLE_NOT_EXIST,"backend/adType.php");
        }
        if (IS_POST) {
        $now_time=date('Y-m-d H:i:s',time());
         /*       if($type_name =="" ){
                $this->alert("error",'缺少参数');//error('缺少参数', U('Backend/Adtype/adType'),1);ErrorMessage::NEED_PARAM
                //OSAdmin::alert("error",ErrorMessage::NEED_PARAM);
            }else{*/
                $update_data =  array ('m_name' => $m_name,'m_content' => $m_content,'m_types' => $m_types, 'm_time' => $now_time );
                $result = D('MsgModule')->updateMsgModuleInfo($id, $update_data );
                //var_dump($result);die;Adtype
                if ($result>=0) {
                    D('SysLog')->addLog ( UserSession::getUserName(), 'MODIFY', '配置信息' ,$id, json_encode($update_data) );
                    $this->exitWithSuccess('修改完成', U('Backend/Message/msgModule'),1);
                    //SysLog::addLog ( UserSession::getUserName(), 'MODIFY', '配置信息' ,$id, json_encode($update_data) );
                    //Common::exitWithSuccess ( '配置信息修改完成','backend/adType.php' );
                } else {
                    $this->alert("error");
                    //OSAdmin::alert("error");
                }
          //  }
        }
        $msgtypes =D('MsgType')->getMsgTypesArray();
      //  dump($msgtypes);
        $this->assign("msgtype" ,$msgtypes);
        $this->assign ( 'Msg', $Msg );
        $this->display ();

    }
}
?>
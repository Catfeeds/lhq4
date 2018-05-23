<?php
namespace Backend\Controller;
use Common\Model\UserModel;
use Think\Controller;
use Common\Common\UserSession;
use Common\Common\Pagination;
class ChannelController extends ComController{
    //渠道管理

    public function channel(){
		$method = $channel_id = $channel_name =$page_no= $search='';

		$user_id = UserSession::getUserId();
		$userModel= new UserModel();
		$userinfo = $userModel->getUserById($user_id);
		$userGroupId = $userinfo['user_group'];
		extract ( $_GET, EXTR_IF_EXISTS );
		if ($method == 'del' && ! empty ( $channel_id )) {
			$channels = D('Channel')->getChannelById($channel_id);

			if(intval($channel_id) <= 0){
                $this->lert("error",'参数不正确');//error('参数不正确', U('Backend/Channel/channel'),1);ErrorMessage::CAN_NOT_DELETE_SYSTEM_MENU
				//OSAdmin::alert("error",ErrorMessage::CAN_NOT_DELETE_SYSTEM_MENU);
			}else{
				$result = D('Channel')->delChannel( $channel_id );
				if ($result) {
                    D('SysLog')->addLog ( UserSession::getUserName(), 'DELETE', '配置信息' ,$channel_id, json_encode($channels) );
					$this->exitWithSuccess('已将配置信息删除', U('Backend/Channel/channel'),1);

					//Common::exitWithSuccess ('已将配置信息删除','backend/channel.php');
				}else{
					$this->alert("error");//error('删除失败', U('Backend/Channel/channel'),1);
					//OSAdmin::alert("error");
				}
			}
		}
		$userInfos = $userModel->getAllUsers();
		//var_dump($userInfos);die;
		$page_size = PAGE_SIZE;
		$page_no=$page_no<1?1:$page_no;
		if($search){
		//	var_dump($user_id,$userGroupId);die;
			$row_count =D('Channel')->countSearch($channel_name,$user_id,$userGroupId);
			//var_dump($row_count);die;
			$total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
			$total_page=$total_page<1?1:$total_page;
			$page_no=$page_no>($total_page)?($total_page):$page_no;
			$start = ($page_no - 1) * $page_size;
			$channels = D('Channel')->search($channel_name,$user_id,$userGroupId,$start , $page_size);
			//var_dump($channels);//die;
		}else{
			$row_count =D('Channel')->countSearch($channel_name,$user_id,$userGroupId);
			$total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
			$total_page=$total_page<1?1:$total_page;
			$page_no=$page_no>($total_page)?($total_page):$page_no;
			$start = ($page_no - 1) * $page_size;
			$channels =D('Channel')->getAllChannel ($user_id,$userGroupId,$start , $page_size );
			// var_dump($row_count);die;
			//var_dump($channels);die;
		}
		/*$total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
        $total_page=$total_page<1?1:$total_page;
        $page_no=$page_no>($total_page)?($total_page):$page_no;

        $start = ($page_no - 1) * $page_size;*/
//END
// 显示分页栏

		$page_html=Pagination::showPager("channel.php",$page_no,PAGE_SIZE,$row_count);


	//	$data = D('Channel')->search($channel_name,$search,$user_id,$userGroupId);
		$this->assign( 'page_no', $page_no );
		$this->assign ( 'page_size', PAGE_SIZE );
		$this->assign( 'row_count', $row_count );
		$this->assign ( '_GET', $_GET );
        $this->assign('channels', $channels);
		$this->assign('page_html', $page_html);
		$this->assign('userInfos', $userInfos);
		$this->assign ( 'user_id', $user_id );
		$this->display ();
    }
    //添加渠道
    public function channel_add(){
    	$channel_id =$channel_name= $channel_url = $channel_admin = $channel_introduce= $email = $mobile = '';
		extract ( $_POST, EXTR_IF_EXISTS );
		$user_id=UserSession::getUserId();
		if (IS_POST) {
			$exist = D('Channel')->getChannelByName($channel_name);
			if($exist){
                $this->alert("error",'名称冲突');//error('已存在，请不要重复添加', U('Backend/Channel/channel'),1);ErrorMessage::NAME_CONFLICT
				//OSAdmin::alert("error",ErrorMessage::NAME_CONFLICT);
			}else{
				$input_data = array ('channel_name' => $channel_name, 'channel_url'=>$channel_url,'channel_admin'=>$channel_admin,'channel_introduce'=>$channel_introduce,
		            'mobile' => $mobile, 'email' => $email,'user_id'=>$user_id
		            );
				$channel_id = D('Channel')->addChannel( $input_data );		
				if ($channel_id) {
                    D('SysLog')->addLog ( UserSession::getUserName(), 'ADD', '配置信息' ,$channel_id, json_encode($input_data) );
                    $this->exitWithSuccess('配置信息添加完成', U('Backend/Channel/channel'),1);

					//Common::exitWithSuccess ('配置信息添加完成','backend/channel.php');
				}
			}
		}

		$this->assign("_POST" ,$_POST);
		$this->display();
    }
    //修改渠道信息
    public function channel_modify(){
    	$channel_id = $channel_name =$channel_url = $channel_admin = $channel_introduce= $mobile = $email = $userid= $httpref='';
		extract ( $_REQUEST, EXTR_IF_EXISTS );

		//Common::checkParam($channel_id);

		$channel = D('Channel')->getChannelById($channel_id);
		if(empty($channel)){
            $this->exitWithError(ErrorMessage::SAMPLE_NOT_EXIST,"Backend/Channel/channel");//error('缺少参数', U('Backend/Channel/channel'),1);
		    //Common::exitWithError(ErrorMessage::SAMPLE_NOT_EXIST,"backend/message_shows.php");
		}
		if (IS_POST) {
		    if($channel_name =="" ){
                $this->alert("error",'缺少参数');//error('缺少参数', U('Backend/Channel/channel'),1);ErrorMessage::NEED_PARAM
		        //OSAdmin::alert("error",ErrorMessage::NEED_PARAM);
		    }else{
		        $update_data = array ('channel_name' => $channel_name,'channel_url'=>$channel_url,'channel_admin'=>$channel_admin,'channel_introduce'=>$channel_introduce,
		            'mobile' => $mobile, 'email' => $email,'user_id' => $userid
		        );
		        $result = D('Channel')->updateChannelInfo($channel_id, $update_data );
		        if ($result>0) {
                    $this->exitWithSuccess('配置信息修改完成',$httpref,1);
		            //SysLog::addLog ( UserSession::getUserName(), 'MODIFY', '配置信息' ,$channel_id, json_encode($update_data) );
		            //Common::exitWithSuccess ( '配置信息修改完成','backend/channel.php' );
		        } else {
                    $this->alert("error");//error('配置信息修改失败', U('Backend/Channel/channel'),1);
		            //OSAdmin::alert("error");
		        }
		    }
		}
		$user_id = UserSession::getUserId();
		$userModel= new UserModel();
		$userInfos = $userModel->getAllUsers();
		$this->assign ( 'user_id', $user_id );
		$this->assign ( 'userInfos', $userInfos );
		$this->assign ( 'channel', $channel );
		$this->display ( );
    }

    //渠道应用
    public function channelList(){
    	$method =$channel_id= $app_id = $app_name= $provider_id = $search='';
		extract ( $_GET, EXTR_IF_EXISTS );

		if ($method == 'del' && ! empty ( $app_id )) {
			$app = D('App')->getAppById($app_id);

			if(intval($app_id) <= 0){
                $this->alert("error",'参数不正确');//error('参数不正确', U('Backend/Channel/channelList'),1);
				//OSAdmin::alert("error",ErrorMessage::CAN_NOT_DELETE_SYSTEM_MENU);
			}else{
				$result = D('App')->delApp ( $app_id );
				if ($result) {
                    $this->exitWithSuccess('已将配置信息删除', U('Backend/Channel/channelList'),1);
					//SysLog::addLog ( UserSession::getUserName(), 'DELETE', '配置信息' ,$app_id, json_encode($app) );
					//Common::exitWithSuccess ('已将配置信息删除','backend/app.php');
				}else{
                    $this->alert("error");//error('删除失败', U('Backend/Channel/channelList'),1);
					//OSAdmin::alert("error");
				}
			}
		}
		$data = D('App')->search($channel_id,$app_name,$provider_id,$search);
		$providers = D('Provider')->getProvidersArray();	
		$channels = D('Channel')->getChannelsArray();
        $this->assign('apps', $data['data']);
		$this->assign('page', $data['page']);
		$this->assign ( '_GET', $_GET );
		$this->assign("channels",$channels);
		$this->assign("providers",$providers);
		$this->display ();
    }
}
?>
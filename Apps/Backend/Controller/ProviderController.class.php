<?php
namespace Backend\Controller;
use Common\Model\UserModel;
use Think\Controller;
use Common\Common\UserSession;
use Common\Common\Pagination;
class ProviderController extends ComController{
	//广告商管理
	public function provider(){
		$method = $channel_id = $provider_name = $provider_id = $search=  $page_no='';

		$providers = D('Provider')->getProviders();
//var_dump($providers);die;
		$user_id=UserSession::getUserId();
		$user_group=UserSession::getUserGroup();
		$userModel=new UserModel();
//var_dump($user_group);die;
		$userInfos=$userModel->getAllUsers();
		extract ( $_GET, EXTR_IF_EXISTS );
		$page_size = PAGE_SIZE;
		$page_no=$page_no<1?1:$page_no;

		if($user_group==1||$user_group==5){
			$row_count =D('Provider')->countSearch($provider_name);
			$total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
			$total_page=$total_page<1?1:$total_page;
			$page_no=$page_no>($total_page)?($total_page):$page_no;
			$start = ($page_no - 1) * $page_size;
			if($search){
				$providers = D('Provider')->search($provider_name,$start , $page_size);
				//  var_dump($providers);die;
			}else{
				$providers = D('Provider')->getAllProvider ( $start , $page_size );
				//  var_dump($providers);die;
			}
		}else{
			$row_count =D('Provider')->countSearchByUserid($provider_name,$user_id);
			$total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
			$total_page=$total_page<1?1:$total_page;
			$page_no=$page_no>($total_page)?($total_page):$page_no;
			$start = ($page_no - 1) * $page_size;
			if($search){
				$providers = D('Provider')->searchByUserid($provider_name,$user_id,$start , $page_size);
				// var_dump($providers);die;
			}else{
				$providers = D('Provider')->getAllProviderByUserid ( $user_id,$start , $page_size );
				//var_dump($row_count);
			}

		}

		$page_html=Pagination::showPager("provider.php",$page_no,PAGE_SIZE,$row_count);

		$this->assign ( 'row_count', $row_count );
		$this->assign ( 'page_size', PAGE_SIZE );
		$this->assign( 'page_no', $page_no );
		$this->assign('page_html', $page_html);
		$this->assign ( '_GET', $_GET );
		$this->assign ('providers',$providers);
		$this->assign ('user_id',$user_id);
		$this->assign ('user_group',$user_group);
		$this->assign ('userInfos',$userInfos);
		$this->display ();
	}
    //添加广告商
	public function providerAdd(){
		$provider_id = $provider_name = $email = $mobile = '';
		$user_id=UserSession::getUserId();
		extract ( $_POST, EXTR_IF_EXISTS );
		if (IS_POST) {
			$exist = D('Provider')->getProviderByName($provider_name);
			if($exist){
				//$this->error('已存在，请不要重复添加', U('Backend/Provider/provider'),1);
				$this->alert("error",'名称冲突');//ErrorMessage::NAME_CONFLICT
			}else{
				$input_data = array ('provider_name' => $provider_name, 'mobile' => $mobile, 'email' => $email,'user_id'=>$user_id );
				$provider_id = D('Provider')->addProvider( $input_data );
				
				if ($provider_id) {
					//$this->success('配置信息添加完成', U('Backend/Provider/provider'),1);
					//var_dump(__CONTROLLER__);die;
                    D('SysLog')->addLog ( UserSession::getUserName(), 'ADD', '配置信息' ,$provider_id, json_encode($input_data) );
					$this->exitWithSuccess ('配置信息添加完成',"provider.php");
				}
			}
		}
		$this->assign("_POST" ,$_POST);
		$this->display();
	}
	//广告商编辑
	public function providerModify(){
		$provider_id = $provider_name = $mobile = $email = $httpref='';

		extract ( $_REQUEST, EXTR_IF_EXISTS );
		$user_id=UserSession::getUserId();
		$user_group=UserSession::getUserGroup();
		$useModel=new UserModel();
		$userInfos=$useModel->getAllUsers();
		//Common::checkParam($provider_id);
		$provider = D('Provider')->getProviderById($provider_id);
		if(empty($provider)){
			//$this->error('参数不正确', U('Backend/Provider/provider'),1);		
			$this->exitWithError(ErrorMessage::SAMPLE_NOT_EXIST,"backend/provider.php");
		}
		if (IS_POST) {			
			if($provider_name =="" ){
				//$this->error('缺少参数', U('Backend/Provider/provider'),1);
				$this->alert("error",'缺少参数');//ErrorMessage::NEED_PARAM
			}else{
				$update_data = array ('provider_name' => $provider_name, 'mobile' => $mobile, 'email' => $email,'user_id'=>$_POST['user_id']);
				$result = D('Provider')->updateProviderInfo($provider_id, $update_data );
				
				if ($result>=0) {
					//$this->success('配置信息修改完成', U('Backend/Provider/provider'),1);
                    D('SysLog')->addLog ( UserSession::getUserName(), 'MODIFY', '配置信息' ,$provider_id, json_encode($update_data) );
					$this->exitWithSuccess ( '配置信息修改完成',$httpref,1);
				} else {
					//$this->error('配置信息修改失败', U('Backend/Provider/provider'),1);
					$this->alert("error");
				}
			}
		}
		$this->assign ( 'user_id', $user_id );
		$this->assign ( 'user_group', $user_group );
		$this->assign ( 'userInfos', $userInfos );
		$this->assign ( 'provider', $provider );
		$this->display ();
	}

}
<?php
namespace Backend\Controller;
use Common\Common\OSAdmin;
use Think\Controller;
use Common\Common\Common;
use Common\Model\UserModel;
use Common\Model\SysLogModel;
use Common\Common\UserSession;
use Common\Common\OSAEncrypt;
use Common\Common\ErrorMessage;

class LoginController extends ComController {
    public function index(){
      	$user_name = $password = $remember = $verify_code = '';
		extract ( $_POST, EXTR_IF_EXISTS );
		$common = new Common();

		$userModel = new UserModel();
		$sysLogModel = new SysLogModel();
		
		if ($common::isPost ()) {
			if(strtolower($verify_code) != strtolower($_SESSION['osa_verify_code'])){
                $this->alert("error",ErrorMessage::VERIFY_CODE_WRONG);
			}else{
				$user_info = $userModel->checkPassword ( $user_name, $password );
				
				if ($user_info) {
					if($user_info['status']==1){
					
						$userModel->loginDoSomething($user_info['user_id']);
						
						if($remember){	
							$encrypted = OSAEncrypt::encrypt($user_info['user_id']);
							$userModel->setCookieRemember(urlencode($encrypted),30);
						}
						$ip = Common::getIp();
						$sysLogModel->addLog( $user_name, 'LOGIN', 'User' ,UserSession::getUserId(),json_encode(array("IP" => $ip)));
						$common::jumpUrl ( '' );
					}else{
                        $this->alert("error",ErrorMessage::BE_PAUSED);
					}
				} else {
                    $this->alert("error",ErrorMessage::USER_OR_PWD_WRONG);//ErrorMessage::USER_OR_PWD_WRONG);
                    //(new \ErrorMessage())::USER_OR_PWD_WRONG;
					$sysLogModel->addLog ( $user_name, 'LOGIN','User' ,'' , json_encode(ErrorMessage::USER_OR_PWD_WRONG));//ErrorMessage::USER_OR_PWD_WRONG) );
				}
			}
		}
		$this->assign ( '_POST',$_POST );
		$this->assign ( 'page_title','登入' );
		$this->Display ( 'Index/login' );
    }
}
?>
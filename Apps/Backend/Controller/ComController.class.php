<?php
namespace Backend\Controller;
use Think\Controller;
use Common\Common\SideBar;
use Common\Model\Menu_urlModel;
use Common\Common\Common;
use Common\Common\UserSession;
use Common\Model\UserModel;
use Common\Common\OSAdmin;
use Common\Common\ErrorMessage;




class ComController extends Controller{
     
    function _initialize( ) {
        $host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
        
        if(filter_var($host, FILTER_VALIDATE_IP)) {// 合法IP
            echo "<script>location.href='404.html'</script>"; 
            die; 
        }
     	$no_need_login_page=array("/block.php","/backend/login","/backend/panel/logout","/mobile/","/ajax/","/api/","/backend/panel/verify_code_cn");
    	C( D( 'yytb_system' ) -> cache( 'system_config' , 120 ) 
            -> where( 'status=1' ) 
            -> getField( 'name,val' ) ) ;
    	//如果不需要登录就可以访问的话
    	$action_url = Common::getActionUrl();
    	if( (new OSAdmin())->checkNoNeedLogin($action_url,$no_need_login_page) ){
    		//for login.php logout.php etc....
    		;
    	}else{
    		//else之后 需要验证登录信息
    		$userModel = new UserModel();
    		if(empty($_SESSION[UserSession::SESSION_NAME])){
    			$user_id=$userModel->getCookieRemember();
    			if($user_id>0){
    				$userModel->loginDoSomething($user_id);
    			}
    		}
    	
    		$this->checkLogin();
    	
    		$this->checkActionAccess();
    /*		$current_user_info=UserSession::getSessionInfo();
    		//如果非ajax请求
    		if(stripos($_SERVER['SCRIPT_NAME'],"/ajax")===false){
    			//显示菜单、导航条、模板
    			$sidebar = SideBar::getTree ();
    	
    			//是否显示quick note
    			if($current_user_info['show_quicknote']){
    				OSAdmin::showQuickNote();
    			}
    	
    			$menu = MenuUrl::getMenuByUrl(Common::getActionUrl());
    			Template::assign ( 'page_title', $menu['menu_name']);
    			Template::assign ( 'content_header', $menu );
    			Template::assign ( 'sidebar', $sidebar );
    			Template::assign ( 'current_module_id', $menu['module_id'] );
    			Template::assign ( 'user_info', UserSession::getSessionInfo());
    		}*/
     	} 

        $sidebar = SideBar::getTree ();
        $sidebarStatus=$_COOKIE['sidebarStatus']==null?"yes":$_COOKIE['sidebarStatus'];
        $menuUrlModel = new Menu_urlModel();
        $common = new Common();

        $tmp = $common->getActionUrl();
        //判断是否有参数
        $n = 0;
        for($i = 0;$i <= 3;$i++) {
            $n = strpos($tmp, '/', $n);
            $i != 3 && $n++;
        }
        //如果有的话截取
        if ($n) {
            $tmp = substr($tmp,0, $n);
        }
        $menu = $menuUrlModel->getMenuByUrl($tmp);
		$this->assign ( 'sidebar', $sidebar );
		$this->assign ( 'sidebarStatus', $sidebarStatus);
		
		$this->assign ( 'page_title', $menu['menu_name']);
		$this->assign ( 'content_header', $menu );
		$this->assign ( 'current_module_id', $menu['module_id'] );
		$this->assign ( 'user_info', UserSession::getSessionInfo());
    }
    
   /* public function exitWithSuccess() {
    	
    }*/
    
    public function checkLogin() {
    	$common = new Common();
    	$user_info = UserSession::getSessionInfo ();
    	if (empty ( $user_info )) {
    		//$common->jumpUrl("login");
    		//$common->jumpUrl("");
    		//return true;
    		$this->redirect("/backend/login");
    		
    		exit;
    	}
    }
    
    public function checkActionAccess() {
    	$common = new Common();
    	$menuUrlModel = new Menu_urlModel();
    	$action_url = $common->getActionUrl();
    
    
    	$user_info = UserSession::getSessionInfo();
    
    	$role_menu_url = $menuUrlModel->getMenuByRole ( $user_info['user_role']);
    	$search_result = in_array ( $action_url, $role_menu_url );
    //	$this->success('success','index');
    	if (! $search_result) {
    		//	 		Common::exitWithMessage ('您当前没有权限访问该功能，如需访问请联系管理员开通权限','index.php' );
    		return true;
    	}
    }

    public function exitWithMessage($message_detail, $forward_url, $second = 3,$type="message",$moban="Index/message") {

        switch ($type) {
            case "success" :
                $page_title="操作成功！";
                break;
            case "error":
                $page_title="错误!";
                break;
            default:
                $page_title="嗯!";
                break;
        }
        $temp = explode('?',$forward_url);
        $file_url = $temp[0];
        if($file_url{0} !=="/"){
            $file_url ='/'.$file_url;
            //$forward_url ='/'.$forward_url;
        }
        /*dump($file_url);
        dump($forward_url);die;*/
        $menu = D('Menu_url')->getMenuByUrl($file_url);
        $forward_title = "首页";
        if(sizeof($menu)>0){
            $forward_title = $menu['menu_name'];
        }
        if ($forward_url) {
            $message_detail = "$message_detail <script>setTimeout(\"window.location.href ='$forward_url';\", " . ($second * 1000) . ");</script>";
           // var_dump($message_detail);die;
        }
        $this->assign ( 'type', $type );
        $this->assign ( 'page_title', $page_title );
        $this->assign ( 'message_detail', $message_detail );
        $this->assign ( 'forward_url', $forward_url );
        $this->assign ( 'forward_title', $forward_title);
     //   $this->display ( $moban );
        $this->display ('Index:message' );
      //  display
        exit();
    }

    public function exitWithError($message_detail, $forward_url, $second = 3,$type="error") {
        self::exitWithMessage($message_detail, $forward_url, $second ,$type);
    }

    public function exitWithSuccess($message_detail, $forward_url, $second = 3 ,$type="success",$moban="message") {
        self::exitWithMessage($message_detail, $forward_url, $second, $type,$moban);
    }
    public function alert($type,$message=""){
       // $ErrorMessage = new ErrorMessage();
        //echo 111;die;
        if($message == "") {
            switch(strtolower($type)){
                case "success":
                    $message=ErrorMessage::SUCCESS;
                    break;
                case "error" :
                    $message=ErrorMessage::ERROR;
                    break;
            }
        }
        $alert_html="<div class=\"alert alert-$type\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">×</button>$message</div>";
        $this->assign("osadmin_action_alert",$alert_html);
    }

}
?>
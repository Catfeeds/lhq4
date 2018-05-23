<?php
namespace Backend\Controller;
use Think\Controller;
use Common\Common\UserSession;
class ProviderconfigController extends ComController{
	 //广告主点击上报配置
    public function providerConfig(){
        $method = $config_id = $config_name = $search=$provider_id='';

		extract ( $_GET, EXTR_IF_EXISTS );
		//var_dump($_GET);
		//页面上只显示广告应用管理里有的广告商
		//查询广告应用管理里的所有provider_id
		$providerids=D('App')->getPid();
		foreach($providerids as $v){
			$providerid[]=$v['provider_id'];
		}
		$provider= D('Provider')->getProvidersArrayByid($providerid);
		if ($method == 'del' && ! empty ( $config_id )) {
			$providers = D('ProviderConfig')->getProviderConfigById($config_id);

			if(intval($config_id) <= 0){
				$this->alert("error",'参数不正确');//error('参数不正确', U('Backend/Providerconfig/providerConfig'),1);
				//OSAdmin::alert("error",ErrorMessage::CAN_NOT_DELETE_SYSTEM_MENU);
			}else{
				$result = D('ProviderConfig')->delProviderConfig( $config_id );
				if ($result) {
                    D('SysLog')->addLog ( UserSession::getUserName(), 'DELETE', '配置信息' ,$config_id, json_encode($providers) );
					$this->exitWithSuccess('已将配置信息删除', U('Backend/Providerconfig/providerConfig'),1);

					//Common::exitWithSuccess ('已将配置信息删除','backend/providerConfig.php');
				}else{
					$this->alert("error");//error('删除失败', U('Backend/Providerconfig/providerConfig'),1);
					//OSAdmin::alert("error");
				}
			}
		}
		$apps = D('App')->getAppsArray();
        $data = D('ProviderConfig')->search($config_name,$search);
		$appPids=D('App')->getProviders();
		$provider_name=D('Provider')->getProvidersArray();

		$this->assign('configs', $data['data']);
		$this->assign('page', $data['page']);
		$this->assign ( '_GET', $_GET );
		$this->assign ('apps',$apps);
		$this->assign ('appPids',$appPids);
		$this->assign ('provider_name',$provider_name);
		$this->assign('providers',$provider);
		$this->display ( );
    }
    //添加广告主点击上报配置信息
    public function providerConfig_add(){
        $config_id = $config_content = $config_name = $app_id = $config_if = '';
		extract (  $_POST, EXTR_IF_EXISTS );
		//var_dump($app_id);die;
		$app_id=$_POST['app_id'];
		// $config_content = I('post.config_content');
		$config_name=$config_name."($app_id)";
		if (IS_POST) {
			if ($app_id == "") {
				$this->alert("error", '广告名称必选');
			} else {
				$exist = D('ProviderConfig')->getProviderConfigByName($config_name);
				if ($exist) {
					//OSAdmin::alert("error",ErrorMessage::NAME_CONFLICT);
					$this->alert("error", '名称冲突');//error('已存在，请不要重复添加', U('Backend/Providerconfig/providerConfig'),1);ErrorMessage::NAME_CONFLICT
				} else {
					$input_data = array('config_name' => $config_name, 'config_content' => $config_content, 'app_id' => $app_id, 'config_if' => $config_if);
					$config_id = D('ProviderConfig')->addProviderConfig($input_data);
					if ($config_id) {
						D('SysLog')->addLog(UserSession::getUserName(), 'ADD', '配置信息', $config_id, json_encode($input_data));
						//Common::exitWithSuccess ('配置信息添加完成','backend/providerConfig.php');
						$this->exitWithSuccess('配置信息添加完成', U('Backend/Providerconfig/providerConfig'), 1);
					}
				}
			}
		}
		$apps = D('App')->getAppsArray();
		$this->assign('apps', $apps);
		$this->assign("_POST" ,$_POST);
		$this->display();
    }
    //修改广告主点击上报配置信息
    public function providerConfig_modify(){
    	$config_id = $config_name = $config_content = $app_id = $config_if = $httpref='';

		extract ( $_REQUEST, EXTR_IF_EXISTS );	 
		//Common::checkParam($config_id);
		$config = D('ProviderConfig')->getProviderConfigById($config_id);

		//查询该app_id对应的provider_id
		$provider_id=D('App')->getPidByAppid($config['app_id'])[0]['provider_id'];
		if($provider_id != 0){
		//根据provider_id查询该广告商的配置信息
			$appArr = D('App')->getAppByPid($provider_id);
			foreach ($appArr as $k => $v) {
				$appids[] = $v['app_id'];
			}
			$configArr =D('ProviderConfig')->getConfigConByAppid($appids);
			$configArr1 =D('ProviderConfig')->getConfigIfByAppid($appids);
		}


		if(empty($config)){
			$this->exitWithError(ErrorMessage::SAMPLE_NOT_EXIST,U('Backend/Providerconfig/providerConfig'),1);//error('参数不正确', U('Backend/Providerconfig/providerConfig'),1);
			//Common::exitWithError(ErrorMessage::SAMPLE_NOT_EXIST,"backend/providerConfig.php");
		}
		if (IS_POST) {
			if ($app_id == "") {
				$this->alert("error", '广告名称必选');
			} else {
				if ($config_name == "") {
					//OSAdmin::alert("error",ErrorMessage::NEED_PARAM);
					$this->alert("error", '缺少参数');//error('缺少参数', U('Backend/Providerconfig/providerConfig'),1);ErrorMessage::NEED_PARAM
				} else {
					//echo $sample.sample_name;
					$update_data = array('config_name' => $config_name, 'config_content' => $config_content, 'app_id' => $app_id, 'config_if' => $config_if);
					$result = D('ProviderConfig')->updateProviderConfigInfo($config_id, $update_data);

					if ($result >= 0) {
						D('SysLog')->addLog(UserSession::getUserName(), 'MODIFY', '配置信息', $config_id, json_encode($update_data));
						//Common::exitWithSuccess ( '配置信息修改完成','backend/providerConfig.php' );
						$this->exitWithSuccess('配置信息修改完成',$httpref, 1);
					} else {
						$this->alert("error");//error('配置信息修改失败', U('Backend/Providerconfig/providerConfig'),1);
						//OSAdmin::alert("error");
					}
				}
			}
		}
		$apps = D('App')->getAppsArray();
		$this->assign('apps', $apps);
		$this->assign('configArrs', $configArr);
		$this->assign('configArrs1', $configArr1);
		$this->assign ( 'config', $config );
		$this->display ();
    }


    //广告主激活配置信息
	public function activeConfig(){
		$method = $config_id = $config_name = $search='';
		extract ( $_GET, EXTR_IF_EXISTS );
		//获取广告应用管理里有的广告商
		$providerids=D('App')->getPid();
		foreach($providerids as $v){
			$providerid[]=$v['provider_id'];
		}
		$provider=D('Provider')->getProvidersArray();
		if ($method == 'del' && ! empty ( $config_id )) {
			$providers = D('ActiveConfig')->getActiveConfigById($config_id);

			if(intval($config_id) <= 0){
				$this->alert("error",'参数不正确');//error('参数不正确', U('Backend/Providerconfig/activeConfig'),1);
				//OSAdmin::alert("error",ErrorMessage::CAN_NOT_DELETE_SYSTEM_MENU);
			}else{
				$result = D('ActiveConfig')->delActiveConfig( $config_id );
				if ($result) {
                    D('SysLog')->addLog ( UserSession::getUserName(), 'DELETE', '配置信息' ,$config_id, json_encode($providers) );
                    $this->exitWithSuccess('已将配置信息删除', U('Backend/Providerconfig/activeConfig'),1);

					//Common::exitWithSuccess ('已将配置信息删除','backend/activeConfig.php');
				}else{
                    $this->alert("error");//error('配置信息删除失败', U('Backend/Providerconfig/activeConfig'),1);				//	OSAdmin::alert("error");
				}
			}
		}
		$apps = D('App')->getAppsArray();
		$appPids=D('App')->getProviders();
		$provider_name=D('Provider')->getProvidersArray();
        $data = D('ActiveConfig')->search($config_name,$search);
        $this->assign('configs', $data['data']);
		$this->assign('page', $data['page']);
		$this->assign ( '_GET', $_GET );
		$this->assign ('apps',$apps);
		$this->assign('providers',$provider);
		$this->assign('appPids',$appPids);
		$this->assign('provider_name',$provider_name);
		$this->display ();

	}
    
    //添加广告主激活配置信息
	public function activeConfig_add(){
		$config_id = $config_content = $config_name = $app_id = $config_if = '';
		extract ( $_POST, EXTR_IF_EXISTS );
		$app_id=$_POST['app_id'];
		$config_name=$config_name."($app_id)";
		if (IS_POST) {
			if ($app_id == "") {
				$this->alert("error", '广告名称必选');
			} else {
				$exist = D('ActiveConfig')->getActiveConfigByName($config_name);
				if ($exist) {
					$this->alert("error", '名称冲突');//error('已存在，请不要重复添加', U('Backend/Providerconfig/activeConfig'),1);
					//OSAdmin::alert("error",ErrorMessage::NAME_CONFLICT);
				} else {
					$input_data = array('config_name' => $config_name, 'config_content' => $config_content, 'app_id' => $app_id, 'config_if' => $config_if);
					$config_id = D('ActiveConfig')->addActiveConfig($input_data);

					if ($config_id) {
						D('SysLog')->addLog(UserSession::getUserName(), 'ADD', '配置信息', $config_id, json_encode($input_data));
						$this->exitWithSuccess('配置信息添加完成', U('Backend/Providerconfig/activeConfig'), 1);

						//Common::exitWithSuccess ('配置信息添加完成','backend/activeConfig.php');
					}
				}
			}
		}
		$apps = D('App')->getAppsArray();
		$this->assign('apps', $apps);
		$this->assign("_POST" ,$_POST);
		$this->display();

	}
    //修改广告主激活配置信息
	public function activeConfig_modify(){
		$config_id = $config_name = $config_content = $app_id = $config_if = $httpref = '';
		extract ( $_REQUEST, EXTR_IF_EXISTS );
		
		//Common::checkParam($config_id);
		$config = D('ActiveConfig')->getActiveConfigById($config_id);

		//根据app_id查询provider_id
		$provider_id=D('App')->getPidByAppid($config['app_id'])[0]['provider_id'];
		if($provider_id != 0){
		//根据provider_id查询该广告商的配置信息
			$appArr = D('App')->getAppByPid($provider_id);
			foreach($appArr as $v){
				$appids[]=$v['app_id'];
			}
			$configArr= D('ActiveConfig')->getConfigConByAppid($appids);
			$configArr1= D('ActiveConfig')->getConfigIfByAppid($appids);
		}
		if(empty($config)){
			$this->exitWithError(ErrorMessage::SAMPLE_NOT_EXIST, U('Backend/Providerconfig/activeConfig'),1);//error('参数不正确', U('Backend/Providerconfig/activeConfig'),1);
			//Common::exitWithError(ErrorMessage::SAMPLE_NOT_EXIST,"backend/providerConfig.php");
		}

		if (IS_GET) {
			$href_url = $_SERVER['HTTP_REFERER'];
		}

		if (IS_POST) {
			if ($app_id == "") {
				$this->alert("error", '广告名称必选');
			} else {
				if ($config_name == "") {
					$this->alert("error", '缺少必填项');//error('缺少参数', U('Backend/Providerconfig/activeConfig'),1);
					//OSAdmin::alert("error",ErrorMessage::NEED_PARAM);
				} else {
					//echo $sample.sample_name;
					$update_data = array('config_name' => $config_name, 'config_content' => $config_content, 'app_id' => $app_id, 'config_if' => $config_if);
					$result = D('ActiveConfig')->updateActiveConfigInfo($config_id, $update_data);

					if ($result > 0) {
						D('SysLog')->addLog(UserSession::getUserName(), 'MODIFY', '配置信息', $config_id, json_encode($update_data));
						$this->exitWithSuccess('配置信息修改完成', $httpref, 1);

						//Common::exitWithSuccess ( '配置信息修改完成','backend/activeConfig.php' );
					} else {
						$this->alert("error");//error('配置信息修改失败', U('Backend/Providerconfig/activeConfig'),1);
						//OSAdmin::alert("error");
					}
				}
			}
		}
		$apps = D('App')->getAppsArray();
		$this->assign('apps', $apps);
		$this->assign('href_url', $href_url);
		$this->assign('configArrs', $configArr);
		$this->assign('configArrs1', $configArr1);
		$this->assign ( 'config', $config );
		$this->display ();
	}
}
?>
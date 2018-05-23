<?php
namespace Backend\Controller;
use Think\Controller;
use Common\Common\UserSession;
use Common\Common\OSAdmin;
use Common\Common\ErrorMessage;
class PcconfigController extends ComController{
    //排重配置信息
    public function pc_config(){
		$method = $config_id  = $config_name = $search='';
		extract ( $_GET, EXTR_IF_EXISTS );
		//var_dump($_GET);
		//获取广告应用管理里有的广告商
		$providerids=D('App')->getPid();
		foreach($providerids as $v){
			$providerid[]=$v['provider_id'];
		}
		$provider=D('Provider')->getProvidersArrayByid($providerid);
		//var_dump($provider);
		if ($method == 'del' && ! empty ( $config_id )) {
			$configs = D('PcConfig')->getPcConfigById($config_id);

			if(intval($config_id) <= 0){
				//OSAdmin::alert("error",ErrorMessage::CAN_NOT_DELETE_SYSTEM_MENU);
				$this->alert("error",'参数不正确');//error('参数不正确', U('Backend/Pcconfig/pc_config'),1);ErrorMessage::CAN_NOT_DELETE_SYSTEM_MENU
			}else{
				$result = D('PcConfig')->delPcConfig ( $config_id );
				if ($result) {
                    D('SysLog')->addLog ( UserSession::getUserName(), 'DELETE', '配置信息' ,$config_id, json_encode($configs) );
					//Common::exitWithSuccess ('已将配置信息删除','backend/pc_config.php');
				    $this->exitWithSuccess('已将配置信息删除', U('Backend/Pcconfig/pc_config'),1);
				}else{
                    $this->alert("error");//error('配置信息删除失败', U('Backend/Pcconfig/pc_config'),1);
					//OSAdmin::alert("error");
				}
			}
		}
		$apps = D('App')->getAppsArray();
		$appPids=D('App')->getProviders();
		$provider_name=D('Provider')->getProvidersArray();
		$data = D('PcConfig')->search($config_name,$search);
		$this->assign ( '_GET', $_GET );
		$this->assign('apps', $apps);
		$this->assign('appPids',$appPids);
		$this->assign('provider_name',$provider_name);
		$this->assign('providers', $provider);
		$this->assign('configs', $data['data']);
		$this->assign('page', $data['page']);
		$this->display ();
    }
    //添加排重配置信息
    public function pcConfig_add(){

    	$config_id = $repeat_url = $config_name = $app_id = $config_if =$config_type= $request = '';
		extract ( $_POST, EXTR_IF_EXISTS );
		$app_id=$_POST['app_id'];
		$config_name=$config_name."($app_id)";
		if (IS_POST) {
			if ($app_id == "") {
				$this->alert("error", '广告名称必选');
			} else {
				$exist = D('PcConfig')->getPcConfigByName($config_name);
				if ($exist) {
					//OSAdmin::alert("error",ErrorMessage::NAME_CONFLICT);
					$this->alert("error", '名称冲突');//error('已存在，请不要重复添加', U('Backend/Pcconfig/pc_config'),1);ErrorMessage::NAME_CONFLICT
				} else {
					$input_data = array('config_name' => $config_name, 'repeat_url' => $repeat_url, 'app_id' => $app_id, 'config_if' => $config_if, 'request' => $request,'config_type'=>$config_type);
					$config_id = D('PcConfig')->addPcConfig($input_data);
					if ($config_id) {
						D('SysLog')->addLog(UserSession::getUserName(), 'ADD', '配置信息', $config_id, json_encode($input_data));
						//Common::exitWithSuccess ('配置信息添加完成','backend/pc_config.php');
						$this->exitWithSuccess('配置信息添加完成', U('Backend/Pcconfig/pc_config'), 1);
					}
				}
			}
		}
		$apps = D('App')->getAppsArray();
		$this->assign('apps', $apps);
		$this->assign("_POST" ,$_POST);
		$this->display();
    }
    //修改排重配置信息
    public function pcConfig_modify(){
        $config_id = $repeat_url = $config_name = $app_id = $config_if = $request =$config_type= $httpref = '';
		extract ( $_REQUEST, EXTR_IF_EXISTS );

		$config = D('PcConfig')->getPcConfigById($config_id);

	//根据app_id查询广告应用里对应的provider_id
		$provider_id=D('App')->getPidByAppid($config['app_id'])[0]['provider_id'];
		if($provider_id != 0){
	//根据provider_id查询出该广告商的配置信息
			$appArr=D('App')->getAppByPid($provider_id);
			foreach($appArr as $v){
				$appids[] = $v['app_id'];
			}
			$configArr=D('PcConfig')->getConfigConByAppid($appids);
			$configArr1=D('PcConfig')->getConfigIfByAppid($appids);
			//var_dump($configArr);die;
		}


		if(empty($config)){
			$this->exitWithError(ErrorMessage::SAMPLE_NOT_EXIST,U('Backend/Pcconfig/pc_config'),1);//error('参数不正确', U('Backend/Pcconfig/pc_config'),1);
			//Common::exitWithError(ErrorMessage::SAMPLE_NOT_EXIST,"backend/Pc_config.php");
		}
		if (IS_POST) {
			if ($app_id == "") {
				$this->alert("error", '广告名称必选');
			} else {
				if ($config_name == "") {
					$this->alert("error", '缺少参数');//error('缺少参数', U('Backend/Pcconfig/pc_config'),1);
					//OSAdmin::alert("error",ErrorMessage::NEED_PARAM);
				} else {

					$update_data = array('config_name' => $config_name, 'repeat_url' => $repeat_url, 'app_id' => $app_id, 'config_if' => $config_if, 'request' => $request,'config_type'=>$config_type);
					$result = D('PcConfig')->updatePcConfigInfo($config_id, $update_data);

					if ($result >= 0) {
						$this->exitWithSuccess('配置信息修改完成', $httpref, 1);
						D('SysLog')->addLog(UserSession::getUserName(), 'MODIFY', '配置信息', $config_id, json_encode($update_data));
						//Common::exitWithSuccess ( '配置信息修改完成','backend/pc_config.php' );
					} else {
						$this->alert("error");
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
}
?>
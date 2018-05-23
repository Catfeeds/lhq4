<?php
namespace Backend\Controller;
use Think\Controller;
use Common\Common\UserSession;
class ChannelconfigController extends ComController{
    //渠道配置信息
    public function channelConfig(){
        $method = $config_id = $page_no = '';
        extract ( $_GET, EXTR_IF_EXISTS );
        if ($method == 'del' && ! empty ( $config_id )) {
            $configs = D('Channel_config')->getChannelConfigById($config_id);

            //if(intval($menu['module_id']) === 1){
            if(intval($config_id) <= 0){
                //OSAdmin::alert("error",ErrorMessage::CAN_NOT_DELETE_SYSTEM_MENU);
                $this->alert("error",'参数不正确');//error('参数不正确', U('Backend/Channelconfig/channelConfig'),1);

            }else{
                $result = D('Channel_config')->delChannelConfig ( $config_id );
                if ($result) {
                    D('SysLog')->addLog ( UserSession::getUserName(), 'DELETE', '配置信息' ,$config_id, json_encode($configs) );
                    //Common::exitWithSuccess ('已将配置信息删除','backend/channelConfig.php');
                    $this->exitWithSuccess('已将配置信息删除', U('Backend/Channelconfig/channelConfig'),1);

                }else{
                    //OSAdmin::alert("error");
                    $this->alert("error");//error('删除失败', U('Backend/Channelconfig/channelConfig'),1);
                }
            }
        }
        $data = D('Channel_config')->search();
        $apps = D('App')->getAppsArray();
        $this->assign ( '_GET', $_GET );
        $this->assign('apps', $apps);
        $this->assign('configs', $data['data']);
        $this->assign('page', $data['page']);
        $this->display ();

    }

    //添加渠道配置信息
    public function channelConfig_add(){
        $config_id = $config_content = $config_name = $config_result = $app_id = '';
        extract ( $_POST, EXTR_IF_EXISTS );
       // dump($_POST);//DIE;
        if (IS_POST) {
            if ($app_id == "") {
                $this->alert("error", '广告名称必选');
            } else {
                $exist = D('Channel_config')->getChannelConfigByName($config_name);
                if ($exist) {
                    //OSAdmin::alert("error",ErrorMessage::NAME_CONFLICT);
                    $this->alert("error", '名称冲突');//error('已存在，请不要重复添加', U('Backend/Channelconfig/channelConfig'),1);ErrorMessage::NAME_CONFLICT
                } else {
                    $input_data = array('config_name' => $config_name, 'config_content' => $config_content, '$config_result' => $config_result, 'app_id' => $app_id);
                    $config_id = D('Channel_config')->addChannelConfig($input_data);

                    if ($config_id) {
                        D('SysLog')->addLog(UserSession::getUserName(), 'ADD', '配置信息', $config_id, json_encode($input_data));
                        //Common::exitWithSuccess ('配置信息添加完成','backend/channelConfig.php');
                        $this->exitWithSuccess('配置信息添加完成', U('Backend/Channelconfig/channelConfig'), 1);
                    }
                }
            }
        }
        $apps = D('App')->getAppsArray();
        $appNames = D('App')->getAppArray();
        $this->assign('apps', $apps);
        $this->assign('appNames', $appNames);
        $this->assign("_POST" ,$_POST);
        $this->display ();
    }
    //修改渠道配置信息
    public function channelConfig_modify(){     
        $config_id = $config_name = $config_content = $app_id = $httpref='';
        extract ( $_REQUEST, EXTR_IF_EXISTS );   
        //Common::checkParam($config_id);
        $config = D('Channel_config')->getChannelConfigById($config_id);
        if(empty($config)){
            $this->exitWithError(ErrorMessage::SAMPLE_NOT_EXIST,"Backend/Channelconfig/channelConfig");//error('参数不正确', U('Backend/Channelconfig/channelConfig'),1);
            //Common::exitWithError(ErrorMessage::SAMPLE_NOT_EXIST,"backend/channel.php");
        }
        if (IS_POST) {
            if ($app_id == "") {
                $this->alert("error", '广告名称必选');
            } else {
                if ($config_name == "") {
                    //OSAdmin::alert("error",ErrorMessage::NEED_PARAM);
                    $this->alert("error", '名称冲突');//error('缺少参数', U('Backend/Channelconfig/channelConfig'),1);ErrorMessage::NEED_PARAM
                } else {
                    $update_data = array('config_name' => $config_name, 'config_content' => $config_content, 'app_id' => $app_id);
                    $result = D('Channel_config')->updateChannelConfigInfo($config_id, $update_data);

                    if ($result >= 0) {
                        D('SysLog')->addLog(UserSession::getUserName(), 'MODIFY', '配置信息', $config_id, json_encode($update_data));
                        //Common::exitWithSuccess ( '配置信息修改完成','backend/channelConfig.php' );
                        $this->exitWithSuccess('配置信息修改完成', $httpref, 1);
                    } else {
                        $this->alert("error");//error('配置信息修改失败', U('Backend/Channelconfig/channelConfig'),1);
                        //OSAdmin::alert("error");
                    }
                }
            }
        }
        $apps = D('App')->getAppsArray();
        $this->assign('apps', $apps);
        $this->assign( 'config', $config ); 
        $this->display ();
    }
}
?>
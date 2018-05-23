<?php
namespace Backend\Controller;
use Think\Controller;
use Common\Common\UserSession;
class AdtypeController extends ComController{
	//广告分类
    public function adType(){
        $method = $id = $task_name = $page_no = '';
		extract ( $_GET, EXTR_IF_EXISTS );
		if ($method == 'del' && ! empty ( $id )) {
		    $adTypes = D('Adtype')->getAdTypeById($id);	    
		    if(intval($id) <= 0){
                $this->alert("error",'系统菜单不能被删除');//\ErrorMessage::CAN_NOT_DELETE_SYSTEM_MENU
               // error('参数不正确', U('Backend/Adtype/adType'),1);
		        //OSAdmin::alert("error",ErrorMessage::CAN_NOT_DELETE_SYSTEM_MENU);
		    }else{
		        $result = D('Adtype')->delAdType ( $id );
		        //var_dump($result);//die;
		        if ($result) {
                    D('SysLog')->addLog ( UserSession::getUserName(), 'DELETE', '配置信息' ,$id, json_encode($adTypes) );
                    $this->  exitWithSuccess ('已将配置信息删除',U('Backend/Adtype/adType'), 1);
		        }else{
                    $this-> alert("error");
		           // OSAdmin::alert("error");
		        }
		    }
		}
		$data = D('Adtype')->search();
		$this->assign ( '_GET', $_GET );
        $this->assign('adTypes', $data['data']);
		$this->assign('page', $data['page']);
		$this->display ();
    }
    //添加广告分类
    public function adTypeAdd(){
        $id = $task_name = $create_time= '';
		extract ( $_POST, EXTR_IF_EXISTS );
		if (IS_POST) {
			$exist = D('Adtype')->getAdTypeByName($task_name);
		    $now_time=time();
			if($exist){
                $this->alert("error",'名称冲突');//ErrorMessage::NAME_CONFLICT
                //error('已存在，请不要重复添加', U('Backend/Adtype/adType'),1);
				//OSAdmin::alert("error",ErrorMessage::NAME_CONFLICT);
			}else{
				$input_data = array ('task_name' => $task_name, 'create_time' => $now_time );
		        $id = D('Adtype')->addAdType( $input_data );
				if ($id) {
                    D('SysLog')->addLog ( UserSession::getUserName(), 'ADD', '任务分类' ,$id, json_encode($input_data) );
                    $this->  exitWithSuccess ('配置信息添加完成',U('Backend/Adtype/adType'),1);
				}
			}
		}
		$this->assign("_POST" ,$_POST);
		$this->display();
    }
     //修改广告分类
    public function adTypeModify(){
        $id = $task_name = $create_time = '';
		extract ( $_REQUEST, EXTR_IF_EXISTS );
		 
		//Common::checkParam($id);
		$AdType = D('Adtype')->getAdTypeById($id);
		if(empty($AdType)){
			$this->exitWithError(ErrorMessage::SAMPLE_NOT_EXIST,"Backend/Adtype/adType");//error('缺少参数', U('Backend/Adtype/adType'),1);
			//Common::exitWithError(ErrorMessage::SAMPLE_NOT_EXIST,"backend/adType.php");
		}
		if (IS_POST) {
		    $now_time=time();
			if($task_name =="" ){
                $this->alert("error",'缺少参数');//error('缺少参数', U('Backend/Adtype/adType'),1);ErrorMessage::NEED_PARAM
				//OSAdmin::alert("error",ErrorMessage::NEED_PARAM);
			}else{
				$update_data = array ('task_name' => $task_name, 'create_time' => $now_time);
				$result = D('Adtype')->updateAdTypeInfo($id, $update_data );
				//var_dump($result);die;Adtype
				if ($result>=0) {
                    D('SysLog')->addLog ( UserSession::getUserName(), 'MODIFY', '配置信息' ,$id, json_encode($update_data) );
					$this->exitWithSuccess('修改完成', U('Backend/Adtype/adType'),1);
					//SysLog::addLog ( UserSession::getUserName(), 'MODIFY', '配置信息' ,$id, json_encode($update_data) );
					//Common::exitWithSuccess ( '配置信息修改完成','backend/adType.php' );
				} else {
					$this->alert("error");
					//OSAdmin::alert("error");
				}
			}
		}

		$this->assign ( 'AdType', $AdType );
		$this->display ();
    }
}
?>
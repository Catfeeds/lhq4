<?php
namespace Backend\Controller;
use Think\Controller;
use Common\Common\UserSession;
class MsgTypeController extends ComController{
	//广告分类
    public function msgType(){
        $method = $id = $type_name = $page_no = '';
		extract ( $_GET, EXTR_IF_EXISTS );
		if ($method == 'del' && ! empty ( $id )) {
		    $msgTypes = D('MsgType')->getMsgTypeById($id);
		    if(intval($id) <= 0){
                $this->alert("error",'系统菜单不能被删除');//\ErrorMessage::CAN_NOT_DELETE_SYSTEM_MENU
               // error('参数不正确', U('Backend/Adtype/adType'),1);
		        //OSAdmin::alert("error",ErrorMessage::CAN_NOT_DELETE_SYSTEM_MENU);
		    }else{
		        $result = D('MsgType')->delMsgType ( $id );
		        //var_dump($result);//die;
		        if ($result) {
                    D('SysLog')->addLog ( UserSession::getUserName(), 'DELETE', '配置信息' ,$id, json_encode($msgTypes) );
                    $this->  exitWithSuccess ('已将配置信息删除',U('Backend/MsgType/msgType'), 1);
		        }else{
                    $this-> alert("error");
		           // OSAdmin::alert("error");
		        }
		    }
		}
		$data = D('MsgType')->search();
		$this->assign ( '_GET', $_GET );
        $this->assign('msgTypes', $data['data']);
		$this->assign('page', $data['page']);
		$this->display ();
    }
    //添加广告分类
    public function msgTypeAdd(){
        $id = $type_name = $create_time= '';
		extract ( $_POST, EXTR_IF_EXISTS );
		if (IS_POST) {
			$exist = D('MsgType')->getMsgTypeByName($type_name);
		    $now_time=time();
			if($exist){
                $this->alert("error",'名称冲突');//ErrorMessage::NAME_CONFLICT
                //error('已存在，请不要重复添加', U('Backend/Adtype/adType'),1);
				//OSAdmin::alert("error",ErrorMessage::NAME_CONFLICT);
			}else{
				$input_data = array ('type_name' => $type_name, 'create_time' => $now_time );
		        $id = D('MsgType')->addMsgType( $input_data );
				if ($id) {
                    D('SysLog')->addLog ( UserSession::getUserName(), 'ADD', '任务分类' ,$id, json_encode($input_data) );
                    $this->  exitWithSuccess ('配置信息添加完成',U('Backend/MsgType/msgType'),1);
				}
			}
		}

		$this->assign("_POST" ,$_POST);
		$this->display();
    }
     //修改广告分类
    public function msgTypeModify(){
        $id = $type_name = $create_time = '';
		extract ( $_REQUEST, EXTR_IF_EXISTS );
		 
		//Common::checkParam($id);
		$MsgType = D('MsgType')->getMsgTypeById($id);
		if(empty($MsgType)){
			$this->exitWithError(ErrorMessage::SAMPLE_NOT_EXIST,"Backend/MsgType/adType");//error('缺少参数', U('Backend/Adtype/adType'),1);
			//Common::exitWithError(ErrorMessage::SAMPLE_NOT_EXIST,"backend/adType.php");
		}
		if (IS_POST) {
		    $now_time=time();
			if($type_name =="" ){
                $this->alert("error",'缺少参数');//error('缺少参数', U('Backend/Adtype/adType'),1);ErrorMessage::NEED_PARAM
				//OSAdmin::alert("error",ErrorMessage::NEED_PARAM);
			}else{
				$update_data = array ('type_name' => $type_name, 'create_time' => $now_time);
				$result = D('MsgType')->updateMsgTypeInfo($id, $update_data );
				//var_dump($result);die;Adtype
				if ($result>=0) {
                    D('SysLog')->addLog ( UserSession::getUserName(), 'MODIFY', '配置信息' ,$id, json_encode($update_data) );
					$this->exitWithSuccess('修改完成', U('Backend/MsgType/msgType'),1);
					//SysLog::addLog ( UserSession::getUserName(), 'MODIFY', '配置信息' ,$id, json_encode($update_data) );
					//Common::exitWithSuccess ( '配置信息修改完成','backend/adType.php' );
				} else {
					$this->alert("error");
					//OSAdmin::alert("error");
				}
			}
		}

		$this->assign ( 'MsgType', $MsgType );
		$this->display ();
    }
}
?>
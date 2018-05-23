<?php
namespace Backend\Controller;
use Think\Controller;
use Common\Common\UserSession;
class LabelController extends ComController{
	//展示
    public function label(){
        $method = $id = $label_name = $page_no = '';
		extract ( $_GET, EXTR_IF_EXISTS );

		if ($method == 'del' && ! empty ( $id )) {
            $label = D('Label')->getLabelById($id);

		    if(intval($id) <= 0){
		    	$this->alert("error",'参数不正确');//error('参数不正确', U('Backend/Task/task'),1);
		        //OSAdmin::alert("error",ErrorMessage::CAN_NOT_DELETE_SYSTEM_MENU);
		    }else{
		        $result = D('Label')->dellabel ( $id );
		        //var_dump($result);//die;
		        if ($result) {
                    $this->exitWithSuccess('删除成功', U('Backend/Label/label'),1);
                    D('SysLog')->addLog ( UserSession::getUserName(), 'DELETE', '配置信息' ,$id, json_encode($label) );
		            //Common::exitWithSuccess ('已将配置信息删除','backend/task.php');
		        }else{
                    $this->alert("error");//error('删除失败', U('Backend/Task/task'),1);
		            //OSAdmin::alert("error");
		        }
		    }
		}
        $data = D('Label')->search();
	    $this->assign('labels', $data['data']);
		$this->assign('page', $data['page']);
		$this->assign ( '_GET', $_GET );
		$this->display ();

    }
    //添加
    public function labelAdd(){
        $id = $label_name = $label_time= '';
		extract ( $_POST, EXTR_IF_EXISTS );
//        var_dump($label_name);
		if (IS_POST) {
			$exist = D('Label')->getLabelByName($label_name);
		    $now_time=time();
			if($exist){
                $this->alert("error",'名称冲突');//error('已存在，请不要重复添加', U('Backend/Task/task'),1);
				//OSAdmin::alert("error",ErrorMessage::NAME_CONFLICT);
			}else{
				$input_data = array ('label_name' => $label_name, 'label_time' => $now_time );
		        $id = D('Label')->addLabel( $input_data );
				//dump($id);
				if ($id) {

                    D('SysLog')->addLog ( UserSession::getUserName(), 'ADD', '标签管理' ,$id, json_encode($input_data) );
                    $this->exitWithSuccess('添加完成', U('Backend/Label/label'),1);

					//Common::exitWithSuccess ('任务分类添加完成','backend/task.php');
				}
			}
		}
		$this->assign("_POST" ,$_POST);
		$this->display();
    }
    //修改
    public function labelModify(){
        $id = $label_name = $label_time = '';
		extract ( $_REQUEST, EXTR_IF_EXISTS ); 

		$label = D('Label')->getLabelById($id);
		if(empty($label)){
            $this->exitWithError(ErrorMessage::SAMPLE_NOT_EXIST,U('Backend/Label/label'),1);//error('缺少参数', U('Backend/Task/task'),1);
			//Common::exitWithError(ErrorMessage::SAMPLE_NOT_EXIST,"backend/task.php");
		}

		if (IS_POST) {
		    $now_time=time();
			if($label_name =="" ){
                $this->alert("error",'缺少参数');//error('缺少参数', U('Backend/Task/task'),1);
				//OSAdmin::alert("error",ErrorMessage::NEED_PARAM);
			}else{
				$update_data = array ('label_name' => $label_name, 'label_time' => $now_time);
				$result = D('Label')->updateLabelInfo($id, $update_data );
				//var_dump($result);die;
				if ($result>=0) {
                    D('SysLog')->addLog ( UserSession::getUserName(), 'MODIFY', '配置信息' ,$id, json_encode($update_data) );
					$this->exitWithSuccess('修改完成', U('Backend/Label/label'),1);

					//Common::exitWithSuccess ( '配置信息修改完成','backend/task.php' );
				} else {
					 $this->alert("error");//error('修改失败', U('Backend/Task/task'),1);
					//OSAdmin::alert("error");
				}
			}
		}
		$this->assign ( 'label', $label );
		$this->display ( );
    }

}
?>
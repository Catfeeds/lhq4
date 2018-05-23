<?php
namespace Backend\Controller;
use Think\Controller;
use Common\Common\UserSession;
class TaskController extends ComController{
	//任务分类展示
    public function task(){
        $method = $id = $task_name = $page_no = '';
		extract ( $_GET, EXTR_IF_EXISTS );

		if ($method == 'del' && ! empty ( $id )) {
		    $tasks = D('Tasktype')->getTaskById($id);

		    if(intval($id) <= 0){
		    	$this->alert("error",'参数不正确');//error('参数不正确', U('Backend/Task/task'),1);
		        //OSAdmin::alert("error",ErrorMessage::CAN_NOT_DELETE_SYSTEM_MENU);
		    }else{
		        $result = D('Tasktype')->delTask ( $id );
		        //var_dump($result);//die;
		        if ($result) {
                    $this->exitWithSuccess('删除成功', U('Backend/Task/task'),1);
                    D('SysLog')->addLog ( UserSession::getUserName(), 'DELETE', '配置信息' ,$id, json_encode($tasks) );
		            //Common::exitWithSuccess ('已将配置信息删除','backend/task.php');
		        }else{
                    $this->alert("error");//error('删除失败', U('Backend/Task/task'),1);
		            //OSAdmin::alert("error");
		        }
		    }
		}
        $data = D('Tasktype')->search();
	    $this->assign('tasks', $data['data']);
		$this->assign('page', $data['page']);
		$this->assign ( '_GET', $_GET );
		$this->display ();

    }
    //添加任务
    public function taskAdd(){
        $id = $task_name = $create_time= '';
		extract ( $_POST, EXTR_IF_EXISTS );

		if (IS_POST) {
			$exist = D('Tasktype')->getTaskByName($task_name);
		    $now_time=time();
			if($exist){
                $this->alert("error",'名称冲突');//error('已存在，请不要重复添加', U('Backend/Task/task'),1);
				//OSAdmin::alert("error",ErrorMessage::NAME_CONFLICT);
			}else{
				$input_data = array ('task_name' => $task_name, 'create_time' => $now_time );
		        $id = D('Tasktype')->addTask( $input_data );
				//dump($id);
				if ($id) {

                    D('SysLog')->addLog ( UserSession::getUserName(), 'ADD', '任务分类' ,$id, json_encode($input_data) );
                    $this->exitWithSuccess('添加完成', U('Backend/Task/task'),1);

					//Common::exitWithSuccess ('任务分类添加完成','backend/task.php');
				}
			}
		}
		$this->assign("_POST" ,$_POST);
		$this->display();
    }
    //修改任务
    public function taskModify(){
        $id = $task_name = $create_time = '';
		extract ( $_REQUEST, EXTR_IF_EXISTS ); 

		$task = D('Tasktype')->getTaskById($id);
		if(empty($task)){
            $this->exitWithError(ErrorMessage::SAMPLE_NOT_EXIST,U('Backend/Task/task'),1);//error('缺少参数', U('Backend/Task/task'),1);
			//Common::exitWithError(ErrorMessage::SAMPLE_NOT_EXIST,"backend/task.php");
		}

		if (IS_POST) {
		    $now_time=time();
			if($task_name =="" ){
                $this->alert("error",'名称冲突');//error('缺少参数', U('Backend/Task/task'),1);
				//OSAdmin::alert("error",ErrorMessage::NEED_PARAM);
			}else{
				$update_data = array ('task_name' => $task_name, 'create_time' => $now_time);
				$result = D('Tasktype')->updateTaskInfo($id, $update_data );
				//var_dump($result);die;
				if ($result>=0) {
                    D('SysLog')->addLog ( UserSession::getUserName(), 'MODIFY', '配置信息' ,$id, json_encode($update_data) );
					$this->exitWithSuccess('修改完成', U('Backend/Task/task'),1);

					//Common::exitWithSuccess ( '配置信息修改完成','backend/task.php' );
				} else {
					 $this->alert("error");//error('修改失败', U('Backend/Task/task'),1);
					//OSAdmin::alert("error");
				}
			}
		}
		$this->assign ( 'task', $task );
		$this->display ( );
    }

}
?>
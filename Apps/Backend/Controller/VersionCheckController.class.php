<?php
namespace Backend\Controller;
use Think\Controller;
use Common\Common\UserSession;
class VersionCheckController extends ComController{
	//后台控制
	public function index(){
	    if (IS_AJAX) {
	    	$id = I('post.id');
	    	$status = I('post.status');
	    	$stu = D('Version')->where(array('id'=>$id))->save(array('status'=>$status));
	    	if ($stu) {
	    		echo 1;
	    	}else{
	    		echo 0;
	    	}
	    }else{
	    	$versionId = I('get.id');
	    	$method = I('get.method');
	    	if ($method == 'del' && ! empty ( $versionId )) {
		        $result = D('Version')->where(array('id'=>$versionId))->delete();
		        if ($result) {
                    D('SysLog')->addLog ( UserSession::getUserName(), 'DELETE', '配置信息' ,$versionId, json_encode($versionId) );
                    $this->exitWithSuccess('删除成功', U('Backend/VersionCheck/index'),1);
		        }else{
                    $this->alert("error");
		        }
			}
		    
	        $data = D('Version')->search();
		    $this->assign('datas', $data['data']);
			$this->assign('page', $data['page']);
			$this->display();
	    }
	}

	//添加
	public function versionUrlAdd(){
		if (IS_POST) {
			$app_name = I('post.app_name');
	    	$version = I('post.version');
	    	$status = I('post.status');
	    	$ad = D('Version')->data(array('app_name'=>$app_name,'version'=>$version,'status'=>$status))->add();
	    	if ($ad) {
	    		D('SysLog')->addLog ( UserSession::getUserName(), 'ADD', '任务分类' ,$ad, json_encode(array('app_name'=>$app_name,'version'=>$version)) );
                $this->exitWithSuccess('添加完成', U('Backend/VersionCheck/index'),1);
	    	}
		}
		$this->display();
	}
	//修改
	public function versionUrlModify(){
		$versionId = I('get.id');
		$version = D('Version')->where(array('id'=>$versionId))->find();
        if (IS_POST) {
        	$app_name = I('post.app_name');
        	$version = I('post.version');
        	$httpref = I('post.httpref');
        	$result = D('Version')->where(array('id'=>$versionId))->save(array('app_name'=>$app_name,'version'=>$version));
            if ($result >= 0) {
				D('SysLog')->addLog(UserSession::getUserName(), 'MODIFY', '配置信息', $versionId, json_encode(array('app_name'=>$app_name,'version'=>$version)));
				$this->exitWithSuccess('修改成功',$httpref, 1);
			} else {
				$this->alert("error");
			}
        }
        $this->assign('version',$version);
        $this->display();
	}



}
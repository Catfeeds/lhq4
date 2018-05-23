<?php
namespace Admin\Controller;
use Think\Controller;

class AdvController extends AdminController
{

	/**
	 * 广告管理 列表输出页面
	 * @access public
	 * @param string
	 * @return void
	 */
    public function index(){ // 广告首页
       	$this->assign('title', '广告位');
       	$this->assign('types', M('adv_type')->where('')->getField('id,id,info,name'));
    	$this->display();
    }
    
    
    /**
     * 广告列表
     * @access public
     * @param string
     * @return void
     */
    public function lists(){ // 列表异步输出
    	if(!IS_AJAX) return;

    	$page = I('param.page', 1, 'int');
    	$typeId = I('param.type_id', 0, 'int');
    	
    	$where = array();
    	if($typeId)
    		$where['type_id'] = $typeId;

    	$advs  = M('adv')->field('id,info,type_id,status')->where($where)->select();
    	$count = M('adv')->where($where)->count();

    	$data['info']   = '';
    	$data['status'] = 1;
    	$data['data'] = $advs;
    	$data['page'] = array(
    			'page' => $page,
    			'count' => $count,
    			'row' => 10,
    			'href' => 'javascript:getList({page: {{page}} })'
    	);

    	$this->ajaxReturn($data);
    }
    


    /**
     * 编辑新增页面
     * @access public
     * @param string
     * @return void
     */
    public function edit(){ // 编辑新增广告
    	$id = I('param.id', 0, 'int');
    
    	$Adv = M('adv');
    
    	if(IS_POST && $Adv->create()){
    		if(I('post.id', 0, 'int'))
    			$Adv->save();
    		else
    			$Adv->add();
    
    		$this->assign('close', 'parent.getList();dialog.close();');
    	}
    
    
    	layout("inc/tpl.min");
    
    	if($id) $this->assign('adv', $Adv->find($id));
    	$this->assign('title', '广告管理');
    	$this->assign('types', M('adv_type')->getField('id,id,info,name'));
    	$this->display();
    }
    
    
    /**
     * 切换状态
     * @access public
     * @param string
     * @return void
     */
    public function toggle(){ // 开启关闭广告
    	$id = I('param.id', 0, 'int');
    	if(!IS_AJAX || !$id) $this->error('请求出错');

    	if(M()->execute("update __PREFIX__adv SET status = if(status != 0, 0, 1) WHERE id = $id"))
    		$this->success(M('adv')->where("id = $id")->getField('status'));
    	
    }
    
    public function del(){ // 删除广告
        $id = I('get.id', 0, 'int');

        if ($id) {
            
           echo jsonEncode($res=M('adv')->delete($id));
            
        }  else {
            die("error");
        }
        
    }
    
}










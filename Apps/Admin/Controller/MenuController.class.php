<?php
namespace Admin\Controller;

use Think\Controller;
use Model\AuthModel;

// 权限控制器
class MenuController extends AdminController
{
    // 列表展示
    function index() { // 菜单管理首页
        // 获得权限信息
    	$types = array(
    			"pc_top" =>"顶部菜单",
    			"pc_bottom" =>"底部菜单",
    			"pc_weixin" =>"微信菜单",
    			"pc_App" =>"App菜单",
    	);
    
        $order = array(
            "pid" => "asc",
            "id" => "asc"
        );


		$menu = M('menu');
		$type = I('get.type', '');
		
		$url = array('type' => $type, 'p' => '');

		$this->assign('cur_type', $type);
		$typess = !empty($type) ? "type = '$type'" : "";
		//print_r($type);die;
		
		$page = I('get.p', 1, 'int');
		
		$count = $menu->where($typess)->count();
		

        $this->assign('page', array(
				'total' => $count,
				'page' => $page,
				'row' => 10,
				'href' => U('Admin/Menu/index', $url) . '{{page}}'
		));
        // $('.page').html(page({:json_encode($page);}));

        $info = D('menu')->order(array('taxis, id desc'))
			->where($typess)
			->page($page, 10)
            ->select();
	
        $info = _____ddddd($info);
        $this->assign('info', $info);
        $this->assign('type', $types);
        $this->display();
    }
    
 
    
    // 添加菜单
    function tianjia(){ // 添加菜单页面
    	$type = array(
    			"pc_top" =>"顶部菜单",
    			"pc_bottom" =>"底部菜单",
    			"pc_weixin" =>"微信菜单",
    			"pc_App" =>"App菜单",
    	);
		$this->assign('type', $type);
		$this->display(); 
    }
    function tianjiachk() { // 添加菜单
    	//定义菜单类型
    	
        $Menu = M('menu');
        if (IS_POST) {
            $info = $Menu->create();
            $info['creatDate'] = time();
            if ($Menu->add($info)) {
                $this->success("菜单添加成功",U("index"),3);
            } else {
            	$this->error("菜单添加失败",U("index"),3);
            }
        }
    }
    
  
    // 修改菜单
    function edit(){ // 编辑菜单
    	$type = array(
    			"pc_top" =>"顶部菜单",
    			"pc_bottom" =>"底部菜单",
    			"pc_weixin" =>"微信菜单",
    			"pc_App" =>"App菜单",
    	);
    	
        $id = I("get.id");
        $Menu = M("Menu");
        $info = $Menu->find($id);
        // dump($info);exit;
        $this->assign('type', $type);
        $this->assign("info", $info);
        
        $pids = $Menu->field("id,name")->select();
        $this->assign("pids", $pids);
        if (IS_POST) {
            $data = $Menu->create();
            $flag = $Menu->save($data);
            if ($flag) {
                $this->success("修改成功！正在跳回...", U("Admin/Menu/index"), 3);
                exit;
            }else {
                $this->error("修改失败！");
            }
        }
        $this->display();
    }
    
    /* 获取菜单类型 */
    public function getType(){ // 获取菜单类型
    	$type = I("get.type","");
    	$types = M('Menu')->where("type='$type' and status = 1" )->select();
    	//print_r($types);
    	echo '<option value="0">-请选择-</option>';
    	echo getOptionsHtml($types);
    	 
    }
    
    
    function del(){ // 删除菜单
        $id=I("post.id");
        $flag=M("Menu")->delete($id);
        if($flag){
            die("删除成功");
        }else{
            die("删除失败");
        }
        
    }
    
    
    
}
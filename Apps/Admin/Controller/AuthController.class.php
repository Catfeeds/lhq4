<?php
namespace Admin\Controller;

use Think\Controller;
use Model\AuthModel;

// 权限控制器
class AuthController extends AdminController
{
    // 列表展示
    function showlist() // 权限列表
    {
        // 获得权限信息
        $order = array(
            "pid" => "asc",
            "order"=>"asc",
            "path" => "asc",
            "id" => "asc"
        );
        // 查询条件数组
        $map = array();
        
        $map["keyword"]=I("get.keyword");
        
        // 接收菜单等级
        $level = $_GET["level"];
        if (isset($level)) {
            $map["level"] = (int) $level;
        } else {
            $map["level"] = - 1;
        }
        // 如果查询全部或者刚进入页面
        if (($map["level"] == - 1)) {
            unset($map["level"]);
        }
        
        //接收父级id查询
        $pid = $_GET["pid"];        
        if (isset($pid)) {
            if($pid==-1){//只查询没有父级菜单的
                $map["pid"] = array("exp","NOT IN (select `id` from `".C('DB_PREFIX')."auth` where `pid` = 0)");
                $map["level"]=array("NEQ",0);
            }else                 
            if($pid==-2){//查询全部
               unset($map["pid"]);
               $map["level"]=array("NEQ",0);
            }else
            if($pid==-3){
                unset($map["pid"]);
            }else{
                $map["pid"] = (int) $pid;
            }
            
            $this->assign("pid",$pid); // 回传pid，用于下拉菜单定位
        }
        
        
      
        /*
         * 分页显示
         */
        $Auth = D("Auth"); // 实例化Auth模型      
        $page = I('get.p', 1, 'int');
        $count = D('Auth')->order($order)->where($map)->count(); // 总页数        
        
        $show = array(
            'total' => $count,
            'page' => $page,
            'row' => 10,
            'href' => unsetParam("p",  __SELF__) . '&p={{page}}'
        );
        
        $info = D('Auth')->order($order)->where($map)->page($page, 10)->select();   
       // die($info);
        //一级权限的id，名称
        $authA = D('Auth')->field("id,name")->order($order)->where("pid=0")->select();
        $this->assign('page',$show);
        $this->assign('authA', $authA);
        $this->assign('info', $info);
        $this->assign('ge', "--/");
        $this->display();
    }
    
    // 添加权限
    function tianjia() // 添加权限
    {
        $Auth = D('Auth');
        if (IS_POST) {
            
            // 通过saveData方法制作权限的"path"和"level"进而实现整条记录的存储
            $info = $Auth->create(); // 过滤非法字段
            if(!$info["display"]){
                $info["display"]="2";
            }
            if($info["display"]=="2"){
               $info["name"]="-".$info["name"];
            }
            //dump($info);exit;
            if ($Auth->saveData($info)) {
                $this->success("权限添加成功，正在跳回权限列表",U("showlist"));
                exit;
            } else {
                echo "fail";
            }
        }
        
        // 获得父级权限
        $order=array(
            "order"=>"asc",
        );
        $Auth_p_info = $Auth->where('level=0')->order($order)->select();
        
        $this->assign('p_info', $Auth_p_info);
        $this->display();
    }
    
    // 修改权限
    function edit() // 编辑权限
    {
        $id = I("get.id");
        $auth = M("auth");
        $info = $auth->find($id);
        // dump($info);exit;
        $this->assign("info", $info);
        
        $pids = $auth->field("id,name")
            ->where("level=0")
            ->select();
        $this->assign("pids", $pids);
        
        if (IS_POST) {
            
            $data = $auth->create();
            
            if ($data["pid"] == 0) {
                $data["level"] = 0;
                $data["path"] = $data["id"];
            } else {
                $data["level"] = 1;
                $data["path"] = $data["pid"] . "-" . $data["id"];
            }
            
            $flag = $auth->save($data);
            if ($flag) {
                $this->success("修改成功！正在跳回...", U("Admin/Auth/showlist"), 1);
                exit;
            }else {
                $this->error("修改失败！");
            }
            
           
        }
        
        $this->display();
        
    }
    
    function del(){ // 删除权限
        $id=I("post.id");
        $flag=M("Auth")->delete($id);
        if($flag){
            die("删除成功");
        }else{
            die("删除失败");
        }
        
    }
    
    function isDisplay(){ // 显示隐藏权限
      
        $id=I("post.id");
        $data=array(
            "display"=>I("post.display")
        );
        $flag=M("Auth")->data($data)->where("id=$id")->save();
        if($flag){
            die("ok");
        }else{
            die("sorry");
        }
       
    }
    
    function changeOrder(){ // 更新权限排序
        $id=I("post.id");        
        $data=array(
            "order"=>I("post.order")
        );
        $flag=M("Auth")->data($data)->where("id=$id")->save(false);
        if($flag){
            die("ok");
        }else{
            die("sorry");
        }
    }
    
    
}
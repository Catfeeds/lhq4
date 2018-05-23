<?php
namespace Admin\Controller;

use Think\Controller;

class AdminerController extends AdminController
{

    public function index(){ // 管理员首页
        
        // 查询条件数组
        $map = array();
        //dump($map);
        //接收角色id查询
        $role_id = $_GET["role_id"];
        if (isset($role_id)) {
           if($role_id==-2){//查询全部
                unset($map["role_id"]);                
            }else{
                $map["role_id"] = (int) $role_id;
            }

            $this->assign("role_id",$role_id); // 回传role_id，用于下拉菜单定位
        }
        
        // 接收状态
        $status = $_GET["status"];
        if ($status) {
            $map["status"] = (int) $status;
        } else {
            unset($map["status"]);
        }
        
        /*
         * 分页显示
         */
        $Adminer = D("adminer"); // 实例化Auth模型
        $count = $Adminer->where($map)->count(); // 总页数        
        $page = I('get.p', 1, 'int');
        $show = array(
            'total' => $count,
            'page' => $page,
            'row' => 10,
            'href' => unsetParam("p",  __SELF__) . '&p={{page}}'
        );
        $order=array(
            "role_id"=>"asc",
        );
        $info = $Adminer->where($map)->order($order)->page($page, ROWS)->select();
        
        $this->assign("page",$show);

        // 获取角色id和名称
        $roles = M("Role")->field("id,name")->select();      
        
        $this->assign("info", $info);

        $this->assign("roles", $roles);
        
       // header('Cache-Control: no-cache');
        $this->display();
    }

    public function edit($id) // 管理员编辑
    {
        $User = M('Adminer');
        if (IS_POST) {
           
            $_validate = array(                
                array('repassword','password',' 确认密码不正确 ',0,'confirm'), // 验证确认密码是否和密码一致
            );
            
            $data = $User->validate($_validate)->create();
            
            if (! $data) {
                $this->error($User->getError());
            }  
            
            if ($data['password'])
                $data['password'] = encrypt_pass($data['password']);
            else
                unset($data['password']);
            
            $flag = $User->where("uid=" . $id)->save($data);
            if ($flag) {
                $this->success("修改成功！", U("index"), 3);
            } else {
                $this->error("修改失败或未修改！"
                	. M()->_sql()
                );
            }
			die;
        }
        
        $this->assign('user', $User->find($id));
        $this->display();
    }

    function del() // 删除管理员
    {
        $id = I("post.id");
        if ($id==1) {
            die("超级管理员不能删除！");
        }
        $flag = M("Adminer")->delete($id);
        if ($flag) {
            die("删除成功");
        } else {
            die("删除失败");
        }
    }
    
    function add(){ // 新增管理员
        if(IS_POST){
            
            $validateRule = array(                
                array( "email","email",  "邮箱格式非法!" ),
                array( "mobile","/^1\d{10}$|^(0\d{2,3}-?|\(0\d{2,3}\))?[1-9]\d{4,7}(-\d{1,8})?$/",  "电话号码格式非法!"),
                array( "username","require",  "用户名不能为空!" ),
                array( "password","/\S{6,}/",  "密码必须为六位以上数字或字母的组合!" ),
                array('repassword','password',' 两次密码不一致 ',0,'confirm'),                
            );
            
            $autoRule = array(               
                array( "create_date", "time", 3, "function" ),      
             
            );
            
            $Adminer = M('Adminer');
            $Adminer->setProperty('_auto', $autoRule);
            $Adminer->setProperty('_validate', $validateRule);
            
            
            $data = $Adminer->create();
            
            if (!$data){                
                exit("<meta charset='utf-8'>".$Adminer->getError());               
            }
            unset($data["uid"]);
            $data["password"]=md5(sha1($data["password"]));
            
            if($data["role_id"]=="0"){
                $this->error("未指定角色！");
            }
           
            $flag=M("Adminer")->add($data);
            if($flag){
                $this->success("添加成功！",U("index"),3);
                exit;
            }else{
                $this->error("添加失败！");
            }  
            
        }
        $roles=M("Role")->field("id,name")->select();
        $this->assign("roles",$roles);
        $this->display();
    }
    
    // 切换状态
    public function toggleStatus() // 关闭开启管理员
    {
        $id = I('post.uid', 0, 'int');
        if ($id == 1) {
            die("admin");
        }
        if ($id) {
            if (M()->execute("update __PREFIX__adminer set status = if(status != 1, 1, 2) where uid = $id")) {
                die("成功");
            } else {
                die("失败");
            }
        }
    }


    //改变角色
    function changeRole(){ // 改变管理员角色
        $role_id=I('post.role_id', 0, 'int');
        $uid=I('post.uid', 0, 'int');
        if ($role_id) {
            if (M()->execute("update __PREFIX__adminer set role_id = ".$role_id." where uid = $uid")) {
                die("成功");
            } else {
                die("失败");
            }
        }
    }
    
}
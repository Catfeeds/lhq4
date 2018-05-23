<?php
namespace Admin\Controller;

use Think\Controller;
use Model\RoleModel;

// 角色控制器
class RoleController extends AdminController
{
    // 列表展示
    function showlist() // 角色列表
    {
        
        /*
         * 分页显示
         */
        $periods = D("Role"); // 实例化Role模型
        $count = D('Role')->count(); // 总页数
         $page = I('get.p', 1, 'int');
        $show = array(
            'total' => $count,
            'page' => $page,
            'row' => 10,
            'href' => unsetParam("p",  __SELF__) . '&p={{page}}'
        );        
        
        $this->assign("page", $show);
        /*
         * 分页显示over
         */
        
        $info = D('Role')->page($page, ROWS)->select();
        
        $this->assign('info', $info);
        $this->assign('ge', "--/");
        $this->display();
    }
    
    // 给角色分配权限    
    function distribute() // 分配权限
    {
        $role_id=I("get.id");
        $role = D("Role");
        
        if (IS_POST) {
            if ($role->saveRole($_POST['id'], $role_id)) {
               $this->success("分配成功！",U("showlist"),1);
               exit;
            } else {
               $this->error("分配失败！");
            }
        }   
       
        // 获得被分配权限角色的信息
        $role_info = $role->find($role_id);
        $authidsarr = explode(',', $role_info['auth_ids']);
        // 获得权限信息
        $order=array(
            "order"=>"asc"
        );
        $auth_infoA = D('Auth')->where('level=0')->order($order)->select();
        $auth_infoB = D('Auth')->where('level=1')->order($order)->select();
        
        $this->assign('authidsarr', $authidsarr);
        $this->assign('role_info', $role_info);
        $this->assign('auth_infoA', $auth_infoA);
        $this->assign('auth_infoB', $auth_infoB);
        $this->display();
    }

    function add(){ // 添加角色
        
        $Role = M('Role');
        //dump($Role);//die;
        if (IS_POST) {
            $info = $Role->create();// 过滤非法字段

           /* $info-> auth_ids ='';
            $info-> auth_ac ='';*/
           // $info -> auth_ids = '';
            //dump($info);die;
            if ($Role->add($info)) {
                $this->success("角色添加成功，正在跳回角色列表",U("showlist"),1);exit;
            } else {
                $this->error("角色添加失败");
            }
        }

        $this->display();
    }
    
    function del() // 删除角色
    {
        $id = I("post.id");
        $flag = M("Role")->delete($id);
        if ($flag) {
            die("删除成功");
        } else {
            die("删除失败");
        }
    }
}

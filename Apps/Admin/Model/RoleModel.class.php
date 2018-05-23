<?php

namespace Admin\Model;
use Think\Model;

//后台model模型类
class RoleModel extends Model{
    
    //给角色分配权限，数据的制作和存储
    function saveRole($authid, $roleid){
        //① 根据$authid 制作ids字符串
        $auth_ids = implode(',',$authid); //例如 : 10,12,31,32
        //② 根据$auth_ids获得对应的控制器和操作方法
        $auth_info = D('Auth')->field('auth_c,auth_a')->select("$auth_ids");
        //拼装controller 和 action
        $s = "";
        foreach($auth_info as $v){
            if(!empty($v['auth_a']))
            $s .= $v['auth_c']."-".$v['auth_a'].",";
        }
        $s = rtrim($s,',');//去除右侧逗号
        
        $sql = "update ".C('DB_PREFIX')."role set auth_ids='$auth_ids',auth_ac='$s' where id='$roleid'";
        return $this -> execute($sql);
    }
}

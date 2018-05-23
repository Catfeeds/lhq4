<?php

namespace Admin\Model;
use Think\Model;

//后台model模型类
class AuthModel extends Model{
    
    //自定义方法实现权限信息的添加
    function saveData($authinfo){
       
        //① 根据已有的4个字段生成一条记录
        $newid = $this -> add($authinfo);
        //② 根据新记录的主键id值进一步制作auth_path和auth_level
        //auth_path处理：顶级/非顶级权限
        if($authinfo['pid']==0){
            //顶级
            $path = $newid;
        }else{
            //非顶级全路径：上级权限全路径-本身记录id值
            $pinfo = $this->find($authinfo['pid']);
            $p_path = $pinfo['path'];
            $path = $p_path."-".$newid;
        }
        
        //auth_level处理：全路径变为数组后元素个数减1的结果
        $level = count(explode('-',$path))-1;
        
        $sql = "update ".C('DB_PREFIX')."auth set path='$path',level='$level' where id='$newid'";
        return $this -> execute($sql);
    }
}

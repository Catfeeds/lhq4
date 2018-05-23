<?php
namespace Weixin\Model;
use Think\Model;

/**
* 云购表模型
* @date: 2015年12月10日 上午9:56:39
* @author: 王崇全
*/
class PeriodsModel extends Model{
    
    /**
    * 将商品添加到云购表
    * @date: 2015年12月10日 下午4:47:14
    * @author: 王崇全
    * @param: array $data 商品信息,键值对形式
    * @return: Boolean 成功true , 失败false
    */
    function PeriodsAdd($data){
        if(!$data) return false;
       
        $flag=M("Periods")->add($data);
        
        /*插入pcode表开始*/
        if( $flag ){
        	if( ! buildPcode( $flag ) ){
        		return false ;
        	}
        }
        /*插入pcode表结束*/
        
        if(!flag) return false;
        
        return true;
    }
    
    /**
    * 获取云购表信息
    * @date: 2015年12月10日 下午5:01:22
    * @author: 王崇全
    * @param: [array $map 查询条件数组  ,省略即查询所有记录]
    * @param: [array $fields 要查询那些字段  ,省略即所有字段]
    * @return: array 二维索引数组
    */
    function getPeriodsInfo($map=null,$fields=null){
        
        return M("Periods")->field($fields)->where($map)->select();
        
    }
    
    /**
     * 修改云购表信息
     * @date: 2015年12月10日 下午5:01:22
     * @author: 王崇全
     * @param: array $map 筛选条件数组
     * @param: array $data 要修的数据,"字段名=>值"的形式
     * @return: Boolean 成功true,失败false.
     */
    function setPeriodsInfo($map,$data){
        if( !($map&&$data) ){
            return false;
        }
    
        $flag=M("Periods")->where($map)->save($data);
        
        if(!$flag){
            return false;
        }
        
        return true;
    
    }

    /**
    * 根据中奖码获取用户id
    * @date: 2015年12月11日 下午12:00:14
    * @author: 王崇全
    * @param: string $winningCode 中奖码
    * @return: string 用户id
    */
    function getUserId($winningCode,$goodsId,$qisu){
        
        if( (!$winningCode) || (!$goodsId) || (!$qisu)  ){
            return false;
        }

        $winUserId=M("periods_detail")
            ->where(array(
                "pcode"=>$winningCode,
                "goodsId"=>$goodsId,
                "qishu"=>$qisu,
            ))
            ->getField("userId",false);
         return $winUserId;
        
    }
    
    /**
    * 批量向云购明细表中添加数据
    * @date: 2015年12月11日 下午4:26:09
    * @author: 王崇全
    * @param: array $data 符合云购明细表格式的数据,二维数组
    * @return: Boolean 成功true,失败false
    */
    function periodsDetailAddAll($data){
        if(!$data){
            return false;
        }
       $flag=M("PeriodsDetail")->addAll($data);        
        if(!$flag){
            return false;
        }
        return true;
    }

    
}
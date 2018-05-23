<?php
namespace Weixin\Model;
use Think\Model;

/**
* 商品表模型
* @date: 2015年12月10日 上午9:56:39
* @author: 王崇全
*/
class GoodsModel extends Model{
    
    /**
    * 获取商品信息
    * @date: 2015年12月10日 下午4:13:35
    * @author: 王崇全
    * @param: string $id 商品编号
    * @return: array 商品信息
    */
    function getGoodsInfo($id){
        if(!$id){
            return false;
        }
       
        $Goods=M("Goods")->find($id);
        if(!$Goods){
            return false;
        }
        return $Goods;
    }
    
    /**
     * 检查商品是否符合购买条件, 若有正好售完的,存入session
     * @date: 2015年12月3日 上午10:08:11
     * @author: 王崇全
     * @param: string $id 商品编号
     * @param: int $num 商品数量
     * @return: Mix 符合,返回 Int当前期数; 不符合,返回 Boolean假
     */
    function checkGoods($id,$num){
        if( (!$id) || (!$num) ){
            return false;
        }
         
        $goods=M("goods")->find($id);
        $qishu=$goods["qishu"];
    
        if(!$goods){
            return false;
        }
    //echo $goods["status"];exit;
        if($goods["status"]==0){
            return false;
        }
		
        if($goods["fenshu"]-$goods["canyushu"]<$num){ //当前期数量不够
            return false;
        }
    
        return $qishu;
    
    }
    
    /**
    * 设置商品表的信息
    * @date: 2015年12月11日 上午11:15:08
    * @author: 王崇全
    * @param: array $map 筛选条件,
    * @param: array $data 要设置的数据,"字段名=>值"的形式
    * @return:成功true,失败false
    */
    function setGoodsInfo($map,$data){
        if( (!$map)||(!$data) ){
            return false;
        }
        
        $flag=M("goods")->where($map)->save($data);       
        if(!$flag){
            return false;
        }
        
        return true;
    }
    
    /**
     * 增加参与数
     * @date: 2015年12月10日 下午3:37:48
     * @author: 王崇全
     * @param: string $id 用户编号
     * @param: float $cost 要扣除的金额
     * @return: Boolean 成功true,失败false
     */
    function canyushuInc($id,$canyushu){
         
        if( (!$id) || ($canyushu<=0) ){
            return false;
        }
        $map=array(
            "id"=>$id,
        );
        $flag=M("Goods")->where($map)->setInc("canyushu",$canyushu);
    
        if(!$flag){
            return false;
        }
        return true;
    }
    
    
}
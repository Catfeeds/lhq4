<?php
/**
* 购物车模型
* @date: 2015年11月19日 上午9:37:48
* @author: 王崇全
*/
namespace Weixin\Model;
use Think\Model\RelationModel;

class ShopCartModel extends RelationModel{
    
    public $userId=null;
    
    protected $_link=array(
        "Goods"=>array(
            "mapping_type"=>self::BELONGS_TO ,
            "foreign_key"=>"goodsid",
            "as_fields"=>"title,qishu,image,fenshu,canyushu,price",
        ),
    );
    
//     function getCarts(){
//         $map=array(
//             "userId"=>UID,           
//         );
//         $fields=array("id,nums");
//         $carts=D("ShopCart")->relation(true)->field($fields)->where($map)->select();
         
//         $this->ajaxReturn ($carts,'JSON');
//     }
    
    
}
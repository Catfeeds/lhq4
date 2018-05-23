<?php  
 namespace Weixin\Controller;
 use Think\Controller;
 use Weixin\Model;
 /**
 * 购物车控制器
 * @date: 2015年11月18日 下午2:21:53
 * @author: 王崇全
 */
 class CartController extends IsLoginController{
     
     function index(){
         $this->assign("title","购物车". C('site_title_separator').C('site_title'));    
                  
         //异步获取购物车信息
         if(IS_POST){
            $this->getcart();
         }
         
         $this->display();
     }
     
     //删除购物车
     function del(){
         
         if(IS_POST){
             $id=I("post.id");
             $uid=UID;
             $flag=M("Shop_cart")->where("id=$id and userId=$uid")->delete();
             if($flag){
                  $this->getcart(); //返回删除后的结果
             }
         }else{
             die("删除失败");
         }
     }
          
     //编辑购物车
     function edit(){
        
         //接收序列化的异步数据
         $info=I("post.");
         $pnum=$info["pnum"];
         $goodsId=$info["goodsId"];
         
         //拼装购物车id,商品数量
         $i=0;
         foreach($pnum as $k=>$v){
             $id[$i]=(string)$k;
             $nums[$i]=(int)$v;
             $i++;
         }
         unset($v);
         //拼装商品编号
         $i=0;
         foreach($goodsId as $v){             
             $gid[$i]=$v;
             $i++;
         }
         unset($v);
         
         //修改购物车的数量
         for ($j=0;$j<$i;$j++){
             $map=array(
                 "id"=>$id[$j],
                 "userId"=>UID,
             );
             $data=array(
                 "nums"=>$nums[$j],
             );             
             //验证数量的合法性
             if( !(is_numeric( (int)$data['nums'] ) && ( (int)$data['nums']>0 ) ) ){
                 die("数据无效");
             }
             M("ShopCart")->where($map)->save($data);
         }
         
         die();
         
     }
     
     /**
     * 预支付, 生成订单并展示
     * @date: 2015年11月28日 上午10:10:29
     * @author: 王崇全
     */
     function prePay(){
		 
         $this->assign("title","订单付款". C('site_title_separator').C('site_title')); 
         
         //获取购物车信息
         $map=array(
             "userId"=>UID,
             'status' => 1,
         );
        
         //获取购物车信息
         $fields=array("id,nums,goodsId");
         $carts=D("ShopCart")->relation(true)->field($fields)->where($map)->select(); 
       
         if(!$carts){  //购物车空,跳转
             $this->error("购物车是空的, 再去挑点儿吧...",U("index/index"),2);
         }
             
         /*
          * 生成订单信息
          * */
         
         //要存入订单表的数据
         $ordData=array();

         //生成订单号
         $orderCode=date("ymdHis", time()).mt_rand(1000, 9999).mt_rand(1000, 9999).mt_rand(1000, 9999).mt_rand(1000, 9999).mt_rand(1000, 9999);
 
         //计算总价
         $ordData["cost"]=0;
         foreach($carts as $v){
              $ordData["cost"]+=$v['nums']*$v['price'];
         }
         $total=sprintf("%.2f",$ordData["cost"]);   

         $ip = get_client_ip();
         $Ip = new \Org\Net\IpLocation('QQWry.dat');
         $area = $Ip->getlocation($ip);

         $ordData["ip"] = $ip;
         $ordData["area"] = isset($area['country']) ? iconv('gb2312', 'utf-8', $area['country']) : '';
         //获取用户ID,订单生成时间,订单编号
         $ordData["user_id"]=UID; 
         $ordData["creat_date"]=time(); 
         $ordData["order_code"]=$orderCode;

         //订单记录入库
         $flag=M("order")->data($ordData)->add();
         if(!$flag){
             $this->error("订单生成失败!");
         }
         
         /*
          * 生成订单明细信息,并入库
          * */         
         $detailData["order_code"]=$orderCode;
         foreach ($carts as $v) {
             $detailData["goods_id"]=$v["goodsid"];
             $detailData["title"]=$v["title"];
             $detailData["nums"]=$v["nums"];
             $detailData["image"]=$v["image"];
             $detailData["price"]=$v["price"];
             $detailData["user_id"]=UID;
             $flag=M("order_detail")->data($detailData)->add();
             if(!$flag){
                 $this->error("订单明细保存失败!");
             }
         }         
        
         /*
          * 清空购物车
          * */
         // $flag=M("ShopCart")->where($map)->delete();
         if(!$flag){
             $this->error("购物车清空失败!");
         }
         
         /*
          * 显示订单信息
          * */
          
         //回传余额
         $uinfo=D("user")->field("balance")->find(UID);
         $this->assign("balance",$uinfo["balance"]);
          
         //回传订单编号
         $this->assign("orderCode",$orderCode);
          
         //获取订单明细,并回传
         $ordDetail=M("OrderDetail")->where("order_code=$orderCode")->select();
         $this->assign("ordDetail",$ordDetail);
          
         //回传总金额
         $this->assign("total",$total);
         
         layout('Layout/noFoot');
         $this->display();
     }
         
     
     /**
     * 异步获取购物车信息
     * @date: 2015年11月19日 下午2:16:34
     * @author: 王崇全
     * @param: 
     * @return: 索引类型的json对象数组
     */
     function getcart(){
         $map=array(
            "userId"=>UID,
             'status' => 1,
         );
         $fields=array("id,nums,goodsId");
         $carts=D("ShopCart")->relation(true)->field($fields)->where($map)->select();
        // dump($carts);exit;
          
         $this->ajaxReturn ($carts,'JSON');
     }
         
     /**
     * 添加商品至购物车 异步请求
     * @date: 2015年11月19日 下午2:43:30
     * @author: 王崇全
     */
     public function add() {
         $goodsId = I('id');
         $num = I('num', 1, 'int');
     
         if(!$goodsId)
             $this->error( '<i class="fa fa-remove"></i> 商品 ID 错误' );
         elseif(!$num)
            $this->error( '<i class="fa fa-remove"></i> 商品数量 错误' );
     
     
         // 获取商品
         $goods = M('goods')->find($goodsId);
         if(!$goods){
           $this->error( '<i class="fa fa-remove"></i> 商品不存在' );
         }else{
             $goods['spare'] = $goods['fenshu'] - $goods['canyushu']; 
         }

         if($num > $goods['spare']){
             $this->error( '<i class="fa fa-remove"></i> 所剩份数不足' );
         }

         // 购物车数据
         $cartItem = array(
             'userId' => UID,
             'goodsId' => $goodsId,
             'qishu' => $goods['qishu'],
             'nums' => $num,
             'updateDate' => time(),
             'status' => 1,
         );
     
         // 实例化 购物车模型
         $ShopCartModel = M('shop_cart');
     
         // 获取 购物车当前商品
         $cart = $ShopCartModel->where(
             array(
                 'userId' => UID,
                 'goodsId' => $goodsId,
             )
          )->find();
     
         // 更新 或 添加 数据
         if($cart){
             $total = $cart['nums'] + $num > $goods['spare'] ? $num : $cart['nums'] + $num;
             $cartItem['nums'] = $total;
             $ShopCartModel->where('id='. $cart['id'])->save($cartItem);
         }else{
             $cartItem['creatDate'] = $cartItem['updateDate'];
             $ShopCartModel->add($cartItem);
         }

         json(array(
         		'info' => "<i class='fa fa-check'></i> 添加成功",
         		'status' => 1,
         		'num' => $ShopCartModel->where(array(
					'userId' => UID,
         			'status' => 1,
         		))->count(),
         ));
         
     }
     
     
     /**
      * 添加商品至购物车 供回舔商品用
      * @date: 2015年12月02日 下午16:46:30
      * @param string $id 商品id
      * @param int $num 商品数量
      * @author: 王崇全
      */
     public function reAdd($goodsId,$num=1) {
        
         // 获取商品
         $goods = M('goods')->find($goodsId);
         if(!$goods){
             $this->error( '商品不存在' );
         }else{
             $goods['spare'] = $goods['fenshu'] - $goods['canyushu'];
         }
     
         if($num > $goods['spare']){
             $this->error( '所剩份数不足' );
         }
     
         // 购物车数据
         $cartItem = array(
             'userId' => UID,
             'goodsId' => $goodsId,
             'qishu' => $goods['qishu'],
             'nums' => $num,
             'updateDate' => time(),
             'status' => 1,
         );
         
         // 实例化 购物车模型
         $ShopCartModel = M('shop_cart');

         // 获取 购物车当前商品
         $cart = $ShopCartModel->where(
             array(
                 'userId' => UID,
                 'goodsId' => $goodsId,
             )
         )->find();
            
             // 更新 或 添加 数据
             if($cart){
                 $total = $cart['nums'] + $num > $goods['spare'] ? $num : $cart['nums'] + $num;
                 $cartItem['nums'] = $total;
                 $ShopCartModel->where('id='. $cart['id'])->save($cartItem);
             }else{
                 $cartItem['creatDate'] = $cartItem['updateDate'];
                 $ShopCartModel->add($cartItem);
             }
             
                 
     }
      
     /**
     *检查购物车中是否有某商品 , 异步POST请求
     * @date: 2015年12月14日 下午3:23:32
     * @author: 王崇全
     * @param: string $goodsId
     * @return:string 有,1;无,0
     */
     function isExist(){
         $goodsId = I('id');
         if(!$goodsId)    die( '商品 ID 错误' );
         
         // 获取商品
         $goods = M('goods')->find($goodsId);
         if(!$goods){
             die( '商品不存在' );
         }else{
             $goods['spare'] = $goods['fenshu'] - $goods['canyushu'];
         }         
         
         // 购物车数据
         $map = array(
             'userId' => UID,
             'goodsId' => $goodsId,
             'qishu' => $goods['qishu'],
         );
         
         $flag=M("ShopCart")->field("id")->where($map)->find();
         
         if(!$flag){
             die("0");
         }
         die("1");
         
     }
     
     
     function getNum(){

     	$cartNum = M('shop_cart')->where(array('userId'=>UID, 'status' => 1))->count();
     	
     	$msgNum = M('user_friends')->where('flag = 0 AND friendsId = ' . UID)->count();
     	
     	json(array(
     			'status' => 1,
     			'nums' => array(
     					'shop-cart' => $cartNum,
     					'user-msg' => $msgNum,
     			)
     	));
     }
 }
 
 
 
 
 
 
 
 
 
 
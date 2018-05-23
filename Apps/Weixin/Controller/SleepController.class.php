<?php

namespace Weixin\Controller ;

use Think\Controller ;

/**
 * 作弊控制器
 * @date 2016年1月9日 上午11:54:23
 * @author 王崇全
 */
class SleepController extends Controller {


    public function index( ) {
         // echo 12112;
		$d = 0;
		layout( false ) ;
		$d && wsDebug('自助:Start');
		//1 取出有效的作弊信息
		//$cheatList = D( "Cheat" ) -> getCheatList( ) ;
        $cheatList = D( "cheat" ) ->where( "switch = 1" )-> select( ) ;;
		//dump($cheatList);//die;
		foreach( $cheatList as $v ){
            //dump($v);
            $periodsList = D( "periods" ) -> where( array(
                "status" => 3,
                "goodsId" => $v["goods_id"],
                "winningCode" => 0
            ) )->find() ;
            //dump($periodsList);

			//小于间隔时间,不执行自动购买
            if($v['last_time']==0){
              //  echo 1;
                $count= floor((time( ) - $periodsList["creatdate"]+$v["start_time"] * 60)/$v['interval_time']);
                // $time=rand(0,$v['interval_time']);
               // dump($v['last_time']);
                $periodList=(int)$periodsList["creatdate"];
                $start=(int)$v["start_time"] * 60;
                $interval=(int)$v["interval_time"];
                $v['last_time']=$periodList+$start+$interval;

                M( 'Cheat' ) -> save( $v) ;

            }else{
                if( time( ) - $v['last_time'] < $v["interval_time"] ){
                    //echo 1;
                    continue ;
                }
                $count=round((time( ) - $v['last_time'])/$v['interval_time']);

            }
            //dump($count);
           // die;
                //2 取出正在进行的云购商品//getPeriodsInfo
                //die;
                //3 判断此商品是否已经超过设定的开始作弊时间
                if( time( ) - $periodsList["creatdate"] < $v["start_time"] * 60 ){
                    //echo 1;
                    continue ;
                }

                $d && wsDebug('Goods:'. $v["goods_id"]);
                    //开启事务

                    M( ) -> startTrans( ) ;
                    try{
                        //dump($count);
                        for($i=0; $i<=$count; $i++){

                        //4 从机器人表中挑选一个参与自动购买次数最少的机器人
                        $userid=D('user')->field("member_id")->where('is_robot=1')->order('member_id desc')->select();
                        $arr=array();
                        foreach ($userid as $key => $value) {
                            $arr[] = $value['member_id'];

                        }
                        $random_keys=array_rand($arr,1);
                        $uid=$arr[$random_keys];
                        //$robotID = D( "Cheat" ) -> getRobotForCheat( ) ;
                        $cheatGood= M( "cheat_robot" )->where(array("user_id"=>$uid,"goods_id"=>$v["goods_id"]))->find();
                        //选择一个机器dump($cheatGood);//die;

                        if(empty($cheatGood) || $cheatGood==''){
                            //查找机器人
                            $cheatRobot["user_id"]=$uid;
                            $cheatRobot["goods_id"]=$v["goods_id"];
                            $cheatRobot["counter"]='1';
                          //  dump($cheatRobot);
                            $robotID =M( "cheat_robot" ) -> add( $cheatRobot ) ;
                            //dump($robotID);
                            if( ! $robotID){
                                //回滚
                                $d && wsDebug('自助:End');
                                M( ) -> rollback( ) ;
                                continue ;
                            } else {
                                //M( ) -> commit( ) ;
                            }
                            $d && wsDebug('自助:'. $robotID);
                        }else{
                            //更新购买次数
                            $cheatR=M("cheat_robot")->where(array('user_id'=>$uid,'goods_id'=>$v["goods_id"]))->find();
                            $counters=(int)$cheatR['counter'];
                            $data["counter"] =$counters+1;
                           // dump($data);
                            $counter =M( "cheat_robot" )->where(array('id'=>$cheatR['id']))-> save($data);
                            //dump($counter);
                        }
                        //5 让机器人自动买一件商品
                        //5.1 生成虚假订单
                        //5.1.1订单表

                            //随机自动购买时间
                            $time=rand(0,$v['interval_time']);
                        $goodsInfo = D( "Goods" ) -> getGoodsInfo( $v["goods_id"]) ;
                        $ordData["order_code"] = date( "ymdHis" , time( ) ) . mt_rand( 1000 , 9999 ) . mt_rand( 1000 , 9999 ) . mt_rand( 1000 , 9999 ) . mt_rand( 1000 , 9999 ) . mt_rand( 1000 , 9999 ) ;
                        $ordData["user_id"] = $uid;
                        $ordData["creat_date"] = $v['last_time']+$time ;
                        $ordData["pay_method"] = "balance" ;

                       // dump($goodsInfo);
                        // 购买数量
                        if( $goodsInfo["fenshu"] - $goodsInfo["canyushu"] <= 10 ){
                            $detailData["nums"] = $goodsInfo["fenshu"] - $goodsInfo["canyushu"] ;
                        }else{
                            $a = 1 ;
                            $t = time( ) ;
                            if( $t % 2 == 0 ){
                                $a = rand( 1 , 9 ) ;
                            }
                            if( $t % 3 == 0 ){
                                $a = 1 ;
                            }
                            if( $t % 7 == 0 ){
                                $a = 2 ;
                            }
                            if( $t % 11 == 0 ){
                                $a = 5 ;
                            }
                            if( $t % 13 == 0 ){
                                $a = 10 ;
                            }
                            if( $t % 17 == 0 ){
                                $a = 15 ;
                            }

                            $detailData["nums"] = $a ;
                        }
                            //查看剩余数
                        $goodsInfo2 = D( "Goods" ) -> getGoodsInfo( $v["goods_id"]) ;

                        if(($goodsInfo2["fenshu"] - $goodsInfo2["canyushu"])<=$detailData["nums"] ){
                           // echo 1;
                            $detailData["nums"]=$goodsInfo2["fenshu"] - $goodsInfo2["canyushu"];
                            $v["switch"]=0;
                            $cheat =M( "cheat" )-> save($v);

                        }


                        $ordData["cost"] = $goodsInfo["price"] * $detailData["nums"] ;
                            //获取ip
                            //dump($uid);
                            $ips=D('user')->field("addr,ip")->where(array('member_id'=>$uid))->find();

                        $ordData["ip"] = $ips['ip'];
                        $ordData["area"] = $ips['addr'];
                        $orderID = M( "order" ) -> add( $ordData ) ;

                        if( ! $orderID ){
                            //回滚
                            M( ) -> rollback( ) ;
                            continue ;
                        }

                        //5.1.2 订单明细表
                        $detailData["order_code"] = $ordData["order_code"] ;
                        $detailData["goods_id"] = $v["goods_id"] ;
                        $detailData["title"] = $goodsInfo["title"] ;
                        $detailData["image"] = $goodsInfo["image"] ;
                        $detailData["price"] = $goodsInfo["price"] ;
                        $detailData["user_id"] = $uid ;
                        //dump($detailData);
                        $orderDetailID = M( "order_detail" ) -> add( $detailData ) ;

                        if( ! $orderDetailID ){
                            //回滚

                            M( ) -> rollback( ) ;
                            continue ;
                        }



                        //5.2 虚拟支付
                            $v['last_time']=$v['last_time']+$v['interval_time'];
                            // dump($v['last_time']);
                            M( 'Cheat' ) -> save( $v) ;
                        //检验订单中的商品能否购买
                        if( D( "Order" ) -> checkOrder( $ordData["order_code"] ) ){
                            //回滚
                            M( ) -> rollback( ) ;
                            continue ;
                        }

                        $flag = D( "Pay" ) -> afterPay( $ordData["order_code"] , true ) ;
                           // dump($flag);die;

                        if( $flag ){

                            //提交事务
                            M( ) -> commit( ) ;
                           // die( "ok" ) ;
                        }else{

                            //回滚
                            M( ) -> rollback( ) ;
                           // die( "no" ) ;
                        }
                        if( $v["switch"]==0){
                            break;
                        }
                       }

                    }catch(\Exception $e){
                        M( ) -> rollback( ) ;
                        $d && wsDebug($e->getMessage());
                        $d && wsDebug(M()->_sql());
                    }






        }

			//修改上次购买时间



	}


}
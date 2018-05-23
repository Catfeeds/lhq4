<?php

/*
 * To change this template, choose Tools | Templates
 * 财务报表页面
 * 高黄金
 * 2016/03/01
 */
namespace Admin\Controller ;

use Think\Controller ;
use Admin\ORG ;

class FinanceController extends AdminController {
    public $listPage=50;//每页多少个
    public function index(){
        if(IS_GET){
            $keyword = explode(',',urldecode(I('keyword')));
//            $start = strtotime($keyword[0]);
//            $end = strtotime($keyword[1])+86400;
            $name = $keyword[0];               
            $map['u.status'] = 1;//必须是启用的

//            if($start && $end){
//                $map['ud.creatDate']  = array('between',array($start,$end));
//                $keyword[0] = date("Y-m-d", $start);
//                $keyword[1] = date("Y-m-d", $end+86400);
//            }
            
            if($name){
                $map['u.phone'] = $name;
                $u_count = M('User')
                        ->alias("u")
//                        ->join('yytb_user_detail ud on u.id=ud.userId')
                        ->where($map)->count();
            }else{                
                $u_count = M('User')
                        ->alias("u")
//                        ->join('yytb_user_detail ud on u.id=ud.userId')
                        ->where($map)->count();                
            }
            $Page = new \Admin\ORG\AdminPage($u_count,$this->listPage);// 实例化分页类 传入总记录数  
            $nowPage = isset($_GET['p'])?$_GET['p']:1;
            $u_result = M('User')
                        ->alias("u")
                        ->field('u.id,u.nickName,u.phone,u.balance,u.comSum')
                        ->page($nowPage.','.$this->listPage)
                        ->order('id desc')
//                        ->join('yytb_user_detail ud on u.id=ud.userId')
                        ->where($map)->select();
            $info['balance']=0;$info['comSum']=0;$info['grandRechange']=0;$info['grandBuy']=0;
            foreach($u_result as $key => $value){
                $rel = '';
                $rel = M()->query('select m.ct as m,n.ct as n from ( select user_id, sum(cost) as ct from yytb_order 
                        where status =1 and order_type in (0,3) and order_status = 1 and user_id = '.$value['id'].' ) m ,
                            (select user_id, sum(cost) as ct from yytb_order 
                        where status =1 and  order_type =1 and order_status = 1 and user_id = '.$value['id'].' ) n
                            limit 1
                            ');
                $u_result[$key]['grandrechange'] = $rel[0]['m'];//购买记录
                $u_result[$key]['grandbuy'] = $rel[0]['n'];//充值记录
                $info['balance'] = $info['balance']+$value['balance'];
                $info['comsum'] = $info['comsum']+$value['comsum'];
                $info['grandrechange'] = $info['grandrechange']+$rel[0]['m'];
                $info['grandbuy'] = $info['grandbuy']+$rel[0]['n'];
            }
            
            $show = $Page->show();// 分页显示输出
            $this->assign('page',$show);// 赋值分页输出
            $this -> assign( 'keyword' , $keyword ) ;
             $this -> assign( 'p' , I('p')?I('p'):1 ) ;
            $this -> assign( 'info' , $info ) ;
            $this -> assign( 'list' , $u_result ) ;            
        }
        $this->display();
    }
    //明细
    public function show(){
        if(I('id')){
		
		$balance = M( 'User' ) -> where( 'id = ' .I('id') )->find();
                $list = M( 'UserChange' ) -> where( 'u_id = ' .I('id') )->select();
//                dump($list);die;
		$this -> assign( 'list' , $list ) ;
		$this -> assign( 'balance' , $balance ) ;
		$this -> display( ) ;
        }else{
            $this->error('数据不存在');
        }
    }
    //充值明细
    public function accountpay(){
        if(I('id')){
                $list = M('Recharge')->where(array('userId'=>I('id')))->select();
//                dump($list);die;
                $balance = M( 'user' ) -> where( 'id = ' .I('id') )->find();
                $this -> assign( 'list' , $list ) ;
                $this -> assign( 'balance' , $balance ) ;
                $this -> display( ) ;
        }else{
            $this->error('数据不存在');
        }
    }
    
    public function exportExcel($expTitle,$expCellName,$expTableData){
        $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
        $fileName = $_SESSION['loginAccount'].date('_YmdHis');//or $xlsTitle 文件名称可根据自己情况设定
        $cellNum = count($expCellName);
        $dataNum = count($expTableData);
//        vendor("PHPExcel.PHPExcel");
        $objPHPExcel = new \Admin\ORG\PHPExcel();
        $cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');
        
        $objPHPExcel->getActiveSheet(0)->mergeCells('A1:'.$cellName[$cellNum-1].'1');//合并单元格
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle.'  Export time:'.date('Y-m-d H:i:s'));  
        for($i=0;$i<$cellNum;$i++){
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i].'2', $expCellName[$i][1]); 
        } 
          // Miscellaneous glyphs, UTF-8   
        for($i=0;$i<$dataNum;$i++){
          for($j=0;$j<$cellNum;$j++){
            $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+3), $expTableData[$i][$expCellName[$j][0]]);
          }             
        }  
        
        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.xls"');
        header("Content-Disposition:attachment;filename=$fileName.xls");//attachment新窗口打印inline本窗口打印
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
        $objWriter->save('php://output'); 
        exit;   
    }
    public function exportdate(){
        if(IS_GET){
            $keyword = explode(',',urldecode(I('keyword')));
            $name = $keyword[0];               
            $map['u.status'] = 1;//必须是启用的
            $map['u.is_robot'] = 0;//不是机器人
            
            if($name){
                $map['u.phone'] = $name;
                $u_count = M('User')
                        ->alias("u")
                        ->where($map)->count();
            }else{                
                $u_count = M('User')
                        ->alias("u")
                        ->where($map)->count();                
            }
            $Page = new \Admin\ORG\AdminPage($u_count,$this->listPage);// 实例化分页类 传入总记录数  
            $nowPage = isset($_GET['p'])?$_GET['p']:1;
            $u_result = M('User')
                        ->alias("u")
                        ->field('u.id,u.nickName,u.phone,u.balance,u.comSum')
                        ->page($nowPage.','.$this->listPage)
                        ->where($map)->select();
            $info['balance']=0;$info['comSum']=0;$info['grandRechange']=0;$info['grandBuy']=0;
            foreach($u_result as $key => $value){
                $rel = '';
                $rel = M()->query('select m.ct as m,n.ct as n from ( select user_id, sum(cost) as ct from yytb_order 
                        where status =1 and order_type in (0,3) and order_status = 1 and user_id = '.$value['id'].' ) m ,
                            (select user_id, sum(cost) as ct from yytb_order 
                        where status =1 and  order_type =1 and order_status = 1 and user_id = '.$value['id'].' ) n
                            limit 1
                            ');
                $u_result[$key]['grandrechange'] = $rel[0]['m'];//购买记录
                $u_result[$key]['grandbuy'] = $rel[0]['n'];//充值记录
                $info['balance'] = $info['balance']+$value['balance'];
                $info['comsum'] = $info['comsum']+$value['comsum'];
                $info['grandrechange'] = $info['grandrechange']+$rel[0]['m'];
                $info['grandbuy'] = $info['grandbuy']+$rel[0]['n'];
            }     
            
            $xlsName  = "财务报表";
            $xlsCell  = array(
                    array('id','ID'),
                    array('nickname','名字'),
                    array('phone','手机'),
                    array('balance','余额'),
                    array('comsum','全城积分'),
                    array('grandrechange','累计充值'),
                    array('grandbuy','累计购买'),
                );
            $this->exportExcel($xlsName,$xlsCell,$xlsData);
            echo "<script language='javascript'>window.opener=null;window.open('','_self');window.close();</script>";
        }
    }
}
?>

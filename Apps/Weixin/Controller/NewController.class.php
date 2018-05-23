<?php

namespace Weixin\Controller;
use Think\Controller;

/**
 * 显示静态页面的临时控制器
 * @date: 2015年11月16日 下午4:27:24
 * @author: 王崇全
 */
class NewController extends CommonController {
    
    function ahout(){
        $this->assign("title","了解云购". C('site_title_separator').C('site_title'));       
        $this->display();        
    }
    
    function account(){
        $this->assign("title","账户明细". C('site_title_separator').C('site_title'));    
        $this->display();
    }
    
    function address_add(){
        $this->assign("title","地址管理". C('site_title_separator').C('site_title'));    
        $this->display();
    }
    
    function address(){
        $this->assign("title","收获地址". C('site_title_separator').C('site_title'));    
        $this->display();
    }
    
    function buy_detail(){
        $this->assign("title","获得者本云所有云购码". C('site_title_separator').C('site_title'));    
        $this->display();
    }
    
    function commission(){
        $this->assign("title","佣金明细". C('site_title_separator').C('site_title'));    
        $this->display();
    }
    
    function invite_record(){
        $this->assign("title","邀请记录". C('site_title_separator').C('site_title'));    
        $this->display();
    }
    
    function my_config(){
        $this->assign("title","账号设置". C('site_title_separator').C('site_title'));    
        $this->display();
    }
    
    function recharge_card(){
        $this->assign("title","充值卡充值". C('site_title_separator').C('site_title'));    
        $this->display();
    }
    
    function recharge(){
        $this->assign("title","网银充值". C('site_title_separator').C('site_title'));    
        $this->display();
    }
    
    function share_cash(){
        $this->assign("title","提现记录". C('site_title_separator').C('site_title'));    
        $this->display();
    }
    
    function share_detail(){
        $this->assign("title","分享明细". C('site_title_separator').C('site_title'));    
        $this->display();
    }
    
    function share_list(){
        $this->assign("title","分项列表". C('site_title_separator').C('site_title'));    
        $this->display();
    }
    
    function share(){
        $this->assign("title","分享赚钱". C('site_title_separator').C('site_title'));    
        $this->display();
    }
    
    function yg_record(){
        $this->assign("title","云购记录". C('site_title_separator').C('site_title'));    
        $this->display();
    }
    
    
    function show_article(){
        $this->assign("title","了解云团". C('site_title_separator').C('site_title'));
        $id = I('get.id',0);
        if($id){
            $article = M('article')->find($id);
       }else{
            $this->error('内容未找到',U('index/index'));
        }
        $this->assign('article',$article);        
        $this->display();
    }
    
    
        
}
<?php
namespace Weixin\Controller ;

use Think\Controller ;


/**
 * 微信端的父控制器
 * @date: 2015年11月16日 下午4:27:16
 * @author: 王崇全
 */
class CommonController extends Controller {

	function _initialize( ) {
		
		$host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
        
        if(filter_var($host, FILTER_VALIDATE_IP)) {// 合法IP
            echo "<script>location.href='404.html'</script>"; 
            die; 
        }

		D('periods')->where("status = 2 AND discloseDate < ". time())->save(array(
				'status' => 1,
		));
		//触发抽奖
//		$this->timing();
		// $SendMsgController = new SendMsgController();
		// $SendMsgController->timing();

	// 	if(!IS_AJAX){
	// 		$P = new \Weixin\Model\PeriodsModel();
	// 		// $P->updatePeriods();
	// 	}

		//checkHostByIlanguo();
		//$invitenum = I( 'get.invitenum' ) ;
		if( $invitenum ){
			cookie( 'invitenum' , null ) ;
			cookie( 'invitenum' , $invitenum , 43200 ) ;
		}
		//读取数据库中的配置,并合并配置参数到全局配置
		C( M( 'system' ) -> cache( 'system_config' , 120 ) 
			-> where( 'status=1' ) 
			-> getField( 'name,val' ) ) ;
	}

}
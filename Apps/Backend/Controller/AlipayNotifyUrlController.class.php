<?php
namespace Backend\Controller;
use Think\Controller;
use Backend\Common\ErrorCode;
class AlipayNotifyUrlController extends Controller{
	public function index(){
		C( D( 'yytb_system' ) -> cache( 'system_config' , 120 ) 
            -> where( 'status=1' ) 
            -> getField( 'name,val' ) ) ;
		require(APP_PATH.'../Thinkphp/Library/Vendor/alipay_batch_trans/alipay.config.php');
		//计算得出通知验证结果
		$alipayNotify = new \AlipayNotify($alipay_config);
		$verify_result = $alipayNotify->verifyNotify();

		if($verify_result) {//验证成功
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			//请在这里加上商户的业务逻辑程序代

			
			//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
			
		    //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
			
			//批量付款数据中转账成功的详细信息

			$success_details = $_POST['success_details'];

			//批量付款数据中转账失败的详细信息
			$fail_details = $_POST['fail_details'];

            $arr['notify_id'] = $_POST['notify_id'];
            $arr['notify_time'] = $_POST['notify_time'];
            $arr['batch_no'] = $_POST['batch_no'];
            $arr['pay_user_name'] = $_POST['pay_user_name'];
            $arr['pay_account_no'] = $_POST['pay_account_no'];
            //判断是否在商户网站中已经做过了这次通知返回的处理
            $if_exists = D('alipay_batch_trans')->where(array('batch_no'=>$arr['batch_no']))->select();
            //如果没有做过处理，那么执行商户的业务程序
            if (!$if_exists) {
            	D('alipay_batch_trans')->data($arr)->add();
            	$pay_time = $arr['notify_time'];
            	$success = explode('|',$success_details);
            	foreach ($success as $k => $v) {
            		$user_pay_info = explode('^', $v);
            		$user_update['reason'] = '成功';
            		$user_update['pay_time'] = $pay_time;
            		$user_update['status'] = '4';
            		D('drawing')->where(array('wd_no'=>$user_pay_info['0']))->save($user_update);
            	}

            	$fail = explode('|',$fail_details);
            	foreach ($fail as $k => $v) {
            		$user_pay_info = explode('^', $v);
            		$user_update['reason'] = ErrorCode::$user_pay_info['5'];
            		$user_update['pay_time'] = $pay_time;
            		$user_update['status'] = '5';
            		D('drawing')->where(array('wd_no'=>$user_pay_info['0']))->save($user_update);
            	}
            }				
			//如果有做过处理，那么不执行商户的业务程序
		        
			echo "success";		//请不要修改或删除

			//调试用，写文本函数记录程序运行情况是否正常
			//logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");

			//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
			
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}
		else {
		    //验证失败
		    echo "fail";

		    //调试用，写文本函数记录程序运行情况是否正常
		    //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
		}
	}
}
vendor('alipay_batch_trans.lib.alipay_notify', '', '.class.php');
?>
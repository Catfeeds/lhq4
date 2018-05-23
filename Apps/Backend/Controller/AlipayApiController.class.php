<?php
namespace Backend\Controller;
use Think\Controller;
class AlipayApiController extends ComController{
	public function index(){
		require(APP_PATH.'../Thinkphp/Library/Vendor/alipay_batch_trans/alipay.config.php');
		 //服务器异步通知页面路径
        $notify_url = ADMIN_URL."/Backend/AlipayNotifyUrl/index";
        //需http://格式的完整路径，不允许加?id=123这类自定义参数

        //付款账号
        $email = C('alipay_account_email');
        //必填

        //付款账户名
        $account_name = C('alipay_account_name');
        //必填，个人支付宝账号是真实姓名公司支付宝账号是公司名称

        //付款当天日期
        $pay_date = date('Ymd');
        //必填，格式：年[4位]月[2位]日[2位]，如：20100801

        //批次号
        $batch_no = date('YmdHis');
        //必填，格式：当天日期[8位]+序列号[3至16位]，如：201008010000001

        //付款总金额
        $batch_fee =I('get.WIDbatch_fee');
        //必填，即参数detail_data的值中所有金额的总和

        //付款笔数
        $batch_num = I('get.WIDbatch_num');
        //必填，即参数detail_data的值中，“|”字符出现的数量加1，最大支持1000笔（即“|”字符出现的数量999个）

        //付款详细数据$_POST['WIDdetail_data']
        $detail_data = I('get.WIDdetail_data');
        //必填，格式：流水号1^收款方帐号1^真实姓名^付款金额1^备注说明1|流水号2^收款方帐号2^真实姓名^付款金额2^备注说明2....

		/************************************************************/

		//构造要请求的参数数组，无需改动
		$parameter = array(
				"service" => "batch_trans_notify",
				"partner" => trim($alipay_config['partner']),
				"notify_url"	=> $notify_url,
				"email"	=> $email,
				"account_name"	=> $account_name,
				"pay_date"	=> $pay_date,
				"batch_no"	=> $batch_no,
				"batch_fee"	=> $batch_fee,
				"batch_num"	=> $batch_num,
				"detail_data"	=> $detail_data,
				"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
		);
		//建立请求
		$alipaySubmit = new \AlipaySubmit($alipay_config);
		$html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
		echo $html_text;
	}

}
vendor('alipay_batch_trans.lib.alipay_submit', '', '.class.php');


?>
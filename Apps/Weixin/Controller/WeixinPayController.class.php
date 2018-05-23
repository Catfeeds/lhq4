<?php
//zend54   
//Decode by www.dephp.cn  QQ 2859470
?>
<?php
namespace Weixin\Controller;

class WeixinPayController extends \Weixin\Controller\CommonController
{
	public function aaa()
	{
		$notify = new PayNotifyCallBack();
		$notify->Handle(false);
		$nn = new PayNotifyCallBack();
		$nn->NotifyProcess();
	}
}

class PayNotifyCallBack extends WxPayNotify
{
	public function Queryorder($transaction_id)
	{
		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input, 20);
		if (array_key_exists("return_code", $result) && array_key_exists("result_code", $result) && ($result["return_code"] == "SUCCESS") && ($result["result_code"] == "SUCCESS")) {
			return true;
		}

		return false;
	}

	public function NotifyProcess($data, &$msg)
	{
		$order_code = $data["out_trade_no"];
		$Pay = new \Weixin\Model\PayModel();
		M()->startTrans();
		$orderInfo = D("Order")->getOrderInfo($order_code);
		$order_type = $orderInfo["order_type"];

		if ($order_type == 3) {
			$flag = $Pay->bbAfterPay($order_code, $data["total_fee"] / 100);
		}
		else {
			$flag = $Pay->afterPay($order_code, $data["total_fee"] / 100);
		}

		if ($flag) {
			$uinfo = session("userInfo");
			$data["grandBuy"] = array("exp", "grandBuy+" . ($data["total_fee"] / 100));
			M("User")->where("id='" . UID . "'")->save($data);
			if (cookie("invitenum") && (cookie("invitenum") != cookie("uid"))) {
				$inviteid = M("User")->where("md5(id)='" . cookie("invitenum") . "'")->getField("id");
				$ud["userId"] = $uinfo["id"];
				$ud["name"] = "XF";
				$ud["consume"] = $data["total_fee"] / 100;
				$ud["comval"] = ($data["total_fee"] / 100) * C("COM_XF");
				$ud["creatDate"] = time();
				$ud["rpUserId"] = $inviteid;
				M("user_detail")->add($ud);
				$data["comsum"] = array("exp", "comsum+" . $ud["comval"]);
				M("User")->where("md5(id)='" . $inviteid . "'")->save($data);
			}

			M()->commit();
			$this->success("支付成功", U("User/pay_success", array("codeid" => $order_code)), 1);
			return true;
		}
		else {
			M()->rollback();
			return false;
		}
	}
}

C(array("WEI_XIN_REPORT_LEVENL" => 1, "WEI_XIN_CURL_PROXY_HOST" => "0.0.0.0", "WEI_XIN_CURL_PROXY_PORT" => 0, "WEI_XIN_SSLCERT_PATH" => "apiclient_cert.pem", "WEI_XIN_SSLKEY_PATH" => "apiclient_key.pem"));
vendor("WxPay.lib.WxPay#JsApiPay", "", ".php");
vendor("WxPay.lib.WxPay#Notify", "", ".php");


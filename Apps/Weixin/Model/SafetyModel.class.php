<?php
//zend54   
//Decode by www.dephp.cn  QQ 2859470
?>
<?php
namespace Weixin\Model;

class SafetyModel extends \Think\Model
{
	protected $autoCheckFields = false;

	public function loginRmaind($userid)
	{
		$appid = C("wei_xin_appid");
		$secret = C("wei_xin_appsecret");
		vendor("WeChat.OrderPush");
		$OrderPush = new OrderPush($appid, $secret);
		$template_id = "ckNtre99f0yshe2BqaiF_b2k8FTsdsseLW9mWwUFxu4";
		$touser = M("UserFastLogin")->where(array("user_id" => $userid))->getField("fast_login_id", false);
		$OrderPush->LoginSend($touser, $template_id);
		return NULL;
	}

	public function validate($phone, $sign, $code)
	{
		$signCode = md5(sha1(C("SEND_MSG_SIGN") . "$phone$code"));

		if ($signCode === $sign) {
			$check = array("sta" => true, "phone" => $phone);
			session("check", $check);
			return true;
		}
		else {
			return false;
		}
	}

	public function sendMsg($phone)
	{
		if (!preg_match("/^1[\d]{10}$/", $phone)) {
			return false;
		}

		$code = rand(100000, 999999);
		$time = time();
		$ip = get_client_ip();
		$sign = md5(sha1(C("SEND_MSG_SIGN") . "$phone$code"));
		$oldTime = S("ip/send_time_$ip");
		if ($oldTime && (($time - $oldTime) < 120)) {
			return false;
		}

		S("ip/send_time_$ip", $time, 120);
		$content = str_replace("{code}", $code, C("sms_tpl_code"));
		$url = str_replace(array("{phone}", "{content}"), array($phone, $content), C("sms_api_url"));
		$c = file_get_contents($url);
		return $sign;
	}

	public function reg()
	{
		$this->assign("title", "密码设置" . C("site_title_separator") . C("site_title"));
		$check = session("check");

		if (!$check["sta"]) {
			$this->error("请先进行身份验证", U("index"));
		}
	}
}



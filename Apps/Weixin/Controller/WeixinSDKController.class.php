<?php
namespace Weixin\Controller;

class WeixinSDKController extends \Weixin\Controller\CommonController
{
	private $appId;
	private $appSecret;

	public function _initialize()
	{
		parent::_initialize();
		$this->appId = "wx44e75a73a7b50d77";
		$this->appSecret = "77756ee72d11921a4f561cc5237e7b63";
	}

	public function getconfig()
	{
		echo $this->appId;
		echo $this->appSecret;
	}

	public function getSignPackage()
	{
		$jsapiTicket = $this->getJsApiTicket();
		$url = $_SERVER["HTTP_REFERER"];
		$timestamp = time();
		$nonceStr = $this->createNonceStr();
		$string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
		$signature = sha1($string);
		$signPackage = array("appId" => $this->appId, "nonceStr" => $nonceStr, "timestamp" => $timestamp, "url" => $url, "signature" => $signature, "rawString" => $string);
		json($signPackage);
	}

	private function createNonceStr($length = 16)
	{
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$str = "";

		for ($i = 0; $i < $length; $i++) {
			$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}

		return $str;
	}

	private function getJsApiTicket()
	{
		$data = S("weixin_getJsApiTicket");
		if (!$data || ($data->expire_time < time())) {
			$accessToken = $this->getAccessToken();
			$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
			$res = json_decode($this->httpGet($url));
			$ticket = $res->ticket;

			if ($ticket) {
				S("weixin_getJsApiTicket", $data, 7200);
			}
		}
		else {
			$ticket = $data->jsapi_ticket;
		}

		return $ticket;
	}

	private function getAccessToken()
	{
		$data = S("weixin_getAccessToken");
		if (!$data || ($data->expire_time < time())) {
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
			$res = json_decode($this->httpGet($url));
			$access_token = $res->access_token;

			if ($access_token) {
				S("weixin_getAccessToken", $data, 7200);
			}
		}
		else {
			$access_token = $data->access_token;
		}

		return $access_token;
	}

	private function httpGet($url)
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 500);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_URL, $url);
		$res = curl_exec($curl);
		curl_close($curl);
		return $res;
	}
}



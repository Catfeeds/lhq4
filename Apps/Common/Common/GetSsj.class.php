<?php
//zend54   
//Decode by www.dephp.cn  QQ 2859470
?>
<?php
namespace Common\Common;

class GetSsj
{
	static public function getNum()
	{
		$date = (isset($_GET["date"]) ? $_GET["date"] : "");

		if (!preg_match("/[\d-]{10}/", $date)) {
			$date = date("Y-m-d");
		}

		$url = "http://baidu.lecai.com/lottery/draw/sorts/ajax_get_draw_data.php?lottery_type=200&date=" . $date;
		$headers = array("Referer: http://baidu.lecai.com/lottery/draw/view/200", "X-Requested-With: XMLHttpRequest", "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.130 Safari/537.36");
		$json = httpGet($url, "", $headers);
		$json = $json["content"];
		$json = json_decode($json, true);
		header("Access-Control-Allow-Origin: *");

		if ($json) {
			$arr = array();
			$arr["qishu"] = $json["data"]["data"][0]["phase"];
			$arr["time_draw"] = $json["data"]["data"][0]["time_draw"];
			$arr["num"] = implode("", $json["data"]["data"][0]["result"]["result"][0]["data"]);
			$arr["next_qishu"] = $arr["qishu"] + 1;
		}

		$tmp_date = date("Hi", strtotime($arr["time_draw"]));
		$timestamp = strtotime($arr["time_draw"]);
		//开奖时间在10点到22点之间
		if ((959 < $tmp_date) && ($tmp_date < 2201)) {
			$arr["next_date"] = $timestamp + 600;
			$arr["next_next_date"] = $timestamp + 1200;
		}
		else {
			if (($tmp_date < 155) || (($tmp_date <= 2359) && (2159 < $tmp_date))) {
				$arr["next_date"] = $timestamp + 300;
				$arr["next_next_date"] = $timestamp + 600;
			}
			else {
				$tmp_var = date("Y-m-d");
				$arr["next_date"] = strtotime($tmp_var . " 10:02:00");
			}
		}

		return $arr;
	}

	static public function winning($type, $num)
	{
		if (!$type) {
			return false;
		}

		if ($num) {
			$where["yytb_order.create_msec"] = array("elt", $num);
		}

		$where["yytb_order.order_status"] = 1;
		$sum = M("Order")->where($where)->order("create_msec desc")->limit("100")->sum("create_msec");
		$record = M("order")->join(" member ON yytb_order.user_id = member.member_id")->field(" yytb_order.user_id, yytb_order.create_msec, member.nickname")->where($where)->order("yytb_order.create_msec desc")->limit("100")->select();
		$result["sum"] = $sum;
		$result["record"] = $record;
		return $result;
	}

	public function httpGet($url = false, $post = array(), $headers = array(), $type = "GET", $timeout = 25)
	{
		if (!$url) {
			return false;
		}

		$c = curl_init();
		curl_setopt($c, CURLOPT_URL, $url);
		curl_setopt($c, CURLOPT_HEADER, false);

		if (!empty($post)) {
			curl_setopt($c, CURLOPT_POST, true);
			curl_setopt($c, CURLOPT_POSTFIELDS, is_array($post) ? http_build_query($post) : $post);
		}

		curl_setopt($c, CURLOPT_CUSTOMREQUEST, $type);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($c, CURLOPT_TIMEOUT, $timeout);

		if (!empty($headers)) {
			curl_setopt($c, CURLOPT_HTTPHEADER, $headers);
		}

		$content = curl_exec($c);
		$response = curl_getinfo($c);
		curl_close($c);
		return array("response" => $response, "content" => $content);
	}
}



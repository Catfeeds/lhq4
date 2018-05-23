<?php
//zend54   
//Decode by www.dephp.cn  QQ 2859470
?>
<?php
namespace Weixin\Controller;

class SendMsgController
{
	public function __construct($appid, $secrect)
	{
		C(M("system")->cache("system_config", 120)->where("status=1")->getField("name,val"));
	}

	public function index()
	{
		layout(false);
		$this->timing();
		$list = M("msg_queue")->where(" `sended` =0 AND `send_time` <=" . time())->order(array("create_time" => "asc"))->limit(10)->select();

		if (!$list) {
			exit("null");
			return NULL;
		}

		foreach ($list as $v ) {
			if ($v["type"] != 1) {
				continue;
			}

			if (time() < $v["send_time"]) {
				continue;
			}

			$content = $v["content"];
			$url = $v["go_url"];
			$status = D("Notice")->weichatNotice(array($v["send_to"]), $content, $url);

			if ($status) {
				M("msg_queue")->where(array("id" => $v["id"]))->save(array("sended" => 1));
				file_put_contents("sendMsg.log.txt", date(PHP_EOL . "Y-m-d H:i:s", time()) . "＞suc" . PHP_EOL, 8);
			}
			else {
				M("msg_queue")->where(array("id" => $v["id"]))->save(array("sended" => 0));
				file_put_contents("sendMsg.log.txt", date(PHP_EOL . "Y-m-d H:i:s", time()) . "＞err" . PHP_EOL, 8);
			}
		}

		M("msg_queue")->where(" `create_time` <" . (time() - (3600 * 24 * 7)) . " AND `sended` =1")->delete();

		if ((1024 * 1024 * 10) < filesize("cheat_success.log.txt")) {
			file_put_contents("cheat_success.log.txt", "");
		}

		exit("done");
	}

	public function timing()
	{
		$where["discloseDate"] = array("elt", time());
		$where["status"] = 2;
		$result = M("Periods")->field("qishu,goodsId as id")->where($where)->select();

		if ($result) {
			foreach ($result as $value ) {
				D("Pay")->lottery($value);
			}
		}

		$where["lottery_time"] = $where["discloseDate"];
		unset($where["discloseDate"]);
		$data = M("Bbooking")->where($where)->select();

		if ($data) {
			foreach ($data as $v ) {
				$map = "";
				$map["bbid"] = $v["id"];
				$map["goods_id"] = $v["goods_id"];
				$goodsInfo["order_code"] = M("OrderDetail")->where($map)->getField("order_code");
				$goodsInfo["goods_title"] = $v["goods_title"];
				D("Pay")->bbLottery($goodsInfo);
			}
		}
	}
}



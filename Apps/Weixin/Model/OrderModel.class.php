<?php
//zend54   
//Decode by www.dephp.cn  QQ 2859470
?>
<?php
namespace Weixin\Model;

class OrderModel extends \Think\Model
{
	public function checkOrder($orderCode)
	{
		if (!defined("UID")) {
			define("UID", session("robotID"));
		}

		if (!$orderCode) {
			return false;
		}

		if ($this->alrPay($orderCode)) {
			return false;
		}

		$noCanBuys = $this->canBuy($orderCode);

		if ($noCanBuys[-1] == -1) {
			$param = array();

			foreach ($noCanBuys as $k => $v ) {
				if ($k == -1) {
					continue;
				}

				R("Cart/reAdd", array($v["id"], $v["num"]));
			}

			$map = array("order_code" => $orderCode, "user_id" => UID);
			M("order_detail")->where($map)->delete();
			M("order")->where("order_code = $orderCode")->delete();
			return true;
		}
		else {
			return false;
		}
	}

	public function bbCheckOrder($orderCode)
	{
		if (!$orderCode) {
			return false;
		}

		if ($this->alrPay($orderCode)) {
			return false;
		}

		$order_detail = M("order_detail")->field("bbid")->where(array("order_code" => $orderCode, "user_id" => UID))->find();
		$bbid = $order_detail["bbid"];
		$bbInfo = M("bbooking")->find($bbid);
		if (($bbInfo["amount"] <= $bbInfo["sales"]) || ($bbInfo["status"] != 1) || (($bbInfo["amount"] - $bbInfo["sales"]) < $order_detail["nums"])) {
			return false;
		}

		return true;
	}

	public function canBuy($orderCode)
	{
		if (!$orderCode) {
			return false;
		}

		$tOrderDetail = C("DB_PREFIX") . "order_detail";
		$tGoods = C("DB_PREFIX") . "goods";
		$join = "LEFT JOIN $tGoods ON $tOrderDetail.goods_id =$tGoods.id";
		$field = "order_code,$tGoods.id,nums";
		$goodsInfo = M("order_detail")->field($field)->join($join)->where("order_code=$orderCode")->select();

		if (!$goodsInfo) {
			$this->error("订单无效");
		}

		$Goods = D("Goods");
		$noCanBuys = array();
		$i = $flag = 0;

		foreach ($goodsInfo as $v ) {
			if ($Goods->checkGoods($v["id"], $v["nums"])) {
				$noCanBuys[$i]["id"] = $v["id"];
				$noCanBuys[$i]["num"] = $v["nums"];
				$i++;
			}
			else {
				$flag = -1;
			}
		}

		if ($flag == -1) {
			$noCanBuys[-1] = -1;
		}

		return $noCanBuys;
	}

	public function getOrderInfo($orderCode)
	{
		if (!$orderCode) {
			return false;
		}

		$map = array("order_code" => $orderCode);
		$order = M("Order")->where($map)->find();

		if (!$order) {
			return false;
		}

		return $order;
	}

	public function getOrder($orderID)
	{
		if (!$orderID) {
			return false;
		}

		$order = M("Order")->find($orderID);

		if (!$order) {
			return false;
		}

		return $order;
	}

	public function addOrder($data)
	{
		if (!$data) {
			return false;
		}

		$orderID = M("Order")->add($data);
		return $orderID;
	}

	public function addOrderDetail($oreDatailData)
	{
		if (!$oreDatailData) {
			return false;
		}

		try {
			$flag = M("order_detail")->addAll($oreDatailData);
		}
		catch (Exception $e) {
			print_r($oreDatailData);
			exit(M()->_sql());
		}

		if (!$flag) {
			return false;
		}

		return true;
	}

	public function setOrderInfo($map, $data)
	{
		if (!$map || !$data) {
			return false;
		}

		$flag = M("Order")->where($map)->save($data);

		if (!$flag) {
			return false;
		}

		return true;
	}

	public function setOrderDetailInfo($map, $data)
	{
		if (!$map || !$data) {
			return false;
		}

		$flag = M("OrderDetail")->where($map)->save($data);

		if (!$flag) {
			return false;
		}

		return true;
	}

	public function getOrderDetail($orderCode)
	{
		if (!$orderCode) {
			return false;
		}

		$info = M("order_detail")->where(array("order_code" => $orderCode))->find();
		return $info;
	}

	public function getOrderDetailJoinGoods($orderCode)
	{
		if (!$orderCode) {
			return false;
		}

		$tOrderDetail = C("DB_PREFIX") . "order_detail";
		$tGoods = C("DB_PREFIX") . "goods";
		$join = "\r\n            LEFT JOIN $tGoods\r\n            ON $tOrderDetail.goods_id =$tGoods.id\r\n        ";
		$field = "\r\n            $tOrderDetail.order_code,\r\n            $tOrderDetail.nums ,\r\n            $tGoods.id,\r\n            $tGoods.canyushu,\r\n            $tGoods.qishu,\r\n            $tGoods.title,\r\n            $tGoods.image,\r\n            $tGoods.typeId,\r\n            $tGoods.limit_buy,\r\n            $tGoods.fenshu,\r\n            $tGoods.originprice,\r\n            $tGoods.price\r\n        ";
		$orderInfo = M("order_detail")->field($field)->join($join)->where("order_code=$orderCode")->select();
		return $orderInfo;
	}

	public function getOrderDetailJoinBBooking($orderCode)
	{
		if (!$orderCode) {
			return false;
		}

		$tOrderDetail = C("DB_PREFIX") . "order_detail";
		$tGoods = C("DB_PREFIX") . "bbooking";
		$join = "\r\n            LEFT JOIN $tGoods\r\n            ON $tOrderDetail.bbid =$tGoods.id\r\n        ";
		$field = "\r\n            $tOrderDetail.order_code,\r\n            $tOrderDetail.nums ,\r\n            $tGoods.id,\r\n            $tGoods.sales,          \r\n            $tGoods.goods_title,\r\n            $tGoods.goods_img,\r\n            $tGoods.goods_type,\r\n            $tGoods.amount,\r\n            $tGoods.goods_value,\r\n            $tGoods.goods_price\r\n        ";
		$bbInfo = M("order_detail")->field($field)->join($join)->where("order_code=$orderCode")->select();
		return $bbInfo;
	}

	public function alrPay($orderCode)
	{
		if (!$orderCode) {
			return false;
		}

		$alrPay = M("Order")->where(array("order_code" => $orderCode))->getField("order_status", false);

		if ($alrPay == 1) {
			return true;
		}

		return false;
	}
}



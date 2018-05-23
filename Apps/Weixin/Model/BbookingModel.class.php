<?php
//zend54   
//Decode by www.dephp.cn  QQ 2859470
?>
<?php
namespace Weixin\Model;

class BbookingModel extends \Think\Model
{
	public function addBbooking($data)
	{
		if (!$data) {
			return false;
		}

		$bbid = M("bbooking")->add($data);
		return $bbid;
	}

	public function getInfo($bbid)
	{
		if (!$bbid) {
			return false;
		}

		$info = M("bbooking")->where(array("id" => $bbid))->find();

		if (!$info) {
			return false;
		}

		return $info;
	}

	public function getInfobyGoodsID($goodsID)
	{
		if (!$goodsID) {
			return false;
		}

		$info = M("bbooking")->where(array("id" => $goodsID))->find();

		if (!$info) {
			return false;
		}

		return $info;
	}

	public function bbookingDetailAddAll($data)
	{
		if (!$data) {
			return false;
		}

		try {
			$flag = M("bbooking_detail")->addAll($data);
		}
		catch (Exception $e) {
			throw $e;
		}

		if (!$flag) {
			return false;
		}

		return true;
	}

	public function addTpin($bbid, $friends)
	{
		if (!$bbid && $friends) {
			return false;
		}

		$i = 0;

		foreach ($friends as $v ) {
			$tpinData[$i]["bbid"] = $bbid;
			$tpinData[$i]["tpin"] = $v;
			$i++;
		}

		$flag = M("BbookingTpin")->addAll($tpinData);

		if (!$flag) {
			return false;
		}

		return true;
	}

	public function existBBooking($userID, $goodsID)
	{
		if (!$userID && $goodsID) {
			E("参数缺失");
		}

		$bbooking = M("bbooking")->field("id,status")->where(array("creater" => $userID, "goods_id" => $goodsID))->find();

		if ($bbooking["status"] != 1) {
			return false;
		}

		if ($bbooking) {
			return true;
		}

		return false;
	}

	public function salesInc($bbid, $canyushu)
	{
		if (!$bbid || ($canyushu <= 0)) {
			return false;
		}

		$map = array("id" => $bbid);
		$flag = M("bbooking")->where($map)->setInc("sales", $canyushu);

		if (!$flag) {
			return false;
		}

		return true;
	}

	public function setInfo($map, $data)
	{
		if (!$map && $data) {
			return false;
		}

		$flag = M("bbooking")->where($map)->save($data);

		if (!$flag) {
			return false;
		}

		return true;
	}
}



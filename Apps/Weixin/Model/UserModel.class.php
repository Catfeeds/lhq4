<?php
//zend54   
//Decode by www.dephp.cn  QQ 2859470
?>
<?php
namespace Weixin\Model;

class UserModel extends \Think\Model
{
	protected $tablePrefix = '';
	protected $tableName = "member";
	public function addUser($data)
	{
		if (!$data) {
			return false;
		}

		$id = D("user")->add($data);

		if (!$id) {
			return false;
		}

		return $id;
	}

	public function getUserInfo($id)
	{
		if (!$id) {
			return false;
		}

		return D("user")->find($id);
	}

	public function setUserInfo($map, $data)
	{
		if (!$map || !$data) {
			return false;
		}

		$flag = D("User")->where($map)->save($data);

		if (!$flag) {
			return false;
		}

		return true;
	}

	public function balanceDec($id, $cost)
	{
		if (!$id || ($cost <= 0)) {
			return false;
		}

		$mapUser = array("member_id" => $id);
		$flag = D("User")->where($mapUser)->setDec("balance", $cost);

		if (!$flag) {
			return false;
		}

		return true;
	}

	public function setLogin($uid)
	{
		if (!$uid) {
			return false;
		}

		$userinfo = D("User")->field("member_id,phone,password")->find($uid);

		if (!$userinfo) {
			return false;
		}

		$config = array("expire" => 3600 * 24 * 30);
		session("userInfo", $userinfo, $config);
		cookie("userId", $userinfo["id"], $config);
		cookie("userAuthSign", data_auth_sign($userinfo), $config);
		$loginTime = time();
		session("lastLoginTime", $loginTime, $config);
		cookie("lastLoginTime", $loginTime, $config);
		cookie("LLT", sha1(md5($loginTime)), $config);
		D("user")->where(array("id" => $uid))->save(array("last_login_time" => $loginTime));
		$inviteDate1 = cookie("invite");

		if ($inviteDate1) {
			cookie("invite", null);
			$this->success("登陆成功,正在跳转....", U("Room/bookingDetail") . "&bbid1=" . $inviteDate1["bbid"] . "&invite1=" . $inviteDate1["code"] . "&isLogin=1");
			exit();
		}

		return true;
	}

	public function updateImage()
	{
		$_newImage = false;

		if (isset($_POST["face"])) {
			if (!preg_match("/^.*?image\/.*?,(.*)$/i", $_POST["face"], $base64String)) {
				$this->msg = "上传失败";
				return false;
			}
			else {
				$base64String = $base64String[1];

				if ($base64String) {

					$_newImage = dirname(__ROOT__)."/pic/" . getOrderNumber(24) . ".jpg";

					file_put_contents($_newImage, base64_decode($base64String));
				}
			}
		}
		else if (isset($_FILES["facePic"])) {
			$upload = new \Think\Upload();
			$upload->maxSize = 1024 * 1204 * 5;
			$upload->exts = array("jpg", "gif", "png", "jpeg");
			$upload->rootPath = "../../";
			$upload->savePath = dirname(__ROOT__)."/pic/";

			$info = $upload->uploadOne($_FILES["facePic"]);

			if (!$info) {
				$this->msg = "上传失败";

				return false;
				return $upload->getError();
			}
			else {
				$_newImage = $info["savepath"] . $info["savename"];

			}
		}
		else {
			$this->msg = "未上传头像";
			return false;
		}

		if ($_newImage) {
			$_oldImage = D("user")->where("member_id=" . UID)->getField("Pic");
			if ($_oldImage && !empty($_oldImage) && !preg_match("/\/icon\.png$/", $_oldImage) && file_exists(__WEB_ROOT__ . $_oldImage)) {
				unlink(__WEB_ROOT__ . $_oldImage);
			}

			D("user")->where("member_id = " . UID)->save(array("pic" => $_newImage));
			$this->msg = "上传成功";
			$this->facePic = $_newImage;
			return true;
		}
	}

	public function getUserByName($nickName)
	{
		$users = D("user")->where(array("nickname" => $nickName))->select();
		return $users;
	}
}



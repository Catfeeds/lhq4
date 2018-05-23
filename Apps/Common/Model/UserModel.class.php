<?php
namespace Common\Model;
use Think\Model;
use Common\Common\Common;
use Common\Common\UserSession;
use Common\Common\OSAEncrypt;

class UserModel extends Model{
	// 表名
//	protected $tablePrefix = 'osa_'; 
	protected $tableName = 'osa_user';
	protected $fields = array('user_id','user_name','password','real_name','mobile','email','user_desc','login_time','status','login_ip','user_group','template','shortcuts','show_quicknote');

	//状态定义
	const ACTIVE = 1;
	const DEACTIVE = 0;
	// public function getTableName(){
	// 	return parent::$table_prefix.self::$table_name;
	// }
	
	 public function getUserByName($user_name) {

	 	$sql= "select * ,g.group_name from osa_user u, osa_user_group g where u.user_name='$user_name' and u.user_group=g.group_id";
	 	$list =(new \Think\Model())->query($sql);// self::getTableName(), self::$columns, $condition );
	 	if ($list) {
	 		$list['login_time']=Common::getDateTime($list['login_time']);
	 		return $list;
	 	}
	 	return array ();
	 }
	
	 public function getUserById($user_id) {
	 	if (! $user_id || ! is_numeric ( $user_id )) {
	 		return false;
	 	}

	 	$common = new Common();
	 	
	 	$condition = array(
	 					"user_id" => $user_id,
	 				);
	 	$list = M('osa_user')->where($condition)->select();
	 	if ($list) {
	 		$list[0]['login_time']=$common::getDateTime($list[0]['login_time']);
	 		return $list [0];
	 	}
	 	return array ();
	 }
	
	 public function setCookieRemember($encrypted,$day=7){
	 	setcookie("osa_remember",$encrypted,time()+3600*24*$day);
	 }
	
	 public function getCookieRemember(){
	 	$encrypted = $_COOKIE["osa_remember"];
	 	$base64=urldecode($encrypted);
	 	return (new OSAEncrypt())->decrypt($base64);
	 }
	
	 public function logout(){
	 	setcookie("osa_remember","",time()-3600);
	 	unset($_SESSION[UserSession::SESSION_NAME]);
	 	unset($_SESSION['osa_timezone']);
	 }
	
	 public function getAllUsers( $start ='' ,$page_size='' ) {

	 	$common = new Common();

	 	$limit ="";
	 	if($page_size){
	 		$limit =" limit $start,$page_size ";
	 	}
	 	$sql = "select * ,coalesce(g.group_name,'已删除') from osa_user u left join  osa_user_group g on u.user_group = g.group_id order by u.user_id desc $limit";
		
	 	$list=(new \Think\Model())->query($sql);;
	 	if(!empty($list)){
	 		foreach($list as &$item){
				
	 			$item['login_time']=$common->getDateTime($item['login_time']);
	 		}
	 	}
		
	 	if ($list) {
	 		return $list;
	 	}
	 	return array ();
	 }
	
	 public function search($user_group ,$user_name, $start ='' ,$page_size='' ) {

	 	$limit ="";
	 	$where = "";
	 	if($page_size){
	 		$limit =" limit $start,$page_size ";
	 	}
	 	if($user_group >0  && $user_name!=""){
	 		$where = " where u.user_group=$user_group and u.user_name like '%$user_name%'";
	 	}else{
	 		if($user_group>0){
	 			$where = " where u.user_group=$user_group ";
	 		}
	 		if($user_name!=""){
	 			$where = " where u.user_name like '%$user_name%' ";
	 		}
	 	}
	 	$sql = "select * ,coalesce(g.group_name,'已删除') from osa_user u left join osa_user_group g on u.user_group = g.group_id $where order by u.user_id desc $limit";
		
	 	$list=(new \Think\Model())->query($sql);
	 	if(!empty($list)){
	 		foreach($list as &$item){
				
	 			$item['login_time']=Common::getDateTime($item['login_time']);
	 		}
	 	}
	 	if ($list) {
	 		return $list;
	 	}
	 	return array ();
	 }
	
	 public function getUsersByGroup( $group_id ) {
	 	$condition = array(
	 			"user_group" => $group_id,
	 					);
	 	$list = M('osa_user')->where($condition)->select();
	 	if ($list) {
	 		foreach($list as &$item){
	 			if($item['login_time']==null){
	 				;
	 			}else{
	 				$item['login_time']=Common::getDateTime($item['login_time']);
	 			}
	 		}
	 		return $list;
	 	}
	 	return array ();
	 }
	
	 public function checkPassword($user_name, $password) {
	 	$md5_pwd = md5 ( $password );

	 	$condition = array("user_name" => $user_name,
	 						"password" => $md5_pwd,
	 				 );
					
	 	$list = M('osa_user')->where($condition)->select();
	 	if ($list) {
			
	 		return $list [0];
	 	} else {
	 		return false;
	 	}
	 }
	
	 public function updateUser($user_id,$user_data) {
		
	 	if (! $user_data || ! is_array ( $user_data )) {
	 		return false;
	 	}

		$condition=array("user_id"=>$user_id);
		
		$id = M('osa_user')->where($condition)->save($user_data);
		return $id;
	}
	
	 /**
	 * 批量修改用户，如批量修改用户分组
	 * user_ids 可以为无key数组，也可以为1,2,3形势的字符串
	 */
	 public function batchUpdateUsers($user_ids,$user_data) {

	 	if (! $user_data || ! is_array ( $user_data )) {
	 		return false;
	 	}
	 	if(!is_array($user_ids)){
	 		$user_ids=explode(',',$user_ids);
	 	}

	 	$condition['user_id']=array('in', $user_ids);

	 	$id = M('osa_user')->where($condition)->save($user_data);
	 	return $id;
	 }
	
	 public function addUser($user_data) {
	 	if (! $user_data || ! is_array ( $user_data )) {
	 		return false;
	 	}
//dump($user_data);
	 	$id = M('osa_user')->add($user_data);
	 	//dump($id);
	 	return $id;
	 }
	
	 public function delUser($user_id) {
	 	if (! $user_id || ! is_numeric ( $user_id )) {
	 		return false;
	 	}
	 	$condition = array("user_id"=>$user_id);
	 	$result = M("osa_user")->where($condition)->delete();
	 	return $result;
	 }
	
	 public function delUserByUserName($user_name) {
	 	if (! $user_name ) {
	 		return false;
	 	}
	 	$condition = array("user_name"=>$user_name);
	 	$result = M("osa_user")->where($condition)->delete();
	 	return $result;
	 }
	
	 public function count($condition = '') {
	 	
	 	$num = count(M('osa_user')->where($condition)->select());
	 	return $num;
	 }
	
	 public function countSearch($user_group,$user_name) {

	 	$condition = array();
	 	if($user_group >0  && $user_name!=""){
	 		$condition['user_group']=$user_group;
	 		$condition['LIKE']=array("user_name"=>$user_name);
	 	}else{
	 		if($user_group>0){
	 			$condition['user_group']=$user_group;
	 		}
	 		if($user_name!=""){
	 			$condition['LIKE']=array("user_name"=>$user_name);
	 		}
	 	}
	 	$num = count(M('osa_user')->where($condition)->select());
	 	return $num;
	 }
	
	 public function setTemplate($user_id,$template){
	 	$user_data=array("template"=>$template);
	 	$ret=$this->updateUser($user_id,$user_data);
	 	return $ret;
	 }
	
	 public function loginDoSomething($user_id){
		
	 	$user_info = $this->getUserById($user_id);
	 	$common = new \Common\Common\Common();
	 	
	 	if($user_info['status']!=1){
	 		$common->jumpUrl("login.php");
	 		return;
	 	}
		$userGroupModel = new User_groupModel();
		$menuUrl = new Menu_urlModel();
	 	//读取该用户所属用户组将该组的权限保存在$_SESSION中
	 	$user_group = $userGroupModel->getGroupById($user_info['user_group']);
		
	 	$user_info['group_id']=$user_group['group_id'];
	 	$user_info['user_role']=$user_group['group_role'];
	 	$user_info['shortcuts_arr']=explode(',',$user_info['shortcuts']);
	 	$menu = $menuUrl->getMenuByUrl('/admin/setting.php');
	 	if(strpos($user_group['group_role'],$menu['menu_id'])){
	 		$user_info['setting']=1;
	 	}
		
	 	$login_time = time();
	 	$login_ip = $common->getIp ();
	 	$update_data = array ('login_ip' => $login_ip, 'login_time' => $login_time );
	 	$this->updateUser ( $user_info['user_id'], $update_data );
	 	$user_info['login_ip']=$login_ip;
	 	$user_info['login_time']=$common->getDateTime($login_time);
	 	UserSession::setSessionInfo( $user_info);
	 }
}
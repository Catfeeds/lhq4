<?php
namespace Common\Model;
use Think\Model;
class Menu_urlModel extends Model {
	protected $tablePrefix = 'osa_'; 
	// 查询字段
	const SESSION_NAME = 'osa_menuurl_list';
	
	public function getMenuByRole($user_role,$online=1) {
		$url_array = array ();
		if (!empty($user_role)) {			
		    $map['me.menu_id'] = array('in',$user_role);
		}
		$map['me.online'] = $online;
        $map['mo.online'] = 1;
		$list =$this-> alias( 'me' )  
		-> join( 'LEFT JOIN osa_module mo ON me.module_id = mo.module_id' )
		-> where( $map ) 
		-> select( ) ;
		// $sql ="select * from osa_menu_url as me ,oas_module as mo 
		//        where me.menu_id in ($user_role) and me.online=$online 
		//        and me.module_id = mo.module_id and  mo.online=1";
		 
		// $list = $this->query($sql);
		
		if ($list) {
			foreach ( $list as $menu_info ) {
				$url_array [] = $menu_info ['menu_url'];
			}
			return $url_array;
		}
		return array ();
	}
	
	 public function getMenuByUrl($url) {
	 	$url_array = array ();
	 	$condition = array("menu_url" => $url);

	 	$list = M('osa_menu_url')->where($condition)->select();
//	 	dump($list);
	 	$moduleModel = new ModuleModel();
	 	if ($list) {
	 		 $menu= $list[0];
	 		 $module = $moduleModel->getModuleById($menu['module_id']);
	 		 $menu['module_id']=$module['module_id'];
	 		 $menu['module_name']=$module['module_name'];
	 		 $menu['module_url']=$module['module_url'];
	 		 if($menu['father_menu']>0){
	 			 $father_menu=$this->getMenuById($menu['father_menu']);
	 			 $menu['father_menu_url'] = $father_menu['menu_url'];
	 			 $menu['father_menu_name'] = $father_menu['menu_name'];
	 		 }

	 		 return $menu;
	 	}
	 	return array ();
	 }
	
	public function getListByModuleId($module_id,$type="all" ) {
		if (! $module_id || ! is_numeric ( $module_id )) {
			return array ();
		}
		switch ($type){
			case "sidebar":
				$sub_condition["is_show"] = 1;
				$sub_condition["online"] =1;
				break;
			case "role":
				$sub_condition["online"] =1;
				break;
			case "navibar":
				$sub_condition["is_show"] = 1;
				$sub_condition["online"] =1;
				break;
			default:
		}
		$sub_condition ["module_id"] = $module_id;
		// $condition = array("AND" => $sub_condition);
		// $db=self::__instance();
		$list = $this->where($sub_condition)->select();
		//$list = $db->select ( self::getTableName(), self::$columns, $condition );
		if ($list) {
			return $list;
		}
		return array ();
	}
	
	 public function getFatherMenuForOptions() {
	 	//$menu_options_array=array("0"=>"无");
	 	$modules = (new ModuleModel())->getAllModules();
	 	foreach($modules as $module){
	 		$list = $this->getListByModuleId($module['module_id'],'navibar');
	 		foreach ($list as $menu){
	 			$menu_options_array[$module['module_name']][$menu['menu_id']]=$menu['menu_name'];
	 		}
	 	}
	 	return $menu_options_array;
	 }
	
	 public function addMenu($function_data) {
	 	if (! $function_data || ! is_array ( $function_data )) {
	 		return false;
	 	}
	 	$id = M('osa_menu_url')->add($function_data );
	 	self::clearSession();
	 	return $id;
	 }
	
	 public function getAllMenus($start ='' ,$page_size='') {
	 	$condition =array();
	 	if($page_size){
	 		$condition['LIMIT'] =array($start,$page_size);
	 	}
	 	$list =M('osa_menu_url')->where($condition)->select();
	 	$session_list = $this->getSessionMenus();
	 	foreach($list as &$menu){
	 		if($menu['father_menu']>0){
	 			$menu['father_menu_name'] = $session_list[$menu['father_menu']]['menu_name'];
	 		}
	 	}
	 	if ($list) {
	 		return $list;
	 	}
	 	return array ();
	 }
	
	 public function clearSession(){
	 	unset($_SESSION[self::SESSION_NAME]);
	 }
	
	 public function getSessionMenus() {
	 	if(array_key_exists(self::SESSION_NAME,$_SESSION)){
	 		return $_SESSION[self::SESSION_NAME];
	 	}else{
	 		$list =M('osa_menu_url')->select();
	 		$new_list=array();
	 		foreach($list as $menu){
	 			$new_list[$menu['menu_id']] = $menu;
	 		}
	 		foreach($new_list as $menu_id =>&$menu){
	 			if($menu['father_menu']>0){
	 				$menu['father_menu_name'] = $new_list[$menu['father_menu']]['menu_name'];
	 			}
	 		}
	 		if ($new_list) {
	 			$_SESSION[self::SESSION_NAME] = $new_list;
	 		}
	 		return $new_list;
	 	}	
	 }
	
	 public function search($module_id,$menu_name,$start,$page_size) {
	 	$limit ="";
	 	$where = "";
	 	if($page_size){
	 		$limit =" limit $start,$page_size ";
	 	}
	 	if($module_id >0  && $menu_name!=""){
	 		$where = " where me.module_id=$module_id and me.menu_name like '%$menu_name%'";
	 	}else{
	 		if($module_id>0){
	 			$where = " where me.module_id=$module_id ";
	 		}
	 		if($menu_name!=""){
	 			$where = " where me.menu_name like '%$menu_name%' ";
	 		}
	 	}

	 	$sql = "select * ,coalesce(mo.module_name,'已删除') from osa_menu_url me left join osa_module mo on me.module_id = mo.module_id $where order by me.module_id,me.menu_id $limit";
	 	$list=M('osa_module')->query($sql);
	 	$session_list = $this->getSessionMenus();
	
	 	foreach($list as &$menu){
	 		if($menu['father_menu']>0){
	 			$menu['father_menu_name'] = $session_list[$menu['father_menu']]['menu_name'];
	 		}
	 	}
	 	if ($list) {
	 		return $list;
	 	}
	 	return array ();
	 }
	
	 public function count($condition = '') {
	 	$num = count(M('osa_menu_url')->where($condition)->select());
	 	return $num;
	 }
	
	 public function countSearch($module_id,$menu_name) {
	 	$condition = array();
	 	if($module_id >0  && $menu_name!=""){
	 		$condition['module_id']=$module_id;
	 		$condition['LIKE']=array("menu_name"=>$menu_name);
	 	}else{
	 		if($module_id>0){
	 			$condition['module_id']=$module_id;
	 		}
	 		if($menu_name!=""){
	 			$condition['LIKE']=array("menu_name"=>$menu_name);
	 		}
	 	}
	 	$num = count(M('osa_menu_url')->where($condition)->select());
	 	return $num;
	 }
	
	 public function delMenu($menu_id) {
	 	if (! $menu_id || ! is_numeric ( $menu_id )) {
	 		return false;
	 	}
	 	$condition = array("menu_id" => $menu_id);
	 	$result = M('osa_menu_url')->where($condition )->delete();
	 	return $result;
	 }
	
	 public function getMenuById($menu_id) {
	 	if (! $menu_id || ! is_numeric ( $menu_id )) {
	 		return false;
	 	}
	 	$condition = array("menu_id" => $menu_id);
	 	$list = M('osa_menu_url')->where($condition)->select();
	 	if ($list) {
	 		return $list [0];
	 	}
		
	 	return array ();
	 }
	
	
	public function getMenuByIds($menu_ids,$online=null,$shortcut_allowed=null) {
        $list = array ();
		$privi=explode(',',$menu_ids);

		foreach ($privi as $v) {		
			$sub_condition['menu_id']=$v;
			if(isset($online)){
				$sub_condition['online']=$online;
			}
			if(isset($shortcut_allowed)){
				$sub_condition['shortcut_allowed']=$shortcut_allowed;
			}
			$list[] = $this->where($sub_condition)->select();
		}

		if ($list) {
			return $list;
		}
		return array ();
 
	}
	
	 public function updateMenuInfo($menu_id,$function_data) {
	 	if (! $function_data || ! is_array ( $function_data )) {
	 		return false;
	 	}
	 	$condition = array("menu_id" => $menu_id);
	 	$id = M('osa_menu_url')->where($condition)->data($function_data)->save();
	 	return $id;
	 }
	
	 /**
	  * 批量修改菜单，如批量修改所属模块
	  * menu_ids 可以为无key数组，也可以为1,2,3形势的字符串
	  */
	 public function batchUpdateMenus($menu_ids,$function_data) {

	 	if (! $function_data || ! is_array ( $function_data )) {
	 		return false;
	 	}
	 	if(!is_array($menu_ids)){
	 		$menu_ids=explode(',',$menu_ids);
	 	}
	 	$condition['menu_id']=array('in', $menu_ids);
		
	 	$id = M('osa_menu_url')->where($condition)->data($function_data)->save();
	 	return $id;
	 }

	
}
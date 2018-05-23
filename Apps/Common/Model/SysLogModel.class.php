<?php
namespace Common\Model;
use Think\Model;
use Common\Common\Common;

class SysLogModel extends Model {
	protected $tablePrefix = 'osa_'; 
/* 	private static $table_name = 'sys_log';
	private static $columns = array('op_id', 'user_name', 'action', 'class_name' , 'class_obj', 'result' , 'op_time');
	
	public static function getTableName(){
		return parent::$table_prefix.self::$table_name;
	} */
	
	public function addLog($user_name, $action, $class_name , $class_obj ,$result = "") {
		$now_time=time();
		$insert_data = array ('user_name' => $user_name, 'action' => $action, 'class_name' => $class_name ,'class_obj' => $class_obj , 'result' => $result ,'op_time' => $now_time);
//dump($insert_data);//die;
		//$id = $this->save($insert_data );
        $id = $this->data($insert_data)->add();
       // dump($id);die;
		return $id;
	}
	
	public function getLogs($class_name,$user_name,$start ,$page_size,$start_date='',$end_date='') {
		
		//$condition=array();
		$sub_condition = array();
		if($class_name != ''){
			$sub_condition['class_name']=$class_name;
		}	
		if($user_name != ''){
			$sub_condition['user_name']=$user_name;
		}
		if($start_date !='' && $end_date !=''){
			$sub_condition["op_time"] =array(array('gt',$end_date),array('lt',$start_date),'and');
		}
		if(empty($sub_condition)){
			$condition = array();
		}else{
			$condition["AND"] = $sub_condition;
		}
		
//		$condition["ORDER"]=" op_id desc";
//		$condition['LIMIT']=array($start,$page_size);

		$list =$this->where($sub_condition)->order(" op_id desc")->limit(array($start,$page_size))->select();
		$common = new Common();
		
		if(!empty($list)){
			foreach ($list as &$item){
				$item['op_time']=$common->getDateTime($item['op_time']);
			}
		}

		if ($list) {
			return $list;
		}
		return array ();
	}
	
	public function count($class_name='',$user_name=0) {
		
		$sub_condition = array();
		if($class_name != ''){
			$sub_condition['class_name[=]']=$class_name;
		}
		if($user_name != ''){
			$sub_condition['user_name']=$user_name;
		}
		
		if(empty($sub_condition)){
			$condition = array();
		}else{
			$condition["AND"] = $sub_condition;
		}
		
		$num = count ($this->where($condition)->select());
		return $num;
	}
	
	public function getCountByDate($class_name,$user_name,$start_date,$end_date) {
		$condition=array();
		if($class_name != ''){
			$sub_condition['class_name']=$class_name;
		}
		if($user_name != ''){
			$sub_condition['user_name']=$user_name;
		}
		$sub_condition["op_time[<>]"] =array($start_date,$end_date);
		$condition["AND"] = $sub_condition;
		
		$num =count( M("osa_sys_log")->where($condition)->select() );
		return $num;
	}
}
?>
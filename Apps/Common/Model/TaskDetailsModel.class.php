<?php
namespace Common\Model;
use Think\Model;
class TaskDetailsModel extends Model{

	// public function getDetails() {
	
	// 	//�������ַ�ʽ����Է���sample��DB
	// 	$db=self::__instance();
	// 	//$db=self::__instance(SAMPLE_DB_ID);
		
	// 	$sql="select ".self::$columns." from ".self::getTableName()." order by detail_id desc";
	// 	$list = $db->query($sql)->fetchAll();
 //        //var_dump($sql);die;
	// 	if ($list) {
	// 		return $list;
	// 	}
	// 	return array ();		
	// }
	
	// public function getDetailsArray() {
	
	// 	//�������ַ�ʽ����Է���sample��DB
	// 	$db=self::__instance();
	// 	//$db=self::__instance(SAMPLE_DB_ID);
	
	// 	$sql="select ".self::$columns." from ".self::getTableName();
	// 	$list = $db->query($sql)->fetchAll();
		
	// 	$data = array();
 //       /// $data['0']='不限';
	// 	foreach ( $list as $key => $value ) {
	// 		$data [$value['detail_id']] = $value['task_name'];
	// 	}		
	// 	return $data;
	// }
	
	 public function getDetailById($member_id) {
	 	if (! $member_id || ! is_numeric ( $member_id )) {
	 		return false;
	 	}
	
	 	$sub_condition ["member_id"] = $member_id;
         //var_dump($sub_condition);die;
	 //	$condition = array("AND" => $sub_condition);
	 	/*$db=self::__instance();
	 	$list = $db->select ( self::getTableName(), self::$columns, $condition );*/
	 	$list=$this->where($sub_condition)->select();
         //var_dump($list);die;
	 	if ($list) {
	 		return $list;
	 	}
	 	return array ();
	 }

	



 //    public function getDetailsByPage($start ,$page_size) {
	// 	$db=self::__instance();
	
	// 	$condition=array();
	
	// 	$condition["ORDER"]=" id desc";
	// 	$condition['LIMIT']=array($start,$page_size);
	
	// 	$list = $db->select ( self::getTableName(), self::$columns, $condition);
	
	// 	if ($list) {
	// 		return $list;
	// 	}
	// 	return array ();
	// }
    public function addTaskDetail($input_data) {
        if (! $input_data || ! is_array ( $input_data )) {
            return false;
        }
        $id = $this->data($input_data)->add();
        return $id;
    }
    //查询当前信息是否存在
    public function getMsidMid($member_id,$mission_id) {  		
        $sub_condition ['member_id'] = $member_id;
        $sub_condition ["mission_id"] = $mission_id;
        $list = $this->field('detail_id')->where($sub_condition)->select();
        //$list = $db->select ( self::getTableName(), "detail_id", $condition );
       // var_dump($list);die;
        if ($list) {
            return $list[0];
        }
        return array ();
    }
     //查询当前信息是否存在(传值不同)；
    public function getMsidMeid($member_id,$mission_id) {
        $sub_condition["member_id"]  = $member_id;
        $sub_condition["mission_id"] = $mission_id;
        $list = $this->field('detail_id')->where($sub_condition)->select();
        if ($list) {
            return $list[0];
        }
        return array ();
    }
    //修改数据
    public function updateTaskDetail($Detailid,$update_data) {
        if (! $update_data || ! is_array ( $update_data )) {
            return false;
        }     
        $condition=array("detail_id"=>$Detailid);
        $id = $this->where($condition)->save($update_data);

        return $id;
    }


      //审核失败修改状态为6
     public function updateMsidMeid($member_id,$mission_id) {
     	$sub_condition["member_id"]  = $member_id;
         $sub_condition ["mission_id"] = $mission_id;
         $tj=array('status'=>'6');
         //var_dump($condition);
        // $id = $db->update ( self::getTableName(), $tj,$condition );
         $id=$this->where($sub_condition)->save($tj);
         return $id;
     }
    public function search($member_id){
        if ($member_id != "") {
            $where['member_id'] = $member_id;
        }
        $perPage = 25;
        $count = count($this->where($where)->select());
        //dump($count);
        $pageObj = new \Think\Page($count,$perPage);
        $pageString = $pageObj->show();
        // 设置样式
        $pageObj->setConfig('first','首页');
        $pageObj->setConfig('prev', '上一页');
        $pageObj->setConfig('next', '下一页');
        $pageObj->setConfig('last','共%TOTAL_PAGE%页');
        $pageObj->setConfig('theme','%FIRST% %UP_PAGE%  &nbsp;%LINK_PAGE%&nbsp; %DOWN_PAGE% %END% <span class="rows">共 %TOTAL_ROW% 条</span>');
        /************** 取某一页的数据 ***************/
        $data = $this->order("detail_id desc") ->
        where($where)// 排序
        ->limit($pageObj->firstRow.','.$pageObj->listRows)            // 翻页
        ->select();

        /************** 返回数据 ******************/
        return array(
            'data' => $data,  // 数据
            'page' => $pageString,  // 翻页字符串
        );
    }


}

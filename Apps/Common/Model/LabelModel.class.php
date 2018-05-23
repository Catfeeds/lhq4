<?php
namespace Common\Model;
use Think\Model;
class LabelModel extends Model {
	protected $tableName = "label";
	public function getLabelById($id) {
		if (! $id || ! is_numeric ( $id )) {
			return false;
		}	
		$sub_condition ["id"] = $id;
		$list = $this->where($sub_condition)->select();
		if ($list) {
			return $list[0];
		}
		return array ();
	}
	
	public function getLabelByName($label_name) {
		if (! $label_name ) {
			return false;
		}
		$sub_condition ["label_name"] = $label_name;
        $list = $this->where($sub_condition)->select();
		if ($list) {
			return $list;
		}
		return array ();
	}
	
	public function addLabel($label_data) {
		if (! $label_data || ! is_array ( $label_data )) {
			return false;
		}
        $id = $this->data($label_data)->add();
		return $id;
	}
	
	public function delLabel($id) {
		if (! $id || ! is_numeric ( $id )) {
			return false;
		}
		$condition = array("id" => $id);
		$result = $this->where($condition)->delete();
		return $result;
	}
	
	public function updateLabelInfo($id,$label_data) {
		if (! $label_data || ! is_array ( $label_data )) {
			return false;
		}
		$condition=array("id"=>$id);
		$id = $this->where($condition)->save($label_data);
		return $id;
	}
	public function label_name(){
		$list = $this->select();
		return $list;
	}
    //分页
    public function search(){
        $perPage = 25;
        $count = $this->where($where)->count();
        $pageObj = new \Think\Page($count,$perPage);
        // 设置样式
        $pageObj->setConfig('next', '下一页');
        $pageObj->setConfig('prev', '上一页');
        $pageString = $pageObj->show();
        /************** 取某一页的数据 ***************/
        $data = $this 
        ->order("id desc")                    // 排序    
        ->limit($pageObj->firstRow.','.$pageObj->listRows)// 翻页
        ->select();       
        /************** 返回数据 ******************/
        return array(
            'data' => $data,  // 数据
            'page' => $pageString,  // 翻页字符串
        );
    }

}

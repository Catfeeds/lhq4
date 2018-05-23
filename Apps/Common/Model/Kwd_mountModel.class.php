<?php
namespace Common\Model;
use Think\Model;
class Kwd_mountModel extends Model {

	public function addKwd($kwd_data){
		if (! $kwd_data || ! is_array ( $kwd_data )) {
            return false;
        }
        $id = $this->data($kwd_data)->add();
        //$id = $db->insert ( self::getTableName(), $kwd_data );
        return $id;
	}

	//根据id查询
	public function getKwdById($id){
		if (! $id || ! is_numeric ( $id )) {
			return false;
		}
		$sub_condition ["id"] = $id;
	    $list = $this->where($sub_condition)->order('id desc')->select();
	    //var_dump($list);die;
		if ($list) {
			return $list[0];
		}
		return array ();
	}

	//根据mission_id查询
	public function getKwdByMid($mission_id){
		if (! $mission_id || ! is_numeric ( $mission_id )) {
			return false;
		}
//		$time=date('Y-m-d',time());
//        $stime=$time." 00:00:00";
//        $etime=$time." 23:59:59";
//        $sub_condition['time']=array(array('egt',$stime),array('elt',$etime),'and');
		$sub_condition ["mid"] = $mission_id;
	    $list = $this->where($sub_condition)->select();
	    //var_dump($list);die;
		if ($list) {
			return $list;
		}
		return array ();
	}	

	//根据mission_id查询
	public function getKwdByLMid($mission_id){
		if (! $mission_id || ! is_numeric ( $mission_id )) {
			return false;
		}
//		$time=date('Y-m-d',time());
//        $stime=$time." 00:00:00";
//        $etime=$time." 23:59:59";
//        $sub_condition['time']=array(array('egt',$stime),array('elt',$etime),'and');
		$sub_condition ["mid"] = $mission_id;
		$sub_condition['lmount_re']=array('gt',0);
	    $list = $this->where($sub_condition)->select();
	    //var_dump($list);die;
		if ($list) {
			return $list;
		}
		return array ();
	}

	//根据mission_id查询id
	public function getKwd($mission_id){
		if (! $mission_id || ! is_numeric ( $mission_id )) {
			return false;
		}
//		$time=date('Y-m-d',time());
//        $stime=$time." 00:00:00";
//        $etime=$time." 23:59:59";
//        $sub_condition['time']=array(array('egt',$stime),array('elt',$etime),'and');
		$sub_condition ["mid"] = $mission_id;
	    $list = $this->field('id,kkwd,mount,mount_re,lmount,lmount_re')->where($sub_condition)->select();
		if ($list) {
			return $list;
		}
		return array ();
	}

	//根据mission_id删除
	public function delKwd($mission_id){
		if (! $mission_id || ! is_numeric ( $mission_id )) {
			return false;
		}
		$sub_condition['mid']=$mission_id;
		$k = $this ->where($sub_condition)->delete();
		//xvar_dump($k);die;
		return $k;
	}

	//删除关键词
	public function delKkwd($mission_id,$kwd){
		if (! $mission_id || ! is_numeric ( $mission_id )) {
			return false;
		}
//		$time=date('Y-m-d',time());
//        $stime=$time." 00:00:00";
//        $etime=$time." 23:59:59";
//        $sub_condition['time']=array(array('egt',$stime),array('elt',$etime),'and');
		$sub_condition['mission_id']=$mission_id;
		$sub_condition['kkwd']=$kwd;
		$k = $this ->where($sub_condition)->delete();
		return $k;
	}

	//更新数据
	public function updateKwd($mission_id,$kwd,$kwd_data){
		if (! $kwd_data || ! is_array ( $kwd_data )) {
            return false;
        }
//        $time=date('Y-m-d',time());
//        $stime=$time." 00:00:00";
//        $etime=$time." 23:59:59";
//        $condition['time']=array(array('egt',$stime),array('elt',$etime),'and');
        $condition['kkwd']=$kwd;
        $condition['mid']=$mission_id;
        $id = $this->where($condition)->save($kwd_data);
        return $id;
	}

	//获取创建的时间
	public function getTime($mission_id){
		if (! $mission_id || ! is_numeric ( $mission_id )) {
			return false;
		}
		$sub_condition['mid']=$mission_id;
		$result=$this->field('time')->where($sub_condition)->select();
		return $result[0];
	}

	//根据任务id和关键词删除
	public function delKwds($mid,$kwd)
	{
		$data = array();
		$data['mid'] = $mid;
		$data['kkwd'] = $kwd;
		$re = $this->where($data)->delete();
		if ($re) {
			return $re;
		}
	}


}
?>
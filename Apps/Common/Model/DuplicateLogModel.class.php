<?php
namespace Common\Model;
use THink\Model;

class DuplicateLogModel extends Model {
//class Sample extends Base {
//	private static $table_name = 'row_repeat';
 
//	private static $columns = 'id,app_id,idfa,result,rtime';
	protected $tableName="row_repeat";
	protected $fields=array('id','app_id','ip','idfa','result','rtime','chanid');

	public function getRowRepeats() {

		$sql="select * from row_repeat order by id desc";
		$list = M("row_repeat")->query($sql);
		if ($list) {
			return $list;
		}
		return array ();		
	}

	public function addRowRepeat($row_data) {
		if (! $row_data || ! is_array ( $row_data )) {
			return false;
		}
        //var_dump($task_data);
		$id = M('row_repeat')->add($row_data );
        //var_dump($id);die;
		return $id;
	}

    public function getCountByDate($app_id,$idfa,$start_date,$end_date,$chan_id) {
        if($app_id != ''){
            $sub_condition['app_id']=array("in",$app_id);
        }
        if($idfa != ''){
            $sub_condition['idfa']=$idfa;
        }
        $sub_condition['chanid']=array('in',$chan_id);
        if($start_date != "" && $end_date == ""){
            $sub_condition["rtime"] =array("egt",$start_date);
        }
        if($start_date == "" && $end_date != ""){
            $sub_condition["rtime"] =array("elt",$end_date);
        }
        if($start_date != "" && $end_date != ""){
            $sub_condition["rtime"] =array(array("egt",$start_date),array("elt",$end_date),'and');
        }
        //var_dump($sub_condition);die;
        $num = M('row_repeat')->where($sub_condition )->count();
        return $num;
    }

    public function getCount($app_id='',$idfa='',$chan_id) {
        $sub_condition = array();
        if($app_id != ''){
            $sub_condition['app_id']=array("in",$app_id);
        }
        if($idfa != ''){
            $sub_condition['idfa']=$idfa;
        }
        $sub_condition['chanid']=array('in',$chan_id);
        $num = M('row_repeat')->where($sub_condition)->count();

        return $num;
    }

    public function getLogs($app_id,$idfa,$start ,$page_size,$start_date='',$end_date='',$chan_id) {
        $sub_condition = array();
        if($app_id != ''){
            $sub_condition['app_id']=array("in",$app_id);
        }
        if($start_date != "" && $end_date == ""){
            $sub_condition["rtime"] =array("egt",$start_date);
        }
        if($start_date == "" && $end_date != ""){
            $sub_condition["rtime"] =array("elt",$end_date);
        }
        if($start_date != "" && $end_date != ""){
            $sub_condition["rtime"] =array(array("egt",$start_date),array("elt",$end_date),'and');
        }
        if($idfa != ''){
            $sub_condition['idfa']=$idfa;
        }
        $sub_condition['chanid']=array('in',$chan_id);
        $list = M('row_repeat')->where($sub_condition)->order(" id desc")->limit($start,$page_size)->select();
        //var_dump($list);die;
        if ($list) {
            return $list;
        }
        return array ();
    }

//查询所有的数据
    public function getDupLog($start, $page_size){
        $list = $this->order("id desc")->limit($start, $page_size)->select();

        if ($list) {
            return $list;
        }
        return array();
    }
    //查询所有的数据的条数
    public function CountgetLog(){
        $num = $this->count();
        // dump($num);//die;
        return $num;
    }

    //特殊情况查询
    public function getCountByDateLog($idfa,$start_date,$end_date,$app_ids,$chan_id) {


        if($idfa != ''){
            $sub_condition['idfa']=$idfa;
        }
        if(!empty($app_ids)){
            $sub_condition['app_id']=array('in',$app_ids);
        }
        $sub_condition['chanid']=array('in',$chan_id);
        if($start_date != "" && $end_date == ""){
            $sub_condition["rtime"] =array("egt",$start_date);
        }
        if($start_date == "" && $end_date != ""){
            $sub_condition["rtime"] =array("elt",$end_date);
        }
        if($start_date != "" && $end_date != ""){
            $sub_condition["rtime"] =array(array("egt",$start_date),array("elt",$end_date),'and');
        }

        $num = $this->where($sub_condition)->count();
        return $num;
    }

    public function getCountLog($idfa='',$app_ids,$chan_id) {
        //$sub_condition = array();

        if($idfa != ''){
            $sub_condition['idfa']=$idfa;
        }
        if(!empty($app_ids)){
            $sub_condition['app_id']=array('in',$app_ids);
        }
        $sub_condition['chanid']=array('in',$chan_id);
//dump($sub_condition);
        $num = $this->where($sub_condition)->count();
        //   dump($num);
        return $num;
    }
    public function getLogsLog($idfa,$start ,$page_size,$start_date='',$end_date='',$app_ids,$chan_id) {
        $sub_condition = array();

        if($start_date != "" && $end_date == ""){
            $sub_condition["rtime"] =array("egt",$start_date);
        }
        if($start_date == "" && $end_date != ""){
            $sub_condition["rtime"] =array("elt",$end_date);
        }
        if($start_date != "" && $end_date != ""){
            $sub_condition["rtime"] =array(array("egt",$start_date),array("elt",$end_date),'and');
        }
        if($idfa != ''){
            $sub_condition['idfa']=$idfa;
        }
        if(!empty($app_ids)){
            $sub_condition['app_id']=array('in',$app_ids);
            
        }
        $sub_condition['chanid']=array('in',$chan_id);
        $list =$this->where($sub_condition)->order("id desc")->limit($start,$page_size)->select();
        // var_dump($chan_id);die;

        if ($list) {
            return $list;
        }
        return array ();
    }

    public  function getAppids() {

        //�������ַ�ʽ����Է���sample��DB

        //$db=self::__instance(SAMPLE_DB_ID);

        $sql="select app_id from  row_repeat  group  by app_id order by id desc ";
        $list = M('row_repeat')->query($sql);
        if ($list) {
            return $list;
        }
        return array ();
    }

    public  function getchanids() {

        //�������ַ�ʽ����Է���sample��DB
    
        //$db=self::__instance(SAMPLE_DB_ID);

        $sql="select chanid from row_repeat  group  by chanid order by id desc ";
        $list = M('row_repeat')->query($sql);
        if ($list) {
            return $list;
        }
        return array ();
    }
}

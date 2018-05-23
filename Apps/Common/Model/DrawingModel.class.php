<?php
/*if(!defined('ACCESS')) {exit('Access denied.');}

class Drawing extends CommonBase {*/
namespace Common\Model;
use Think\Model;


class DrawingModel extends Model{
//class Sample extends Base {
/*	private static $table_name = 'drawing';
 
	private static $columns = 'id,member_id,alipy,phone,member_name,weixin,wd_way,wd_time,wd_money,status,nickname';*/


/*	public static function getTableName(){
		return self::$table_name;
	}
	 */
	public static function getDrawings() {

        $db=self::__instance();
        //var_dump($db);die;
        $sql="select ".self::$columns." from ".self::getTableName()." order by id desc";
        //var_dump($sql);//die;

        $list = $db->query($sql)->fetchAll();
        //var_dump($list);die;
        if ($list) {
            return $list;
        }
        //var_dump($list);die;
        return array ();
	}


	
	public static function getDrawingById($id) {
		if (! $id || ! is_numeric ( $id )) {
			return false;
		}
	
		$sub_condition ["id"] = $id;
		$condition = array("AND" => $sub_condition);
		$db=self::__instance();
		$list = $db->select ( self::getTableName(), self::$columns, $condition );
        //var_dump($list);die;
		if ($list) {
			return $list[0];
		}
		return array ();
	}

   public  function getDrawById($id,$val) {
       //$condition['misssion_id']=$id;
        $condition =array('id'=>$id);
        if($val==1){
            //$tj['status']='2';
            $tj=array('status'=>'2');
        }else if($val==2){
            //$tj['status']='1';
            $tj=array('status'=>'3');
        }
      //  $db=self::__instance();
     //   $list = $db->update ( self::getTableName(),$tj,$condition );
       $list=$this->where($condition)->save($tj);
       //var_dump($list);die;
       if ($list) {
           return $list;
       }
       return array ();
    }
    public static function delDrawing($id) {
        if (! $id || ! is_numeric ( $id )) {
            return false;
        }
        $db=self::__instance();
        $condition = array("id" => $id);
        $result = $db->delete ( self::getTableName(), $condition );
        return $result;
    }

   /* public static function getDrawingByPage($start ,$page_size) {
		$db=self::__instance();
	
		$condition=array();
	
		$condition["ORDER"]=" id desc";
		$condition['LIMIT']=array($start,$page_size);
	
		$list = $db->select ( self::getTableName(), self::$columns, $condition);
	//var_dump($list);die;
		if ($list) {
			return $list;
		}
		return array ();
	}
    public static function getCountByDate($member_id,$start_date,$end_date) {
        $db=self::__instance();
        $condition=array();
        if($member_id != ''){
            $sub_condition['member_id']=$member_id;
        }

        //echo $idfa;die;
        $sub_condition["wd_time[<>]"] =array($start_date,$end_date);
        $condition["AND"] = $sub_condition;

        $num = $db->count ( self::getTableName(),$condition );
        //var_dump($num);die;
        return $num;
    }

    public static function count($member_id) {
        $db=self::__instance();

        $sub_condition = array();

        if(empty($sub_condition)){
            $condition = array();
        }else{
            $condition["AND"] = $sub_condition;
        }

        if($member_id != ''){
            //$sub_condition['idfa']=$idfa;
            $condition['LIKE']=array("member_id"=>$member_id);
            //var_dump($sub_condition);die;
        }
        //var_dump($condition);
        $num = $db->count ( self::getTableName(),$condition);
        //var_dump($db->count ( self::getTableName(),$condition));die;

        return $num;
    }

    public static function getLists($member_id,$start ,$page_size,$start_date='',$end_date='') {
        $db=self::__instance();
//var_dump($db);
        $condition=array();
        $sub_condition = array();


        if($start_date !='' && $end_date !=''){
            $sub_condition["wd_time[<>]"] =array($start_date,$end_date);
        }
        if(empty($sub_condition)){
            $condition = array();
        }else{
            $condition["AND"] = $sub_condition;
        }

        if($member_id != ''){
            //$sub_condition['idfa']=$idfa;
            $condition['LIKE']=array("member_id"=>$member_id);
        }
       // var_dump($condition);
        $condition["ORDER"]=" id desc";
        $condition['LIMIT']=array($start,$page_size);
        //var_dump($condition);
        $list = $db->select ( self::getTableName(), self::$columns, $condition);
        //var_dump($list);die;

        if ($list) {
            return $list;
        }
        return array ();
    }
    public static function getwdDrawingById($member_id) {
        if (! $member_id || ! is_numeric ( $member_id )) {
            return false;
        }
        $sub_condition ["member_id"] = $member_id;
       // $sub_condition["ORDER"]=" id desc";

        $condition = array("AND" => $sub_condition);
        $condition["ORDER"]=" id desc";
        $db=self::__instance();
        $list = $db->select ( self::getTableName(), self::$columns, $condition );
        if ($list) {
            //var_dump($list);
            return $list;
        }
        return array ();
    }*/

  //在Drawing添加一行数据
    public function addDrawing($data) {
        if (! $data || ! is_array ( $data )) {
            return false;
        }
        /*$db=self::__instance();
        $id = $db->insert ( self::getTableName(), $input_data );*/
        $id = $this->data($data)->add();
        return $id;
    }
//验证码
    public static function checkYzm($verify){
       // $verify = I('post.verify');
        //echo 1;die;
        session_start();
        $yzm=strtolower($verify);
        $yzm1=strtolower($_SESSION['osa_verify_code']);
        if($yzm==$yzm1){
            return true;
        }else{
            return false;
        }
    }

    //通过id查询这条数据的信息
    public function getData($id)
    {
        if (! $id || ! is_numeric ( $id )) {
            return false;
        }
        $condition ["id"] = $id;
       // $condition = array("AND" => $sub_condition);
        $list=$this->where($condition)->select();
        //var_dump($list);die;
        if ($list) {
            return $list[0];
        }
    }
    //通过id查找member_id
    public function selectMid($id) {
        //通过idfa查询所对应的member_id;
        $sub_condition ["id"] = $id;
     //   dump($sub_condition);
        $list=$this->where($sub_condition)->field('member_id')->select();
        //dump($list);
       // $list = $db->select ( self::getTableName(), "member_id", $condition );
        if ($list) {
            return $list[0];
        }
        return array ();
    }
    //修改表中数据
//分页

    public function search($start_date,$end_date,$member_id,$status){
        $perPage = 25;
        if ($member_id != "") {
            $where['member_id'] = $member_id;
        }
        if ($start_date == '' && $end_date != '') {
            $where['wd_time'] = array('elt',$end_date);
        }
        if ($start_date != '' && $end_date == '') {
            $where['wd_time'] = array('egt',$start_date);;
        }
        if ($start_date != '' && $end_date != '') {
           $where["wd_time"] = array(array('egt',$start_date),array('elt',$end_date));
        }
        if ($status != "") {
            $where["status"] = $status;
        }
        $count = $this->where($where)->count();
      //  dump($count);
        $pageObj = new \Think\Page($count,$perPage);
        // 设置样式
        $pageObj->setConfig('first','首页');
        $pageObj->setConfig('prev', '上一页');
        $pageObj->setConfig('next', '下一页');
        $pageObj->setConfig('last','共%TOTAL_PAGE%页');
        $pageObj->setConfig('theme','%FIRST% %UP_PAGE%  &nbsp;%LINK_PAGE%&nbsp; %DOWN_PAGE% %END% <span class="rows">共 %TOTAL_ROW% 条</span>');
        $pageString = $pageObj->show();
        /************** 取某一页的数据 ***************/
        $data = $this
            ->order("id desc")                    // 排序
            ->where($where)            // 翻页
            ->limit($pageObj->firstRow.','.$pageObj->listRows)
            ->select();
        $data_total = $this
            ->field('sum(wd_money) as total, count(*) as count')                 
            ->where($where)           
            ->find();
       // dump($data);die;
        /************** 返回数据 ******************/
        return array(
            'data' => $data,  // 数据
            'page' => $pageString,  // 翻页字符串
            'data_total' => $data_total,
        );
    }

}

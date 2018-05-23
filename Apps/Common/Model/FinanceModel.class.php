<?php
namespace Common\Model;
use Think\Model;

class FinanceModel extends Model{

//class Sample extends Base {
/*	private static $table_name = 'finance';
 
	private static $columns = 'id,t_number,widthdraw_money,paid_money,offer,invoice_title,account,bank_account,address,apply_time,end_time,status';


	public static function getTableName(){
		return self::$table_name;
	}*/
	 
	public function getFinances() {

       // $db=self::__instance();
        //var_dump($db);die;
       // $sql="select ".self::$columns." from ".self::getTableName()." order by id desc";
        //var_dump($sql);//die;

        //$list = $db->query($sql)->fetchAll();
        $list=$this->order('id')->select();
        //var_dump($list);die;
        if ($list) {
            return $list;
        }
        //var_dump($list);die;
        return array ();
	}
	

	
	public static function getFinanceById($id) {
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

   public function getFinById($id,$val) {
       //$condition['misssion_id']=$id;
        $condition =array('id'=>$id);
        if($val==1){
            //$tj['status']='2';
            $tj=array('status'=>'2');
        }elseif($val==2){
            //$tj['status']='1';
            $tj=array('status'=>'3');
        }

        /*$db=self::__instance();
        $list = $db->update ( self::getTableName(),$tj,$condition );*/
       $list=$this->where($condition)->save($tj);
       //var_dump($list);die;
       if ($list) {
           return $list;
       }
       return array ();
    }

   /* public static function getFinanceByPage($start ,$page_size) {
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
    public static function getCountByDate($account,$start_date,$end_date) {
        $db=self::__instance();
        $condition=array();
        if($account != ''){
            $sub_condition['account']=$account;
        }

        //echo $idfa;die;
        $sub_condition["apply_time[<>]"] =array($start_date,$end_date);
        $condition["AND"] = $sub_condition;

        $num = $db->count ( self::getTableName(),$condition );
        //var_dump($num);die;
        return $num;
    }

    public static function count($account) {
        $db=self::__instance();

        $sub_condition = array();

        if(empty($sub_condition)){
            $condition = array();
        }else{
            $condition["AND"] = $sub_condition;
        }

        if($account != ''){
            //$sub_condition['idfa']=$idfa;
            $condition['LIKE']=array("account"=>$account);
            //var_dump($sub_condition);die;
        }
        //var_dump($condition);
        $num = $db->count ( self::getTableName(),$condition);
        //var_dump($db->count ( self::getTableName(),$condition));die;

        return $num;
    }

    public static function getLists($account,$start ,$page_size,$start_date='',$end_date='') {
        $db=self::__instance();
//var_dump($db);
        $condition=array();
        $sub_condition = array();


        if($start_date !='' && $end_date !=''){
            $sub_condition["apply_time[<>]"] =array($start_date,$end_date);
        }
        if(empty($sub_condition)){
            $condition = array();
        }else{
            $condition["AND"] = $sub_condition;
        }

        if($account != ''){
            //$sub_condition['idfa']=$idfa;
            $condition['LIKE']=array("account"=>$account);
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
    }*/
//分页

    public function search($account){
        $perPage = 25;

        if ($account != "") {
            $where['account'] = array('like', "%$account%");
        }

        $count = count($this->where($where)->select());
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
        // dump($data);die;
        /************** 返回数据 ******************/
        return array(
            'data' => $data,  // 数据
            'page' => $pageString,  // 翻页字符串
        );
    }

}

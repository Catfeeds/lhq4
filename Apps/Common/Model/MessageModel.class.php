<?php
namespace Common\Model;
use Think\Model;

class MessageModel extends Model{
/*if(!defined('ACCESS')) {exit('Access denied.');}

class Message extends CommonBase {*/
//class Sample extends Base {
//	private static $table_name = 'message';
//
//	private static $columns = 'message_id,title,content,msg_from,msg_to,status,create_time,display';
//
//
/*	public static function getTableName(){
		return self::$table_name;
	}*/
	 
	public function getMessages() {

		$db=self::__instance();
		
		$sql="select ".self::$columns." from ".self::getTableName()." order by message_id desc";
		$list = $db->query($sql)->fetchAll();
		if ($list) {
			return $list;
		}
        //var_dump($list);
        //var_dump($db->query($sql)->fetchAll());die;
		return array ();		
	}
	public function getMessagesArray() {

		$list = M('message')->select();
		
		$data = array();
        $data['0']='不限';
		foreach ( $list as $key => $value ) {
			$data [$value['message_id']] = $value['title'];
            //$data [$value['app_id']] = $value['adtype_id'];
		}
		return $data;
	}
    //查询群发消息的信息
    public function getMsgToArray() {
        $db=self::__instance();

        $sub_condition ["msg_to"] = '0';
        $sub_condition ["display"] = '1';
        $condition = array("AND" => $sub_condition);
        $db=self::__instance();
        $list = $db->select ( self::getTableName(), self::$columns, $condition );

        $data = array();
       // $data['0']='不限';
        foreach ( $list as $key => $value ) {
            $data [$value['message_id']] = $value['msg_to'];
            //$data [$value['app_id']] = $value['adtype_id'];
        }
        return $data;
    }
    public function getMessageByName($message_name) {
        if (! $message_name ) {
            return false;
        }

        $sub_condition ["mission_name"] = $message_name;
        $condition = array("AND" => $sub_condition);
        $db=self::__instance();
        $list = $db->select ( self::getTableName(), self::$columns, $condition );
        if ($list) {
            return $list;
        }
        return array ();
    }
	public function getMessageById($message_id) {
		if (! $message_id || ! is_numeric ( $message_id )) {
			return false;
		}
	
		//$sub_condition ["message_id"] = $message_id;
		//$condition = array("AND" => $sub_condition);
		//$db=self::__instance();
	//	$list = $db->select ( self::getTableName(), self::$columns, $condition );
        $sub_condition =array("message_id" => $message_id);
        $list=$this->where($sub_condition)->select();
		if ($list) {
			return $list[0];
		}
		return array ();
	}

	//添加数据
	public function addMessage($input_data) {
		if (! $input_data || ! is_array ( $input_data )) {
			return false;
		}
        //var_dump($input_data);//
		//$db=self::__instance();
		//$id = $db->insert ( self::getTableName(), $input_data );
        $id = $this->data($input_data)->add();
      //  var_dump($id);//die;
		return $id;
	}
	//删除
	public function delMessage($message_id) {
		if (! $message_id || ! is_numeric ( $message_id )) {
			return false;
		}
		//$db=self::__instance();
        $condition = array("message_id" => $message_id);
        $result = $this->where($condition)->delete();
		return $result;
	}
	//修改
	public function updateMessage($message_id,$update_data) {
		if (! $update_data || ! is_array ( $update_data )) {
			return false;
		}
		//$db=self::__instance();
	//	$condition=array("message_id"=>$message_id);
	   $condition=array("message_id"=>$message_id);
        $id = $this->where($condition)->save($update_data);
		return $id;
	}


    public function countSearch($title) {
      //  $db=self::__instance();
       // $condition = array();
        if($title!=""){
//            $condition['LIKE']=array("title"=>$title);
            $condition['title']=array('like',"%$title%");
        }
        // var_dump($condition);
//        $num = $db->count( self::getTableName(), $condition);
        //var_dump($num);die;
        $num = $this->where($condition)->count();
        return $num;
    }
    //查询
    public function search($title, $start ='' ,$page_size='' ) {
       // $db=self::__instance();
        $limit ="";
        $where = "";
        if($page_size){
            $limit =" limit $start,$page_size ";
        }
        // echo $channel_name;die;
        if($title!=""){
            $where = " where title like '%$title%' ";
        }

        $sql = "select * from ".self::getTableName()." $where order by message.message_id desc $limit";

       // $list=$db->query($sql)->fetchAll();
        $list=$this->query($sql)->fetchAll();
        //var_dump($list);die;
        if ($list) {
            return $list;
        }
        return array ();
    }

    public function getAllMessage( $start ='' ,$page_size='' ) {
        $db=self::__instance();
        $limit ="";
        if($page_size){
            $limit =" limit $start,$page_size ";
        }
        $sql = "select * from ".self::getTableName()."  order by message.message_id desc $limit";

        $list=$db->query($sql)->fetchAll();
        //var_dump($list);die;
        if ($list) {
            return $list;
        }
        return array ();
    }
    //获取msg_to=0的最新的一条数据
    public function getNewMsg($msgTo) {

        $condition ["msg_to"] = $msgTo;
       // $condition = array("AND" => $sub_condition);
      //  $condition["ORDER"] = " message_id desc";

        $list=$this->where($condition)->order(" message_id desc")->select();
        if ($list) {
            return $list[0];
        }
        return array ();
    }
    //通过会员id查他所有的消息
    public function getMessage($member_id) {
        //var_dump($member_id);//die;
        $sub_condition ["msg_to"] = $member_id;
        $sub_condition ["display"] = '1';
        $condition = array("AND" => $sub_condition);
       // var_dump($condition);
        $db=self::__instance();
        $list = $db->select ( self::getTableName(), self::$columns, $condition );
        if ($list) {
            return $list;
        }
        return array ();
    }
    //通过消息id查他所有群的消息
    public function SelectPublicMsg() {
        $sub_condition ["display"] = '1';
        $sub_condition ["msg_to"] = '0';
        $condition = array("AND" => $sub_condition);
        $condition["ORDER"] = " message_id desc";
        $db=self::__instance();
        $list = $db->select ( self::getTableName(), self::$columns, $condition );
        if ($list) {
            return $list;
        }
        return array ();
    }
    //查找公共消息
    //  //通过消息id查他所有的消息
    public  function SelectMessages($message_id) {
        //var_dump($member_id);//die;
        $sub_condition ["message_id"] = $message_id;
        $sub_condition ["display"] = '1';
        $condition = array("AND" => $sub_condition);
        // var_dump($condition);
      //  $db=self::__instance();
        ///$list = $db->select ( self::getTableName(), self::$columns, $condition );
        $list=$this->where($condition)->save( );
        if ($list) {
            return $list[0];
        }
        return array ();
    }
    //查询个人消息数
    public function count($member_id,$click_time='')
    {
       // $db = self::__instance();
        $sub_condition['msg_to'] = array(array("eq",$member_id),array("eq",0),'or');
        $sub_condition['status'] = '0';
        $sub_condition['display'] = '1';
       // $sub_condition['create_time'] =date("Y-m-d", time());
       // $condition = array("AND" => $sub_condition);
      //  $list = $db->select ( self::getTableName(), self::$columns, $condition );
        $list= $this ->where($sub_condition )->save();
        //$grxx=count($list);
       // DUMP($list);DIE;
        if($click_time){
            $sl=0;
            foreach ($list as $k => $v) {
                if ($v['create_time'] >$click_time) {
                    ++$sl;
                }
            }
        }else {
            $sl = count($list);
        }
        return $sl;
    }
    //修改消息状态
    public  function StatusChanges($msg,$mid) {
        $time=date('Y-m-d H:i:s',time());
        $sub_condition['message_id'] = $msg;
        $sub_condition['msg_to'] = $mid;
        //$sub_condition['status'] = '1';
//        $condition = array("AND" => $sub_condition);
        $zt=array('status'=>'1','create_time'=>$time);
//        $db=self::__instance();
        $list=$this->where($sub_condition)->save($zt);
        //$list = $db->update ( self::getTableName(),$zt,$condition );
        if ($list) {
            return $list;
        }
        return array ();
    }
    public function getCountByDate($msg_from,$title,$start_time,$end_time) {
       // $db=self::__instance();
      //  $condition=array();
        if($msg_from != ''){
//            $condition['LIKE']=array("msg_from"=>$msg_from);
            $where['msg_from']=array('like',"%$msg_from%");
        }
        if($title != ''){
           // $condition['LIKE']=array("title"=>$title);
            $where['title']=array('like',"%$title%");
        }

       // $sub_condition["create_time[<>]"] =array($start_time,$end_time);
        if ($start_time == '' && $end_time != '') {
            $where['start_time'] = array('elt',$end_time);
        }
        if ($start_time != '' && $end_time == '') {
            $where['start_time'] = array('egt',$start_time);;
        }
        if ($start_time != '' && $end_time != '') {
            // $where["wd_time"] = array('between',$start_date,$end_date);
            $where["start_time"] = array(array('egt',$start_time),array('elt',$end_time));
        }
        $num=count($this->where($where)->select());
       // var_dump($num);//die;
        return $num;
    }
    public function counts($msg_from,$title) {
    //    $db=self::__instance();
       // $sub_condition = array();
        if($msg_from != ''){
            $where['msg_from']=array('like',"%$msg_from%");
           // $condition['LIKE']=array("msg_from"=>$msg_from);
        }

        if($title != ''){
//            $condition['LIKE']=array("title"=>$title);
            $where['title']=array('like',"title"=>$title);
        }
       // dump($where);
      //$num = $db->count ( self::getTableName(),$condition);
        $num = count($this->where($where)->select());
      //  dump($num);die;
        //var_dump($db->count ( self::getTableName(),$condition));die;
        return $num;
    }
    public function getLogs($msg_from,$title, $start, $page_size, $start_time, $end_time ) {
     //   $db=self::__instance();
        if($msg_from != ''){
//            $condition['LIKE']=array("msg_from"=>$msg_from);
            $where['msg_from']=array('like',"%$msg_from%");
        }
        if($title != ''){
            // $condition['LIKE']=array("title"=>$title);
            $where['title']=array('like',"%$title%");
        }

        // $sub_condition["create_time[<>]"] =array($start_time,$end_time);
        if ($start_time == '' && $end_time != '') {
            $where['create_time'] = array('elt',$end_time);
        }
        if ($start_time != '' && $end_time == '') {
            $where['create_time'] = array('egt',$start_time);;
        }
        if ($start_time != '' && $end_time != '') {
            // $where["wd_time"] = array('between',$start_date,$end_date);
            $where["create_time"] = array(array('egt',$start_time),array('elt',$end_time));
        }
     //   $where["ORDER"]=" message_id desc";
    //    $where['LIMIT']=array($start,$page_size);
        //dump($where);
      //  $list = $db->select ( self::getTableName(), self::$columns, $condition);
        $list=$this->where($where)->order("message_id desc")->limit($start,$page_size)->select();
      //                                                                                                                                                                                                                                                dump($list);die;
        if ($list) {
            return $list;
        }
        return array ();
    }
    public function getTitleArray() {

        $db=self::__instance();
        //$db=self::__instance(SAMPLE_DB_ID);

        $sql="select ".self::$columns." from ".self::getTableName();
        $list = $db->query($sql)->fetchAll();

        $data = array();

        foreach ( $list as $key => $value ) {
            $data [$value['message_id']] = $value['title'];
        }
        return $data;
    }
    public function getContentArray() {

        $db=self::__instance();
        //$db=self::__instance(SAMPLE_DB_ID);

        $sql="select ".self::$columns." from ".self::getTableName();
        $list = $db->query($sql)->fetchAll();

        $data = array();

        foreach ( $list as $key => $value ) {
            $data [$value['message_id']] = $value['content'];
        }
        return $data;
    }

    //消息类型
    public function getM_typeArray() {

        $list = M('message')->select();

        $data = array();
        // $data['0']='不限';
        foreach ( $list as $key => $value ) {
            $data [$value['message_id']] = $value['m_type'];
            //$data [$value['app_id']] = $value['adtype_id'];
        }
        return $data;
    }
    //消息去向
    public function getMsg_toArray() {

        $list = M('message')->select();

        $data = array();
        // $data['0']='不限';
        foreach ( $list as $key => $value ) {
            $data [$value['message_id']] = $value['msg_to'];
            //$data [$value['app_id']] = $value['adtype_id'];
        }
        return $data;
    }


}

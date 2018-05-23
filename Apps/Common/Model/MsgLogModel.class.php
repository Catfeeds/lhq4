<?php
namespace Common\Model;
use Think\Model;

class MsgLogModel extends Model
{

/*if(!defined('ACCESS')) {exit('Access denied.');}

class MsgLog extends CommonBase {*/
//class Sample extends Base {
//	private static $table_name = 'msg_log';
 
//	private static $columns = 'id,member_id,message_id,status,create_time';
 
	
/*	public static function getTableName(){
		return self::$table_name;
	}
	 */
	public static function getMsgLogs() {

		$list = M('msg_log')->order("id desc")->select();
		if ($list) {
			return $list;
		}
        //var_dump($list);
        //var_dump($db->query($sql)->fetchAll());die;
		return array ();		
	}



	//添加数据
	public function addMsgLog($input_data) {
		if (! $input_data || ! is_array ( $input_data )) {
			return false;
		}
        //var_dump($input_data);//
		//$db=self::__instance();
		//$id = $db->insert ( self::getTableName(), $input_data );
        $id=$this->data($input_data)->add();
       // var_dump($id);die;
		return $id;
	}
	//删除
	public function delMsgLog($id) {
		if (! $id || ! is_numeric ( $id )) {
			return false;
		}
		//$db=self::__instance();
        $condition = array("id" => $id);
        $result = $this->where($condition)->delete();
		return $result;
	}
    //	//通过消息ID删除所以数据
    public function delMsgLogByMessage_id($message_id) {
        if (! $message_id || ! is_numeric ( $message_id )) {
            return false;
        }
        $condition = array("message_id" => $message_id);
        $result = $this->where($condition)->delete();
        return $result;
    }
	//修改
	public static function updateMsgLog($message_id,$update_data) {
		if (! $update_data || ! is_array ( $update_data )) {
			return false;
		}
		$db=self::__instance();
		$condition=array("message_id"=>$message_id);
		$id = $db->update ( self::getTableName(), $update_data,$condition );
	
		return $id;
	}
    //查询是否存在这条数据
    public function SelectMsg($member_id,$message_id) {

        $sub_condition ["member_id"] = $member_id;
        $sub_condition ["message_id"] = $message_id;

       // $condition = array("AND" => $sub_condition);
        // $condition["ORDER"] = " message_id desc";
        //$db=self::__instance();
       // $list = $db->select ( self::getTableName(), self::$columns, $condition );
        $list=$this->where($sub_condition)->select();
        if ($list) {
            return $list[0];
        }
        return array ();
    }
    //通过会员id查他所有的消息
    public function getMsg($message_id,$member_id) {
        //var_dump($member_id);//die;
        $sub_condition ["member_id"] = $member_id;
        $sub_condition ["message_id"] = $message_id;
//dump($sub_condition);
        $list=$this->where($sub_condition)->order('id desc')->select();
      //  var_dump($list);//die;
        if ($list) {
            return $list;
        }
        return array ();
    }
    //通过消息id查询消息条数

    public function getMsgLog($message_id) {
        //var_dump($member_id);//die;
        $sub_condition ["message_id"] = $message_id;;
        $list=$this->where($sub_condition)->order('id desc')->select();
        // var_dump($list);
        if ($list) {
            return $list;
        }
        return array ();
    }
    //查询个人消息数
    public function count($member_id,$click_time)
    {
       // $db = self::__instance();
        $sub_condition['member_id'] = $member_id;
        $sub_condition['status'] = '0';
       // $condition = array("AND" => $sub_condition);
       // $list = $this->select ( self::getTableName(), self::$columns, $condition );
        $list=$this->where($sub_condition)->select();
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
    public  function StatusChanges($message,$mid) {

        $time=date('Y-m-d H:i:s',time());
        $sub_condition['message_id'] = $message;
        $sub_condition['member_id'] = $mid;
        //$sub_condition['status'] = '1';
      //  $condition = array("AND" => $sub_condition);
       //var_dump($condition);
        $zt=array('status'=>'1','create_time'=>$time);
      //  $db=self::__instance();
        $list=$this->where($sub_condition)->save($zt);
        //$list = $db->update ( self::getTableName(),$zt,$condition );
        if ($list) {
            return $list;
        }
        return array ();
    }
    public function getCountByDate($member_id, $start_date, $end_date) {
        if ($member_id != '') {
            $condition['member_id'] =array("in",$member_id);
        }
        //var_dump($user_num);die;
        if ($start_date == '' && $end_date != '') {
            $condition['create_time'] = array('elt',$end_date);
        }
        if ($start_date != '' && $end_date == '') {
            $condition['create_time'] = array('egt',$start_date);;
        }
        if ($start_date != '' && $end_date != '') {
            // $where["wd_time"] = array('between',$start_date,$end_date);
            $condition["create_time"] = array(array('egt',$start_date),array('elt',$end_date));
        }

        $num = count($this->where($condition)->fetchSql()->select());

        return $num;
    }

    public function getCount($member_id) {

       // $sub_condition = array()
      //  var_dump($member_id);
        if ($member_id != '') {
            $where['member_id'] = $member_id;
        }

      //  $numq =  $this->where($where)->select();
        $num =count($this->where($where)->select());
        return $num;
    }


    public function getmsgByPage($member_id,$start ,$page_size,$start_date='',$end_date='') {
        $condition=array();
       // $sub_condition = array();
        if($member_id != ''){
            $condition['member_id']=$member_id;
        }
      //  var_dump($condition);
     /*   if ($start_date == '' && $end_date != '') {
            $condition['create_time'] = array('elt',$end_date);
        }
        if ($start_date != '' && $end_date == '') {
            $condition['create_time'] = array('egt',$start_date);;
        }*/
        if ($start_date != '' && $end_date != '') {
            // $where["wd_time"] = array('between',$start_date,$end_date);
            $condition["create_time"] = array(array('egt',$start_date),array('elt',$end_date));
        }
//        $condition["ORDER"]=;
      //  $condition['LIMIT']=array($start,$page_size);
        //var_dump($condition);
        $list = $this->where($condition)->order("id desc")->limit($start,$page_size)->select();
       // var_dump($list);//die;
        if ($list) {
            return $list;
        }
        return array ();
    }
    //分页
    public function search($member_id,$start_date,$end_date){
        $perPage = 25;
        // if ($search) {

        if ($member_id != "") {
            $where['member_id'] = $member_id;
        }

        if ($start_date == '' && $end_date != '') {
            $where['create_time'] = array('elt',$end_date);
        }
        if ($start_date != '' && $end_date == '') {
            $where['create_time'] = array('egt',$start_date);;
        }
        if ($start_date != '' && $end_date != '') {
            // $where["wd_time"] = array('between',$start_date,$end_date);
            $where["create_time"] = array(array('egt',$start_date),array('elt',$end_date));
        }

        // $join = "LEFT JOIN provider ON app.provider_id =provider.provider_id";
        //   }
        //  dump($where);//die;
        $count = count($this->where($where)->select());
        // dump($count);
        $pageObj = new \Think\Page($count,$perPage);

        // 设置样式
        $pageObj->setConfig('first','首页');
        $pageObj->setConfig('prev', '上一页');
        $pageObj->setConfig('next', '下一页');
        $pageObj->setConfig('last','共%TOTAL_PAGE%页');
        $pageObj->setConfig('theme','%FIRST% %UP_PAGE%  &nbsp;%LINK_PAGE%&nbsp; %DOWN_PAGE% %END% <span class="rows">共 %TOTAL_ROW% 条</span>');
        $pageString = $pageObj->show();
        /************** 取某一页的数据 ***************/
        $data = $this->alias('i')->field('i.*,m.title,m.content,m.msg_to,m.m_type,a.nickname')
            ->join('left join message m on i.message_id=m.message_id')
            ->join('left join member a on a.member_id=i.member_id')
            ->where($where)
            ->order("id desc")
            ->limit($pageObj->firstRow.','.$pageObj->listRows)
            ->select();
       // dump($data);die;
        /************** 返回数据 ******************/
        return array(
            'data' => $data,  // 数据
            'page' => $pageString,  // 翻页字符串
        );
    }

//通过用户ID查找消息其状态
    public function getStatusdArray($member_id)
    {
        $sub_condition ["member_id"] = $member_id;
        $list = $this->where($sub_condition)->select();
       // dump($list);
        $data = array();
        //$data['0']='不限';
        foreach ($list as $key => $value) {
            $data [$value['message_id']] = $value['status'];
        }
        return $data;
    }

    //查询所有的数据
    public function getMsglogLog($start, $page_size){
        $list = $this ->order("id desc")->limit($start, $page_size)->select();

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
    public function getCountByDateLog($start_date,$end_date) {

        $sub_condition["create_time"] =array(array("egt",$start_date),array("elt",$end_date),'and');
        $num = $this->where($sub_condition)->count();
        return $num;
    }


    public function getLogsLog($start ,$page_size,$start_date='',$end_date='') {
      //  $sub_condition = array();
        if($start_date !='' && $end_date !=''){
            $sub_condition["create_time"] =array(array('egt',$start_date),array('elt',$end_date),'and');
        }
        $list =$this->where($sub_condition)->order("id desc")->limit($start,$page_size)->select();
        // var_dump($chan_id);die;

        if ($list) {
            return $list;
        }
        return array ();
    }

}

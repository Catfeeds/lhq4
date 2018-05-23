<?php
namespace Common\Model;
use Think\Model;

class IncomeDetailsModel extends Model
{
/*if(!defined('ACCESS')) {exit('Access denied.');}

class IncomeDetails extends CommonBase
{*/
//class Sample extends Base {
    private static $table_name = 'income_details';

    private static $columns = 'income_id,member_id,income,time,mission_id,income_type,invited_id';


 /*   public static function getTableName()
    {
        return self::$table_name;
    }*/

    public  function getDetails()
    {

        //�������ַ�ʽ����Է���sample��DB
        $db = self::__instance();
        //$db=self::__instance(SAMPLE_DB_ID);
        $sql = "select " . self::$columns . " from " . self::getTableName() . " order by income_id desc";
        $list = $db->query($sql)->fetchAll();
        //var_dump($sql);die;
        if ($list) {
            return $list;
        }
        return array();
    }

    public function getDetailById($member_id)
    {
        if (!$member_id || !is_numeric($member_id)) {
            return false;
        }
        $sub_condition ["member_id"] = $member_id;
        //var_dump($sub_condition);die;
        //$condition = array("AND" => $sub_condition);
        //$condition["GROUP"]=" member_id";
        /*$db = self::__instance();
        $list = $db->select(self::getTableName(), self::$columns, $condition);*/
        $list=$this->where($sub_condition)->select();
        //var_dump($list);die;
        if ($list) {
            return $list;
        }
        return array();
    }
    //查询被邀请人任务信息
    public function getDetailByInviteId($id)
    {
        if (!$id || !is_numeric($id)) {
            return false;
        }
        $sub_condition ["invited_id"] = $id;

        $list=$this->where($sub_condition)->order('income_id desc')->select();
        //var_dump($list);die;
        if ($list) {
            return $list;
        }
        return array();
    }

    //查询被邀请人任务信息
    public function countDetailByInviteId($id)
    {
        if (!$id || !is_numeric($id)) {
            return false;
        }
        $sub_condition ["invited_id"] = $id;
     //   var_dump($sub_condition);
      //  $count=count($this->where($sub_condition)->order('income_id desc')->select());
        $count=$this->field('count(*) as count,invited_id')->where($sub_condition)->select();
       // var_dump($count);die;
        if ($count) {
            return $count;
        }
        return array();
    }
    //
    /*public function DetailList($member_id)
    {
        if (!$member_id || !is_numeric($member_id)) {
            return false;
        }
        $sub_condition ["member_id"] = $member_id;
        //var_dump($sub_condition);die;
        $sub_condition ["income_type"] = '2';
        $condition = array("AND" => $sub_condition);
        $condition["GROUP"]=" income_id desc";
        $db = self::__instance();
        $list = $db->select(self::getTableName(), self::$columns, $condition);
        //var_dump($list);die;
        if ($list) {
            return $list;
        }
        return array();
    }



    public  function getDetailsByPage($start, $page_size)
    {
        $db = self::__instance();
        $condition = array();
        $condition["ORDER"] = " income_id desc";
        $condition['LIMIT'] = array($start, $page_size);
        $list = $db->select(self::getTableName(), self::$columns, $condition);
        if ($list) {
            return $list;
        }
        return array();
    }
    public  function getDetailsArray() {

        //�������ַ�ʽ����Է���sample��DB
        $db=self::__instance();
        //$db=self::__instance(SAMPLE_DB_ID);

        $sql="select ".self::$columns." from ".self::getTableName();
        // var_dump($sql);die;
        $list = $db->query($sql)->fetchAll();
        //var_dump($list);//die;
        $data = array();

        foreach ( $list as $key => $value ) {
            $data [$value['member_id']] = $value['member_name'];
        }
        return $data;
    }

    public  function getidCounts() {
        $condition = array();
        $condition["ORDER"] = " income_id desc";
        $condition["GROUP"]=" member_id";
        $db=self::__instance();
        $list = $db->select ( self::getTableName(), self::$columns, $condition );
        //var_dump($list);die;
        if ($list) {
            return $list;
        }
        return array ();
    }
    //当前用户当天做任务收益
    public  function getTotalincome($member_id) {
        $condition = array();
        //$condition["ORDER"] = " income_id desc";
        $condition ["member_id"] = $member_id;
        $db=self::__instance();
        $list = $db->select ( self::getTableName(), self::$columns, $condition );
        //var_dump($list);die;
        $data = 0;
        //var_dump($num);die;
        foreach ($list as $k => $v) {
            if (substr($v['time'], 0, 10) == date("Y-m-d", time())) {
                $data += $v['income'];
            }
            //var_dump($list);die;
        }
        return $data;
        //echo $data;die;
    }



   public  function getIncome_detailsById($member_id) {
        if (! $member_id || ! is_numeric ( $member_id )) {
            return false;
        }
        $sub_condition ["member_id"] = $member_id;
       $sub_condition ["income_type"] = '1';
        $condition = array("AND" => $sub_condition);
       $condition["ORDER"] = " income_id desc";
    //    var_dump($condition);
        $db=self::__instance();
        $list = $db->select ( self::getTableName(), self::$columns, $condition );
        if ($list) {
            return $list;
        }
        return array ();
    }*/
    //添加数据
    public function addDetail($input_data) {
        if (! $input_data || ! is_array ( $input_data )) {
            return false;
        }
        $id = $this->data($input_data)->add();
        // $id = $db->insert ( self::getTableName(), $input_data );
        // //var_dump($id);die;
        return $id;
    }

    public function search($member_id){
        if ($member_id != "") {
            $where['member_id'] = $member_id;
        }
        $perPage = 25;
        $count = $this->where($where)->count();
        //dump($count);
        $pageObj = new \Think\Page($count,$perPage);

        // 设置样式
        $pageObj->setConfig('first','首页');
        $pageObj->setConfig('prev', '上一页');
        $pageObj->setConfig('next', '下一页');
        $pageObj->setConfig('last','共%TOTAL_PAGE%页');
        $pageObj->setConfig('theme','%FIRST% %UP_PAGE%  &nbsp;%LINK_PAGE%&nbsp; %DOWN_PAGE% %END% <span class="rows">共 %TOTAL_ROW% 条</span>');
        $pageString = $pageObj->show();
        /************** 取某一页的数据 ***************/
        $data = $this->order("income_id desc") ->
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

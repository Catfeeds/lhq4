<?php
namespace Common\Model;
use Think\Model;

class CustomerModel extends Model{
//class Sample extends Base {
	private static $table_name = 'customer';
 
	private static $columns = 'id,member_id,nickname,message,time_stamp,reply,time_reply,reply_status,openid';
 
	
	public  function getTableName(){
		return self::$table_name;
	}
	public  function getCustomer() {
		//$db=self::__instance();
	//	$sql="select ".self::$columns." from ".self::getTableName()." order by id desc";
		//$list = $db->query($sql)->fetchAll();
        $list= $this ->order('id desc')->select();
//        var_dump($list);
		if ($list) {
			return $list;
		}
		return array ();		
	}

    //检索
//    public  function countSearch($nickname) {
//    //    $db=self::__instance();
//        $condition = array();
//        if($nickname!=""){
////            $condition['LIKE']=array("nickname"=>$nickname);
//            $condition['nickname'] = array('like', "%$nickname%");
//        }
//        // var_dump($condition);
//        $num = $this->where($condition)->count();
//        //var_dump($num);die;
//        return $num;
//    }
    //查询
//    public  function search($nickname, $start ='' ,$page_size='',$channel_name ) {
//        //$db=self::__instance();
//        $limit ="";
//        $where = "";
//        $perPage = 25;
//       // var_dump($nickname);die;
//        $count = $this->count();
//        //   dump($count);
//        $pageObj = new \Think\Page($count,$perPage);
//        // 设置样式
//        $pageObj->setConfig('next', '下一页');
//        $pageObj->setConfig('prev', '上一页');
//        $pageString = $pageObj->show();
//        /************** 取某一页的数据 ***************/
//        if($page_size){
//            $limit =" limit $start,$page_size ";
//        }
//        if($nickname!=""){
//         //   $where = " where channel_name like '%$nickname%' ";
//            $where['channel_name'] = array('like', "%$channel_name%");
////            var_dump($where);die;
//        }
//     //   $sql = "select * from ".self::getTableName()." $where order by customer.id desc $limit";
//        $list=$this ->order("customer.id desc")                    // 排序
//        ->where($where)            // 翻页
//        ->limit($pageObj->firstRow.','.$pageObj->listRows)
//         ->select();
//      //  var_dump($list);die;
//        if ($list) {
//            return $list;
//        }
//        return array ();
//    }
    public function search($nickname,$search){
        $perPage = 25;
//        var_dump($channel_name);
        if ($search) {
            $where['nickname'] = array('like', "%$nickname%");
        }
//var_dump($where);
//        var_dump($where);
        $count = $this->where($where)->count();

        $pageObj = new \Think\Page($count,$perPage);
        // 设置样式
        $pageObj->setConfig('first','首页');
        $pageObj->setConfig('prev', '上一页');
        $pageObj->setConfig('next', '下一页');
        $pageObj->setConfig('last','共%TOTAL_PAGE%页');
        $pageObj->setConfig('theme','%FIRST% %UP_PAGE%  &nbsp;%LINK_PAGE%&nbsp; %DOWN_PAGE% %END% <span class="rows">共 %TOTAL_ROW% 条</span>');
        $pageString = $pageObj->show();
        /************** 取某一页的数据 ***************/
        $data = $this->order("id desc")                    // 排序
        ->limit($pageObj->firstRow.','.$pageObj->listRows)
            ->where($where)            // 翻页
            ->select();
//        var_dump($data);
        /************** 返回数据 ******************/
        return array(
            'data' => $data,  // 数据
            'page' => $pageString,  // 翻页字符串
        );
    }



    public  function getAllChannel( $start ='' ,$page_size='' ) {
      //  $db=self::__instance();
        $limit ="";
        if($page_size){
            $limit =" limit $start,$page_size ";
        }
      //  $sql = "select * from ".self::getTableName()."  order by customer.id desc $limit";
       // $list=$this->query($sql)->fetchAll();
        $list=$this->order('customer.id desc $limit')->select();
        //var_dump($list);die;
        if ($list) {
            return $list;
        }
        return array ();
    }
    public  function getCustomerById($id) {
        if (! $id || ! is_numeric ( $id )) {
            return false;
        }

        $sub_condition ["id"] = $id;
//        $condition = array("AND" => $sub_condition);
        //$db=self::__instance();
       // $list = $db->select ( self::getTableName(), self::$columns, $condition );
        $list=$this->where($sub_condition)->select();
//        var_dump($list);die;
        if ($list) {
            return $list[0];
        }
        return array ();
    }
    //用id获取openid
    public  function getCustomerByOpenid($id) {
      //  $db=self::__instance();
        $condition=array();
        if($id != ''){
            $sub_condition['id']=$id;
        }
//        $condition["AND"] = $sub_condition;
//        $callback =  $db->select ( self::getTableName(), "openid", $condition);
        $callback = $this->where($sub_condition )->select( "openid");
//        var_dump($callback);
        return $callback;
    }
//修改
    public  function updateCustomerInfo($id, $update_data) {
        if (! $update_data || ! is_array ( $update_data )) {
            return false;
        }
      //  $db=self::__instance();
        $condition=array("id"=>$id);
       // $id = $db->update ( self::getTableName(), $update_data,$condition );->select($update_data)
        $id = $this->where($condition) ->save($update_data );

        return $id;
    }
    //添加
    public  function addCustomer($update_data) {
        if (! $update_data || ! is_array ( $update_data )) {
            return false;
        }
        //
    //    $db=self::__instance();
        $id = $this->insert (  $update_data );
        //var_dump($id);die;
        return $id;
    }
//    //用openid获取member_id
//    public static function getCustomerBymemberid($openid) {
//        $db=self::__instance();
//        $condition=array();
//        if($openid != ''){
//            $sub_condition['openid']=$openid;
//        }
//        $condition["AND"] = $sub_condition;
//        $callback =  $db->select ( self::getTableName(), "member_id", $condition);
//        return $callback;
//    }

}

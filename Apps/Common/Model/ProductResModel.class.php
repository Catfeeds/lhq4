<?php
namespace Common\Model;
use Think\Model;
class ProductResModel extends Model {
	private static $table_name = 'product_res';
 
	private static $columns = 'id,owner,product_type,product_name,method,input_price,output_price,callback_method';
 
	
	/*public static function getTableName(){
		return self::$table_name;
	}*/
	 
	public function getProductRess() {
	
		//�������ַ�ʽ����Է���sample��DB
		/*$db=self::__instance();
		//$db=self::__instance(SAMPLE_DB_ID);
		
		$sql="select ".self::$columns." from ".self::getTableName()." order by id desc";
		$list = $db->query($sql)->fetchAll();*/
		$list=$this->order('id desc')->select();
       // dump($list);
		if ($list) {
			return $list;
		}
		return array ();		
	}
	
	/*public static function getProductRessArray() {
	
		//�������ַ�ʽ����Է���sample��DB
		$db=self::__instance();
		//$db=self::__instance(SAMPLE_DB_ID);
	
		$sql="select ".self::$columns." from ".self::getTableName();
		$list = $db->query($sql)->fetchAll();
		
		$data = array();
		
		foreach ( $list as $key => $value ) {
			$data [$value['id']] = $value['product_type'];
		}		
		return $data;
	}*/
	
	public function getProductResById($app_id) {
		if (! $app_id || ! is_numeric ( $app_id )) {
			return false;
		}
	
		$sub_condition ["id"] = $app_id;
		//$condition = array("AND" => $sub_condition);
		//$db=self::__instance();
	//	$list = $db->select ( self::getTableName(), self::$columns, $condition );
        $list=$this->where($sub_condition)->select();
		if ($list) {
			return $list[0];
		}
		return array ();
	}
	
	public function addProductRes($product_data) {
		if (! $product_data || ! is_array ( $product_data )) {
			return false;
		}
		//$db=self::__instance();
		//$id = $db->insert ( self::getTableName(), $product_data );
        $id = $this->data($product_data)->add();
		return $id;
	}
	
	public function delProductRes($id) {
		if (! $id || ! is_numeric ( $id )) {
			return false;
		}
		//$db=self::__instance();
		$condition = array("id" => $id);
      //  dump($condition);
		//$result = $db->delete ( self::getTableName(), $condition );
        $result = $this->where($condition)->delete();
		return $result;
	}
	
	public function updateProductResInfo($product_id,$product_data) {
		if (! $product_data || ! is_array ( $product_data )) {
			return false;
		}
	//	$db=self::__instance();
		$condition=array("id"=>$product_id);
	//	$id = $db->update ( self::getTableName(), $product_data,$condition );
        $id = $this->where($condition)->save($product_data);
		return $id;
	}
	
/*	public static function getProductResByPage($start ,$page_size) {
		$db=self::__instance();
	
		$condition=array();
	
		$condition["ORDER"]=" id desc";
		$condition['LIMIT']=array($start,$page_size);
	
		$list = $db->select ( self::getTableName(), self::$columns, $condition);
	
		if ($list) {
			return $list;
		}
		return array ();
	}*/
    //分页
    public function search(){
        $perPage = 25;

        $count = count($this->select());
     //   dump($count);
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
           // ->join($join)
            ->order("id desc")                    // 排序
          //  ->where($where)            // 翻页
            ->limit($pageObj->firstRow.','.$pageObj->listRows)
            ->select();

        /************** 返回数据 ******************/
        return array(
            'data' => $data,  // 数据
            'page' => $pageString,  // 翻页字符串
        );
    }

}

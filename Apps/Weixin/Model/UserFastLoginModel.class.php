<?php

namespace Weixin\Model ;

use Think\Model ;

class UserFastLoginModel extends Model {

	/**
	 * 向表中添加数据
	 * @date: 2015年12月21日 上午10:53:32
	 * @author: 王崇全
	 * @param: array $data 键值对数据
	 * @return:Boolean 成功,true;失败false
	 */
	function addInfo( $data ) {

		if( ! $data ){
			return false ;
		}
		
		$flag = M( "UserFastLogin" ) -> add( $data ) ;
		
		if( ! $flag ){
			return false ;
		}
		
		return true ;
	
	}

	/**
	 * 获取表内数据
	 * @date: 2015年12月21日 上午11:24:41
	 * @author: 王崇全
	 * @param: [array $map 键值对条件 为空,即所有记录]
	 * @param: [string $fields 逗号分隔的字段集合 为空,即所有字段]
	 * @return:array 一条记录,关联数组; 多条数据,索引数组
	 */
	function getInfo( $map = null , $fields = null ) {

		$info = M( "UserFastLogin" ) -> field( $fields ) 
			-> where( $map ) 
			-> select( ) ;
		
		if( count( $info ) == 1 ){
			return $info[0] ;
		}else{
			return $info ;
		}
	
	}

	/**
	 * 查询某字段中是否存在某值
	 * @date 2015年12月26日 上午11:31:46
	 * @author 王崇全
	 * @param string $field 字段名
	 * @param string $val 字段值
	 * @return Boolean 有,true;无,false
	 */
	function isExist( $field , $val ) {

		$flag = M( "UserFastLogin" ) -> where( array(
			$field => $val
		) ) -> find( ) ;
		
		if( ! $flag ){
			return false ;
		}
		return true ;
	
	}

	/**
	 * 设置表内数据
	 * @date: 2015年12月21日 上午11:24:41
	 * @author: 王崇全
	 * @param: array $map 键值对条件
	 * @param: array $data 键值对数据
	 * @return:Boolean 成功,true;失败,false
	 */
	function setInfo( $map , $data ) {

		if( ! ( $map && $data ) ){
			return false ;
		}
		
		$flag = M( "UserFastLogin" ) -> where( $map ) -> save( $data ) ;
		
		if( ! $flag ){
			return false ;
		}else{
			return true ;
		}
	
	}


}
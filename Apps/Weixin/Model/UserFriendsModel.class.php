<?php
namespace Weixin\Model ;

use Think\Model ;

/**
 * 朋友表模型
 * @date 2015年12月31日 下午5:42:40
 * @author 王崇全
 */
class UserFriendsModel extends Model {

	/**
	 * 获取某位用户的朋友列表
	 * @date 2015年12月31日 下午5:44:03
	 * @author 王崇全
	 * @param string $userid 用户编号
	 * @return 成功,由朋友用户编号等信息组成的索引数组;失败,false
	 */
	function myFriends( $userid ) {

		if( ! $userid ){
			return false ;
		}
		
		$tBt = C( "DB_PREFIX" ) . "user_friends" ;
		$tU =  "member" ;
        //$tU = D( 'member' );
		$joinset = "LEFT JOIN $tU ON $tBt.friendsId = $tU.member_id" ;
		
		$field = "$tU.member_id,$tU.nickname,$tU.pic, $tBt.anchorLink" ;
		$myFriends = M( "user_friends" ) -> field( $field ) 
			-> where( array(
			"partiesId" => $userid
		) ) 
			-> join( $joinset ) 
			-> select( ) ;
		
		return $myFriends ;
	
	}


}
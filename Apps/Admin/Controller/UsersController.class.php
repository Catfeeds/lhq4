<?php

namespace Admin\Controller ;

use Think\Controller ;

class UsersController extends AdminController {

	public function index( ) { // 用户管理首页
		$this -> assign( 'title' , '用户列表' ) ;
		$this -> display( ) ;
	
	}
	
	// 返回 用户列表 JSON
	public function lists( ) { // Ajax 用户列表

		$page = I( 'get.page' , 1 , 'int' ) ;
		$status = I( 'get.status' , 0 , 'int' ) ;
		
		$page -- ;
		$row = I( 'get.rows' , 10 , 'int' ) ;
		$skip = $page * $row ;
		$order = array(
			'member_id','creatdate' => 'desc'
		) ;
		$keyword = I( 'keyword' , '' , 'trim' ) ;
		
		if( ! empty( $keyword ) ) $where['phone|nickName'] = array(
			'like',"%$keyword%"
		) ;
		
		if( $status ){
			if( $status < 0 ){
				$where['is_robot'] = $status == - 1 ? 1 : 0 ;
			}else{
				$where['subscribe'] = $status ;
			}
		}
		//subscribe
		$order = array(
			"creatDate" => "desc"
		);
		
		$list = D('user')->where($where) 
			->limit("$skip, $row") 
			->order($order) 
			->select();
		$sql = D()->_sql() ;
		
		$total = D('User')->where($where)->count();
		$count = count($list);
		$pages = ceil($total / $row);

		//
		foreach($list as &$v){
			$v['share_num'] = M('user_detail')->where("name = 'ZC' AND rpUserId = {$v['member_id']}")->count();
			// $v['share_com'] = M('user')->where('id = '. $v['id'])->getField('comsum');

			$v['share_val'] = M('user_detail')->where("rpUserId = {$v['member_id']}")->sum('comval');
			$v['share_val'] = round($v['share_val'], 2);
			$v['share_com'] = round($v['comsum'], 2);
		}


		$data = array(
			'code' => 1,'_sql' => $sql,'page' => $page + 1, // 当前页数
			'pages' => $pages, // 总页数
			'count' => $count,'total' => $total, // 总条数
			'row' => $row, // 每页条数
			'skip' => $skip,'list' => $list
		) ;
		
		admJson( $data ) ;

//dump($data);
	
	}
	
	// 切换状态
	public function toggleStatus( ) { // 开启关闭用户

		$id = I( 'post.id' , 0 , 'int' ) ;
		
		if( $id ){
			M( ) -> execute( "update __PREFIX__user set status = if(status != 1, 1, 2) where id = $id" ) ;
		}
		
		admJson( array(
			'admin' => 32
		) ) ;
	
	}
	
	// 用户 修改页
	public function edit( ) { // 编辑用户信息

		if( IS_POST ){
			$user = D( 'user' ) ;
			$data = $user -> create( ) ;
			if( $user -> data( $data ) -> save( ) ){
				$this -> success( "修改成功" , "javascript:self.close()" , 3 ) ;
			}else{
				$this -> error( "您未作修改 或 修改失败 ! " ) ;
			}
			
			exit( ) ;
		}
		
		$id = I( 'get.member_id' , 0 ) ;
		$this -> assign( 'title' , '用户详情' ) ;
		$users = D( 'user' ) -> find( $id ) ;
		$this -> assign( 'users' , $users ) ;
		layout( "inc/tpl.min" ) ;
		$this -> display( ) ;
	
	}

	/**
	 * 添加用户
	 * @date: 2015年11月5日 下午2:22:32
	 * @author: 王崇全
	 * @param: 
	 * @return:
	 */
	function add( ) { // 新增用户

		if( IS_POST ){

			$validateRules = array(
				array(
					'pic','require','必须上传头像！'
				),array(
					'nickname','','昵称已经存在！',0,'unique'
				),array(
					'email','','邮箱已经存在！',0,'unique'
				),array(
					'email','','邮箱已经存在！',0,'unique'
				),array(
					'phone','','电话已经存在！',0,'unique'
				)/*,array(
					'password','/\S{6,}/','密码至少为6位可显示的字符'
				)*/
			) ; // 自定义函数验证密码格式
			

			$autoRules = array(
			/*	array(
					'password','',2,'ignore'
				),*/
                array(
					'grandBuy','0'
				),array(
					'grandRechange','0'
				),array(
					'bonus','0'
				),array(
					'balance','0'
				),array(
                    'creatDate',time()
                )
				/*,array(
					'password','md5',3,'function'
				),array(
					'creatDate','time',3,'function'
				)*/
			) ;
			
			$User = D( 'user' ) ;
            //dump($User);DIE;
			$User -> setProperty( '_validate' , $validateRules ) ;
			$User -> setProperty( '_auto' , $autoRules ) ;
			//dump($User);die;
			if( ! $User -> create( ) ){
				$this -> error( $User -> getError( ) ) ;
			}else{
				if( ! D( "user" ) -> add( ) ){
					$this -> error( $User -> getError( ) ) ;
				}else{
					$this -> success( "添加成功" , U( "index" ) ) ;
				}
			}
		}
		
		//layout( "inc/tpl.min" ) ;
		$this -> display( ) ;
	
	}

	/**
	 * 充值记录
	 * @date: 2015年11月5日 下午2:21:55
	 * @author: 王崇全
	 * @param: 
	 */
	function rechargeRecord( ) { // 充值页面

		$this -> display( ) ;
	
	}

	/**
	 * 删除用户
	 * @date: 2015年11月5日 下午2:18:32
	 * @author: 王崇全
	 * @param: 
	 * @return:
	 */
	public function del( ) { // 删除用户

		$id = I( 'post.id' , 0 , 'int' ) ;
		
		$arr = array(
			'member_id' => $id,'a' => 'del'
		) ;
		
		$flag = M( "user_fast_login" ) -> where( array(
			"user_id" => $id
		) ) -> find( ) ;
		
		$flag1 = M( "periods_detail" ) -> where( array(
			"userId" => $id
		) ) -> find( ) ;
		
		if( $flag || $flag1 ){
			$arr['code'] = 501 ;
			$arr['msg'] = '此用户已经参与过本站,不能删除.(可以禁用此用户)' ;
			admJson( $arr ) ;
		}
		
		if( ! $id ){
			$arr['code'] = 501 ;
			$arr['msg'] = '参数 id 错误' ;
		}elseif( ! D( 'user' ) -> delete( $id ) ){
			
			$arr['code'] = 500 ;
			$arr['msg'] = '删除 错误' ;
		}else{
			M( "user_fast_login" ) -> where( array(
				"user_id" => $id
			) ) -> delete( ) ;
		}
		
		admJson( $arr ) ;
	
	}


}
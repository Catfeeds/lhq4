<?php

/**
 * 简爱
 * Date 2015年11月24日
 */
namespace Admin\Controller ;

use Think\Controller ;

class GoodsController extends AdminController {

	public function index( ) {

		$this -> assign( 'title' , '商品列表' ) ;
		$types = M( 'goods_type' ) -> where( "status = 1" ) 
			-> order( array(
			'taxis, id desc'
		) ) 
			-> select( ) ;
		
		//更新设置的缓存
		S( 'system_config' , null ) ;
		M( 'system' ) -> cache( 'system_config' , 120 ) -> getField( 'name,val' ) ;
		
		$this -> assign( 'types' , $types ) ;
		$this -> display( ) ;
	
	}

	/**
	 * 选取特定商品,返回商品ID
	 * @date 2016年1月6日 上午9:18:52
	 * @author 王崇全
	 */
	function goodsSelect( ) {

		if( IS_AJAX ){
			admJson( D( 'goods' ) -> jsonList( ) ) ;
		}
		
		$types = M( 'goods_type' ) -> where( "status = 1" ) 
			-> order( array(
			'taxis, id desc'
		) ) 
			-> select( ) ;
		$this -> assign( 'types' , $types ) ;
		layout( "inc/tpl.min" ) ;
		$this -> display( ) ;
	
	}

	/**
	 * 新增商品页面
	 * @access public
	 * @param
	 * @return void
	 */
	public function add( ) {

		$this -> assign( 'title' , '新增商品' ) ;
		
		// $user = M('User')->field('uid,nickname,username,email')->find($uid);
		//$this -> assign( 'user' , $user ) ;
		

		layout( "inc/tpl.min" ) ;
		$types = M( 'goods_type' ) -> where( "status = 1" ) 
			-> order( array(
			'taxis, id desc'
		) ) 
			-> select( ) ;
		$this -> assign( 'do' , 'add' ) ;
		$this -> assign( 'types' , $types ) ;
		$this -> display( 'edit' ) ;
	
	}

	/**
	 * 异步返回商品列表
	 * @access public
	 * @param string
	 * @return void
	 */
	public function lists( ) {

		admJson( D( 'goods' ) -> jsonList( ) ) ;
	
	}

	/**
	 * 商品编辑页面
	 * @access public
	 * @param
	 * @return void
	 */
	public function edit( ) {

		if( IS_POST ){
			$gid = I( 'id' , 0 , 'int' ) ;
			//
			//检验当前期是否已经出售
			$ginfo = M( 'goods' ) -> find( $gid ) ;
			$qishu = $ginfo['qishu'] ;
			$pinfo = M( "periods" ) -> where( array(
				"goodsId" => $gid,"qishu" => $qishu
			) ) -> find( ) ;
            //var_dump($pinfo);
			if( $pinfo['canyushu'] != 0 ){
				$this -> error( "此商品已有人参与,不能修改" ) ;
			}
			
			//检查此商品在售的包场是否已售出			
			$bbinfos = M( "bbooking" ) -> where( array(
				"goods_id" => $gid,"status" => 1
			) ) -> select( ) ;
			if( $bbinfos ){
				foreach( $bbinfos as $bbinfo ){
					if( $bbinfo['sales'] != 0 ){
						$this -> error( "此商品的包场已有人参与,不能修改" ) ;
						break ;
					}
				}
			}
			
			try{
				
				//修改商品销售信息
				M( 'Periods' ) -> where( array(
					'goodsId' => $gid,'status' => 3
				) ) -> save( array(
					'total' => I( 'originPrice' ),'fenshu' => I( 'fenshu' )
				) ) ;
				
				//修改包场信息
				M( "bbooking" ) -> where( array(
					"goods_id" => $gid,"status" => 1
				) ) -> save( array(
					'goods_value' => I( 'originPrice' ),'amount' => I( 'fenshu' )
				) ) ;
				
				//修改零元购商品销售信息
				// 				M( 'PeriodsZero' ) -> where( array(
				// 					'goods_id' => $gid,'status' => 1
				// 				) ) -> save( array(
				// 					'goods_value' => I( 'originPrice' ),'amount' => I( 'fenshu' )
				// 				) ) ;
				

				//重新生成pcode
				if( $pinfo['id'] ){
					//var_dump($pinfo['id']);//die;
					//清理pcode
					M( "pcode" ) -> where( array(
						"pid" => $pinfo['id']
					) ) -> delete( ) ;
					
					//生成pcode
					buildPcode( $pinfo['id'] ) ;
				}
				//重新生成bbcode
				if( $bbinfos ){
					$bbids = array() ;
					$i = 0 ;
					foreach( $bbinfos as $bbinfo ){
						
						//清理bbcode
						M( "bbpcode" ) -> where( array(
							"bbid" => $bbinfo['id']
						) ) -> delete( ) ;
						
						//生成bbcode
						buildbbPcode( $bbinfo['id'] ) ;
					}
				}
               // echo 1;
				//dump($bbinfo);
				return $this -> update( ) ;
			}catch(\Exception $e){

				die( M( ) -> _sql( ) ) ;
			}
		}
		
		$id = I( 'get.id' , 0 ) ;
		$this -> assign( 'title' , '商品详情' ) ;
		
		$goods = M( 'goods' ) -> find( $id ) ;
		
		if( empty( $goods ) ) $this -> error( '商品不存在' , 'javascript:self.close()' ) ;
		
		$types = M( 'goods_type' ) -> where( "status = 1" ) 
			-> order( array(
			'taxis, id desc'
		) ) 
			-> select( ) ;
		
		$images = M( 'goods_detail' ) -> where( array(
			'goodsId' => $id,'type' => 'img'
		) ) 
			-> order( 'taxis, id desc' ) 
			-> 
		// ->getField('content', true)
		field( 'id, taxis, content' ) 
			-> select( ) ;
		
		$goods['originprice'] = round( $goods['originprice'] , 2 ) ;
		
		// _d($images);
		

		$this -> assign( 'do' , 'edit' ) ;
		$this -> assign( 'goods' , $goods ) ;
		$this -> assign( 'types' , $types ) ;
		$this -> assign( 'images' , $images ) ;
		
		layout( "inc/tpl.min" ) ;
		$this -> display( ) ;
	
	}

	/**
	 * 检测商品名称是否重复
	 */
	public function check_name( ) {

		if( IS_AJAX ){
			$title = I( 'get.title' , '' ) ;
			$flag = M( 'goods' ) -> where( array(
				'title' => $title
			) ) -> find( ) ;
			if( $flag ){ //查询是否有记录
				$status = 1 ;
			}else{
				$status = 0 ;
			}
		}
		admJson( array(
			'status' => $status
		) ) ;
	
	}

	/*
	 * 0元购方法
	 */
	public function zero( ) {

		if( IS_POST ){
			$time = time( ) ;
			$id = I( 'post.id' , 0 , 'int' ) ;
			$discloseDate = I( 'post.discloseDate' , '' ) ;
			
			if( $id && $discloseDate ){
				$goods = M( 'goods' ) ; //商品模型
				$periods = M( 'periods_zero' ) ;
				
				if( strtotime( $discloseDate ) > $time ){
					
					$data = $goods -> find( $id ) ;
					//查询最大期数
					$qishu = $periods -> query( 'select case when MAX(qishu) then MAX(qishu)+1 else 1 end as qishu from yytb_periods_zero where goodsId = ' . $data['id'] ) ;
					//var_dump($qishu);exit;
					$_p_data = array(
						'title' => $data['title'],'goodsId' => $data['id'],'canyushu' => 0,'creatDate' => $time,
						//'creatMicrotime' => _date(),
						'qishu' => $qishu[0]['qishu'],'discloseDate' => strtotime( $discloseDate )
					) ;
					//var_dump($_p_data);exit;
					if( $periods -> add( $_p_data ) ){
						$code = 200 ;
					}
					;
				}
			}
			admJson( array(
				'code' => $code
			) ) ;
		}
		
		$id = I( 'get.id' , 0 ) ;
		
		$this -> assign( 'id' , $id ) ;
		layout( "inc/tpl.min" ) ;
		$this -> display( ) ;
	
	}

	/**
	 * 异步更新添加商品
	 * @access public
	 * @param
	 * @return void
	 */
	public function update( ) {

		$_validate = array(
			array(
				'price',array(
					1,5,10,20,100
				),'价格错误',2,'in'
			)
		) ;
		$_auto = array(
			array(
				'status','1'
			),array(
				'qishu','1'
			),array(
				'updateDate','time',3,'function'
			),array(
				'creatDate','time',1,'function'
			),array(
				'content','htmlspecialchars_decode',3,'function'
			)
		) ;
		
		// 实例化 商品、 云购表
		$goods = D( 'Goods' ) ;
        //dump($goods);
		$periods = M( 'periods' ) ;
		$id = I( 'post.id' , 0 , 'int' ) ;
		// $goods = new \Admin\Model\GoodsModel();
		// 删除状态
		unset( $_POST['status'] ) ;
		
		$id = I( 'post.id' , 0 , 'int' ) ;
		
		$msg = false ;
		
		$__status = I( 'post.status' , 0 , 'int' ) ;
		
		if( I( 'post.maxqishu' , 0 , 'int' ) < 1 ){
			admJson( array(
				'code' => 10,'msg' => '最大期数错误'
			) ) ;
		}
		if( I( 'post.fenshu' , 0 , 'int' ) < 1 ){
			admJson( array(
				'code' => 10,'msg' => '份数错误'
			) ) ;
		}

		if( $id ){
			
			#修改
			if( ! $data = $goods -> validate( $_validate ) 
				-> auto( $_auto ) 
				-> create( ) ){
				$msg = $goods -> getError( ) ;
                //dump($msg);
			}else{
				if( $id && isset( $data['qishu'] ) ) unset( $data['qishu'] ) ;
				$on = $goods -> save( $data ) ;
				//dump($on);
				$_info = M( 'goods' ) -> find( $id ) ;
				$qishu = $_info['qishu'] ;
				
				if( $_info['fenshu'] == $_info['canyushu'] && $_info['qishu'] < $_info['maxqishu'] ){
					M( 'goods' ) -> where( "id = $id" ) -> save( array(
						'canyushu' => 0,'qishu' => $_info['qishu'] + 1
					) ) ;
					
					$qishu ++ ;
				}
			}
			
			$data = $goods -> find( $id ) ;
//dump($data);
			// $qishu = I( 'post.qishu_bak' , 0 , 'int' ) ;
			

			D( 'periods' ) -> getPeriodsId( $id , $qishu ) ;
		}else{
            //var_dump($_auto);
			#添加			
			$qishu = 1 ;

			// 新增 商品 增加云购表 信息
			$data = $goods -> auto( $_auto ) 
				-> validate( $_validate ) 
				-> create( ) ;
			//var_dump($data);//die;
			if( ! $data ){
				appError( $goods -> getError( ) ) ;
			}
          /*  $goods -> taxis = 0;
            $goods -> canyushu = 0;
            $goods -> lock_version = '';*/
			$_goods_id = $goods -> add( ) ;
            //dump($_goods_id);
            ///echo 1;
			if( $_goods_id ){
//dump($on);
				$pid = D( 'periods' ) -> getPeriodsId( $on , 1 ) ;
				 				//echo '123' ;
				// 				dump( $pid ) ;
				// 				exit( ) ;
				//dump($pid);
				if( $pid ){
					
					/*创建云购码开始*/
					if( ! buildPcode( $pid ) ){
						$on = 500 ;
					}
					/*创建云购码结束*/
				}
			}
		}
		
		$code = $_goods_id ? 200 : 500 ;
      //  dump($code);
		$goodsId = $id ? $id : $_goods_id ;
       // dump($_goods_id);
		if( $goodsId ){
			// 更新 云购表信息
			$type = I( 'post.type' , 1 , 'int' ) ;
			
			$periods -> where( array(
				'goodsId' => $goodsId
			) ) -> save( array(
				'type' => $type
			) ) ;
		}
		
		$code = 200 ;
		// 新增文章必须 返回 id

		admJson( array(
			'code' => $code,'msg' => $msg,'id' => $goodsId,'data' => $data
		) ) ;
	
	}

	/**
	 * 异步增删 商品图片
	 * @access public
	 * @param
	 * @return void
	 */
	public function editImage( ) {

		$do = I( 'post.ac' , 'del' ) ;
		
		if( $do == 'del' )
			$r = $this -> delImage( $id ) ;
		else
			$r = $this -> addImage( $url ) ;
		
		admJson( $r ) ;
	
	}
	
	// 添加详情图片
	private function addImage( ) {

		$url = I( 'post.url' , 0 ) ;
		$goodsId = I( 'post.goodsId' , 0 , 'int' ) ;
		
		$arr = array(
			'id' => 0,'url' => $url,'a' => 'add'
		) ;
		
		$goodsDetail = M( 'goods_detail' ) ;
		
		$data = $goodsDetail -> create( array(
			'goodsId' => $goodsId,'content' => $url
		) ) ;
		
		$data['type'] = 'img' ;
		
		if( ! $url || ! $goodsId ){
			$arr['code'] = 501 ;
			$arr['msg'] = '参数 id 错误' ;
		}elseif( ! $arr['id'] = $goodsDetail -> add( $data ) ){
			$arr['code'] = 500 ;
			$arr['msg'] = '新增 错误' ;
		}
		
		return $arr ;
	
	}
	
	// 删除详情图片
	private function delImage( ) {

		$id = I( 'post.id' , 0 , 'int' ) ;
		
		$arr = array(
			'id' => $id,'a' => 'del'
		) ;
		
		if( ! $id ){
			$arr['code'] = 501 ;
			$arr['msg'] = '参数 id 错误' ;
		}elseif( ! M( 'goods_detail' ) -> delete( $id ) ){
			$arr['code'] = 500 ;
			$arr['msg'] = '删除 错误' ;
		}
		return $arr ;
	
	}

	/**
	 * 异步删除商品
	 * @access public
	 * @param
	 * @return void
	 */
	public function del( ) {

		$id = I( 'post.id' , 0 , 'int' ) ;
		
		$arr = array(
			'id' => $id,'a' => 'del'
		) ;
		
		if( ! $id ){
			$arr['code'] = 501 ;
			$arr['msg'] = '参数 id 错误' ;
		}elseif( ! M( 'goods' ) -> delete( $id ) ){
			$arr['code'] = 500 ;
			$arr['msg'] = '删除 错误' ;
		}
		admJson( $arr ) ;
	
	}

	public function dellist( ) {

		$idlist = I( "post.idlist" ) ;
		//dump( $idlist);
		$list = array(
			"id" => array(
				'in',$idlist
			)
		) ;
		if( ! $idlist ){
			$arr['code'] = 501 ;
			$arr['msg'] = '参数 id 错误' ;
		}elseif( ! M( 'goods' ) -> where( $list ) -> delete( $list ) ){
			$arr['code'] = 500 ;
			$arr['msg'] = '删除 错误' ;
		}
		//echo M()->getLastSql();
		dump( $arr ) ;
	
	}

	/**
	 * 异步切换商品状态
	 * @access public
	 * @param
	 * @return void
	 */
	public function toggleGoods( ) {

		$id = I( 'post.id' , 0 , 'int' ) ;
		if( ! $id ) return ;
		
		$_info = M( 'goods' ) -> find( $id ) ;
		if( $_info['status'] == 0 && $_info['qishu'] == $_info['maxqishu'] ){
			admJson( array(
				'code' => 0,'msg' => '修改 最大期数 后再开启商品'
			) ) ;
		}
		
		M( 'goods' ) -> execute( "
			update __PREFIX__goods
			set status = if(status != 1, 1, 0)
			where id = $id
		" ) ;
		admJson( array(
			'code' => 1
		) ) ;
	
	}
	//初始化数据库
	public function initPeriod( ) {

		set_time_limit( 0 ) ;
		
		$list = M( 'goods' ) -> field( 'id, qishu' ) -> select( ) ;
		
		foreach( $list as $vo ){
			
			$id = $vo['id'] ;
			$qishu = $vo['qishu'] ;
			
			D( 'periods' ) -> getPeriodsId( $id , $qishu ) ;
		}
		
		echo 'ok' ;
		exit( ) ;
	
	}

	/**
	 * 切换是否推荐
	 * @date 2016年2月23日 上午9:33:06
	 * @author ilanguo_cqwang
	 * @param  int gid 商品编号
	 * @return 
	 */
	function switchRecommend( ) {
		
		//接收商品编号
		$gid = I( 'post.gid' , 0 , 'int' ) ;
		
		$sql = "
    		update __GOODS__ 
    		set recommend=if(recommend=1,0,1)
    		where id=$gid
    		" ;
		M( 'goods' ) -> execute( $sql ) ;
	
	}

	/**
	 * 设置商品的人气阀值
	 * @date 2016年2月23日 上午10:40:34
	 * @author ilanguo_cqwang
	 * @param int rt 商品人气阀值
	 * @return
	 */
	function setRT( ) {
		
		//接收商品人气的阀值
		$rt = I( 'post.rt' , 0 , 'float' ) ;
		
		$rt = round( $rt , 0 ) ;
		if( $rt <= 0 ){
			$rt = 65534 ;
		}
		
		//修改人气阀值
		$flag = M( "system" ) -> where( array(
			"name" => "recommend_threshold"
		) ) -> save( array(
			"val" => $rt
		) ) ;
		
		if( ! $flag ){
			$this -> error( "修改失败" ) ;
		}
		
		//更新设置的缓存
		S( 'system_config' , null ) ;
		M( 'system' ) -> cache( 'system_config' , 120 ) -> getField( 'name,val' ) ;
		
		$this -> success( "人气阀值已改为$rt" , "$rt" ) ;
		die( ) ;
	
	}


}

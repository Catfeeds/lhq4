<?php

namespace Weixin\Controller ;

use Think\Controller ;

/**
 * 二维码生成器
 * @date 2016年1月7日 上午10:55:15
 * @author 王崇全
 */
class QRcodeController extends CommonController {

	function contact( ) {

		$tel = "客服电话:" . C( "service_tel" ) ;
		$qq = "客服QQ:" . C( "service_qq" ) ;
		
		$data = $tel . "\r\n" . $qq ;
		
		$data = "MECARD:N:壹圆喜购QQ" . C( "service_qq" ) . ";TEL:" . C( "service_tel" ) . ";" ;
		$data = $this -> QRcode( $data ) ;
	
	}

	/**
	 * 将内容转成二维码
	 * @date 2016年1月7日 下午12:03:06
	 * @author 王崇全
	 * @param string $data 文本内容
	 * @return 直接输出二维码
	 */
	function QRcode( $data ) {
		
		//强制浏览器进行缓存
		browserCache( ) ;
		
		vendor( "phpqrcode.phpqrcode" ) ;
		
		//二维码内容	
		//$data = '客服电话:400-827-7727  QQ:4008277727' ;
		// 纠错级别：L、M、Q、H
		$level = 'L' ;
		// 点的大小：1到10,用于手机端4就可以了
		$size = 10 ;
		
		// 下面注释了把二维码图片保存到本地的代码,如果要保存图片,用$fileName替换第二个参数false
		//$path = "images/";		
		// 生成的文件名
		//$fileName = $path.$size.'.png';
		

		$QRcode = new \QRcode( ) ;
		$QRcode :: png( $data , false , $level , $size ) ;
	
	}


}

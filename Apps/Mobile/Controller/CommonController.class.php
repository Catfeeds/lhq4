<?php
namespace Mobile\Controller;
use Think\Controller;
class CommonController extends Controller{
	function _initialize( ) {
        $host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
		
		if(filter_var($host, FILTER_VALIDATE_IP)) {// 合法IP
			echo "<script>location.href='404.html'</script>"; 
            die; 
		}
    }  


}
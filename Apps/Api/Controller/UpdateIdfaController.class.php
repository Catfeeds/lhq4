<?php
namespace Api\Controller ;
use Think\Controller ;
class UpdateIdfaController extends Controller{
	//更新idfa
	public function index(){		
		$mid = I('get.mid');
		$idfa = I('get.idfa');

		if (!empty($_GET)) {

			$re = D('member')->where(array('member_id'=>$mid))->save(array('idfa'=>$idfa));
		 	if($re) {
		 		echo true;
		 	} else {
		 		echo false;
		 	}
		}
	}

} 

?>

<?php
namespace Api\controller;
use Think\controller;
class VersionCheckController extends Controller{
	//接口访问
    public function version(){
        if (IS_GET) {
            //$app_name = I('get.appName');
        	$app_name = "xbmusic";
            $version = I('get.version');
            $id = I('get.id');
            $status = D('Version')->field('status')->where(array('app_name'=>$app_name,'version'=>$version))->find();
            if ($status['status'] == 1) {
               // $member_id = D('Member')->field('member_id')->where(array('idfa'=>$idfa))->find();
                if (strlen($id) == 36) {
                    if ($id == '00000000-0000-0000-0000-000000000000') {
                        echo json_encode(array("message"=>"yes","url"=>"http://yyb.cndiandian.cn","mid"=>"0"));
                    }else{
                        $member_id = D('Member')->field('member_id')->where(array('idfa'=>$id))->find();
        
                        if (empty($member_id)) {
                            $memberId = D('Member')->data(array('idfa'=>$id))->add();
                            echo json_encode(array("message"=>"yes","url"=>"http://yyb.cndiandian.cn","mid"=>$memberId));exit;
                        }
                        echo json_encode(array("message"=>"yes","url"=>"http://yyb.cndiandian.cn","mid"=>$member_id['member_id']));
                    }
                }else{
                    echo json_encode(array("message"=>"no","url"=>''));
                }
                
            }else{
                echo json_encode(array("message"=>"no","url"=>''));
            }

        }
    }
}

?>
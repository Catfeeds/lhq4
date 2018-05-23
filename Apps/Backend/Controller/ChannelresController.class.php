<?php
namespace Backend\Controller;
use Think\Controller;
use Common\Common\UserSession;
class ChannelresController extends ComController {
    public function channelRes(){
        $method = $id = $page_no = '';
        extract ( $_GET, EXTR_IF_EXISTS );

        if ($method == 'del' && ! empty ( $id )) {
            if (intval($id) <= 0) {
                // OSAdmin::alert("error",ErrorMessage::CAN_NOT_DELETE_SYSTEM_MENU);
                $this->alert("error",'参数不正确');//error('参数不正确', U('Backend/Channelres/channelRes'), 1);
            } else {
                $channels = D('ChannelRes')->getChannelById($id);
                $result = D('ChannelRes')->delChannel($id);
                if ($result) {
                    D('SysLog')->addLog ( UserSession::getUserName(), 'DELETE', '配置信息' ,$id, json_encode($channels) );
                    $this->exitWithSuccess('已将配置信息删除', U('Backend/Channelres/channelRes'), 1);
                } else {
                   // $this->error('删除失败', U('Backend/Channelres/channelRes'), 1);
                    $this->alert("error");
                }
            }
        }
       // $list = D('ChannelRes')->getChannelRess();

//dump($list);die;
        $data = D('ChannelRes')->search();


        $this->assign ( '_GET', $_GET );
        $this->assign('list', $data['data']);
        $this->assign('page', $data['page']);
      // $this->assign ('list',$list);
        $this->display();
    }
    public function channelRes_add(){
        $id = $owner = $media_name = $channel_type = $type = $max = $company = $contact = '';

        extract ( $_POST, EXTR_IF_EXISTS );
//dump($_POST);DIE;
        if (IS_POST) {

            $input_data = array ('owner'=>$owner,'type'=>$type,'media_name' => $media_name,
                'channel_type' => $channel_type, 'max' => $max, 'company'=>$company,'contact'=>$contact);
            $channel_id =  D('ChannelRes')->addChannel( $input_data );

            if ($channel_id) {
                D('SysLog')->addLog ( UserSession::getUserName(), 'ADD', '配置信息' ,$channel_id, json_encode($input_data) );
                $this->exitWithSuccess('配置信息添加完成', U('Backend/Channelres/channelRes'),1);
            }
            //}
        }
        $this->assign("_POST" ,$_POST);
        $this->display();
    }
    public function channelRes_modify(){
        $id = $owner = $media_name = $channel_type = $type = $max = $company = $contact = '';
        extract ( $_REQUEST, EXTR_IF_EXISTS );

        //Common::checkParam($id);

        $channel = D('ChannelRes')->getChannelById($id);
        if(empty($channel)){
            $this->exitWithError(ErrorMessage::SAMPLE_NOT_EXIST,U('Backend/Channelres/channelRes_modify'),1);//error('参数不正确', U('Backend/Channelres/channelRes_modify'),1);
        }

        if (IS_POST) {

            if($owner =="" || $media_name ==""  ){

                $this->alert("error",'缺少参数');//error('缺少参数', U('Backend/Channelres/channelRes_modify'),1);ErrorMessage::NEED_PARAM
            }else{
                $update_data = array ('owner'=>$owner,'type'=>$type,'media_name' => $media_name,
                    'channel_type' => $channel_type, 'max' => $max, 'company'=>$company,'contact'=>$contact);
                $result =D('ChannelRes')->updateChannelInfo($id, $update_data );

                if ($result>=0) {
                    D('SysLog')->addLog ( UserSession::getUserName(), 'MODIFY', '配置信息' ,$id, json_encode($update_data) );
                    $this->exitWithSuccess('配置信息修改完成', U('Backend/Channelres/channelRes'),1);
                } else {
                    $this->alert("error");//error('配置信息修改失败', U('Backend/Channelres/channelRes_modify'),1);
                }
            }
        }

        $this->assign( 'channel', $channel );

        $this->display();
    }
}
?>
<?php
namespace Backend\Controller;
use Think\Controller;
use Common\Common\ErrorMessage;
use Common\Model\UserModel;
use Common\Common\UserSession;
class ProductresController extends ComController {
    public function productRes(){
        $method = $id = $page_no = '';
        extract ( $_GET, EXTR_IF_EXISTS );
//dump($id);die;
        if ($method == 'del' && ! empty ( $id )) {

          //  $common = new ErrorMessage();
            //if(intval($menu['module_id']) === 1){
            if(intval($id) <= 0){
                $this->alert("error",'参数不正确');//ErrorMessage::CAN_NOT_DELETE_SYSTEM_MENU
               // $this->error('参数不正确', U('Backend/Productres/productRes'), 1);
            }else{
                $ress = D('ProductRes')->getProductResById($id);
                $result = D('ProductRes')->delProductRes( $id );
                if ($result) {
                    D('SysLog')->addLog ( UserSession::getUserName(), 'DELETE', '配置信息' ,$id, json_encode($result) );
                    $this->  exitWithSuccess ('已将配置信息删除',U('Backend/Productres/productRes'), 1);
                } else {
                   // $this->error('删除失败', U('Backend/Productres/productRes'), 1);
                    $this-> alert("error");

                }
            }
        }

       // $ress = D('ProductRes')->getProductRess();
        $data = D('ProductRes')->search();


        $this->assign ( '_GET', $_GET );
        $this->assign('ress', $data['data']);
        $this->assign('page', $data['page']);
      //  $this->assign ('ress',$ress);
        $this->display();
    }
    public function productResAdd(){
        $id = $owner = $product_type = $product_name = $method = $input_price = $output_price = $callback_method = '';
        extract ( $_POST, EXTR_IF_EXISTS );
        //$common = new Common();
       // $sysLogModel = new SysLogModel();
        if (IS_POST) {

            $input_data = array ('owner'=>$owner,'product_type'=>$product_type,'product_name' => $product_name,
                'method' => $method, 'input_price' => $input_price, 'output_price'=>$output_price,'callback_method'=>$callback_method);
            $result =  D('ProductRes')->addProductRes( $input_data );

            if ($result) {
              //  $this->success( U('Backend/Productres/productRes'),1);exit;
                D('SysLog')->addLog ( UserSession::getUserName(), 'ADD', '配置信息' ,$result, json_encode($input_data) );
                $this->  exitWithSuccess ('配置信息添加完成',U('Backend/Productres/productRes'), 1);
            } else {
                $this-> alert("error");
            }
            //}
          //  die;
        }
        $this->assign("_POST" ,$_POST);
        $this->display();
    }
    public function productResModify(){
        $id = $owner = $product_type = $product_name = $method = $input_price = $output_price = $callback_method = '';
        extract ( $_REQUEST, EXTR_IF_EXISTS );

       // Common::checkParam($id);
//dump($id);
        $res = D('ProductRes')->getProductResById($id);
        //dump($res);die;
        if(empty($res)){
          //  $this->error('参数不正确', U('Backend/Productres/productRes'),1);
            $this-> exitWithError(ErrorMessage::SAMPLE_NOT_EXIST,"Backend/Productres/productRes");
        }

        if (IS_POST) {

            //if($app_name == "" ){
//
           // OSAdmin::alert("error",ErrorMessage::NEED_PARAM);
            //}else{
            $update_data = array ('owner'=>$owner,'product_type'=>$product_type,'product_name' => $product_name,
                'method' => $method, 'input_price' => $input_price, 'output_price'=>$output_price,'callback_method'=>$callback_method);
            $result = D('ProductRes')->updateProductResInfo($id, $update_data );

            if ($result>=0) {
                D('SysLog')->addLog ( UserSession::getUserName(), 'MODIFY', '配置信息' ,$id, json_encode($update_data) );
                $this->  exitWithSuccess ('配置信息修改完成',U('Backend/Productres/productRes'), 1);
            } else {
                $this->alert("error");
            }
            //}
        }

        $this->assign( 'res', $res );

        $this->display();
    }
}
?>
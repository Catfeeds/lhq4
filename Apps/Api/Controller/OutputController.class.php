<?php
namespace Api\Controller;
use Think\Controller;
use Common\Common\UserSession;
class OutputController extends Controller{


public function index()
{

    require('PHPExcel/Classes/PHPExcel.php');
    $method = $chan_id = $provider_id = $look = $start_date = $end_date = $start_time = $end_time = $format = '';
    extract($_GET, EXTR_IF_EXISTS);
    $user_id=UserSession::getUserId();
    $user_group=UserSession::getUserGroup();

//var_dump($_GET);//die;
    if ($chan_id == "0") {
        $_GET["chan_id"] = "";
        $chan_id = "";
    }
    if ($provider_id == "0") {
        $_GET["provider_id"] = "";
        $provider_id = "";
    }
    if($user_group==1||$user_group==5){
        $arr =D('App')->getAPP($chan_id,$provider_id);
    }elseif($user_group==3){
        if($provider_id==0){
            $arr = D('Provider')->getAllProviderByUserid($user_id);
            foreach ($arr as $v) {
                $providerIds[] = $v['provider_id'];
            }

            $arr =D('App')->getAPPByProid($providerIds);
        }else{
            $arr =D('App')->getAPPByProid($provider_id);
        }
    }elseif($user_group==4){
        $channels = D('Channel')->getChannelsSelectedArrayByUserid($user_id);
      //  var_dump($channels);die;
        if($chan_id==0){
            $chanIds=D('Channel')->getUserChanid($user_id);
            if(!empty($chanIds))
                $arr =D('App')->getAPPByChanid($chanIds);
        }else{
            $arr =D('App')->getAPP($chan_id,$provider_id);
        }
    }
    if(!empty($arr)){
        foreach ($arr as $k => $v) {
            $appid[] = $v['app_id'];
        }
    }

   // error_reporting(0);
 //   echo 1;
   error_reporting(0);
  //  set_error_handler(errorHandler);
 //   register_shutdown_function(fatalErrorHandler);
   // phpinfo();
 //   set_error_handler(errorHandler());
 //   register_shutdown_function(fatalErrorHandler());


        if (IS_GET) {
            if(!empty($appid)){
                // $database = new Medoo('adbe');
                if ($look == "1") { //上报激活
                    //  $datas = $database->select("provider_log",["idfa","time"],array("AND"=>array("time[<>]"=>[$start_date,$end_date],"provider_id"=>$provider_id,"app_id"=>$app_id,"chan_id"=>$chan_id)));
                    //$datas = D('ProviderLog')->getOutputLogs($provider_id, $adsid, $chan_id, $start, $page_size, $start_time, $end_time);
                    $datas = D('ChannelActiveLog')->getTrueOutputLogs($appid,$chan_id,$start_date, $end_date);
                } else if($look == "2"){ //上报点击类型
                    // $datas = $database->select("channel_log",["idfa","time"],array("AND"=>array("time[<>]"=>[$start_date,$end_date],"provider_id"=>$provider_id,"app_id"=>$app_id,"chan_id"=>$chan_id)));
                    //$datas = D('ChannelLog')->getOutputLogs($provider_id, $adsid, $chan_id, $start, $page_size, $start_time, $end_time);
                    $datas = D('ChannelLog')->getTrueOutputLogs($appid,$chan_id,$start_date, $end_date);;
                }else if($look == "3"){ //上报排重类型
                    $datas = D('RowRepeat')->getOutputLogs($appid,$chan_id,$start_date, $end_date);
                }else if($look == "4"){
                    $datas = D('ProviderLog')->getTrueOutputLogs($appid,$chan_id,$start_date, $end_date);
                }else if($look == "5"){
                    $datas = D('ChannelCallbackLog')->getOutputLogs($appid,$chan_id,$start_date, $end_date);
                }

                //创建一个excel
                $objPHPExcel = new \PHPExcel();
                //var_dump($objPHPExcel::_cellXfCollection);die;
// 设置excel的属性：
                $objPHPExcel->getProperties()->setCreator("ctos")
                    ->setLastModifiedBy("ctos")
                    ->setTitle("Office 2007 XLSX Test Document")
                    ->setSubject("Office 2007 XLSX Test Document")
                    ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                    ->setKeywords("office 2007 openxml php")
                    ->setCategory("Test result file");

                if ($format === 'xlsx') {

                    // 设置宽度
                    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    // 设置行高度
                    $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(22);
                    $objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);

                    // 字体和样式
                    $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);
                    $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
                    $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

                    // 设置水平居中
                    $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    //  合并
                    //$objPHPExcel->getAct
                    // 表头
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A1', 'IDFA')
                        ->setCellValue('B1', '完成时间');
                    // 内容
                    for ($i = 0, $len = count($datas); $i < $len; $i++) {
                        $objPHPExcel->getActiveSheet(0)->setCellValue('A' . ($i + 2), $datas[$i]['idfa']);
                        $objPHPExcel->getActiveSheet(0)->setCellValue('B' . ($i + 2), $datas[$i]['time']);

                    }
                    //  var_dump($datas[$i]['idfa']);die;
                    //Rename sheet
                    $objPHPExcel->getActiveSheet()->setTitle('数据表');

                    $objPHPExcel->setActiveSheetIndex(0);

                    // 输出
                    // header('Content-Type: api/vnd.ms-excel;charset=utf-8');
                    // header('Content-Disposition: attachment;filename="' . '数据表' . '.xlsx"');
                    // header('Cache-Control: max-age=0');
                    ob_end_clean();
                    $time=date('Y.m.d',time());
                    $filename=$time.'.xlsx';
                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');  
                    header("Content-Disposition:attachment;filename=".$filename); 
                    header('Cache-Control: max-age=0');  

                    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                    $objWriter->save('php://output');
                }
                if ($format == 'csv') {
                    $time=date('Y.m.d',time());
                    $filename=$time.'.csv';
                    header("Content-type:text/csv");
                    header("Content-Disposition:attachment;filename=".$filename);
                    header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
                    header('Expires:0');
                    header('Pragma:public');
                    ob_end_clean();
                    ob_start();

                    echo iconv("utf-8",'gbk',"IDFA,完成时间\n");
                    // $file_str="";
                    //   echo "\r\n";
                    foreach($datas as $v){
                        echo '"';
                        echo iconv('utf-8', 'gb2312', implode('","', array(
                            $v['idfa'],
                            "\t". $v['time'],
                        )));
                        echo "\"\r\n";
                    }
                    exit;
                }


            }
        }

   // set_error_handler('errorHandler');
    // register_shutdown_function('fatalErrorHandler');
    //
   // die;


}
/*
//捕获fatalError
   public  function fatalErrorHandler(){
//echo 123;
        $e = error_get_last();
        switch($e['type']){
            case E_ERROR:
            case E_PARSE:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:
                errorHandler($e['type'],$e['message'],$e['file'],$e['line']);
                break;
        }
    }
    function errorHandler($errno, $errstr, $errfile, $errline) {
        echo "<b>Custom error:</b> [$errno] $errstr<br />";
        echo "Error on line $errline in $errfile<br />";
        echo "Ending Script";
        die();
    }

*/
}

?>

    

<?php
namespace Backend\Controller;
use Think\Controller;
use Common\Model\ChannelModel;
use Common\Model\AppModel;
use Common\Model\ProviderModel;
use Common\Model\ChannelLogModel;
use Common\Model\ChannelCallbackLogModel;
use Common\Model\ProviderLogModel;
use Common\Model\DuplicateLogModel;
use Common\Model\ChannelActiveLogModel;
use Common\Model\MsgLogModel;
use Common\Model\MemberModel;
use Common\Model\MessageModel;
use Common\Common\UserSession;
use Common\Common\Pagination;

class LogController extends ComController {

    //渠道点击
    public function channelClickLog(){
        $method = $app_name = $chan_id = $idfa= $page_no = $start_date = $end_date =$kid=$provider_id= '';
        extract ( $_GET, EXTR_IF_EXISTS );

        if($chan_id == "0") {
            $_GET["chan_id"] = "";
            $chan_id = "";
        }
        if($provider_id == "0") {
            $_GET["provider_id"] = "";
            $provider_id="";
        }
        $appModel = new AppModel();
        $channelModel = new ChannelModel();
        $channelLogModel = new ChannelLogModel();
        $providerModel = new ProviderModel();
        $channels = $channelModel->getChannelsSelectedArray();
        $apps = $appModel->getChannelAppsArray();
        $providers = $providerModel->getProvidersArray();

        //渠道商点击上报日志里有的广告主
        $pidArr=$channelLogModel->getPids();
        foreach ($pidArr as $v) {
            $pids[]=$v['provider_id'];
        }
        $providerArr=$providerModel->getProvidersArrayById($pids);

        if($_GET){
            $kid=$_GET['kid'];
            if ($app_name !='') {
                $appName = $appModel->SelectAppByName($app_name);
                $aName=array();
                foreach ($appName as $k => $v) {
                    foreach ($v as $key => $value) {
                        $aName[] = $value;
                    }
                }
                $app_id = $aName;
                if(!empty($app_id)){
                    //START 数据库查询及分页数据
                    if($start_date != '' || $end_date !=''){
                        $row_count =$channelLogModel->getCountByDate($app_id,$chan_id,$provider_id,$idfa,$start_date,$end_date,$kid);
                    }else{
                        $row_count = $channelLogModel->getCount($app_id,$chan_id,$provider_id,$idfa,$kid);
                    }

                    $page_size = PAGE_SIZE;
                    $page_no=$page_no<1?1:$page_no;
                    $total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
                    $total_page=$total_page<1?1:$total_page;
                    $page_no=$page_no>($total_page)?($total_page):$page_no;
                    $start = ($page_no - 1) * $page_size;
                    // 显示分页栏//END
                    $page_html=Pagination::showPager("channelClickLog?app_name=$app_name&chan_id=$chan_id&provider_id=$provider_id&idfa=$idfa&start_date=$start_date&end_date=$end_date&kid=$kid",$page_no,PAGE_SIZE,$row_count);

                    $clogs = $channelLogModel->getLogs($app_id,$chan_id,$provider_id,$idfa, $start, $page_size, $start_date, $end_date,$kid);

                }else{
                    $row_count =0;
                    $page_size = PAGE_SIZE;
                    $page_no=$page_no<1?1:$page_no;
                    $total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
                    $total_page=$total_page<1?1:$total_page;
                    $page_no=$page_no>($total_page)?($total_page):$page_no;
                    $start = ($page_no - 1) * $page_size;
                    $page_html=Pagination::showPager("channelClickLog?app_name=$app_name&chan_id=$chan_id&provider_id=$provider_id&idfa=$idfa&start_date=$start_date&end_date=$end_date&kid=$kid",$page_no,PAGE_SIZE,$row_count);

                }
            }else{
                if($start_date != '' || $end_date !=''){
                    $row_count =$channelLogModel->getCountByDateLog($chan_id,$provider_id,$idfa,$start_date,$end_date,$kid);
                }else{
                    $row_count = $channelLogModel->getCountLog($chan_id,$provider_id,$idfa,$kid);
                }

                $page_size = PAGE_SIZE;
                $page_no=$page_no<1?1:$page_no;
                $total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
                $total_page=$total_page<1?1:$total_page;
                $page_no=$page_no>($total_page)?($total_page):$page_no;
                $start = ($page_no - 1) * $page_size;
                $page_html=Pagination::showPager("channelClickLog?chan_id=$chan_id&provider_id=$provider_id&idfa=$idfa&start_date=$start_date&end_date=$end_date&kid=$kid",$page_no,PAGE_SIZE,$row_count);
                $clogs = $channelLogModel->getLogsLog($chan_id,$provider_id, $idfa,$start, $page_size, $start_date, $end_date,$kid);
            }

        }else{
            $row_count = $channelLogModel->CountgetLog();
            $page_size = PAGE_SIZE;
            $page_no=$page_no<1?1:$page_no;
            $total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
            $total_page=$total_page<1?1:$total_page;
            $page_no=$page_no>($total_page)?($total_page):$page_no;
            $start = ($page_no - 1) * $page_size;
            // 显示分页栏//END
            $page_html=Pagination::showPager("channelClickLog?app_name=$app_name&chan_id=$chan_id&provider_id=$provider_id&idfa=$idfa&start_date=$start_date&end_date=$end_date",$page_no,PAGE_SIZE,$row_count);
            $clogs = $channelLogModel->getChanLog($start, $page_size);
        }



        if($chan_id == "") {
            $_GET["chan_id"] = "0";
            $chan_id = "0";
        }
        $this->assign ( 'page_no', $page_no );
        $this->assign ( 'page_size', PAGE_SIZE );
        $this->assign ( 'row_count', $row_count );
        $this->assign ( 'page_html', $page_html );
        $this->assign ( '_GET', $_GET );

        $this->assign ('providerArr',$providerArr);
        $this->assign ('providers',$providers);
        $this->assign ('apps',$apps);
        $this->assign ('clogs',$clogs);
        $this->assign ('channels',$channels);
        //Template::display ( 'backend/channelLog.tpl' );
        $this->display("backend/channelLog");

    }

    public function channelCallbackLog(){
        $method = $app_name = $chan_id = $idfa = $page_no = $start_date = $end_date =$provider_id= '';
        extract ( $_GET, EXTR_IF_EXISTS );


        if($chan_id == "0") {
            $_GET["chan_id"] = "";
            $chan_id = "";
        }
        if($provider_id == "0") {
            $_GET["provider_id"] = "";
            $provider_id="";
        }

        $appModel = new AppModel();
        $channelModel = new ChannelModel();
        $providerModel = new ProviderModel();
        $channelCallbackLogModel = new ChannelCallbackLogModel();
        $channels = $channelModel->getChannelsSelectedArray();
        $apps = $appModel->getChannelAppsArray();
        $providers = $providerModel->getProvidersArray();
        //var_dump($channels);die;
        //渠道激活回调日志里有的广告主
        $pidArr=$channelCallbackLogModel->getPids();
        foreach ($pidArr as $v) {
            $pids[]=$v['provider_id'];
        }
        $providerArr=$providerModel->getProvidersArrayById($pids);


        if($_GET) {
            if ($app_name != '') {
                $appName = $appModel->SelectAppByName($app_name);
                $aName=array();
                foreach ($appName as $k => $v) {
                    foreach ($v as $key => $value) {
                        $aName[] = $value;
                    }
                }
                $app_id = $aName;
                if (!empty($app_id)) {
                    if($start_date != '' || $end_date !=''){
                        $row_count =$channelCallbackLogModel->getCountByDate($app_id,$chan_id,$provider_id,$idfa,$start_date,$end_date);
                    }else{
                        $row_count = $channelCallbackLogModel->getCount ($app_id,$chan_id,$provider_id,$idfa);
                    }
                    $page_size = PAGE_SIZE;
                    $page_no=$page_no<1?1:$page_no;
                    $total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
                    $total_page=$total_page<1?1:$total_page;
                    $page_no=$page_no>($total_page)?($total_page):$page_no;
                    $start = ($page_no - 1) * $page_size;
                    //END   // 显示分页栏
                    $page_html=Pagination::showPager("channelCallbackLog?app_name=$app_name&chan_id=$chan_id&provider_id=$provider_id&idfa=$idfa&start_date=$start_date&end_date=$end_date",$page_no,PAGE_SIZE,$row_count);
                    $clogs = $channelCallbackLogModel->getLogs($app_id, $chan_id,$provider_id,$idfa,$start, $page_size, $start_date, $end_date);
                }else{
                    $row_count =0;
                    $page_size = PAGE_SIZE;
                    $page_no=$page_no<1?1:$page_no;
                    $total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
                    $total_page=$total_page<1?1:$total_page;
                    $page_no=$page_no>($total_page)?($total_page):$page_no;
                    $start = ($page_no - 1) * $page_size;
                    $page_html=Pagination::showPager("channelCallbackLog?app_name=$app_name&chan_id=$chan_id&provider_id=$provider_id&idfa=$idfa&start_date=$start_date&end_date=$end_date",$page_no,PAGE_SIZE,$row_count);

                }

            }else{
                if($start_date != '' || $end_date !=''){
                    $row_count =$channelCallbackLogModel->getCountByDateLog($chan_id,$provider_id,$idfa,$start_date,$end_date);
                }else{
                    $row_count = $channelCallbackLogModel->getCountLog($chan_id,$provider_id,$idfa);
                }

                $page_size = PAGE_SIZE;
                $page_no=$page_no<1?1:$page_no;
                $total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
                $total_page=$total_page<1?1:$total_page;
                $page_no=$page_no>($total_page)?($total_page):$page_no;
                $start = ($page_no - 1) * $page_size;
                $page_html=Pagination::showPager("channelCallbackLog?chan_id=$chan_id&provider_id=$provider_id&idfa=$idfa&start_date=$start_date&end_date=$end_date",$page_no,PAGE_SIZE,$row_count);
                $clogs = $channelCallbackLogModel->getLogsLog($chan_id,$provider_id, $idfa,$start, $page_size, $start_date, $end_date);

            }

        }else{
            $row_count = $channelCallbackLogModel->CountgetLog();
            $page_size = PAGE_SIZE;
            $page_no=$page_no<1?1:$page_no;
            $total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
            $total_page=$total_page<1?1:$total_page;
            $page_no=$page_no>($total_page)?($total_page):$page_no;
            $start = ($page_no - 1) * $page_size;
            // 显示分页栏//END
            $page_html=Pagination::showPager("channelCallbackLog?app_name=$app_name&chan_id=$chan_id&provider_id=$provider_id&idfa=$idfa&start_date=$start_date&end_date=$end_date",$page_no,PAGE_SIZE,$row_count);
            $clogs = $channelCallbackLogModel->getChanLog($start, $page_size);
        }

        //START 数据库查询及分页数据

        //var_dump($clogs);die;

        if($chan_id == "") {
            $_GET["chan_id"] = "0";
            $chan_id = "0";
        }

        $this->assign ( 'page_no', $page_no );
        $this->assign ( 'page_size', PAGE_SIZE );
        $this->assign ( 'row_count', $row_count );
        $this->assign ( 'page_html', $page_html );
        $this->assign ( '_GET', $_GET );

        $this->assign ('providerArr',$providerArr);
        $this->assign ('providers',$providers);
        $this->assign ('apps',$apps);
        $this->assign ('clogs',$clogs);
        $this->assign ('channels',$channels);
        //Template::display ( 'backend/channelCallbackLog.tpl' );
        $this->display("backend/channelCallbackLog");
    }

    public function providerCallbackLog(){
        $method = $app_name =$chan_id = $idfa= $start_date= $end_date= $page_no = $provider_id='';

        extract ( $_GET, EXTR_IF_EXISTS );
        $providerModel = new ProviderModel();
        $appModel = new AppModel();
        $channelModel = new ChannelModel();
        $providerLogModel = new ProviderLogModel();
        $providers = $providerModel->getProvidersArray();
        $apps = $appModel->getChannelAppsArray();
        $channels =$channelModel->getChannelsSelectedArray();

        //广告商激活回调日志里有的广告主
        $pidArr=$providerLogModel->getPids();
        foreach ($pidArr as $v) {
            $pids[]=$v['provider_id'];
        }
        $providerArr=$providerModel->getProvidersArrayById($pids);

        if($chan_id == "0") {
            $_GET["chan_id"] = "";
            $chan_id="";
        }
        if($provider_id == "0") {
            $_GET["provider_id"] = "";
            $provider_id="";
        }
        if($_GET){
            if ($app_name !='') {
                $appName = $appModel->SelectAppByName($app_name);
                $aName = array();
                foreach ($appName as $k => $v) {
                    foreach ($v as $key => $value) {
                        $aName[] = $value;
                    }
                }
                //implode(',',$aa)
                $app_id = $aName;
                if(!empty($app_id)){
                    if($start_date != '' || $end_date !=''){
                        $row_count =$providerLogModel->getCountByDate($app_id,$chan_id,$provider_id,$idfa,$start_date,$end_date);
                    }else{

                        $row_count = $providerLogModel->getCount($app_id,$provider_id,$chan_id,$idfa);
                    }

                    $page_size = PAGE_SIZE;
                    $page_no=$page_no<1?1:$page_no;

                    $total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
                    $total_page=$total_page<1?1:$total_page;
                    $page_no=$page_no>($total_page)?($total_page):$page_no;

                    $start = ($page_no - 1) * $page_size;
                    //END
                    // 显示分页栏
                    $page_html=Pagination::showPager("providerCallbackLog?app_name=$app_name&chan_id=$chan_id&provider_id=$provider_id&idfa=$idfa&start_date=$start_date&end_date=$end_date",$page_no,PAGE_SIZE,$row_count);

                    $plogs = $providerLogModel->getLogs($app_id, $chan_id,$provider_id, $idfa,$start, $page_size, $start_date, $end_date);
                }else{
                    $row_count =0;
                    $page_size = PAGE_SIZE;
                    $page_no=$page_no<1?1:$page_no;
                    $total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
                    $total_page=$total_page<1?1:$total_page;
                    $page_no=$page_no>($total_page)?($total_page):$page_no;
                    $start = ($page_no - 1) * $page_size;
                    $page_html=Pagination::showPager("providerCallbackLog?chan_id=$chan_id&idfa=$idfa&start_date=$start_date&end_date=$end_date",$page_no,PAGE_SIZE,$row_count);
                    //	$plogs = $providerLogModel->getLogsLog($chan_id, $idfa,$start, $page_size, $start_date, $end_date);
                }
            }else{
                if($start_date != '' || $end_date !=''){
                    $row_count =$providerLogModel->getCountByDateLog($chan_id,$provider_id,$idfa,$start_date,$end_date);
                }else{
                    $row_count = $providerLogModel->getCountLog($chan_id,$idfa,$provider_id);
                }

                $page_size = PAGE_SIZE;
                $page_no=$page_no<1?1:$page_no;
                $total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
                $total_page=$total_page<1?1:$total_page;
                $page_no=$page_no>($total_page)?($total_page):$page_no;
                $start = ($page_no - 1) * $page_size;
                $page_html=Pagination::showPager("providerCallbackLog?chan_id=$chan_id&provider_id=$provider_id&idfa=$idfa&start_date=$start_date&end_date=$end_date",$page_no,PAGE_SIZE,$row_count);
                $plogs = $providerLogModel->getLogsLog($chan_id,$provider_id, $idfa,$start, $page_size, $start_date, $end_date);
            }
        }else{
            $row_count = $providerLogModel->CountgetLog();
            $page_size = PAGE_SIZE;
            $page_no=$page_no<1?1:$page_no;
            $total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
            $total_page=$total_page<1?1:$total_page;
            $page_no=$page_no>($total_page)?($total_page):$page_no;

            $start = ($page_no - 1) * $page_size;
            $page_html=Pagination::showPager("providerCallbackLog?app_name=$app_name&chan_id=$chan_id&provider_id=$provider_id&idfa=$idfa&start_date=$start_date&end_date=$end_date",$page_no,PAGE_SIZE,$row_count);
            $plogs = $providerLogModel->getProLog($start, $page_size);
        }
        if($chan_id == "") {
            $_GET["chan_id"] = "0";
            $chan_id = "0";
        }
        //var_dump($plogs);
        $this->assign ( 'page_no', $page_no );
        $this->assign ( 'page_size', PAGE_SIZE );
        $this->assign ( 'row_count', $row_count );
        $this->assign ( 'page_html', $page_html );
        $this->assign ( '_GET', $_GET );

        $this->assign ('providers',$providers);
        $this->assign ('apps',$apps);
        $this->assign ('providerArr',$providerArr);
        $this->assign ('channels',$channels);
        $this->assign ('plogs',$plogs);
        $this->display ( 'backend/providerCallbackLog' );

    }

    public function output() {
        $method = $chan_id =$provider_id = $look =$page_no = $start_date = $end_date = '';
        extract ( $_GET, EXTR_IF_EXISTS );
        $user_id=UserSession::getUserId();
        $user_group=UserSession::getUserGroup();
        /* 	$adsid = $_GET['adsid'];
            $provider_id = $_GET['provider_id'];
            $look = $_GET['look']==''|| $_GET['look']==null?0:1;
            $start_date = $_GET['start_date'];
            $end_date = $_GET['end_date'];
            $app_name = $_GET['app_name'];
            $chan_id = $_GET['chan_id'];
            $idfa = $_GET['idfa']; */


        // if($start_date != "" && $end_date != "") {
        // 	$start_date = ($start_date." 00:00:00");
        // 	$end_date = ($end_date." 23:59:59");
        // }
        if($chan_id == "0") {
            $_GET["chan_id"] = "";
            $chan_id = "";
        }

        if($provider_id == "0") {
            $_GET["provider_id"] = "";
            $provider_id = "";
        }

        $appModel = new AppModel();
        if($user_group==1||$user_group==5){
            $providers = D('Provider')->getProvidersArray();
            $channels = D('Channel')->getChannelsSelectedArray();
            $appid =$appModel->getAPP($chan_id,$provider_id);
        }elseif($user_group==3){
            $providers=D('Provider')->getProvidersArrayByUserid($user_id);
            if($provider_id==0){
                $arr = D('Provider')->getAllProviderByUserid($user_id);
                foreach ($arr as $v) {
                    $providerIds[] = $v['provider_id'];
                }
                $appid =$appModel->getAPPByProid($providerIds);
            }else{
                $appid =$appModel->getAPPByProid($provider_id);
            }
        } elseif($user_group==4){
            $channels = D('Channel')->getChannelsSelectedArrayByUserid($user_id);
            //var_dump($channels);
            if($chan_id==0){
                $chanIds=D('Channel')->getUserChanid($user_id);
                //var_Dump($chanIds);die;
                if(!empty($chanIds))
                    $appid =$appModel->getAPPByChanid($chanIds);
            }else{
                $appid =$appModel->getAPP($chan_id,$provider_id);
            }

        }


        //var_dump($appid);die;
        $providerLogModel = new ProviderLogModel();
        $channelLogModel = new ChannelLogModel();
        if(!empty($appid)) {
            if ($look == '1') { //上报激活
                $clogs = array();
                foreach ($appid as $k => $v) {
                    $clogss = D('ChannelActiveLog')->getOutPutChannelActive($v, $chan_id, $start_date, $end_date);
                    $success = D('ChannelActiveLog')->getTrueOutPutChannelActive($v, $chan_id, $start_date, $end_date);

                    for ($i = 0; $i < count($clogss); $i++) {
                        $clogss[$i]['scount'] = $success[$i]['count'];
                    }

                    if ($clogss) {
                        foreach ($clogss as $value) {
                            if ($value['count'] > 0) {
                                $clogs[] = $value;
                            }
                        }
                    }
                    $row_count = count($clogs);
                    // $row_count += $row_count;
                }
            } elseif ($look == "4") {//广告商激活回调
                $clogs = array();
                foreach ($appid as $k => $v) {

                    $clogss = $providerLogModel->getOutPutActiveByTime($v, $chan_id, $start_date, $end_date);
                    $success = $providerLogModel->getTrueOutPutActiveByTime($v, $chan_id, $start_date, $end_date);
                    for ($i = 0; $i < count($clogss); $i++) {
                        $clogss[$i]['scount'] = $success[$i]['count'];
                    }
                    if ($clogss) {
                        foreach ($clogss as $value) {
                            if ($value['count'] > 0) {
                                $clogs[] = $value;
                            }
                        }
                    }
                    $row_count = count($clogs);
                }
            } elseif ($look == "2") {      //上报点击
                $clogs = array();
                foreach ($appid as $k => $v) {
                    //var_dump($appid);
                    $clogss = $channelLogModel->getOutPutClickByTime($v, $chan_id, $start_date, $end_date);
                    //var_dump($clogss);die;
                    $success = $channelLogModel->getTrueOutPutClickByTime($v, $chan_id, $start_date, $end_date);

                    for ($i = 0; $i < count($clogss); $i++) {
                        $clogss[$i]['scount'] = $success[$i]['count'];
                    }
                    //var_dump($clogss);die;
                    if ($clogss) {
                        foreach ($clogss as $value) {
                            if ($value['count'] > 0) {
                                $clogs[] = $value;
                            }
                        }
                    }
                    $row_count = count($clogs);
                }
            } elseif ($look == "3") { //上报排重类型
                $clogs = array();
                foreach ($appid as $k => $v) {
                    $clogss = D('RowRepeat')->getOutPutQueryIdfa($v, $chan_id, $start_date, $end_date);
                    for ($i = 0; $i < count($clogss); $i++) {
                        $clogss[$i]['scount'] = $clogss[$i]['count'];
                    }
                    if ($clogss) {
                        foreach ($clogss as $value) {
                            if ($value['count'] > 0) {
                                $clogs[] = $value;
                            }
                        }
                    }
                    $row_count = count($clogs);
                }
            } elseif ($look == "5") { //渠道激活回调类型
                $clogs = array();
                foreach ($appid as $k => $v) {
                    $clogss = D('ChannelCallbackLog')->getOutPutChannelCallback($v, $chan_id, $start_date, $end_date);
                    if ($clogss) {
                        foreach ($clogss as $value) {
                            if ($value['count'] > 0) {
                                $clogs[] = $value;
                            }
                        }
                    }
                    $row_count = count($clogs);
                }
            }
        }
        $appid = $v['app_id'];
        $apps = $appModel->getChannelAppsArray();

        $page_size = PAGE_SIZE;
        $page_no=$page_no<1?1:$page_no;

        $total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
        $total_page=$total_page<1?1:$total_page;
        $page_no=$page_no>($total_page)?($total_page):$page_no;

        $start = ($page_no - 1) * $page_size;
        //END

        // 显示分页栏
        $page_html=Pagination::showPager("output?provider_id=$provider_id&chan_id=$chan_id&look=$look&start_date=$start_date&end_date=$end_date",$page_no,PAGE_SIZE,$row_count);

        if( ($start + $page_size) < $row_count){
            for ($i=$start; $i <$start + $page_size ; $i++) {
                $clogsa[]=$clogs[$i];
            }
        }else{
            for ($i=$start; $i <$row_count ; $i++) {
                $clogsa[]=$clogs[$i];
            }
        }


        $this->assign('user_group',$user_group);
        $this->assign ( 'page_no', $page_no );
        $this->assign ( 'page_size', PAGE_SIZE );
        $this->assign ( 'row_count', $row_count );
        $this->assign ( 'page_html', $page_html );
        $this->assign ( '_GET', $_GET );
        $this->assign ( 'clogsa', $clogsa );
        $this->assign ('apps',$apps);
        $this->assign ('providers',$providers);
        $this->assign ('channels',$channels);
        $this->assign ('plogs',$plogs);
        $this->assign ('look',$look);
        $this->assign ('chan_id',$chan_id);
        $this->display ( 'backend/output' );

    }

    public function duplicateLog() {
        $method = $app_name= $idfa= $start_date= $end_date= $page_no =$chanid=$provider_id='';
        extract ( $_GET, EXTR_IF_EXISTS );

        $appModel = new AppModel();
        $providerModel = new ProviderModel();
        $apps = $appModel->getChannelAppsArray();
        $duplicateLogModel = new DuplicateLogModel();
        $channelModel=new ChannelModel();
        $channel_name = $channelModel->getChannelName();

        //查询排重日志里面有记录的广告主
        $appids=$duplicateLogModel->getAppids();
        foreach($appids as $appid){
            $app_id[]=$appid['app_id'];
        }
        $providerids=$appModel->getPidsByAppid($app_id);
        //var_dump($providerids);die;
        foreach($providerids as $providerid){
            $provider_ids[]=$providerid['provider_id'];
        }
        $providers=$providerModel->getProvidersArrayById($provider_ids);

        //排重日志中有记录的渠道
        $chanids=$duplicateLogModel->getchanids();
        if(!empty($chanids)){
            foreach($chanids as $v){
                $chan_ids[]=$v['chanid'];
            }
        }
        $channels=$channelModel->getChannelByChanid($chan_ids);

        if($_GET) {
            $provider_id=$_GET['provider_id'];
            if($provider_id!=0){
                $appids=$appModel->getappid($provider_id);
                foreach($appids as $v){
                    $app_ids[]=$v['app_id'];
                }
            }else{
                $appids=$duplicateLogModel->getAppids();
                foreach($appids as $v){
                    $app_ids[]=$v['app_id'];
                }
            }
            $chanid=$_GET['chanid'];
            if($chanid!=0){
                $chan_id=$chanid;
            }else{
                $chan_id=$chan_ids;
            }

            if ($app_name != '') {
                if($provider_id!=0){

                    $appName = $appModel->SelectAppByNamePid($app_name,$provider_id);
                    $aName=array();
                    foreach ($appName as $k => $v) {
                        //var_dump($v);die;
                        foreach ($v as $key => $value) {
                            $aName[] = $value;
                        }
                    }
                }else{

                    $appName = $appModel->SelectAppByName($app_name);
                    $aName=array();
                    foreach ($appName as $k => $v) {
                        //var_dump($v);die;
                        foreach ($v as $key => $value) {
                            $aName[] = $value;
                        }
                    }
                }

                $app_id = $aName;
                if (!empty($app_id)) {
                    //START 数据库查询及分页数据
                    if($start_date != '' || $end_date !=''){
                        $row_count =$duplicateLogModel->getCountByDate($app_id,$idfa,$start_date,$end_date,$chan_id);
                    }else{
                        $row_count = $duplicateLogModel->getCount($app_id,$idfa,$chan_id);
                    }
                    $page_size = 25;//PAGE_SIZE;
                    $page_no=$page_no<1?1:$page_no;
                    $total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
                    $total_page=$total_page<1?1:$total_page;
                    $page_no=$page_no>($total_page)?($total_page):$page_no;
                    $start = ($page_no - 1) * $page_size;
                    //END
                    // 显示分页栏
                    $page_html=Pagination::showPager("duplicateLog?app_name=$app_name&idfa=$idfa&chanid=$chanid&start_date=$start_date&end_date=$end_date&provider_id=$provider_id",$page_no,PAGE_SIZE,$row_count);
                    $rlogs = $duplicateLogModel->getLogs($app_id, $idfa,$start, $page_size, $start_date, $end_date, $chan_id);

                }else{
                    $row_count =0;
                    $page_size = PAGE_SIZE;
                    $page_no=$page_no<1?1:$page_no;
                    $total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
                    $total_page=$total_page<1?1:$total_page;
                    $page_no=$page_no>($total_page)?($total_page):$page_no;
                    $start = ($page_no - 1) * $page_size;
                    $page_html=Pagination::showPager("duplicateLog?app_name=$app_name&idfa=$idfa&chanid=$chanid&start_date=$start_date&end_date=$end_date&provider_id=$provider_id",$page_no,PAGE_SIZE,$row_count);

                }

            }else{
                if($start_date != '' || $end_date !=''){
                    $row_count =$duplicateLogModel->getCountByDateLog($idfa,$start_date,$end_date,$app_ids,$chan_id);
                }else{
                    $row_count = $duplicateLogModel->getCountLog($idfa,$app_ids,$chan_id);

                }

                $page_size = PAGE_SIZE;
                $page_no=$page_no<1?1:$page_no;
                $total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
                $total_page=$total_page<1?1:$total_page;
                $page_no=$page_no>($total_page)?($total_page):$page_no;
                $start = ($page_no - 1) * $page_size;
                $page_html=Pagination::showPager("duplicateLog?app_name=$app_name&idfa=$idfa&chanid=$chanid&start_date=$start_date&end_date=$end_date&provider_id=$provider_id",$page_no,PAGE_SIZE,$row_count);
                $rlogs = $duplicateLogModel->getLogsLog($idfa,$start, $page_size, $start_date, $end_date,$app_ids,$chan_id);

            }

        }else{
            $row_count = $duplicateLogModel->CountgetLog();
            $page_size = PAGE_SIZE;
            $page_no=$page_no<1?1:$page_no;
            $total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
            $total_page=$total_page<1?1:$total_page;
            $page_no=$page_no>($total_page)?($total_page):$page_no;

            $start = ($page_no - 1) * $page_size;
            $page_html=Pagination::showPager("duplicateLog?app_name=$app_name&idfa=$idfa&chanid=$chanid&start_date=$start_date&end_date=$end_date&provider_id=$provider_id",$page_no,PAGE_SIZE,$row_count);
            $rlogs = $duplicateLogModel->getDupLog($start, $page_size);
        }
        //array_unshift($channels, "不限");
        $this->assign ( 'page_no', $page_no );
        $this->assign ( 'page_size', PAGE_SIZE );
        $this->assign ( 'row_count', $row_count );
        $this->assign ( 'page_html', $page_html );
        $this->assign ( '_GET', $_GET );

        $this->assign ('apps',$apps);
        $this->assign ('providers',$providers);
        $this->assign ('channel_name',$channel_name);
        $this->assign ('channels',$channels);
        //Template::assign ('channels',$channels);
        $this->assign ('rlogs',$rlogs);
        $this->display ( 'backend/duplicateLog' );


    }

    public function channelActiveLog() {
        $method = $app_name = $chan_id = $idfa= $page_no = $start_date = $end_date = $kid=$provider_id='';
        extract ( $_GET, EXTR_IF_EXISTS );

        if($chan_id == "0") {
            $_GET["chan_id"] = "";
            $chan_id = "";
        }
        if($provider_id == "0") {
            $_GET["provider_id"] = "";
            $provider_id="";
        }

        $channelModel = new ChannelModel();
        $appModel = new AppModel();
        $providerModel = new ProviderModel();
        $channelActiveLogModel = new ChannelActiveLogModel();
        $channels = $channelModel->getChannelsSelectedArray();
        $apps = $appModel->getChannelAppsArray();
        $providers = $providerModel->getProvidersArray();

        //渠道激活回调日志里有的广告主
        $pidArr=$channelActiveLogModel->getPids();
        foreach ($pidArr as $v) {
            $pids[]=$v['provider_id'];
        }
        $providerArr=$providerModel->getProvidersArrayById($pids);

        if($_GET) {
            $kid=$_GET['kid'];
            if ($app_name != '') {
                $appName = $appModel->SelectAppByNameChanid($app_name,$chan_id);
                $aName=array();
                foreach ($appName as $k => $v) {
                    foreach ($v as $key => $value) {
                        $aName[] = $value;
                    }
                }
                //implode(',',$aa)
                $app_id = $aName;
                if (!empty($app_id)) {
                    //START 数据库查询及分页数据
                    if($start_date != '' || $end_date !=''){
                        $row_count =$channelActiveLogModel->getCountByDate($app_id,$chan_id,$provider_id,$idfa,$start_date,$end_date,$kid);
                    }else{
                        $row_count = $channelActiveLogModel->getCount ($app_id,$chan_id,$provider_id,$idfa,$kid);
                    }
                    $page_size = 25;//PAGE_SIZE;
                    $page_no=$page_no<1?1:$page_no;
                    $total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
                    $total_page=$total_page<1?1:$total_page;
                    $page_no=$page_no>($total_page)?($total_page):$page_no;

                    $start = ($page_no - 1) * $page_size;
                    //END// 显示分页栏
                    $page_html=Pagination::showPager("channelActiveLog?app_name=$app_name&chan_id=$chan_id&provider_id=$provider_id&idfa=$idfa&start_date=$start_date&end_date=$end_date&kid=$kid",$page_no,PAGE_SIZE,$row_count);
                    $clogs = $channelActiveLogModel->getLogs($app_id, $chan_id, $provider_id,$idfa, $start, $page_size, $start_date, $end_date,$kid);
                }else{
                    $row_count =0;
                    $page_size = PAGE_SIZE;
                    $page_no=$page_no<1?1:$page_no;
                    $total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
                    $total_page=$total_page<1?1:$total_page;
                    $page_no=$page_no>($total_page)?($total_page):$page_no;
                    $start = ($page_no - 1) * $page_size;
                    $page_html=Pagination::showPager("channelActiveLog?app_name=$app_name&chan_id=$chan_id&provider_id=$provider_id&idfa=$idfa&start_date=$start_date&end_date=$end_date&kid=$kid",$page_no,PAGE_SIZE,$row_count);

                }
            }else{
                if($start_date != '' || $end_date !=''){
                    $row_count =$channelActiveLogModel->getCountByDateLog($chan_id,$provider_id,$idfa,$start_date,$end_date,$kid);
                }else{
                    $row_count = $channelActiveLogModel->getCountLog($chan_id,$provider_id,$idfa,$kid);
                }

                $page_size = PAGE_SIZE;
                $page_no=$page_no<1?1:$page_no;
                $total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
                $total_page=$total_page<1?1:$total_page;
                $page_no=$page_no>($total_page)?($total_page):$page_no;
                $start = ($page_no - 1) * $page_size;
                $page_html=Pagination::showPager("channelActiveLog?chan_id=$chan_id&provider_id=$provider_id&idfa=$idfa&start_date=$start_date&end_date=$end_date&kid=$kid",$page_no,PAGE_SIZE,$row_count);
                $clogs = $channelActiveLogModel->getLogsLog($chan_id, $provider_id,$idfa,$start, $page_size, $start_date, $end_date,$kid);
            }
        }else{
            $row_count = $channelActiveLogModel->CountgetLog();
            $page_size = PAGE_SIZE;
            $page_no=$page_no<1?1:$page_no;
            $total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
            $total_page=$total_page<1?1:$total_page;
            $page_no=$page_no>($total_page)?($total_page):$page_no;

            $start = ($page_no - 1) * $page_size;
            $page_html=Pagination::showPager("channelActiveLog?app_name=$app_name&chan_id=$chan_id&provider_id=$provider_id&idfa=$idfa&start_date=$start_date&end_date=$end_date",$page_no,PAGE_SIZE,$row_count);
            $clogs = $channelActiveLogModel->getChanLog($start, $page_size);
        }

        if($chan_id == "") {
            $_GET["chan_id"] = "0";
            $chan_id = "0";
        }

        $this->assign ( 'page_no', $page_no );
        $this->assign ( 'page_size', PAGE_SIZE );
        $this->assign ( 'row_count', $row_count );
        $this->assign ( 'page_html', $page_html );
        $this->assign ( '_GET', $_GET );

        $this->assign ('providerArr',$providerArr);
        $this->assign ('providers',$providers);
        $this->assign ('apps',$apps);
        $this->assign ('clogs',$clogs);
        $this->assign ('channels',$channels);
        $this->display ( 'backend/channelActiveLog' );
    }

    public function msgLog() {
        $method = $member_name=$start= $page_no = $start_date=$end_date='';
        extract ( $_GET, EXTR_IF_EXISTS );

        // dump($member_name);
        $msgLogModel = new MsgLogModel();
        $messageModel = new MessageModel();
        $memberModel = new MemberModel();
        // $msgLogs = $msgLogModel->getMsgLogs();
        $title=$messageModel->getMessagesArray();
        $type=$messageModel->getM_typeArray();
        $msgto=$messageModel->getMsg_toArray();
        $memberName=$memberModel->getMembersArray();
        if($_GET) {
            if ($member_name != '') {
                $mName=D('Member')->SelectMemberByName($member_name);

                foreach ($mName as $k => $v) {
                    foreach ($v as $key => $value) {
                        $aName = $value;
                    }
                }
                $member_id = $aName;

                if ($member_id) {

                    if($start_date != '' || $end_date !=''){
                        $row_count =$msgLogModel->getCountByDate($member_id,$start_date,$end_date);
                    }else{
                        $row_count = $msgLogModel->getCount($member_id);
                    }
                    //die;
                    $page_size = 25;//PAGE_SIZE;
                    $page_no=$page_no<1?1:$page_no;
                    $total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
                    $total_page=$total_page<1?1:$total_page;
                    $page_no=$page_no>($total_page)?($total_page):$page_no;
                    $start = ($page_no - 1) * $page_size;
                    //END// 显示分页栏
                    $page_html=Pagination::showPager("msgLog?member_name=$member_name&start_date=$start_date&end_date=$end_date",$page_no,PAGE_SIZE,$row_count);
                    $msgLogs = $msgLogModel->getmsgByPage($member_id,$start ,$page_size,$start_date,$end_date);
                    //dump($msgLogs);
                }else{
                    $row_count =0;
                    $page_size = PAGE_SIZE;
                    $page_no=$page_no<1?1:$page_no;
                    $total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
                    $total_page=$total_page<1?1:$total_page;
                    $page_no=$page_no>($total_page)?($total_page):$page_no;
                    $start = ($page_no - 1) * $page_size;
                    //END// 显示分页栏
                    $page_html=Pagination::showPager("msgLog?member_name=$member_name&start_date=$start_date&end_date=$end_date",$page_no,PAGE_SIZE,$row_count);
                    //dump($msgLogs);
                }
            }else{
                if($start_date != '' || $end_date !=''){
                    $row_count =$msgLogModel->getCountByDateLog($start_date,$end_date);
                }
                $page_size = PAGE_SIZE;
                $page_no=$page_no<1?1:$page_no;
                $total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
                $total_page=$total_page<1?1:$total_page;
                $page_no=$page_no>($total_page)?($total_page):$page_no;
                $start = ($page_no - 1) * $page_size;
                $page_html=Pagination::showPager("msgLog?member_name=$member_name&start_date=$start_date&end_date=$end_date",$page_no,PAGE_SIZE,$row_count);
                $msgLogs = $msgLogModel->getLogsLog($start, $page_size, $start_date, $end_date);

            }
        }else{
            $row_count = $msgLogModel->CountgetLog();
            $page_size = PAGE_SIZE;
            $page_no=$page_no<1?1:$page_no;
            $total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
            $total_page=$total_page<1?1:$total_page;
            $page_no=$page_no>($total_page)?($total_page):$page_no;
            $start = ($page_no - 1) * $page_size;
            $page_html=Pagination::showPager("msgLog?member_name=$member_name&start_date=$start_date&end_date=$end_date",$page_no,PAGE_SIZE,$row_count);
            $msgLogs = $msgLogModel->getMsglogLog($start, $page_size);

        }
        //var_dump($msgLogs);die;
        $this->assign ( 'page_no', $page_no );
        $this->assign ( 'page_size', PAGE_SIZE );
        $this->assign ( 'row_count', $row_count );
        $this->assign ( 'page_html', $page_html );
        $this->assign ( '_GET', $_GET );


        $this->assign ('msgLogs',$msgLogs);
        $this->assign ('type',$type);
        $this->assign ('msgto',$msgto);
        $this->assign ('title',$title);
        $this->assign ('memberName',$memberName);
        /*  $mName=D('Member')->SelectMemberByName($member_name);

          foreach ($mName as $k => $v) {
              foreach ($v as $key => $value) {
                  $aName[] = $value;
              }
          }
          $member_id = $aName;
          $data =D('MsgLog')-> search($member_id,$start_date,$end_date);
          //  dump($data);die;
          // $this->assign ('members',$members);
          $this->assign('msgLogs', $data['data']);
          $this->assign('page_html', $data['page']);*/
        $this->display ( 'backend/msgLog' );

    }
    //审核
    public function drawingAjax(){
        $id=$_POST['mid'];
        $val=$_POST['val'];
//var_dump($val);
        $sj=Drawing::getDrawById($id,$val);//更改审核状态
//更新提现金额
        $Id=Drawing::getData($id);//获取状态
        if($Id['status']=='2'){
            $mid=Drawing::selectMid($id);
            $money=Member::SelectMoney($mid);
            $wd=$money['total_wd']+$Id['wd_money'];
            // $balanc=$money['balance']-$Id['wd_money'];
            // $input_data = array('total_wd' => $wd,'balance' => $balanc);
            $input_data = array('total_wd' => $wd);
            $id = Member::updateMember($mid, $input_data);

        }else if($Id['status']=='3'){
            $mid=Drawing::selectMid($id);
            $money=Member::SelectMoney($mid);
            $balanc=$money['balance']+$Id['wd_money'];
            $input_data = array('balance' => $balanc);
            $id = Member::updateBalance($mid, $input_data);
        }
//var_dump($sj);die;
        echo json_encode($sj);//die;
    }
    /* */
}
?>

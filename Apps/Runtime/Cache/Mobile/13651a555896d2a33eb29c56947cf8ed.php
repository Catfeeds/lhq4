<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>任务中心</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="msapplication-tap-highlight" content="no">
    <script src="/lhq/Public/mobile/js/jquery.min.js"></script>
    <link rel="stylesheet" href="/lhq/Public/mobile/css/style.css" type="text/css">
</head>

<body onload="daoTime()">       
    <div class="devBox">
        <a href="<?php echo U('Index/index',array('member_id'=>$member_id));?>"><img src="/lhq/Public/mobile/images/a2.png" alt class="fl"></a>
        <h1 class="biaoti">任务中心</h1>          
    </div>

 <div class="biao">
    <?php if(is_array($missions)): foreach($missions as $key=>$mission): ?><a id="clickTest" misd="<?php echo ($mission["mission_id"]); ?>" statu="<?php echo ($status[$mission[mission_id]]); ?>" 
        adtypeid="<?php echo ($mission["adtype_id"]); ?>"  

        <?php if(($status[$mission[mission_id]] != 1) AND ($status[$mission[mission_id]] != 6)): if($zt == 2): if($status[$mission[mission_id]] == 2): ?>href="details.php?mission_id=<?php echo ($mission["mission_id"]); ?>"
                   onclick="test(this)"
                <?php elseif($status[$mission[mission_id]] == 4): ?>
                   href="javascript:alert('此任务正在审核中,请选择其他任务');"
                <?php else: ?>
                   href="javascript:alert('你已有进行中的任务,请先完成你的任务');"<?php endif; ?>
            <?php else: ?>
                <?php if($status[$mission[mission_id]] == 4): ?>href="javascript:alert('此任务正在审核中,请选择其他任务');"
                <?php else: ?>
                    href="javascript:void(0);"
                    onclick="test(this)"<?php endif; endif; ?>
            class="lianjie3"
            <?php if($mission["adtype_id"] <= 2): ?>style="border:1px solid #FF9999"
            <?php elseif($mission["adtype_id"] > 2): ?>
                style="border:1px solid #33CC99"<?php endif; ?>>
            <img
                <?php if($mission["img"] == ' '): ?>src="/lhq/Public/img/default_logo.png"
                <?php else: ?>
                    src="/lhq/Public<?php echo ($mission["img"]); ?>"<?php endif; ?>
            class="rwtb"/>
            <div class="yuanbg">
                <?php if($status[$mission[mission_id]] == 1): ?><p class="yuanwc">已完成</p>
                <?php elseif($status[$mission[mission_id]] == 2): ?>
                   <p class="yuan"> 进行中</p>
                <?php elseif($status[$mission[mission_id]] == 4): ?>
                   <p class="yuan">  审核中</p>
                <?php else: ?>
                    <p class="yuan"> <?php echo ($mission["price"]); ?>元</p><?php endif; ?>  
            </div>

            <?php if($status[$mission[mission_id]] == 1): ?><p class="chanpinwc"><?php echo ($mission["mission_name"]); ?></p>
            <?php else: ?>
                <p class="chanpin"><?php echo ($mission["mission_name"]); ?></p><?php endif; ?>
            
            <div class="timediv">
                <p class="shengyu" endtime = "<?php echo ($mission["end_time"]); ?>">结束时间:<?php echo (substr($mission["end_time"],5,11)); ?></p> 
            </div><?php endif; ?>
        </a><?php endforeach; endif; ?>
</div>
<div class="biao">
    <?php if(is_array($Waitsmissions)): foreach($Waitsmissions as $key=>$Waitsmission): ?><a id="clickTest1"class="lianjie3" href="javascript:alert('此任务还未正式上线，请静心等待');">
            <img src="/lhq/Public/mobile/images/weishangxian.png"
                class="rwtb">
            <div class="yuanbg">
                <p class="yuan"> <?php echo ($Waitsmission["price"]); ?>元</p>
            </div>
            <p class="chanpin">? ? ?</p>
            <div class="wslabeldiv">
                <p style="padding:2px;color:#000;" class="label">? ? ?</p>
            </div>
            <div class="wstimediv">
                <p class="shengyu">开始时间:<?php echo (substr($Waitsmission["start_time"],5,11)); ?></p>
            </div>
        </a><?php endforeach; endif; ?>
    <?php if(is_array($Endmissions)): foreach($Endmissions as $key=>$Endmission): ?><a id="clickTest"class="lianjie3"
            <?php if($Endmission["re_num"] == 0): ?>href="javascript:alert('此任务已售完请选择其他任务');"
            <?php else: ?>
                href="javascript:alert('此任务已下线请选择其他任务');"<?php endif; ?>
                style="background-color: #FCFCFC;">
            <img src="/lhq/Public<?php echo ($imgs[$appIds[$Endmission[mission_id]]]); ?>"
            class="rwtb">
            <p class="chanpinwc"><?php echo ($Endmission["mission_name"]); ?></p>
            <p class="yuanwc" style="color: #bdbdbd;">已下线</p>
            <div class="timediv">
                 <p class="shengyu" style="color: #bdbdbd;">结束时间:<?php echo (substr($Endmission["end_time"],5,11)); ?></p>
            </div>
        </a><?php endforeach; endif; ?>
    <?php if(is_array($Rnummissions)): foreach($Rnummissions as $key=>$Rnummission): ?><a id="clickTest" class="lianjie3" href="javascript:alert('此任务已售完请选择其他任务');" style="background-color: #FCFCFC;">
            <img src="/lhq/Public<?php echo ($imgs[$appIds[$Rnummission[mission_id]]]); ?>"
            class="rwtb">
            <p class="chanpinwc"><?php echo ($Rnummission["mission_name"]); ?></p>
            <p class="yuan" style="color: #bdbdbd;">已抢光</p>
            
            <div class="timediv">
                <p class="shengyuwc">结束时间:<?php echo (substr($Rnummission["end_time"],5,11)); ?></p>
            </div>
        </a><?php endforeach; endif; ?>
    <?php if(is_array($Finishs)): foreach($Finishs as $key=>$Finish): ?><a id="clickTest"class="lianjie3" href="javascript:alert('此任务你已做过请选择其他任务');" style="background-color: #FCFCFC;">
            <img src="/lhq/Public<?php echo ($imgs[$appIds[$Finish[mission_id]]]); ?>"
            class="rwtb">
            <p class="chanpinwc"><?php echo ($missionName[$Finish[mission_id]]); ?></p>
            <?php if($Finish["status"] == 1): ?><p class="yuan" style="color: #bdbdbd;">已完成</p>
            <?php elseif($Finish["status"] == 6): ?>
                <p class="yuan" style="color: #bdbdbd;">审核失败</p><?php endif; ?>

            <div class="timediv">
                <p class="shengyu">完成时间:<?php echo (substr($Finish["ctime"],5,11)); ?></p>
         </div>
        </a><?php endforeach; endif; ?>
</div>

<script>
function test(a) {
    event.preventDefault();
    msid=$(a).attr('misd');
    url=$(a).attr('href');
    mid='<?php echo ($member_id); ?>';
    adtypeId=$(a).attr('adtypeid');
    status=$(a).attr('statu');

    $.get("<?php echo U('Task/taskDownAjax');?>",{mission_id:msid},function(e){
        if (e == '2') {
            alert('任务以下线');
            window.onload='';
        }
        if (e == '3') {
            alert('此任务已达任务上限，请选择其他任务');
            window.onload='';
        }
        if(e == '1'){
            if (status == '' || status == '5') {
                if(adtypeId=='1'){  //回调任务
                    $.get("<?php echo U('Api/Channelclickmobileapi/Channelclickmobileapi');?>", {msid:msid,mid:mid}, function (data) {
                        if (data=='1') {
                            alert('系统繁忙请稍后再试！');
                            return false;
                        }else if (data=='0'){
                            $.get("<?php echo U('/Mobile/Task/callBack');?>", {msid:msid,mid:mid}, function (e) {
                                if (e.message=='201') {
                                    alert('系统繁忙请稍后再试！');
                                    return false;
                                }else if(e.isSuccess == 'Y'){
                                    location.href="<?php echo U('Task/details');?>"+'?'+"mission_id="+msid;
                                }
                                else if (e.message=='200'){
                                    location.href="<?php echo U('Task/details');?>"+'?'+"mission_id="+msid;
                                }
                            }, 'json');
                        }
                    }, 'json');
                }
                if (adtypeId=='2' || adtypeId=='6') {   //排重回调  和  排重,上报点击
                    $.post("<?php echo U('Api/Queryidfamobile/queryId_mobile');?>", {msid:msid,mid:mid},
                    function (data) {
                        if(data=="1"){
                            alert('此App已安装过，请接其他任务');
                            return false;
                        }else if(data=="2"){
//                            alert('系统繁忙请稍后再试！');
//                            return false;
                            $.get("<?php echo U('Api/Channelclickmobileapi/Channelclickmobileapi');?>", {msid:msid,mid:mid}, function (data) {
                                if (data=='1') {
//                                    alert('系统繁忙请稍后再试！');
//                                    return false;
                                    $.get("<?php echo U('/Mobile/Task/callBack');?>", {msid:msid,mid:mid}, function (e) {
                                        if (e.code=='201') {
                                            alert('12');
                                            alert('系统繁忙请稍后再试！');
                                            return false;
                                        }else if(e.isSuccess == 'Y'){
                                            location.href="<?php echo U('Task/details');?>"+'?'+"mission_id="+msid;
                                        }
                                        else if (e.code=='200'){
                                            location.href="<?php echo U('Task/details');?>"+'?'+"mission_id="+msid;
                                        }
                                    }, 'json');
                                }else if (data=='0'){
                                    $.get("<?php echo U('/Mobile/Task/callBack');?>", {msid:msid,mid:mid}, function (e) {
                                        if (e.code=='201') {
                                            alert(e);
                                            alert('系统繁忙请稍后再试！');
                                            return false;
                                        }else if(e.isSuccess == 'Y'){
                                            location.href="<?php echo U('Task/details');?>"+'?'+"mission_id="+msid;
                                        }
                                        else if (e.code=='200'){
                                            location.href="<?php echo U('Task/details');?>"+'?'+"mission_id="+msid;
                                        }
                                    }, 'json');
                                }
                            }, 'json');
                        }else if(data=="0"){
                            $.get("<?php echo U('Api/Channelclickmobileapi/Channelclickmobileapi');?>", {msid:msid,mid:mid}, function (data) {
                                if (data=='1') {
                                    alert('系统繁忙请稍后再试！');
                                    return false;
                                }else if (data=='0'){
                                    $.get("<?php echo U('/Mobile/Task/callBack');?>", {msid:msid,mid:mid}, function (e) {
                                        if (e.message=='201') {
                                            alert('系统繁忙请稍后再试！');
                                            return false;
                                        }else if(e.isSuccess == 'Y'){
                                            location.href="<?php echo U('Task/details');?>"+'?'+"mission_id="+msid;
                                        }
                                        else if (e.message=='200'){
                                            location.href="<?php echo U('Task/details');?>"+'?'+"mission_id="+msid;
                                        }
                                    }, 'json');
                                }
                            }, 'json');
                        }
                    }, 'json');
                }
                if (adtypeId=='4') {    //上报激活任务
//                    $.get("<?php echo U('Api/ReportActiveMobileApi/index');?>", {msid:msid,mid:mid}, function (e) {
//                        if (e.code=='201') {
//                            alert('系统繁忙请稍后再试！');
//                            return false;
//                        }else if (e.code=='501'){
//                            alert('请完善个人信息！');
//                            return false;
//                        }else if (e.code=='200'){
//                            location.href="<?php echo U('Task/details');?>"+'?'+"mission_id="+msid;
//                        }
//                    }, 'json');
                    location.href="<?php echo U('Task/details');?>"+'?'+"mission_id="+msid;
                }
                if (adtypeId=='5') {  //排重任务
                    $.post("<?php echo U('Api/Queryidfamobile/queryId_mobile');?>", {msid:msid,mid:mid},
                    function (data) {  
                        if(data=="1"){
                            alert('此App已安装过，请接其他任务');
                            return false;
                        }else if(data=="2"){
                            alert('系统繁忙请稍后再试！');
                            return false;
                        }else if(data=="0"){
                            location.href="<?php echo U('Task/details');?>"+'?'+"mission_id="+msid;
                        }
                    }, 'json');
                }
                if (adtypeId=='7') {  //排重,上报激活任务
                    $.post("<?php echo U('Api/Queryidfamobile/queryId_mobile');?>", {msid:msid,mid:mid},
                    function (data) {  
                        if(data=="1"){
                            alert('此App已安装过，请接其他任务');
                            return false;
                        }else if(data=="2"){
                            alert('系统繁忙请稍后再试！');
                            return false;
                        }else if(data=="0"){
//                            $.get("<?php echo U('Api/ReportActiveMobileApi/index');?>", {msid:msid,mid:mid},
//                            function (e) {
//                                if (e.code=='201') {
//                                    alert('系统繁忙请稍后再试！');
//                                    return false;
//                                }else if (e.code=='501'){
//                                    alert('请完善个人信息！');
//                                    return false;
//                                }else if (e.code=='200'){
//                                    location.href="<?php echo U('Task/details');?>"+'?'+"mission_id="+msid;
//                                }
//                            }, 'json');
                            location.href="<?php echo U('Task/details');?>"+'?'+"mission_id="+msid;
                        }
                    }, 'json');
                }
                if (adtypeId=='8') {  //上报点击,上报激活任务
                    $.get("<?php echo U('Api/Channelclickmobileapi/Channelclickmobileapi');?>", {msid:msid,mid:mid}, function (e) {
                        if (e.code=='201') {
                            alert('系统繁忙请稍后再试！');
                            return false;
                        }else if (e.code=='501'){
                            alert('请完善个人信息！');
                            return false;
                        }else if (e.code=='200'){
//                            $.get("<?php echo U('Api/ReportActiveMobileApi/index');?>", {msid:msid,mid:mid},
//                            function (e) {
//                                if (e.code=='201') {
//                                    alert('系统繁忙请稍后再试！');
//                                    return false;
//                                }else if (e.code=='501'){
//                                    alert('请完善个人信息！');
//                                    return false;
//                                }else if (e.code=='200'){
//                                    location.href="<?php echo U('Task/details');?>"+'?'+"mission_id="+msid;
//                                }
//                            }, 'json');
                            location.href="<?php echo U('Task/details');?>"+'?'+"mission_id="+msid;
                        }
                    }, 'json');
                }
                if (adtypeId=='9') {  //排重,上报点击,上报激活任务
                    $.post("<?php echo U('Api/Queryidfamobile/queryId_mobile');?>", {msid:msid,mid:mid},
                    function (data) {  
                        if(data=="1"){
                            alert('此App已安装过，请接其他任务');
                            return false;
                        }else if(data=="2"){
                            alert('系统繁忙请稍后再试！');
                            return false;
                        }else if(data=="0"){
                            $.get("<?php echo U('Api/Channelclickmobileapi/Channelclickmobileapi');?>", {
                            msid:msid,mid:mid}, function (e) {
                                if (e.code=='201') {
                                    alert('系统繁忙请稍后再试！');
                                    return false;
                                }else if (e.code=='501'){
                                    alert('请完善个人信息！');
                                    return false;
                                }else if (e.code=='200'){
//                                    $.get("<?php echo U('Api/ReportActiveMobileApi/index');?>", {msid:msid,mid:mid},
//                                    function (e) {
//                                        if (e.code=='201') {
//                                            alert('系统繁忙请稍后再试！');
//                                            return false;
//                                        }else if (e.code=='501'){
//                                            alert('请完善个人信息！');
//                                            return false;
//                                        }else if (e.code=='200'){
//                                            location.href="<?php echo U('Task/details');?>"+'?'+"mission_id="+msid;
//                                        }
//                                    }, 'json');
                                }
                                location.href="<?php echo U('Task/details');?>"+'?'+"mission_id="+msid;
                            }, 'json');
                        }
                    }, 'json');
                }
            }else if(status == '2'){
                location.href="<?php echo U('Task/details');?>"+'?'+"mission_id="+msid;
            }
        }
    },'json');
}

    function daoTime(){

        countTime();
    }
    //任务倒计时
    function countTime() { 
  
        $('.shengyuwc').each(function(){
            //获取当前时间  
            var date = new Date();  
            var now = date.getTime(); 
            //获取结束时间
            var endtime =this.getAttribute("endtime");
            var endDate = new Date(endtime);  
            var end = endDate.getTime(); 
            //计算剩余时间
            var leftTime = end-now;  
            //定义变量 d,h,m,s保存倒计时的时间  
            var d,h,m,s;  
            if (leftTime >= 0) {   
                d = Math.floor(leftTime/1000/60/60/24);  
                h = Math.floor(leftTime/1000/60/60%24);  
                m = Math.floor(leftTime/1000/60%60);  
                s = Math.floor(leftTime/1000%60); 
                //将倒计时赋值到div中 
                $(this).html("剩余时间 : "+d+"天"+h+"时"+m+"分"+s+"秒"); 
                if (d == 0 && h== 0 && m == 0 && s == 0) {
                    $(this).html("已下线"); 
                }              
            }
            if (leftTime < 0 ) {
                clearInterval(clock);
                window.onload='';
            };
        });
    }

    var clock = setInterval('countTime()',1000);

</script>


<br><br>


</body>

</html>
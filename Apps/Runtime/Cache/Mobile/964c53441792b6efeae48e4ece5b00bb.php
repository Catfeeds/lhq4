<?php if (!defined('THINK_PATH')) exit();?><html>

<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">


    <title>个人中心</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="msapplication-tap-highlight" content="no">


    <link rel="stylesheet" href="/lhq/Public/mobile/css/style.css" type="text/css">
    <link rel="stylesheet" href="/lhq/Public/mobile/css/mobiscroll_date.css" type="text/css">

</head>

<body>
<!--<{include file="./tishikuang.tpl"}>-->
<div class="devBox">
    <a href="<?php echo U('Index/index',array('member_id'=>I('get.member_id')));?>"><img src="/lhq/Public/mobile/images/a2.png" alt class="fl"></a>
    <h1 class="biaoti">个人中心</h1>

</div>



<form id="main_form"  name="upform" accept-charset="utf-8" action="<?php echo U('My/xgtx',array('member_id'=>I('get.member_id')));?>" style="padding-bottom: 4em;"enctype="multipart/form-data" method="post">
    <div class="user_info">
        <ul>
            <li class="diyige"><p class="juzhong">头像</p>
                <div  class="uploader">
                    <!-- <input type="text" class="filename" readonly/> -->
                    <a class="license">
                        <?php if($members["pic"] == ''): ?><img name="upfile" src="/lhq/Public/img/1654509913107329972.jpg">
                         <?php else: ?>
                            <img name="upfile" src="<?php echo ($members["pic"]); ?>"><?php endif; ?>
                    <!--    <{if $members.pic eq ''}>
                        <{if $members.sex eq 2}>
                    <img name="upfile" src="/lhq/Public/img/a686c9177f3e6709d16cd4d23ac79f3df8dc55aa.jpg">
                        <{else }>
                    <img name="upfile" src="/lhq/Public/img/1654509913107329972.jpg">
                        <{/if}>
                        <{else}>-->

                     <!--   <{/if}>-->

                    </a>
                    <input id="file0" class="file-3" type="file" size="30" accept="image/*"
                           capture="camera"  multiple="true" name="upfile"  />
                </div>
                <div class="yulan">
                    <img src="" id="img0">
                    <div class="enter">
                        <button class="btn-2 left">取消</button>
                        <button class="btn-3 right">确定</button>
                    </div>
                </div>
            </li>

            <li><span>昵称</span><input class="info_input" type="text" id="usename" placeholder="linghuaqian" value="<?php echo ($members["nickname"]); ?>"name="nickname"></li>
            <li><span>性别 </span>
                <?php if($members["sex"] == 1): ?><select name="sex" class="info_input" >
                    <option value="1" selected="selected">男</option>
                    <option value="2" >女</option>
                </select>
                <?php elseif($members["sex"] == 2): ?>
                    <select name="sex" class="info_input" >
                        <option value="1">男</option>
                        <option value="2"  selected="selected">女</option>

                    </select><?php endif; ?>
            <li><span>生日</span><input type="text" value="<?php echo (date("Y-m-d",strtotime($members["birthday"]))); ?>" name="birthday" id="USER_AGE" readonly class="info_input" placeholder="请填写你的出生日期" /></li>

            <li><span>绑定手机</span>
                <?php if($members["phone"] == ''): ?><a href="<?php echo U('My/bindmobile',array('member_id'=>I('get.member_id')));?>" class="bangding" >未绑定</a>
                <?php else: ?>
                <a href="javascript:void(0);" class="bangding" style="color: #080808"><?php echo ($members["phone"]); ?></a><?php endif; ?>
            </li>
           
            <li><span>绑定微信</span>
                <?php if($members["weixin"] == ''): ?><a href="<?php echo U('My/bindweixin',array('member_id'=>I('get.member_id')));?>" class="bangding">未绑定</a>
                <?php else: ?>
                <a href="javascript:void(0);" class="bangding" style="color: #080808"><?php echo ($members["weixin"]); ?></a><?php endif; ?></li>


            <li><span>绑定支付宝</span>
                <?php if($members["alipay"] == ''): ?><a href="<?php echo U('My/bindalipay',array('member_id'=>I('get.member_id')));?>" class="bangding">未绑定</a>
                <?php else: ?>
                <a href="javascript:void(0);" class="bangding" style="color: #080808"><?php echo ($members["alipay"]); ?></a><?php endif; ?></li>

        </ul>

        <input type="submit" id="tj" class="submit"  value="保存"><br>

        <!-- <button type="submit" id="tj" class="submit" value="保存"><strong>保存</strong></button>-->

    </div>

</form>



<script src="/lhq/Public/mobile/js/jquery.min.js"></script>
<script src="/lhq/Public/mobile/js/iscroll-zoom.js"></script>
<script src="/lhq/Public/mobile/js/hammer.js"></script>
<script src="/lhq/Public/mobile/js/jquery.photoClip.js"></script>
<!--  <script type="text/javascript">
      function setImagePreview() {
          var preview, img_txt, localImag, file_head = document.getElementById("file_head"),
                  picture = file_head.value;
          if (!picture.match(/.jpg|.gif|.png|.bmp/i)) return alert("您上传的图片格式不正确，请重新选择！"),
                  !1;
          if (preview = document.getElementById("preview"), file_head.files && file_head.files[0]) preview.style.display = "block",
                  preview.style.width = "63px",
                  preview.style.height = "63px",
                  preview.src = window.navigator.userAgent.indexOf("Chrome") >= 1 || window.navigator.userAgent.indexOf("Safari") >= 1 ? window.webkitURL.createObjectURL(file_head.files[0]) : window.URL.createObjectURL(file_head.files[0]);
          else {
              file_head.select(),
                      file_head.blur(),
                      img_txt = document.selection.createRange().text,
                      localImag = document.getElementById("localImag"),
                      localImag.style.width = "63px",
                      localImag.style.height = "63px";
              try {
                  localImag.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale)",
                          localImag.filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = img_txt
              } catch(f) {
                  return alert("您上传的图片格式不正确，请重新选择！"),
                          !1
              }
              preview.style.display = "none",
                      document.selection.empty()
          }
          return document.getElementById("DivUp").style.display = "block",
                  !0
      }

-->
<script src="/lhq/Public/mobile/js/jquery.min.js"></script>
<script src="/lhq/Public/mobile/js/mobiscroll_date.js" charset="gb2312"></script>
<script src="/lhq/Public/mobile/js/mobiscroll.js"></script>
<script type="text/javascript">
    $(function () {
        var currYear = (new Date()).getFullYear();
        var opt={};
        opt.date = {preset : 'date'};
        opt.datetime = {preset : 'datetime'};
        opt.time = {preset : 'time'};
        opt.default = {
            theme: 'android-ics light', //皮肤样式
            display: 'modal', //显示方式
            mode: 'scroller', //日期选择模式
            dateFormat: 'yyyy-mm-dd',
            lang: 'zh',
            showNow: true,
            nowText: "今天",
            startYear: currYear - 70, //开始年份
            endYear: currYear + 0 //结束年份
        };

        $("#USER_AGE").mobiscroll($.extend(opt['date'], opt['default']));

    });
</script>



<script>window.jQuery || document.write('<script src="/lhq/Public/mobile/index_files/jquery-2.1.1.min.js"><\/script>')</script>
<script src="/lhq/Public/mobile/js/iscroll-zoom.js"></script>
<script src="/lhq/Public/mobile/js/hammer.js"></script>
<script src="/lhq/Public/mobile/js/jquery.photoClip.js"></script>

<!--<script>
var obUrl = ''
//document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
$("#clipArea").photoClip({
width: 199,
height: 166,
file: "#file",
view: "#view",
ok: "#clipBtn",
loadStart: function() {
console.log("照片读取中");
},
loadComplete: function() {
console.log("照片读取完成");
},
clipFinish: function(dataURL) {
console.log(dataURL);
}
});
</script>
<script>
$(function(){
$("#logox").click(function(){
$(".htmleaf-container").show();
})
$("#clipBtn").click(function(){
$("#logox").empty();
$('#logox').append('<img src="' + imgsource + '" align="absmiddle" style=" width: 5rem;height: 4.16rem; margin-left: 1.5rem;margin-top: 1.92rem">');
$(".htmleaf-container").hide();
})
});
</script>
<script type="text/javascript">
$(function(){
jQuery.divselect = function(divselectid,inputselectid) {
var inputselect = $(inputselectid);
$(divselectid+" small").click(function(){
$("#divselect ul").toggle();
$(".mask").show();
});
$(divselectid+" ul li a").click(function(){
var txt = $(this).text();
$(divselectid+" small").html(txt);
var value = $(this).attr("selectid");
inputselect.val(value);
$(divselectid+" ul").hide();
$(".mask").hide();
$("#divselect small").css("color","#333")
});
};
$.divselect("#divselect","#inputselect");
});
</script>

<script type="text/javascript">
$(function(){
$(".mask").click(function(){
$(".mask").hide();
$(".all").hide();
})
$(".right input").blur(function () {
if ($.trim($(this).val()) == '') {
$(this).addClass("place").html();
}
else {
$(this).removeClass("place");
}
})
});
</script>
<script>
$("#file0").change(function(){
var objUrl = getObjectURL(this.files[0]) ;
obUrl = objUrl;
console.log("objUrl = "+objUrl) ;
if (objUrl) {
$("#img0").attr("src", objUrl).show();
}
else{
$("#img0").hide();
}
}) ;
function qd(){
var objUrl = getObjectURL(this.files[0]) ;
obUrl = objUrl;
console.log("objUrl = "+objUrl) ;
if (objUrl) {
$("#img0").attr("src", objUrl).show();
}
else{
$("#img0").hide();
}
}
function getObjectURL(file) {
var url = null ;
if (window.createObjectURL!=undefined) { // basic
url = window.createObjectURL(file) ;
} else if (window.URL!=undefined) { // mozilla(firefox)
url = window.URL.createObjectURL(file) ;
} else if (window.webkitURL!=undefined) { // webkit or chrome
url = window.webkitURL.createObjectURL(file) ;
}
return url ;
}
</script>
<script type="text/javascript">
var subUrl = "";
$(function (){
$(".file-3").bind('change',function(){
subUrl = $(this).val()
$(".yulan").show();
$(".file-3").val("");
});

$(".file-3").each(function(){
if($(this).val()==""){
$(this).parents(".uploader").find(".filename").val("营业执照");
}
});
$(".btn-3").click(function(){
$("#img-1").attr("src", obUrl);
$(".yulan").hide();
$(".file-3").parents(".uploader").find(".filename").val(subUrl);
})
$(".btn-2").click(function(){
$(".yulan").hide();
})

});
</script>



-->

<script type="text/javascript">
    window.onload=function()
    {
        var bt=document.getElementById("tj");
        bt.onclick=function()
        {
            if(document.upform.usename.value=="")
            {
                alert("用户名不能为空!");
                document.upform.usename.focus();
                return false;
            }
            // if("<<?php echo ($members["phone"]); ?>>" == "")
            //{
            //  alert("请绑定手机号！");
            //  return false;
            // }

        }
    }


</script>

</body></html>
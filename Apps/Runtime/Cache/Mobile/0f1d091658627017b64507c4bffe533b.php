<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>

<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        

        <title>绑定手机</title>

        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
        <meta name="format-detection" content="telephone=no">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <meta name="msapplication-tap-highlight" content="no">
    <script src="/lhq/Public/mobile/js/jquery.min.js" type="text/javascript"></script>
        
        <link rel="stylesheet" href="/lhq/Public/mobile/css/style.css" type="text/css">
        
    </head>
<script type="text/javascript">
    var intervalid;
    var i;
    function sendmsg(a){
        i = 59;
        //alert(1);
        var tel=$("input[name='phone']").val();
        //alert(tel);
        if ($("input[name='phone']").val().match(/^[1][3,4,5,7,8][0-9]{9}$/)) {
            /*alert(1);
            alert(tel);*/
            $('#send').attr('onclick','');
            $.ajax({
                url:"<?php echo U('My/sendvercode');?>",
                data:{tel:tel},
                dataType:'html',
                type:'post',
                success:function(msg){
                    //alert(msg);
                    if(msg==1){
                        $("#send").html('重新发送(60)');
                        intervalid = setInterval("fun()", 1000);
                        alert('发送成功,请注意查收！');
                    }else{
                        alert('发送失败！');
                        $('#send').attr('onclick','return sendmsg(this)');
                    }
                }
            })
        }else{
            alert('请输入正确的手机号！');
        }
    }
   function fun() {
       if (i <=0) {
           $("#send").html('重新发送');
           $("#send").attr('onclick','return sendmsg(this);');
           $("#send").css('background',"#F33812");
           clearInterval(intervalid);
       }else{
           $("#send").html('重新发送('+i+')');
           $("#send").attr('onclick','');
           $("#send").css('background',"#BCBCBC");
       }
       i-=1;
   }
</script>

    <body>

             <!--   <{include file="./tishikuang.tpl"}>-->
			
				<div class="devBox">
					<a href="<?php echo U('My/my',array('member_id'=>I('get.member_id')));?>"><img src="/lhq/Public/mobile/images/a2.png" alt class="fl"></a>
					<h1 class="biaoti">绑定手机</h1>

				</div>

                <div class="hang">
                    
                    <form method="post" action="<?php echo U('My/bindmobile',array('member_id'=>I('get.member_id')));?>">
                        <div id="Vali" style="text-align: left"></div>
                    <div class="bind">
                
                    <input type="text" name="phone" class="mobile"  placeholder="请输入您的手机号">
                        <!--  <input type="submit" id="hq"  onclick="return seedMsg(this)" value="获取验证码" />

                        <input type="button" id="send" value="发送验证码" onclick="return sendmsg(this)">-->
                        <span id="send" class="huoqu" onclick="return sendmsg(this)">发送验证码</span>
                    </div>
         <div class="bind">
                     <input type="text" name="v_code"  name="checkcode" class="mobile" placeholder="短信验证码">
                         <!--<input type="submit" id="tj" class="tijiao" value="提交" />-->
                        <button type="submit" id="tj" class="tijiao"<strong>提交</strong></button>
                        </div>
                    </form>

                </div>
			


            
        

    

</body></html>
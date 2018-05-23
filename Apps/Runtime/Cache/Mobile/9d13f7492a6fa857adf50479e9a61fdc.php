<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>

<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        

        <title>绑定微信</title>

        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
        <meta name="format-detection" content="telephone=no">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <meta name="msapplication-tap-highlight" content="no">

       
        <link rel="stylesheet" href="/lhq/Public/mobile/css/style.css" type="text/css">
        
    </head>

    <body>

                <!--<{include file="./tishikuang.tpl"}>-->

			
				<div class="devBox">

					<a href="<?php echo U('My/my',array('member_id'=>I('get.member_id')));?>"><img src="/lhq/Public/mobile/images/a2.png" alt class="fl"></a>
					<h1 class="biaoti">绑定微信</h1>

				</div>


                <form method="post" action="<?php echo U('My/bindweixin',array('member_id'=>I('get.member_id')));?>">
                <ul class="weixin">

                    <li><img src="/lhq/Public/mobile/images/saomiao.jpg" class="saomiao" alt=""></li>

                    <li>直接扫描关注</li>
                    <li><input type="text" name="yzm_code" class="yanzheng"placeholder="请输入微信验证码"></li>
                   <!-- <li><input type="submit" id="tj" class="bang" value="绑定微信" /></li>-->
                    <button type="submit" id="tj" class="bang"<strong>绑定微信</strong></button>
                </ul>
                    </form>
			<div class="kuang">为了保障你的账户安全，请先进行微信号绑定，完成绑定后即可体现。</div>
            <ul class="weixin">

                    <li><img src="/lhq/Public/mobile/images/bangdingweixin.jpg" class="tianjia" alt=""></li>

                    
                    
                    

                </ul>
                    


            
        

    

</body></html>
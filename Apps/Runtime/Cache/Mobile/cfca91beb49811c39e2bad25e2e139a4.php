<?php if (!defined('THINK_PATH')) exit();?> <!DOCTYPE html>

<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">


        <title>首页</title>

        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
        <meta name="format-detection" content="telephone=no">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <meta name="msapplication-tap-highlight" content="no">


        <link rel="stylesheet" href="/lhq/Public/mobile/css/style.css" type="text/css">
        <script src="/lhq/Public/mobile/js/jquery.min.js"></script>
        <script src="/lhq/Public/mobile/js/eventsource.js"></script>



			<script type="text/javascript">
                function showDiv(){

                    if('<?php echo ($members["phone"]); ?>'==''){
                        document.getElementById('popDiv').style.display='block';
                        document.getElementById('bg').style.display='block';
                            }else{
                        window.location.href="<?php echo U('Tixian/tixian',array('member_id'=>I('get.member_id')));?>";
                    }


                }
                function closeDiv(){
                document.getElementById('popDiv').style.display='none';
                document.getElementById('bg').style.display='none';
                }
            </script>

    </head>

    <body>
      <div id="wrapper" style="opacity: 1;">
        <div class="content" >
          <div class="page-header">
            <a href="<?php echo U('Message/message',array('member_id'=>I('get.member_id')));?>" class="tp">
              <?php if($counts == 0): ?><img src="/lhq/Public/mobile/images/g1.png" class="gonggao"></a>
              <?php else: ?>
              <img src="/lhq/Public/mobile/images/g1.png" class="gonggao">
              <span class="nav-counter" id="msg"><?php echo ($counts); ?></span><?php endif; ?>
            </a>
            <p class="gonggao2">
             <marquee directon="left" scrollamount="2" valign="middle" align="middle">关于应用帮闪退问题解决办法：请退出应用重新进入或者卸载应用重新安装</marquee>
            </p>
        </div>
        <div class="content-header">     
           <?php if($members["pic"] == '' AND $members["sex"] == 1): ?><a href="<?php echo U('My/my',array('member_id'=>I('get.member_id')));?>" class="license1">
                  <img id="img-1" src="/lhq/Public/img/1654509913107329972.jpg" id="touxiang">
              </a>
            <?php elseif($members["pic"] == '' AND $members["sex"] == 2): ?>
              <a href="<?php echo U('My/my',array('member_id'=>I('get.member_id')));?>" class="license1">
                <img id="img-1" src="/lhq/Public/img/a686c9177f3e6709d16cd4d23ac79f3df8dc55aa.jpg" id="touxiang">
              </a>
              <?php else: ?>
                <a href="<?php echo U('My/my',array('member_id'=>I('get.member_id')));?>" class="license1">
                  <img id="img-1" src="<?php echo ($members["pic"]); ?>" id="touxiang">
              </a><?php endif; ?>

        <p>ID:<?php echo ($members["member_id"]); ?></p>
      </div>
  	    <div class="containter">
          <div class="c_left">
            <p>今日收入：</p>
            <p><?php echo ($income); ?> 元</p>
          </div>

          <div class="c_min">
            <p>累计收益：</p>
            <?php if($members["income"] == ''): ?><p>0 元</p>
            <?php else: ?>
            <p><?php echo ($members["income"]); ?> 元</p><?php endif; ?>
          </div>
          <div class="c_right">
            <p>账户余额</p>
            <p><?php echo ($members["balance"]); ?> 元</p>
          </div>
       </div>


      </div>
      <ul>
        <li class="zhuanqian">
          <a href="<?php echo U('Task/task',array('member_id'=>I('get.member_id')));?>" class="lianjie"><img src="/lhq/Public/mobile/images/b12.png" class="img1">
            <p class="renwu">任务中心</p>
            <span class="xiao right">做任务赚钱 ></span>
          </a>
        </li>
      </ul>
      <div class="kuai">
        <div class="leftkuai left">
          <a href="<?php echo U('Invite/invite',array('member_id'=>I('get.member_id')));?>" class="lianjie2">
            <img src="/lhq/Public/mobile/images/b3.png" class="img">
              <p class="miaoshu">邀请好友</p>
              <p class="miaoshu2">与好友一起赚钱</p>
          </a>
        </div>
        <div id="popDiv" class="mydiv" style="display:none;">
          请先完善个人资料再提现
          <div class="xian"></div>
          <div class="tantop" style="color: #fff;" onclick="openMy()">
            前往完善个人资料
          </div>
          <div class="tandown" onclick="closeDiv()" style="color: #1e8dec;">
            稍后再去
          </div>
        </div>
        <div id="bg" class="bg" style="display:none;"
          <a href="javascript:closeDiv()" class="bg"></a>
        </div>
        <div class="rightkuai right">
          <span type="Submit" name="" value="显示层" class="lianjie2"onclick="javascript:showDiv()" >
            <img src="/lhq/Public/mobile/images/b4.png" class="img">
           <p class="miaoshu">快速提现</p>
           <p class="miaoshu2">提现快速有保障</p>
          </span>
        </div>
      </div>
      <div class="kuai">
        <div class="leftkuai left">
          <a href="<?php echo U('Books/books',array('member_id'=>I('get.member_id')));?>" class="lianjie2">
            <img src="/lhq/Public/mobile/images/b2.png" class="img">
            <p class="miaoshu">收支明细</p>
            <p class="miaoshu2">每笔钱清晰明了</p>
          </a>
        </div>
        <div class="rightkuai right">
          <a href="<?php echo U('My/my',array('member_id'=>I('get.member_id')));?>" class="lianjie2">
            <img src="/lhq/Public/mobile/images/b6.png" class="img">
            <p class="miaoshu">个人中心</p>
            <p class="miaoshu2">我的资料信息</p>
          </a>
        </div>    
      </div>
      <div class="kuai">
        <div class="leftkuai left">
           <a href="<?php echo U('Message/message',array('member_id'=>I('get.member_id')));?>" class="lianjie2">
                <img src="/lhq/Public/mobile/images/b8.png" class="img">
                             <p class="miaoshu">通知中心</p>
                             <p class="miaoshu2">消息通知全知道</p>
                    </a>
              
          </div>

          <div class="rightkuai right">
            <a href="<?php echo U('About/more',array('member_id'=>I('get.member_id')));?>" class="lianjie2">
                <img src="/lhq/Public/mobile/images/b5.png" class="img">
                             <p class="miaoshu">更多</p>
                             <p class="miaoshu2">问题要求点这里</p>
                      </a>
              
          </div>
       
          

      </div>
		
         <div id="verDiv" class="mydiv" style="display:none;">
          是否使用新用户？
          <div class="xian"></div>
          <div class="verific" style="color: #fff;" onclick="closeVer()">
            使用新账户
          </div>
          <div class="verific" onclick="openWeixin()" >
            找回老帐户
          </div>
        </div> 
 

  <script>
      $(function(){
          var mmid="<?php echo ($members["member_id"]); ?>";
         // alert(mmid);
          $(".tp").click(function(){
             // url = "<?php echo U('Index/msgClickAjax');?>";
              $.post("<?php echo U('Index/msgClickAjax');?>",{mmid:mmid,type:1},function(data){
                 // alert(type);
                  //console.log(data);//die;
                  if(data<=0){

                      var html='<img src="/lhq/Public/mobile/images/g1.png" class="gonggao" />';
                      $(".tp").html(html);
                  }else{
                      var html='<img src="/lhq/Public/mobile/images/g1.png" class="gonggao" />' +
                              '<span class="nav-counter" id="msg">'+data+'</span>';
                      $(".tp").html(html);
                  }
              },'html')
          })
          setInterval(function(){
              //url = "<?php echo U('Index/msgClickAjax');?>";
              $.post("<?php echo U('Index/msgClickAjax');?>",{mmid:mmid,type:2},function(data){
                  //console.log(data);//die;
                  //alert(type);
                  if(data<=0){
                      var html='<img src="/lhq/Public/mobile/images/g1.png" class="gonggao" />';
                      $(".tp").html(html);
                  }else{
                      var html='<img src="/lhq/Public/mobile/images/g1.png" class="gonggao" />' +
                              '<span class="nav-counter" id="msg">'+data+'</span>';
                      $(".tp").html(html);
                  }
              },'html');
          },'18000000');
        /* $(function(){
              var es = new EventSource("../ajax/msgClickAjax.php");
             es.addEventListener("myevent",function(e){
                    data= e.data;
                 if(data<=0){
                     var html='<img src="<<?php echo ($smarty["const"]["ADMIN_URL"]); ?>>/assets/mobile/images/g1.png" class="gonggao" />';
                     $(".tp").html(html);
                 }else{
                     var html='<img src="<<?php echo ($smarty["const"]["ADMIN_URL"]); ?>>/assets/mobile/images/g1.png" class="gonggao" />' +
                             '<span class="nav-counter" id="msg">'+data+'</span>';
                     $(".tp").html(html);
                 }
             },false);//使用false表示在冒泡阶段处理事件，而不是捕获阶段。
          })*/

      })

  </script>
<script type="text/javascript">
function openMy(){
    window.location.href = "<?php echo U('My/my',array('member_id'=>I('get.member_id')));?>";
}
</script>
<script type="text/javascript">
function openWeixin(){
    window.location.href = "<?php echo U('My/bindweixin',array('member_id'=>I('get.member_id')));?>";
}
</script>
<script type="text/javascript">
    var b = document.referrer;
    var mid="<?php echo ($members["member_id"]); ?>";
    if (b == "") {
        $.post("<?php echo U('Index/userMsg');?>",{mid:mid},function(data){
            if (data == '2') {
                document.getElementById('verDiv').style.display='block';
                document.getElementById('bg').style.display='block';
            } else if (data == '1'){
                document.getElementById('verDiv').style.display='none';
                document.getElementById('bg').style.display='none';
            }
        });
    }
//      function openVer(){
//          document.getElementById('verDiv').style.display='block';
//          document.getElementById('bg').style.display='block';
//          }
//      function closeVer(){
//            document.getElementById('verDiv').style.display='none';
//            document.getElementById('bg').style.display='none';
//            }
      </script>


 <footer>© 2012 Sencha inc.

            </footer>
        

        <br>

    

</body></html>
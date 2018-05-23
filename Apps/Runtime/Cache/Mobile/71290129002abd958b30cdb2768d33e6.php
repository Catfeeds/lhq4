<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html>

<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <title>邀请好友</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
        <meta name="format-detection" content="telephone=no">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <meta name="msapplication-tap-highlight" content="no">
    <script src="/lhq/Public/mobile/js/jquery.min.js" type="text/javascript"></script>
    <link rel="stylesheet" href="/lhq/Public/mobile/css/common.css" type="text/css">
        <link rel="stylesheet" href="/lhq/Public/mobile/css/style.css" type="text/css">
    <link rel="stylesheet" href="/lhq/Public/mobile/css/content.css" type="text/css">
    <link rel="stylesheet" href="/lhq/Public/mobile/css/reset1.css" type="text/css">
    <link rel="stylesheet" href="/lhq/Public/mobile/css/swiper1.min.css" type="text/css">
    <link rel="apple-touch-icon" href="http://fs.rehulu.com/site_media/images/rehulu_logo.png">
    <link rel="stylesheet" href="http://fs.rehulu.com/site_media/css/reset.css?t=20150318"/>

    <link rel="stylesheet" href="http://fs.rehulu.com/site_media/css/swiper.min.css"/>
    <style>
        body,html{background:#FFF}
       .top-box{background:rgb(246,246,246);}
        .top-box .title-box{color:#262626}
        .fu-box{width:100%;height:auto}
        .fu-box .title{color:#262626;text-align:center;font-size:1.5em;margin:0px auto 0px;padding:15px 0;background:rgb(246,246,246)}
        .fu-box .gr{color:#64c112}
        #back{position: absolute;top: 10px;left: 10px;width: 30px;height: 30px;background: url('http://fs.rehulu.com/site_media/images/green_back.png') no-repeat 0 0px;background-size: 100%;}
        .swiper-container{height:360px;margin-top:30px}
        .swiper-container-horizontal>.swiper-pagination-bullets{bottom:40px}
        .swiper-slide{width:240px;height:345px;background-size:100%;background-repeat:no-repeat;background-position:center;}
        .swiper-slide.disabled{}
        .swiper-slide img{border-radius:5px}

        .swiper-slide .front i{position:absolute;width:20px;height:20px;top:10px;right:10px;background:url(http://fs.rehulu.com/site_media/images/tip_icon_2.png) 0 0 no-repeat;background-size:100%;opacity:0.6}

        .swiper-slide .front img{width:95%}
     .swiper-slide .back img{width:100%}
        .swiper-slide .back i{position:absolute;width:20px;height:20px;top:10px;left:10px;background:url(http://fs.rehulu.com/site_media/images/back_icon.png) 0 0 no-repeat;background-size:100%}
       .swiper-slide .card-num{position:absolute;width:30px;bottom:20px;right:10px;text-align:center}
        .swiper-slide .card-num span{border-radius:100%;padding:5px;border:1px solid #F9B405;color:#F9B405;}
        .swiper-slide .front .img-merger{position:absolute;top:0;left:0;}

        .swiper-slide .front .qr-code{position:absolute;width:110px;bottom:224px;left:42%;margin-left:-45px;text-align:center;-webkit-font-smoothing: antialiased}
      /*  .redeem-btn.disabled{background:#909090}*/
        .tip{color:#ff8512;text-align:center;font-size:15px;margin-top:15px}

     </style>


    </head>

    <body>

 
				<div class="devBox">
					<a href="<?php echo U('Invite/invite',array('member_id'=>I('get.member_id')));?>"><img src="/lhq/Public/mobile/images/a2.png" alt class="fl"></a>
					<h1 class="biaoti">邀请好友</h1>
                    <a href="<?php echo U('Invite/cheats',array('member_id'=>I('get.member_id')));?>" style="color: #ffffff" class="fr">邀请攻略</a>

				</div>

	<div style="margin-top:7%;">			
    <ul id="tab2">
        <li class="current">图片邀请</li>
        <li>二维码邀请</li>
       
    </ul>
    <div  style=" width: 100%;  margin: 9% auto;">
       <div id="content">
        <ul  style="display:block;">
            <div class="swiper-container swiper-container-horizontal swiper-container-3d swiper-container-coverflow">
        <div class="swiper-wrapper" style="transform: translate3d(648px, 0px, 0px); transition-duration: 0ms;">
            <div class=" swiper-slide disabled swiper-slide-active" style="transform: translate3d(0px, 0px, -100px) rotateX(0deg) rotateY(-50deg); z-index: 0; transition-duration: 0ms;">
                <div class="flipper">
                    <div class="front">
                        <img src="/lhq/Public/mobile/images/yq_1.png" class="bgtheme">
                        <img src="/lhq/Public/qrcode/<?php echo ($qrcode["qrcode"]); ?>"  class="qr-code">
                        <img src="" class="img-0 img-merger" style="-webkit-touch-callout: default;">
                    </div>

                </div>
                
            </div>
            <div class="swiper-slide flip-container disabled swiper-slide-next" style="transform: translate3d(0px, 0px, -200px) rotateX(0deg) rotateY(-100deg); z-index: -1; transition-duration: 0ms;">
                <div class="flip-container">
                <div class="flipper">
                    <div class="front">
                        <img src="/lhq/Public/mobile/images/yq_2.png" class="bgtheme">
                        <img src="/lhq/Public/qrcode/<?php echo ($qrcode["qrcode"]); ?>" class="qr-code">
                       <img src="" class="img-1 img-merger" style="-webkit-touch-callout: default;">
                    </div>
                </div>
                </div>
          </div>
            
            <div class="swiper-slide flip-container disabled" style="transform: translate3d(0px, 0px, -300px) rotateX(0deg) rotateY(-150deg); z-index: -2; transition-duration: 0ms;">
                <div class="flip-container">
                <div class="flipper">
                    <div class="front">
                        <img src="/lhq/Public/mobile/images/yq_3.png" class="bgtheme">
                        <img src="/lhq/Public/qrcode/<?php echo ($qrcode["qrcode"]); ?>" class="qr-code">
                       <img src="" class="img-2 img-merger" style="-webkit-touch-callout: default;">
                    </div>
                </div>
                </div>
            </div>
            
            <div class="swiper-slide flip-container disabled" style="transform: translate3d(0px, 0px, -400px) rotateX(0deg) rotateY(-200deg); z-index: -3; transition-duration: 0ms;">
                <div class="flip-container">
                <div class="flipper">
                    <div class="front">
                        <img src="/lhq/Public/mobile/images/yq_4.png" class="bgtheme">
                        <img src="/lhq/Public/qrcode/<?php echo ($qrcode["qrcode"]); ?>" class="qr-code">
                     <img src="" class="img-3 img-merger" style="-webkit-touch-callout: default;"> 
                    </div>
                </div>
                </div>
            </div>
            
             <div class="swiper-slide flip-container disabled" style="transform: translate3d(0px, 0px, -500px) rotateX(0deg) rotateY(-250deg); z-index: -4; transition-duration: 0ms;">
                <div class="flip-container">
                <div class="flipper">
                    <div class="front">
                        <img src="/lhq/Public/mobile/images/yq_5.png" class="bgtheme">
                        <img src="/lhq/Public/qrcode/<?php echo ($qrcode["qrcode"]); ?>" class="qr-code">
                        <img src="" class="img-4 img-merger" style="-webkit-touch-callout: default;">
                    </div>
                </div>
                </div>
              </div>
            
          </div>

    </div>
        </ul>

        <ul>
            <img src="/lhq/Public/qrcode/<?php echo ($qrcode["qrcode"]); ?>" class="erweimayaoqing" >
        </ul>

    
    </div>
    </div>

<p class="tip">长按图片保存到手机后即可分享图片</p>
<script src="/lhq/Public/mobile/js/ajaxjs_jquery.min.js"></script>
<script>
    $(function(){
        window.onload = function()
        {
            var $li = $('#tab2 li');
            var $ul = $('#content ul');
                        
            $li.mouseover(function(){
                var $this = $(this);
                var $t = $this.index();
                $li.removeClass();
                $this.addClass('current');
                $ul.css('display','none');
                $ul.eq($t).css('display','block');
            })
        }
    });
</script>

<!-- <script src="//hm.baidu.com/hm.js?73586d9456385c1f6e1b51968afe865d"></script> -->
<script src="/lhq/Public/mobile/js/hm.js.下载"></script>
<script type="text/javascript" src="/lhq/Public/mobile/js/jquery-2.1.0.min.js.下载"></script>
        
<script type="text/javascript" src="/lhq/Public/mobile/js/swiper.min.js.下载"></script>
<script>
    var data=[];
    function draw(slideIdx){
        var c=document.createElement('canvas'),ctx=c.getContext('2d'),len=data.length;

        c.width=492;
        c.height=724;
        ctx.rect(0,0,c.width,c.height);
        function drawing(n){
            if(n<len){
                var img=new Image;
                img.crossOrigin = 'Anonymous'; //解决跨域
                img.src=data[n];
                    img.onload=function(){
                        if(n == 0){
                            ctx.drawImage(img,0,0,c.width,c.height);
                        }
                        if(n == 1){
                            ctx.drawImage(img,126,250,224,-224);
                           // ctx.drawImage(img,130,255,233,-202);
                           // ctx.drawImage(img,126,250,244,-244);
                           // len=data.length;
                        }
                        drawing(n+1);//递归
                        // data=[];
                    };
                
            }else{
                //保存生成作品图片
                $(".img-"+slideIdx).attr("src",c.toDataURL("image/png"));

                data=[] ;
            }
        }
        drawing(0);
    }
    var swiper = new Swiper('.swiper-container', {
        // pagination: '.swiper-pagination',
        effect: 'coverflow',
        grabCursor: true,
        centeredSlides: true,
        slidesPerView: 'auto',
        coverflow: {
            rotate: 55,
            stretch: 0,
            depth: 100,
            modifier: 1,
            slideShadows : true
        }
    });
    swiper.on('onTransitionEnd', function () {
        data=[];
        var slideIdx = swiper.activeIndex;
        var domWrap = swiper.slides[slideIdx];
        var imgBg = $(domWrap).find(".bgtheme"),imgQr = $(domWrap).find(".qr-code");
        var imgBgSrc=$(imgBg[0]).attr("src"),imgQrSrc=$(imgQr[0]).attr("src");
        if(!$(".img-"+slideIdx).attr("src")){
            data.push(imgBgSrc,imgQrSrc);
            draw(slideIdx);
        }
        
    });
    
    
    $(function(){
        var domWrap = swiper.slides[0];
        var imgBg = $(domWrap).find(".bgtheme"),imgQr = $(domWrap).find(".qr-code");
        var imgBgSrc=$(imgBg[0]).attr("src"),imgQrSrc=$(imgQr[0]).attr("src");
        data.push(imgBgSrc,imgQrSrc);
        draw(0);
        
        $(".redeem-active").on("click",function(){
            $.ajax({
                type:'POST',
                url: "/activity/fucard/redeem.json",
                data:'',
                cache:false,
                dataType:'json',
                beforeSend:function(){
                },
                success:function(data){
                    if(data.status == 0){
                       // alert("红包兑换成功，6.6元已到账！");
                        window.location.reload();
                    }
                    else{
                        alert(data.message);
                    }
                },
                complete:function(){
                },
                error: function(data){
                }
            });
        });
    });
</script>

    <!--   代码统计 -->
        <script>
        var _hmt = _hmt || [];
        (function() {
          var hm = document.createElement("script");
          hm.src = "//hm.baidu.com/hm.js?73586d9456385c1f6e1b51968afe865d";
          var s = document.getElementsByTagName("script")[0]; 
          s.parentNode.insertBefore(hm, s);
        })();
        </script>





    
</div>
</body></html>
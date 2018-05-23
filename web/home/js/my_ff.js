$(function  () {
	//获取短信验证码
	var validCode=true;
	$(".msgs").click (function  () {
		var time=30;
		var code=$(this);
		if (validCode) {
			validCode=false;
			code.addClass("msgs1");
		var t=setInterval(function  () {
			time--;
			code.html(time+"秒");
			if (time==0) {
				clearInterval(t);
			code.html("重新获取");
				validCode=true;
			code.removeClass("msgs1");

			}
		},1000)
		}
	});

  $("input.mh_date").manhuaDate({                
    Event : "click",//可选               
    Left : 0,//弹出时间停靠的左边位置
    Top : -16,//弹出时间停靠的顶部边位置
    fuhao : "-",//日期连接符默认为-
    isTime : false,//是否开启时间值默认为false
    beginY : 1949,//年份的开始默认为1949
    endY :2100//年份的结束默认为2049
  });
  // 验证码倒计时
$(document).ready(function() {
    var wait = 120;
    function time(o) {
        if (wait == 0) {
            o.removeAttribute("disabled");
            o.value = "免费获取验证码";
            wait = 120;
        } else {
            o.setAttribute("disabled", true);
            o.value = "重新发送(" + wait + ")";
            wait--;
            setTimeout(function() {
                time(o)
            },
            1000)
        }
    }

    document.getElementById("btn").onclick = function() {
        time(this);
    }
})

  $('#ul_Menu li ').click(function(){
    //$(this).addClass('hit').siblings().removeClass('hit');
    var left = $(this).position().left;
    var width = $(this).width();    
    $('#midNavLine').width(width);
    $('#midNavLine').css('left', left+32);  
    var i = $(this).index();
    $('.content-wrap').eq(i).css('display','block');
    $('.content-wrap').eq(i).siblings('.content-wrap').css('display','none');

    //$('.content-wrap>div:eq('+$(this).index()+')').show().siblings().hide(); 
  });

  $('.g-obtain-title ul li ').click(function(){
  $(this).addClass('curr').siblings().removeClass('curr');
  
   var i = $(this).index();
    $('.fri-req-wrap').eq(i).css('display','block');
    $('.fri-req-wrap').eq(i).siblings('.fri-req-wrap').css('display','none');
  });

$('.u-del').click(function(){
  var obj = $(this);
  layer.open({
          type: 1,
          skin: 'layui-layer-rim', //加上边框
          area: ['420px', '200px'], //宽高
          content: $('#pageDialog'),
          btn:['确定','取消'],
          yes:function(index){
            $(obj).parent().parent().empty();
            layer.close(index);
          }
     }); 
  });
});


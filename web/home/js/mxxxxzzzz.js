$(function(){
  $('.button05').click(function(){

  $('#divEditor').show();
  $('.Pub-inp-click').hide();
  });

});
$(function(){
  $('.button06').click(function(){

  $('.Pub-inp-click').show();
  $('#divEditor').hide();
  });

});

$(function(){

  $('.Topic-tab ul li ').click(function(){
   $(this).addClass('tabcur').siblings().removeClass('tabcur');
   var i = $(this).index();
    $('.panes').eq(i).css('display','block');
    $('.panes').eq(i).siblings('.panes').css('display','none');
   });
   });
    $(document).ready(function() {
      $('#pJoinGroup').mouseover(function() {
        $('#pJoinGroup').hide();
        $('#divGroupList').show()
      });
    });
  $(document).ready(function() {
      $('#divGroupList').mouseleave(function() {
        $('#pJoinGroup').show();
        $('#divGroupList').hide()
      });
    });
  $(document).ready(function(){
 $("#bt").click(function(){
  if($("#email1").val()=="")
  {
   alert("邮箱不能为空");
   return false;
  }
  var email=$("#email1").val();
  if(!email.match(/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/))
  {
   alert("格式不正确！请重新输入");
   $("#email1").focus();
  }
 });
});
$(document).ready(function(){
 $("#bt").click(function(){
    $('#divSend').hide();
    $('#divChecking').show();
     });
});

// 验证码倒计时

$(document).ready(function(){
var wait=120;
function time(o) {
        if (wait == 0) {
            o.removeAttribute("disabled");          
            o.value="免费获取验证码";
            wait = 120;
        } else {
            o.setAttribute("disabled", true);
            o.value="重新发送(" + wait + ")";
            wait--;
            setTimeout(function() {
                time(o)
            },
            1000)
        }
    }

  document.getElementById("btn").onclick=function(){time(this);}
})
// 倒计时
function delayURL(url) {
    var delay = document.getElementById("time").innerHTML;
    if (delay > 0) {
        delay--;
        document.getElementById("time").innerHTML = delay
    } else {
        window.top.location.href = url
    }
    setTimeout("delayURL('" + url + "')", 1000)
}

	$(document).ready(function(){
 $("#btt").click(function(){
 $('#divSend').hide();
 $('#divChecking').show();
     });
});


	
	
/*
 * 登录
 */
var _OP_ = {
		imgcode: '/index.php?s=Admin/Login/code/'
};
$(document)
	// 显示隐藏密码
	.on('click', '.hide-pwd', function(){
		$t = $(this);
		if($('i.fa', this).hasClass('fa-eye-slash')){
			$('i.fa', this)
				.removeClass('fa-eye-slash')
				.addClass('fa-eye');
			$(this).siblings('input').attr('type', 'text');
		}else{
			$('i.fa', this)
				.removeClass('fa-eye')
				.addClass('fa-eye-slash');
			$(this).siblings('input').attr('type', 'password');
		}
	})

	// 刷新验证码
	.on('click', '.rest-imgcode', function(){rest_imgcode();})

	// 登录表单提交
	.on('submit', 'form.login-form', function(){
		var data = $(this).serialize(), url = $(this).attr('action');
		$.post(url, data, function(s){
			if(s.code == 200){
				window.location.reload();
			}else{
				msg(s.data, 'warning');
				rest_imgcode();
			}
		})

		return false;
	})

function rest_imgcode(){
	$('input.i-imgcode').css('background-image', 'url('+ _OP_.imgcode + new Date().getTime() +')').val('');
	$('img.i-imgcode').attr('src',  _OP_.imgcode + new Date().getTime());
}

// alert/information error warning notification success
function msg(text, type){
	return noty({
		text: text,
		type: type,
		theme: 'relax',
		// relax defaultTheme bootstrapTheme
		layout: 'topCenter',
		animation   : {
			open  : 'animated bounceInDown',
			close : 'animated bounceOutDown',
			easing: 'swing',
			speed : 0,
			fadeSpeed: 'fast',
		},
		modal: (type && type == 'error' ? true : false),
		timeout: 1500
	});
}

$(rest_imgcode);


$(function(){
	$.ajax({
		url: 'http://api.asilu.com/bg/?callback=?',
		dataType: "jsonp",
		cache: true,
		jsonpCallback: "jsonpBgCallback",
		success: function(s){
			if(!s || !s.images) return;

			// s.images.sort(function () { return 0.5 - Math.random(); });

			$('head').append('<style>body{ \
				background: url('+ s.images[0].url +') no-repeat top center; \
				background-attachment: fixed; \
				background-clip: border-box; \
				background-origin: padding-box; \
				background-size: cover; \
			}<style>');

			$('body').removeClass('bg-1');
		}
	});
})



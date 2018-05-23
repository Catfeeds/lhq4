var _old_time = new Date().getTime();

function 倒计时(countDownCallback) {
	var countDownCallback = countDownCallback || function(){};
	var _n = 0;
	var _time_ = (new Date().getTime() - _old_time) / 1000;

	$(".lxftime").each(function() {
		var seconds = $(this).data('seconds') - _time_,
			minutes = Math.floor(seconds / 60),
			hours = Math.floor(minutes / 60),
			microtime = Math.floor(seconds * 100 % 100);
		
		$dom = $(this).siblings('.time-item2');
		if (seconds <= 0) {
			//$('strong.millisecond_show', $dom).html('<s></s>00');
			$(this).parent().html('<strong>正在计算中……</strong>').animate({height:'45px'},'slow');
			$(this).removeClass('lxftime');

			if($(this).data('reload')){
				window.location.href = $(this).data('reload');
				// window.location.reload();
			}
			countDownCallback($(this).data('id'), this);
		} else {
			_n++;

			$('.minute_show', $dom).html('<s></s>' + pad(minutes));
			$('.second_show', $dom).html('<s></s>' + pad(parseInt(seconds % 60)));
			$('.millisecond_show', $dom).html('<s></s>' + pad(microtime));
		}
	});

	function pad(value) {
		return value > 9 ? value : '0' + value;
	}

	setTimeout(function(){
		倒计时(countDownCallback);
	}, (_n > 0 ? 52 : 1000));
}
















// 总条数  每页显示条数  当前页  分页显示数量  分页地址生成函数
function getPageHtml(x, w, z, A, s) {
	var t = 1,
	y = 1,
	v = 1;
	var r = "";
	t = parseInt(x / w);
	if (x % w > 0) {
		t++
	}
	if (t < 1) {
		t = 1
	}
	r += "<div>";
	if (x > 0) {
		y = 1;
		while (y + A < t + 1 && y + A < z + 2) {
			y += A - 2
		}
		v = y + A - 1;
		if (v > t) {
			v = t
		}
		if (z == 1) {
			r += '<span class="f-noClick"><a href="javascript:;"><i class="f-tran f-tran-prev">&lt;</i>上一页</a></span>'
		} else {
			r += '<span><a title="上一页" href="' + s(z - 1) + '"><i class="f-tran f-tran-prev">&lt;</i>上一页</a></span>'
		}
		if (y > 1) {
			r += '<span><a href="' + s(1) + '">1</a></span><span>…</span>'
		}
		for (var u = y; u <= v; u++) {
			if (u != z) {
				r += '<span><a href="' + s(u) + '">' + u + "</a></span>"
			} else {
				r += '<span class="current"><a>' + u + "</a></span>"
			}
		}
		if (v < t) {
			r += '<span>…</span><span><a href="' + s(t) + '">' + t + "</a></span>"
		}
		if (z < t) {
			r += '<span><a href="' + s(z + 1) + '" title="下一页">下一页<i class="f-tran f-tran-next">&gt;</i></a></span>'
		} else {
			r += '<span class="f-noClick"><a>下一页<i class="f-tran f-tran-next">&gt;</i></a></span>'
		}
	}
	r += '<span class="f-mar-left">共<em>' + t + "</em>页，去第</span>";
	r += '<span><input type="number" value="' + z + ' " max=" '+ t +' "  min="1" >页</span>';
	r += '<span class="f-mar-left"><a id="btnGotoPage" href="javascript:;" title="确定">确定</a></span>';
	r += "</div>";
	return r
};
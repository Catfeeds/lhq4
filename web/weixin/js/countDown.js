var _old_time = new Date().getTime();

function lxfEndtime(countDownCallback) {
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
			$('strong.millisecond_show', $dom).html('<s></s>00');
			$(this).removeClass('lxftime');

			if($(this).data('reload')){
				window.location.href = $(this).data('reload');
				// window.location.reload();
			}
			countDownCallback($(this).data('id'), this);
		} else {
			_n++;

			$('strong.minute_show', $dom).html('<s></s>' + pad(minutes));
			$('strong.second_show', $dom).html('<s></s>' + pad(parseInt(seconds % 60)));
			$('strong.millisecond_show', $dom).html('<s></s>' + pad(microtime));
		}
	});

	function pad(value) {
		return value > 9 ? value : '0' + value;
	}

	if(_n > 0){
		setTimeout(function(){
			lxfEndtime(countDownCallback);
		}, 52);
	}
}




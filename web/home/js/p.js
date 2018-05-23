$('.dom-type').on('click', function(){
	$(this).addClass('current').siblings().removeClass('current');
	getNew({type: $(this).data('type-id')});
});

$('#divPage')
	.on('click', '#btnGotoPage', goPage)
	.on('keyup', 'input', function(event) {

    if (event.keyCode == "13") {
        $('#btnGotoPage').click();
    }
});

$('.dom-list')
	.on('mouseenter', '.m-lottery-list', function(){
		if($(this).attr('type')=='isRaff'){
			$(this).addClass('m-lottery-hover');
		}
	})
	.on('mouseleave', '.m-lottery-list', function(){
		$(this).removeClass('m-lottery-hover');
	});

function goPage(){
	var page = parseInt($('#divPage input').val());
	
	if(page > 0 && page != _o_.data.page && $('#divPage input').attr('max') >= page){
		getNew({page: page});
	}
}

function getNew(p){
	if(_is_loading){
		return;
	}
	if(p){
		if(p.page) _o_.data.page = p.page;
		if(typeof p.type != 'undefined') _o_.data.type = p.type;
	}

	_o_._list.html('<div id="divLoading" class="g-loading-2014"><i></i></div>');
	var _is_loading = true;

	$.get('', _o_.data, function(j){
		console.log(j);
		_o_._list.html(_o_._export(j));
		$('#divPage').html(getPageHtml(
			j.total, j.row, j.page, 5, function(g){
				return "javascript:getNew({page:"+ g +"});";
			}
		));
		$('#lotteryCount').html(j.show_total);
		_is_loading = false;
	});
}


倒计时(function(id, that){
	var id = id, that = that;
	$(that).parent().html('获取中...');
	
	setTimeout(function(){
		getNewOne(id);
	}, 1000);
});

function getNewOne(id){
	var id = id;
	if(!id) return;
	$.post(_o_.getOne, {id: id}, function(j){
		console.log(j);
		
		var $dom = $('.dom-sleep[data-id='+ id +']');
		if($dom.hasClass('m-lottery-bor-rb')){
			$dom.toggleClass('m-lottery-special m-lottery-bor-rb');
		}
		
		$dom.html(_o_._export({list:[j.info]}))
			.toggleClass('m-lottery-list m-lottery-anning m-anning-height m-lottery-list');
	})
}
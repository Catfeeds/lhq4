template.helper('date', _date);

function ajaxGetListInit(list, tpl, call){
	var _page = 0, _max_page = 1, _is_loading = false, _call = call || function(){},
		$_loading = $('.list-loading'), tpl = tpl || '.tpl-list', list = list || '.goodList ul:first',
		$_list = $(list), _export = template.compile($(tpl).html(), {escape: false});

	ajaxGetList();

	$(window).on('scroll', function(){
		if($(document).scrollTop() >= $(document).height() - $(window).height()-300){
			var str ='';
			ajaxGetList();
		}
	});


	function ajaxGetList(){
		var p = p || _page + 1;

		if(p > _max_page || _is_loading) return;
		_is_loading = true;

		$_loading.show();
		$.get('', {page: p}, function(j){

			_page = j.page
			_max_page = j._max_page

			if(j.page >= j.max_page)
				$(window).off('scroll');
			_is_loading = false;

			if(j.list.length < 1){
				$_list.append('<li class="tc mt15 f16">'+ (p == 1 ? '暂无信息' : '&nbsp;') +'</li>');
			}else{
				$_list.append(_export(j));
			}
			_call(j);
			$_loading.hide();
		})
	}
}

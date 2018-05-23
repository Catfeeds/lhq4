// 商品管理
var tpl = '<tr data-id={{id}}><td>{{num}}</td> <td>{{nickname}}</td> <td>{{content}}</td> <td>{{time}}</td>'
	+ '<td><a href="javascript:toggleStatus({{id}});" title="{{a}}当前晒单">{{type}}</a></td>'
	+ '<td><a class="periods-show-edit poi">查看详情</a> <a class="periods-show-del poi">删除</a></td></tr>',
	$lists = $('.lists'), $list = $lists.find('tbody'), $page = $lists.find('.page');

api.pageInfo = api.d = {};


getList();


function getList(d){
	status = $('input[name=status]:checked').val();
	api.d = $.extend({}, api.d, d, {status: status});
	// loading();
	$list.html('');
	$.getJSON(api.list, api.d, function(j){
		$.each(j.list, function(a, b){
                        b.num = a+1;
			b.time = date('Y-m-d 周C H:i:s', b.creatdate);
			b.type = b.status == 1 ? '显示' : '关闭';
			b.a = b.status == 1 ? '关闭' : '显示';
			console.log(b);
			$list.append(tpl.tpl(b));
		});
		
		console.log(j);
		
		$page.html(page({
			total: j.total,
			page: j.page,
			row: j.row,
			status: j.status,
			href: 'javascript:getList({page:{{page}} });'
		}));
		// loading(true);
	});
}

$lists
	.on('click', '.periods-show-edit', function(){
		openWin(http_url(api.edit, 'id='+ $(this).parents('[data-id]:first').data('id')), 'edit');
	})
	.on('click', '.periods-show-del', function(){
		var $tr = $(this).parents('[data-id]:first'),
			id = $tr.data('id') , title = $tr.find('td:eq(1)').text();
		if(confirm('确认删除  '+ id +' -- '+ title +' 的晒单 ?')){
			$.post(api.del, {id: id}, function(j){
				if(j.code == 200) getList();
			});
		}
	});


// 分类切换
$('.goods-type').on('change', function(){
	var $t = $(this), type = $t.val();
	getList({typeId: type});
})

// 状态切换
$('input[name=status], .periods-show-status').on('click', function(){
	setTimeout(getList, 200);
});

$('.search').on('click', searchKeyWord);
$('input.keyword').on('keyup', function(e){
	console.log(e.code)
	e.keyCode == 13 && searchKeyWord();
});




function searchKeyWord(){
	var keyword = $('.keyword').val().replace(/(^\s*|\s*$)/g, '');
	if(keyword == '' || api.keyword == keyword) return;
	api.keyword == keyword;

	getList({page: 1, keyword: keyword});	
}

function toggleStatus(id){
	$.post(api.toggleStatus, {id: id}, function(){
		getList();		
	});
}





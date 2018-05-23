// 文章管理
var tpl = '<tr data-id={{id}}><td>{{id}}</td> <td>{{title}}</td>  <td>{{time}}</td> <td><a href="javascript:togglearticle({{id}}, {{status}});" title="{{a}}当前文章">{{type}}</a></td>'
	+ '<td><a class="goods-edit poi">编辑</a> <a class="goods-del poi">删除</a></td></tr>',
	$goods = $('.article-list'), $list = $goods.find('tbody'), $page = $goods.find('.page');

api.pageInfo = api.d = {};


getList();


function getList(d){
	status = $('input[name=status]:checked').val();
	api.d = $.extend({}, api.d, d, {status: status});
	// loading();
	$list.html('');
	
	$.getJSON(api.list, api.d, function(j){
		$.each(j.article, function(a, b){
			b.time = date('Y-m-d 周C H:i:s', b.creatdate);
			b.type = b.status == 1 ? '显示' : '隐藏';
			b.a = b.status == 1 ? '隐藏' : '开启';
			$list.append(tpl.tpl(b));
		});
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

$goods
	.on('click', '.goods-edit', function(){
		openWin(http_url(api.edit, 'id='+ $(this).parents('[data-id]:first').data('id')), 'edit');
	})
	.on('click', '.goods-del', function(){
		var $tr = $(this).parents('[data-id]:first'),
			id = $tr.data('id'), title = $tr.find('td:eq(1)').text();
		if(confirm('确认删除  '+ id +' -- '+ title +' 的文章 ?')){
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
$('input[name=status], .goods-status').on('click', function(){
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

function togglearticle(id){
	$.post(api.togglearticle, {id: id}, function(){
		getList();		
	});
}





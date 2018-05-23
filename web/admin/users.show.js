// 用户管理
var tpl = '<tr data-id={{member_id}}><td>{{member_id}}</td> <td>{{nickname}}</td> <td>{{phone}}</td> <td>{{addr}}</td><td>{{balance}}</td>'
	+ '<td>{{share_num}}</td><td>{{share_com}}</td><td>{{creatdate}}</td>'
	+ '<td><a href="javascript:toggleStatus({{id}});" title="{{a}}当前用户">{{type}}</a></td>'
	+ '<td><a class="periods-show-edit poi">详情/编辑</a> <a class="periods-show-chongzhi poi">充值</a> <a class="periods-show-del poi">删除</a></td></tr>',
	$lists = $('.lists'), $list = $lists.find('tbody'), $page = $lists.find('.page');


api.pageInfo = api.d = {};


getList();


function getList(d){
	var status = $('input[name=status]:checked').val();
	api.d = $.extend({}, api.d, d, {status: status});
	// loading();
	$list.html('');

	$.getJSON(api.list, api.d, function(j){

		console.log(j);
		$.each(j.list, function(a, b){
			b.creatdate = date('Y-m-d 周C H:i:s', b.creatdate);
			b.type = b.subscribe == 1 ? '可用' : '禁用';
			b.a = b.subscribe == 1 ? '禁用' : '可用';
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

$lists

	//编辑
	.on('click', '.periods-show-edit', function(){
		openWin(http_url(api.edit, 'member_id='+ $(this).parents('[data-id]:first').data('id')), 'edit');
	})
	//充值
	.on('click', '.periods-show-chongzhi', function(){
		
//		index=layer.open({
//			type:2,
//			title:false,
//			area:["400px","250px"],
//			shade:[0.6,'#000'],
//			content:http_url(api.chongzhi, 'id='+ $(this).parents('[data-id]:first').data('id'))
//		});
		openWin(http_url(api.chongzhi, 'id='+ $(this).parents('[data-id]:first').data('id')), 'edit');
	})
	//删除
	.on('click', '.periods-show-del', function(){
		var $tr = $(this).parents('[data-id]:first'),
			id = $tr.data('id'), title = $tr.find('td:eq(1)').text();
		if(confirm('确认删除  '+ id +' -- '+ title +' 的用户 ?')){
			$.post(api.del, {id: id}, function(j){				
				
				if(j.code == 200) {
					getList();					
				}else{
					layer.msg(j.msg);
				}
			});
		}
	});

// 状态切换
$('input[name=status], .periods-show-status').on('click', function(){
	setTimeout('getList()', 200);
});

$('.search').on('click', searchKeyWord);
$('input.keyword').on('keyup', function(e){
	console.log(e.code);
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
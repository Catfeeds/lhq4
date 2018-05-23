
//显示数据的<tr>
var tpl = '<tr data-id={{id}} style="text-align:center; "><td><input class="xuhao w70" style="background: none; text-align:center;" type="text" value="{{taxis}}"></input></td><td>{{id}}</td> <td>{{typename}}</td> <td>{{description}}</td>'
	+ '<td><a href="javascript:toggleArticleType({{id}}, {{status}});" title="{{a}}当前文章类型">{{type}}</a></td>'
	+ '<td><a class="article-edit poi">编辑</a> <a class="article-del poi">删除</a></td></tr>';

//显示列表的div
var	$article = $('.article-list');

//显示数据的body体
var	$list = $article.find('tbody');

//显示页码
var	$page = $article.find('.page');

api.pageInfo = {};

//异步请求要发送的数据
api.data = {};

function getList(data){
	
	sta = $('input[name=status]:checked').val();	
	api.data = $.extend( {}, api.data, data, {status: sta} );	
	$list.html( ' ' );
	
	$.getJSON(
			api.list,		 //请求地址
			api.data,		 //发送的数据
			function(jdata){		//回调函数
				$.each( jdata.article_type , function(name, val){
					val.type = val.status == 1 ? '开启' : '关闭';					
					$list.append(		tpl.tpl(val)	); 		//tpl()封装在__LIB_URL__/ja.js
				});
				
				var p={
					total: jdata.total,
					page: jdata.page,
					row: jdata.row,					
					status: jdata.status,					
					href: 'javascript:getList({page:{{page}}});'
				}
				$page.html( page(p)	);	//page()封装在__LIB_URL__/ja.js
			});
}
getList(  );


$article
	.on('click', '.article-edit', function(){
		openWin( http_url(api.edit, 'id='+ $(this).parents('[data-id]:first').data('id')), 'edit' );
	})
	.on('click', '.article-del', function(){
		var $tr = $(this).parents('[data-id]:first'),
			id = $tr.data('id'), title = $tr.find('td:eq(1)').text();
		if(confirm('确认删除  '+ id +' -- '+ title +' 的文章类型 ?')){
			$.post(api.del, {id: id}, function(j){
				if(j.code == 200) getList();
			});
		}
	});


// 分类切换
$('.article-type').on('change', function(){
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


//gd
//关键词搜索
function searchKeyWord(    ){
	var keyword = $('.keyword').val().replace(/(^\s*|\s*$)/g, '');//去除前后空白
	if(keyword == '' || api.keyword == keyword) return;//关键词为空活为上次的, 不查询
	api.keyword == keyword;//将关键词放入api对象
	var where={page: 1, keyword: keyword}	;
	getList(	where );	//获取符合条件的列表
}

//gd
//切换商品类型
function toggleArticleType( id ){
	$.post(
			api.toggleArticleType,
			{id: id},
			function(){
				getList();
			}
	);
}

//修改序号
$(".article-list").on("change",".xuhao",function(){
	 var taxis=parseInt($(this).val());
	 var id=$(this).parents('[data-id]:first').data('id');
	$.post(
			api.edit,
			{taxis:taxis,id:id,flag:1},
			function(){
				getList();
			}	
	);
});

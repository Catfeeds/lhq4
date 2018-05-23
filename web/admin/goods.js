// 商品管理
var tpl = '<tr data-id={{id}}><td><input class="dom-check" type="checkbox" name="ids[]" value="{{id}}"></td> <td>{{id}}</td> <td>{{title}}</td> <td class="tc">{{canyushu}} / {{fenshu}}</td> <td class="tc">{{qishu}}</td> <td>{{time}}</td>'
	+ '<td><a class="btn btn-{{type_class}} btn-xs" href="javascript:toggleGoods({{id}}, {{status}});" title="{{a}}当前商品">{{type}}</a></td>'
	+ '<td><a class="tuijian btn btn-{{type_tuijian}} btn-xs">{{tuijian}}</a></td>'
	+'<td><a class="goods-edit poi btn btn-default btn-xs">编辑</a> <a class="goods-del poi btn btn-danger btn-xs">删除</a></td></tr>',
	$goods = $('.goods-list'), $list = $goods.find('tbody'), $page = $goods.find('.page');

api.pageInfo = api.d = {};


getList();


function getList(d){
	status = $('input[name=status]:checked').val();
	api.d = $.extend({}, api.d, d, {status: status});
	// loading();
	$list.html('<tr><td colspan=30><i class="fa fa-spinner fa-pulse fa-4x"></i></td></tr>');
	
	$.getJSON(api.list, api.d, function(j){
		$list.html('');
		$.each(j.goods, function(a, b){
			b.time = date('Y-m-d 周C H:i:s', b.creatdate);
			b.tuijian=b.recommend==1?'已推荐':'未推荐';
			b.type_tuijian=b.recommend==1?'success' : 'danger';
			b.type = b.status == 1 ? '在售' : '关闭';
			b.type_class = b.status == 1 ? 'success' : 'danger';
			b.a = b.status == 1 ? '关闭' : '开启';
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
	.on('click', '.goods-zero-2', function(){
		openWin(http_url(api.zero, 'id='+ $(this).parents('[data-id]:first').data('id')), 'edit');
	})
	.on('click', '.goods-zero-1', function(){
		layer.alert('本商品0元购正在进行中，请结束后，再加入0元购！');
	})
	.on('click', '.tuijian', function(){
		$.post(api.switchRecommend,{gid:$(this).parents('[data-id]:first').data('id')},function(rel){
			getList();
		});
		//layer.alert('推荐成功！'+$(this).parents('[data-id]:first').data('id'));
	})
	.on('click', '.goods-del', function(){
		var $tr = $(this).parents('[data-id]:first'),
			id = $tr.data('id'), title = $tr.find('td:eq(1)').text();
		if(confirm('确认删除  '+ id +' -- '+ title +' 的商品 ?')){
			$.post(api.del, {id: id}, function(j){
				if(j.code == 200) getList();
			});
		}
	});

$(document).on('click', '.goods-add,a[href*="'+ api.add +'"]', function(){
	openWin(api.add, 'edit');
	return false;
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

function toggleGoods(id){
	$.post(api.toggleGoods, {id: id}, function(j){
		if(j.code != 1){
			msg(j.msg, 'warning');
		}else{
			getList();
		}
	});
}


$(function () {  
            $("#checkall").click(function () {
                
                 $("td :checkbox").prop("checked", true);  
            });  
  
            $("#checknoall").click(function () { 
                 $("td :checkbox").removeProp("checked");   
            });  
        });
        
        function dellist(){
            var checkedNum = $("td :checked").length; 
                if(checkedNum == 0) { 
                alert("请选择至少一项！"); 
                return; 
            } 
// 批量选择 
            if(confirm("确定要删除所选项目？")) { 
            var idlist = new Array(); 
            $("td :checked").each(function() { 
                idlist.push($(this).val()); 
            });
            //console.log(api.listdel);return;
            $.post(api.listdel,{idlist:idlist},function(){                                
                getList();                           
            });
        }
    }



/*
 * 中奖用户相关的js
 * 
 * */

$(function() {

	//	中奖用户文本框获得焦点
	$(".userc").focus(function() {

		//		清空文本框,将原值保存
		$(this).data('oldVal', $(this).val());
		$(this).val("");

		//		获取商品id和期数				
		goodsid = $.trim($(this).parent().parent().children(".gid").html());
		qishu = $.trim($(this).parent().parent().children(".qishu").html());

	});

	//	用户昵称自动完成
	$('.userc').autocomplete(
			{
				autoFocus : true,
				delay : 200,
				minLength : 1,

				source : function(request, response) {

					//			取回符合条件的用户编号,昵称,电话
					$.post(getUserUrl, {
						goodsId : goodsid,
						qishu : qishu,
						nickname : request.term,
					}, function(j) {
						response($.map(j, function(item) {

							userid = item.id;
							return {
								id : item.id,
								label : item.id + " -- " + item.nickname
										+ " -- " + item.phone,
							}
						}));
					}, 'json');
				},

				select : function(a, b) { // 选择 后操作
					console.log(b.item.value);

					//			异步获取本期数本商品本用户的一个中奖码		
					$.post(setWinCodeUrl, {
						goodsid : goodsid,
						qishu : qishu,
						userid : userid,
					}, function() {
						window.location.reload();
					}

					);

					//			修改失去焦点时的值
					$(".userc").data('oldVal', b.item.value);

				},

			});

	//	切换状态
	$(".btn").click(function() {
		var status = $(this).find("input").attr("value");		
		window.location.href = selfUrl+"&status=" + status;
	});

	//	失去焦点时恢复值
	$(".userc").blur(function() {
		$(this).val($(this).data('oldVal'));
	});	
	
//	function GetQueryString(name){
//	     var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
//	     var r = window.location.search.substr(1).match(reg);
//	     if(r!=null)return  unescape(r[2]); return null;
//	}
//	 
//	// 调用方法
//	alert(GetQueryString("status"));
	
	
});
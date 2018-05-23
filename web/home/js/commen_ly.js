/**
 * top/header/nav/footer/bottom/rightCart通用js
 */


$(function(){
	if (window.innerHeight)
		winHeight = window.innerHeight;
	else if ((document.body) && (document.body.clientHeight))
		winHeight = document.body.clientHeight;
	
	//顶端时不显示购物车栏
	/*$(window).scroll(function(){
		if($(document).scrollTop()==0){
			$('#rightTool').fadeOut();
		}else{
			$('#rightTool').fadeIn();
		}
	});*/
	$('#rightTool').show();
	
	/*
	 * top.html
	 */
	$('.u-arr,.u-arr-1yyg,.u-arr-news').hover(function(){
		$(this).addClass('u-arr-hover');
	},function(){
		$(this).removeClass('u-arr-hover');
	});
	
	/*
	 * header.html
	 
	$('#txtHSearch').focus(function(){
		$(this).val('');
		$(this).siblings().children().hide();
	});
	$('#txtHSearch').blur(function(){
		$(this).val('输入“汽车”试试');
		$(this).siblings().children().show();
	});
	*/
	getTotalBuy();
	setInterval(getTotalBuy,5000);
	
	
	/*
	 * nav.html
	 */
	if($('#divSortList').css('display')=='none'){
		$('#divGoodsSort').hover(function(){
			$('#divSortList').show();
		},function(){
			$('#divSortList').hide();
		});
	}
	
	/*
	 * rightCart.html
	 */
	//购物车弹出栏
	//列表上栏高33px，低栏高62px
	var height = winHeight-33-62;
	var height2 = winHeight-33;
	$('.f-shopping-cart').mouseover(function(){
		$('#divRTCartMain').height(winHeight).show();
		$('#ulRToolList').children('li:first').children().children('b').show();
		$('#cart_box').height(height).css('overflow','scroll');
	});
	
	$('#divRTCartMain').hover(function(){
		$(this).show();
		$('#ulRToolList').children('li:first').children().children('b').show();
	},function(){
		$(this).hide();
		$('#ulRToolList').children('li:first').children().children('b').hide();
	});
	
	$('#ulRToolList li').not('.f-shopping-cart').hover(function(){
		$('#divRTCartMain').hide();
		$('#ulRToolList').children('li:first').children().children('b').hide();
	});
	
	//我的关注弹出栏
	$('.f-attention').hover(function(){
		$('#colect_box').height(height2);
		$('#divRTColect').height(winHeight).show();
		$('.f-attention').addClass('cart-hover');
	},function(){
		//$('#divRTColect').hide();
		$('#divRTColect').hover(function(){
			$(this).show();
			$('.f-attention').addClass('cart-hover');
		},function(){
			$(this).hide();
			$('.f-attention').removeClass('cart-hover');
		});
	});
	$('#ulRToolList li').not('.f-attention').hover(function(){
		$('#divRTColect').hide();
		$('.f-attention').removeClass('cart-hover');
	});
	
	//下载客户端、关注官方微信、在线客服、意见反馈、置顶
	$('.f-client,.f-weixin,.f-customer-service,.f-feedback,.f-back-to').hover(function(){
		$(this).addClass('cart-hover');
	},function(){
		$(this).removeClass('cart-hover');
	});
	
	//删除
	/*$('.delete-close').click(function(){
		alert('a');
		$(this).parent().parent().remove();
		countPrice();
	});*/
	
	countPrice();
	
	
	//分页
	$('#divPage').find('input').mouseover(function(){
		$(this).select();
	});
	$('#divPage').find('input').bind('input propertychange',function(){
		var num = parseInt($(this).val());
		var totalPage = parseInt($('#totalPage').val());
		if(parseInt(num) >= totalPage){
			$(this).val(totalPage);
		}else{
			if(num==1||num==0||num==''||isNaN(parseInt(num))){
				$(this).val(1);
			}else{
				$(this).val(num);
			}
		}
	});
	
});

/*
 * 获取累计参与人次
 */
function getTotalBuy(){
	$.get(totalURL,function(data){
		for(var key in data){
			var totalBuy = data[key].totalBuy;
			//var totalBuy = Math.ceil(Math.random()*1000000000);
			//最新的
			var a = totalBuy%10,//个位
			b = (totalBuy%100-a)/10,//十位
			c = (totalBuy%1000-totalBuy%100)/100,//百位
			d = (totalBuy%10000-totalBuy%1000)/1000,//千位
			e = (totalBuy%100000-totalBuy%10000)/10000,//万位
			f = (totalBuy%1000000-totalBuy%100000)/100000,//十万位
			g = (totalBuy%10000000-totalBuy%1000000)/1000000,//百万位
			h = (totalBuy%100000000-totalBuy%10000000)/10000000,//千万位
			i = (totalBuy%1000000000-totalBuy%100000000)/100000000;//亿位
			//alert(i+'----'+h+'----'+g+'----'+f+'----'+e+'----'+d+'----'+c+'----'+b+'----'+a);
			
			/*var indexs = new Array();
			$('#ulHTotalBuy').children('.num').each(function(){
				var top = $(this).children('cite').css('top');
				var index = -parseInt(top.split('p')[0])/27;//每个数显示的第几个
				indexs.push(index);	
			});*/
			
			//目前显示的
			var ii = parseInt($('#ulHTotalBuy').children('.num:eq(0)').find('em:eq(9)').text()),//亿位;
			hh = parseInt($('#ulHTotalBuy').children('.num:eq(1)').find('em:eq(9)').text()),//千万位;
			gg = parseInt($('#ulHTotalBuy').children('.num:eq(2)').find('em:eq(9)').text()),//百万位;
			ff = parseInt($('#ulHTotalBuy').children('.num:eq(3)').find('em:eq(9)').text()),//十万位;
			ee = parseInt($('#ulHTotalBuy').children('.num:eq(4)').find('em:eq(9)').text()),//万位;
			dd = parseInt($('#ulHTotalBuy').children('.num:eq(5)').find('em:eq(9)').text()),//千位;
			cc = parseInt($('#ulHTotalBuy').children('.num:eq(6)').find('em:eq(9)').text()),//百位;
			bb = parseInt($('#ulHTotalBuy').children('.num:eq(7)').find('em:eq(9)').text()),//十位;
			aa = parseInt($('#ulHTotalBuy').children('.num:eq(8)').find('em:eq(9)').text());//个位;
			//alert(ii+'----'+hh+'----'+gg+'----'+ff+'----'+ee+'----'+dd+'----'+cc+'----'+bb+'----'+aa);
			
			changeNum(1,ii,i);
			changeNum(2,hh,h);
			changeNum(3,gg,g);
			changeNum(4,ff,f);
			changeNum(5,ee,e);
			changeNum(6,dd,d);
			changeNum(7,cc,c);
			changeNum(8,bb,b);
			changeNum(9,aa,a);
			
		}
	},'json');
}


/**
 * 累计参与人次：从一个数字变为另一个数字
 * @param no 第几个数字要改，如亿位是1，个位是9
 * @param bofore 之前的数
 * @param after 现在的数
 */
function changeNum(no,before,after){
	
	if(before < after) 
		var i = after - before;
	else if(before > after) 
		var i = after - before + 10;
	else return;
	var top = (27*i-243)+'px';
	$('#ulHTotalBuy').children('.num:eq('+(no-1)+')').children('cite').animate({
		top:top
	},2000,function(){
		$('#ulHTotalBuy').children('.num:eq('+(no-1)+')').children('cite').empty();
		for(var i=0;i<=9;i++){
			if(after+i<=9)
				var em = '<em>'+(after+i)+'</em>';
			else
				var em = '<em>'+(after+i-10)+'</em>';
			$('#ulHTotalBuy').children('.num:eq('+(no-1)+')').children('cite').prepend(em);
		}
		$('#ulHTotalBuy').children('.num:eq('+(no-1)+')').children('cite').css('top','-243px');
	});
	
	
}


/*
 * 算数
 */
function countPrice(){
	var totalCount = 0;
	var totalPrice = 0;
	$('.z-ygrc').each(function(){
		if($(this).children().length==2){
			totalCount++;
			var str = $(this).children('.two-row').children('cite:first').text();
			var price = parseInt(str.substr(5));
			totalPrice += price;
		}
	});
	$('#totalCount').html($('.z-ygrc').length);
	$('#totalCount2').html($('.z-ygrc').length);
	$('#totalPrice').html(parseFloat(totalPrice).toFixed(2));
}

function delOneCartPro(obj, goodsId, qishu){
	
	//var url = "{:U('Cart/deleteCartByGoodsId')}";
	var param = new Object();
	param.goodsId = goodsId;
	param.qishu = qishu;
	
	//console.log(goodsId + "," + qishu);
	
	$.ajax({
        type:'post',
        url:dUrl,
        data:param,
        success:function(data){
        	if(data['status']){
        		$(obj).parent().parent().remove();
        		countPrice();       		
        	}else{
        		layer.msg(data['info']);
        	}
        },
        dataType:"JSON"
	})
	
	

}




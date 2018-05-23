/**
 * 正在揭晓页面
 */
function lxfEndtime() {
	$(".lxftime").each(function() {
		var i = $(this).index('.lxftime');//第几个倒计时
		var lxfday = $(this).attr("lxfday"); //用来判断是否显示天数的变量
		
		//现在时间
		var str = $(this).attr("nowtime");
		var strs = str.split(',');
		var nowtime = new Date(strs[0],strs[1],strs[2],strs[3],strs[4],strs[5],strs[6]);
		
		//结束时间
		var str2 = $(this).attr("endtime");
		var strs2 = str2.split(',');
		var endtime = new Date(strs2[0],strs2[1],strs2[2],strs2[3],strs2[4],strs2[5],strs2[6]);
		
		//剩余时间
		var youtime = endtime - nowtime; //还有多久(毫秒值)
		
		var seconds = youtime / 1000;
		var minutes = Math.floor(seconds / 60);
		var hours = Math.floor(minutes / 60);
		var days = Math.floor(hours / 24);
		var CDay = days;
		var CHour = hours % 24;
		var CMinute = minutes % 60;
		var CSecond = Math.floor(seconds % 60); //"%"是取余运算，可以理解为60进一后取余数，然后只要余数。
		var CMSecond = Math.floor(seconds * 100 % 100); // 计算100毫秒
		
		if (endtime <= nowtime) {
			
			/*
			 * 1.倒计时结束后变为正在计算中...
			 */
			var $product = $(this).parent().parent().parent().parent().parent();
			$(this).parent().html('<strong>正在计算中....</strong>').animate({height:'45px'},'slow');
			
			/*
			 * 2.将倒计时结束的商品变为已揭晓样式商品
			 */
			//模拟获取数据之前的延迟
			setTimeout(function(){
				
				var imgSrc = $product.find('.f-lott-comm').find('img').prop('src');
				var goodsName = $product.find('dt').find('a').text();
				var goodsPrice = $product.find('dd:eq(0)').text();
				
				var str = '<ul>'+
			       '<li class="f-lott-comm"><a title="'+goodsName+'" target="_blank" href="announced_detail.html"><img src="'+imgSrc+'"></a></li>'+
			       '<li class="f-lott-detailed">'+
			        '<div class="u-user-info">'+
			         '<p class="fl"><a title="15546****98" target="_blank" href="announced_detail.html"><img src="'+imageURL+'20120913170030105.jpg" type="userPhoto">'+
			           '<s></s></a></p>'+
			         '<dl class="fl">'+
			          '<dt>'+
			           '<em>获得者：</em>'+
			           '<span><a title="15546****98" target="_blank" href="announced_detail.html">15546****98</a></span>'+
			          '</dt>'+
			          '<dd class="z-lott-lz">'+
			           '来自：广东省广州市'+
			          '</dd>'+
			          '<dd>'+
			           '幸运云团码：'+
			           '<strong class="orange">10003902</strong>'+
			          '</dd>'+
			          '<dd>'+
			           '本云参与：'+
			           '<i class="orange">5</i>人次'+
			          '</dd>'+
			         '</dl>'+
			        '</div>'+
			        '<div class="u-comm-info">'+
			         '<dl>'+
			          '<dt>'+
			           '<a title="'+goodsName+'" target="_blank" href="announced_detail.html">'+goodsName+'</a>'+
			          '</dt>'+
			          '<dd>'+
			           ''+goodsPrice+''+
			          '</dd>'+
			          '<dd>'+
			           '揭晓时间：刚刚'+
			          '</dd>'+
			          '<dd class="z-lott-btn">'+
			           '<span><a title="查看详情" target="_blank" href="announced_detail.html">查看详情</a></span>'+
			          '</dd>'+
			         '</dl>'+
			        '</div>'+
			       '</li>'+
			      '</ul>' ;
				
				
				
				//class属性先按两个的赋值，特殊的用checkClass方法赋值
				$product.removeClass().addClass('m-lottery-list').html(str).attr('type','isRaff');
				
				
				/*
				 * 3.删除已揭晓列表最后一个
				 */
				var goodsLen = $('#divLottery').children().length;
				$('#divLottery').children(':eq('+(goodsLen-1)+')').remove();
				
				
				/*
				 * 4.正在揭晓第一个再加一个
				 */
				$('#divLottery').children(':eq(0)').addOneCounting();
				
				checkClass();
				
			},2000);
			
			
			//$('.millisecond_show').eq(i).html('<s></s>' + '00');
		} else {
			if(CMinute<=9)
				$('.minute_show').eq(i).html('<s></s>' + '0' + CMinute);
			else
				$('.minute_show').eq(i).html('<s></s>' + CMinute);
			
			if(CSecond<=9)
				$('.second_show').eq(i).html('<s></s>' + '0' + CSecond);
			else
				$('.second_show').eq(i).html('<s></s>' + CSecond);
			
			if(CMSecond<=9)
				$('.millisecond_show').eq(i).html('<s></s>' + '0' + CMSecond);
			else
				$('.millisecond_show').eq(i).html('<s></s>' + CMSecond);
			//当前时间变化
			$(this).attr("nowtime", zhtime(nowtime.getTime() + 52));
		}
	});
	setTimeout("lxfEndtime()", 52);
}

function zhtime(needtime) {
	//console.log(needtime);
	var oks = new Date(needtime);
	var year = oks.getFullYear();
	var month = oks.getMonth();
	var date1 = oks.getDate();
	var hour = oks.getHours();
	var minute = oks.getMinutes();
	var second = oks.getSeconds();
	var msecond = oks.getMilliseconds();
	return year + ',' + month + ',' + date1 + ',' + hour + ',' + minute + ',' + second + ',' + msecond;
	
}


$.fn.addOneCounting = function(){
	var str = '<div class="m-lottery-list m-lottery-anning m-anning-height ">'+
    '<ul>'+
    '<li class="f-lott-comm"><a title="伊利 安慕希常温酸牛奶 205mlx12盒 礼盒装" target="_blank" href="announcing_detail2.html"><img src="'+imageURL+'20150605165935473.jpg" /></a></li>'+
   '<li class="f-lott-detailed">'+
     '<dl>'+
      '<dt>'+
       '<a title="(第30952云)伊利 安慕希常温酸牛奶 205mlx12盒 礼盒装" target="_blank" href="announcing_detail2.html">(第30952云)伊利 安慕希常温酸牛奶 205mlx12盒 礼盒装</a>'+
      '</dt>'+
      '<dd>'+
       '商品价值：￥66.00'+
      '</dd>'+
      '<dd class="z-ymy">'+
       '已满员'+
      '</dd>'+
      '<dd class="z-jx-time">'+
       '<p>揭晓倒计时</p>'+
       '<div class="lxftime" nowtime="2015,11,13,12,58,00,100" endtime="2015,11,13,12,59,00,0"></div>'+
       '<cite><span class="minute minute_show"></span><em>:</em><span class="second second_show"></span><em>:</em><span><i class="millisecond millisecond_show"></i><i class="last"></i></span></cite>'+
      '</dd>'+
     '</dl></li>'+
   '</ul>'+
   '<b class="transparent-png"></b>'+
  '</div>';
	$(this).before(str);
	
	checkClass();
	
}

/*
 * 正在揭晓：每行第一个 m-lottery-list m-lottery-anning m-anning-height，后两个多个m-lottery-bor-rb
 * 已揭晓：每行第一个m-lottery-list m-lottery-special，后两个没有m-lottery-special
 */

function checkClass(){
	$('#divLottery').children().each(function(){
		
		var index = $(this).index()+1;
		
		//正在揭晓商品
		if($(this).hasClass('m-lottery-anning')){
			
			//每行第一个
			if(index%3 == 1){
				$(this).removeClass('m-lottery-bor-rb');
			}
			//后两个
			else{
				$(this).addClass('m-lottery-bor-rb');
			}
			
		}
		//已揭晓商品
		else{
			
			//每行第一个
			if(index%3 == 1){
				$(this).addClass('m-lottery-special');
			}
			//后两个
			else{
				$(this).removeClass('m-lottery-special');
			}
			
		}
	});
}





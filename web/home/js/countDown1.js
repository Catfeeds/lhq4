/**
 * 主页
 */
function lxfEndtime() {
	$(".lxftime").each(function() {
		var i = $(this).index('.lxftime');
		var lxfday = $(this).attr("lxfday"); //用来判断是否显示天数的变量
		
		var str = $(this).attr("nowtime");
		var strs = str.split(',');
		var nowtime = new Date(strs[0],strs[1],strs[2],strs[3],strs[4],strs[5],strs[6]);
		
		var str2 = $(this).attr("endtime");
		var strs2 = str2.split(',');
		var endtime = new Date(strs2[0],strs2[1],strs2[2],strs2[3],strs2[4],strs2[5],strs2[6]);
		
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
			
			$.ajax({
	            type:'post',
	            url:newPublishUrl,
	            success:function(data){
	            	if(data){
		    			var html = template('new_publish', data);
		    			document.getElementById('ul_Lottery').innerHTML = html;	 	    
		    			
		    			$('#em_lotcount').html(data['total']);
		    			
	            	}
	            	if(data['list'].length == 0){
	            		
	            		document.getElementById('ul_Lottery').innerHTML = '暂无数据';	
	            	}
	            },
	            dataType:"JSON"
			})
			
/*			var goodsLen = $('#divLottery').children().length;
			$('#ul_Lottery').children(':eq('+(goodsLen-1)+')').remove();
			$('#ul_Lottery').children(':eq(0)').addOneCounting();*/
			
			//checkClass();
			
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
	
	var str = '<li class="current">'+
  '<dl class="m-in-progress">'+
	'<dt>'+
		'<a title="(第57423云) 苹果（Apple）iPhone 6 A1586 16G版 4G手机" target="_blank" href="announcing_detail2.html"><img src="'+imageURL+'20140910144439506.jpg" alt="(第57423云) 苹果（Apple）iPhone 6 A1586 16G版 4G手机" /></a>'+
	'</dt>'+
	'<dd class="u-name">'+
		'<a title="(第57423云) 苹果（Apple）iPhone 6 A1586 16G版 4G手机" href="announcing_detail2.html">(第57423云) 苹果（Apple）iPhone 6 A1586 16G版 4G手机</a>'+
	'</dd>'+
	'<dd class="gray">'+
		'价值：￥4588.00'+
	'</dd>'+
	'<div class="lxftime" nowtime="2015,11,13,12,58,35,100" endtime="2015,11,13,12,59,00,0"></div>'+
	'<dd id="dd_time" class="u-time">'+
		'<em>揭晓倒计时</em>'+
		'<span><b class="minute_show">00</b> : <b class="second_show"></b> : <b class="millisecond_show"><i></i><i>3</i></b></span>'+
	'</dd>'+
  '</dl>'+
  '<s class="transparent-png"></s>'+
'</li>';
	
	$(this).before(str);
	
	checkClass();
	
}

/*
 * 正在揭晓：每行第一个 m-lottery-list m-lottery-anning m-anning-height，后两个多个m-lottery-bor-rb
 * 已揭晓：每行第一个m-lottery-list m-lottery-special，后两个没有m-lottery-special
 */

function checkClass(){
	$('#ul_Lottery').children().each(function(){
		
		var index = $(this).index()+1;
		
		if(index == 5){
			$(this).addClass('current2');
		}
		
	});
}






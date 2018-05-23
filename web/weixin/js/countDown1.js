var identify=false;
function lxfEndtime() {
	$(".lxftime").each(function() {
		var i = $(this).index('.lxftime');
		var lxfday = $(this).attr("lxfday"); //用来判断是否显示天数的变量
		var endtime = Date.parse(new Date(parseInt($(this).attr("endtime"))*1000));//取结束日期(毫秒值)
		//var endtime = new Date($(this).attr("endtime")).getTime(); //取结束日期(毫秒值)
		var nowtime = Date.parse(new Date());//取结束日期(毫秒值)
	
		var youtime = endtime - nowtime; //还有多久(毫秒值.
		
		var seconds = youtime /1000;
		var minutes = Math.floor(seconds / 60);
		var hours = Math.floor(minutes / 60);
		var days = Math.floor(hours / 24);
		var CDay = days;
		var CHour = hours % 24;
		var CMinute = minutes % 60;
		var CSecond = Math.floor(seconds % 60); //"%"是取余运算，可以理解为60进一后取余数，然后只要余数。
		var CMSecond = Math.floor(seconds * 100 % 100); // 计算100毫秒
		if (endtime <= nowtime) {
			if(identify)return;
			identify=true;
			$.post(modifystatusurl,{id:baocid})
			$('strong.millisecond_show').parent().html("该包场已揭晓,请在'已揭晓'中查看...");
		} else {
			if(CMinute<=9)
				$('strong.minute_show').eq(i).html('<s></s>' + '0' + CMinute);
			else
				$('strong.minute_show').eq(i).html('<s></s>' + CMinute);
			
			if(CSecond<=9)
				$('strong.second_show').eq(i).html('<s></s>' + '0' + CSecond);
			else
				$('strong.second_show').eq(i).html('<s></s>' + CSecond);
			
			if(CMSecond<=9)
				$('strong.millisecond_show').eq(i).html('<s></s>' + '0' + CMSecond);
			else
				$('strong.millisecond_show').eq(i).html('<s></s>' + CMSecond);
			//当前时间变化
			$(this).attr("nowtime", zhtime(nowtime + 100));
		}
	});
	setTimeout("lxfEndtime()", 100);
}

function zhtime(needtime) {
	var oks = new Date(needtime);
	var year = oks.getFullYear();
	var month = oks.getMonth() + 1;
	var date = oks.getDate();
	var hour = oks.getHours();
	var minute = oks.getMinutes();
	var second = oks.getSeconds();
	var msecond = oks.getMilliseconds()
	return month + '/' + date + '/' + year + ' ' + hour + ':' + minute + ':' + second + '.' + msecond;
}
function lxfEndtime(alterurl) {
	
	var _n = 0;
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
			//$(this).html("已过期"); //如果结束日期小于当前日期就提示过期啦
			//$('strong.millisecond_show').eq(i).html('<s></s>' + '00');
			
			location.href=alterurl;	
			
		} else {
			_n++;
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
			$(this).attr("nowtime", zhtime(nowtime.getTime() + 100));
			
		}
	});
	if(_n > 0)
		setTimeout("lxfEndtime('"+alterurl+"')", 100);
}

function zhtime(needtime) {
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
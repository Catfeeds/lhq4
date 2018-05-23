/**
 * 正在揭晓商品详情页面
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
			//$(this).html("已过期"); //如果结束日期小于当前日期就提示过期啦
			if(bool){//为防止重复执行,设置标志,第一次执行完毕之后就会
				return;
			}
			bool=true;
			//隐藏倒计时
			$('#ul_lotterytime').hide();
			//显示滚动条
			$('#div_eveling').show();
			//$('#imgFunny').prop('src','../images/n1.gif');

			setTimeout(function(){

				$.post('/index.php?m=Home&c=Goods&a=getOneNew',{id:id},function(res){
				if (res) {
					$('#showwinuser').removeClass();
					$('#showwinuser').html('<div class="result-main" style="\
					    width: 500px;height: 105px;padding: 20px 25px 15px 25px;margin: \
							12px auto;border-radius: 20px;background-color: #fff2b7;position: relative;overflow: hidden;">\
							<div class="result-con-info" style="width: 220px;height: 85px;padding: \
							10px 20px 10px 85px;background-color: #fff;border: 1px solid #ffba6e;\
							border-radius: 20px;float: left;text-align: left;margin-right: 9px;color: #949494;\
							font-size: 12px;line-height: 20px;position: relative;"><p class="r-name" style="\
							font-size: 12px;line-height: 24px;line-height: 20px;"><span><a href="'+shopping+'&\
							id='+res.info["winuserid"]+'" target="_blank" title="'+res.info["nickname"]+'"></a>\
							</span>获得者:(<em>'+res.info["nickname"]+'</em>)</p><p>本云参与：<span class="r-num">\
							'+res.info["nums"]+'</span>人次<a id="a_luckynum" href="javascript:getpcodedetails();" class="r-look">点击查看</a>\
							</p><p>揭晓时间：<span>'+res.info["disclosedate"]+'</span></p><p>云团时间：<span>\
							'+res.info["creat_date"]+'</span></p><div class="result-head-pic" style="width: 100px;\
							height: 100px;position: absolute;top: 12px;left: 6px;overflow: hidden;z-index: 11;">\
							<div class="rh-wrap"><img width="80" height="80" src="/t/?w=80&amp;h=80&amp;src=\
							'+res.info["facepic"]+'" style="width: 80px;height: 80px;"></div><a rel="nofollow" href=\
							"'+shopping+'&id='+res.info["winuserid"]+'" target="_blank" title="'+res.info["nickname"]+'\
							" class="ng-result-head transparent-png"></a></div></div><div class="result-con-code" style="\
							width: 160px;height: 59px;background-color: #fff;border: 1px solid #ffba6e;border-radius: 20px;\
					    float: left;text-align: center;padding: 23px 0;"><p class="code-name" style="\
					    color: #999;font-size: 16px;line-height: 24px;">— 幸运云团码 —</p><span class="code-num" style="\
							color: #de4849;font-size: 26px;line-height: 36px;"> '+res.info["winningcode"]+'</span></div></div>');
				}
			});
			},1000);

			return;
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



// alert('微信 JS 加载');

if(typeof wx != 'undefined' && typeof shareData != 'undefined' && typeof shareData != 'gshareurl'){
	// alert('获取 signature');
	$.get(gshareurl,function(result){
		wx.config({
			debug: false,
			appId:result.appId,
			timestamp:result['timestamp'] ,
			nonceStr: result['nonceStr'],
			signature: result['signature'],
			jsApiList: [
				'onMenuShareTimeline',
				'onMenuShareAppMessage',
				'onMenuShareQQ',
				'onMenuShareWeibo',
				'onMenuShareQZone',
			]
		});
	});

	wx.ready(function(){
			wx.onMenuShareAppMessage(shareData);
			wx.onMenuShareTimeline(shareData);
			wx.onMenuShareQQ(shareData);
			wx.onMenuShareWeibo(shareData);
			wx.onMenuShareQZone(shareData);
	});

}
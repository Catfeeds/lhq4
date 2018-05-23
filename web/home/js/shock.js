/**
 * 
 */

/*
 * img1 img2 img3 下落的三个图片class属性
 * top1 top2 top3 下落后的top属性值
 */
function shock(img1,img2,img3,top1,top2,top3){
	
	$('.'+img1).animate({
		top:top1+'px'
	},1000,function(){
		$(this).animate({
			top:'-=10'
		},100,function(){
			$(this).animate({
				top:'+=10'
			},100,function(){
				$('.'+img2).animate({
					top:top2+'px'
				},1000,function(){
					$(this).animate({
						top:'-=10'
					},100,function(){
						$(this).animate({
							top:'+=10'
						},100,function(){
							$('.'+img3).animate({
								top:top3+'px'
							},1000,function(){
								$(this).animate({
									top:'-=10'
								},100,function(){
									$(this).animate({
										top:'+=10'
									},100,function(){
										
									});
								});
							});
						});
					});
				});
			});
		});
	});
	
	
}

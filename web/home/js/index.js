/**
 * 只有主页使用
 */

//购物栏图标晃动
	function shock(obj, goodsId, qishu){
		
		$('#cartImg').animate({
			'left':'-=4'
		},30,function(){
			$(this).animate({
				'left':'+=8'
			},30,function(){
				$(this).animate({
					'left':'-=8'
				},30,function(){
					$(this).animate({
						'left':'+=8'
					},30,function(){
						$(this).animate({
							'left':'-=8'
						},30,function(){
							$(this).animate({
								'left':'+=4'
							},30,function(){
								
							});
						});
					});
				});
			});
		});
		
		var count = parseInt($('#totalCount').html());
		
		//加入购物车后购物车图标上方+1图标
		$('#addOne').show();
		$('#addOne').animate({
			'top':'-=80',
			'opacity':'0'
		},1000,function(){
			$(this).animate({
				'top':'+=80',
				'opacity':'1'
			},1,function(){
				//购物车商品数+1
				//$('#totalCount').html(count+1);
			});
		});
		

		//在右侧购物栏新增商品
		addOneToRightCart($(obj), goodsId, qishu);
		
		
	}	
	
	//往右侧购物栏新增商品
	function addOneToRightCart(obj, goodsId, qishu){
		
		var codeId = $(obj).attr('codeid');
		var canAdd = true;
		
		$('#cart_shower').children('dl').each(function(){
			if(codeId == $(this).attr('codeid')){
				canAdd = false;
				
				var num = parseInt($(this).find('.two-row').children(':eq(0)').text().substr(5));
				$(this).find('.two-row').children(':eq(0)').html('<em>云团人次：</em>'+(num+1));
				$(this).find('.two-row').children(':eq(1)').html('<em>小计：</em>￥'+(num+1)+'.00');
				
				return;
			}
		});
		
		if(canAdd){
			
			var name = $(obj).parent().siblings('.soon-list-name').children('a').text();
			var img = $(obj).parent().siblings('.g-soon-pic').find('img').prop('src');
			var limit = parseInt($(obj).attr('limit'));
			
			var str = 
				'<dl class="" buynum="1" codeid="'+codeId+'">'+
			       '<dd>'+
			        '<a target="_blank" title="'+name+'" href="announcing_detail1.html"><img src="'+img+'" /></a>';
			        
			if(limit == 1){
				str += '<b class="limitbuy-icon"></b>';
			} 
			str +=  '</dd>'+
				       '<dd class="z-ygrc">'+
				        '<p class="two-row" style="">'+
				        	'<cite><em>云团人次：</em>1</cite>'+
				        	'<cite><em>小计：</em>￥1.00</cite>'+
				        '</p>'+
				        '<p class="three-row">'+
							'<span class="gray6">剩余 {{fenshu-canyushu}}人次</span>'+
							'<span class="modify"><a class="unclick" href="javascript:;">-</a>'+
							'<input type="text" mylimitsales="0" limitbuy="5" surplus="5259" value="1"/>'+
							'<a class="" href="javascript:;">+</a></span>'+
							'<span id="miniTips">限购5人次</span>'+
						'</p>'+
				       '</dd>'+
				       '<dd class="z-close">'+
				        '<a class="delete-close transparent-png" title="删除" href="javascript:;" onClick="delOneCartPro(this, ' +  goodsId + ', ' + qishu + ');"></a>'+
				       '</dd>'+
				     '</dl>';    

			 $('#cart_box').css('display', 'block');
			 $('.cartEmpty').css('display', 'none');
			 $('#cart_shower').prepend(str);
		}
		
		countPrice();
		
	}
	
	
	function slideLeftShow(slide,left,speed,callback){
		$(slide).animate({
			left:left+'px'
		},speed,callback);
	}	
	
	function slideLeftHide(slide,left,speed,callback){
		$(slide).animate({
			left:left+'px'
		},speed,callback);
	}
	

	$(function() {
		
		
		//顶端不显示购物车栏
		$('#rightTool').hide();
		$(window).scroll(function(){
			if($(document).scrollTop()==0){
				$('#rightTool').fadeOut();
			}else{
				$('#rightTool').fadeIn();
			}
		});
		
		
		/*//飞入购物栏效果
		$('.u-cart').click(function(event){
			
			
			//event.pageX Y,offset.left top都是相对于整个网页的位置，相对于窗口的位置要-sTop
			//滚动条高度
			var sTop = $(window).scrollTop();
			var img = $(this).parent().siblings(':first').children().children().attr('src');
			var offset = $('#cartImg').offset();
			var	flyer = $('<img width="40px" height="40px" class="u-flyer" src="'+img+'"/>');
			flyer.fly({
				start: {
					left: event.pageX,
					top: event.pageY-sTop,
				},
				end: {
					left: offset.left+5,
					top: offset.top-sTop+5,
					width: 20,
					height: 20
				},
				onEnd:function(){
					shock();
					$('.u-flyer').remove();
				}
			});
			
			
		});*/
		
		
		//商品hover样式
		$('.soon-list-con').hover(function() {
			$(this).addClass('soon-list-hover');
		}, function() {
			$(this).removeClass('soon-list-hover');
		});
		
		$('.g-hotL-list').hover(function() {
			$(this).addClass('g-hotL-hover');
		}, function() {
			$(this).removeClass('g-hotL-hover');
		});
		
		
		
		//了解1元云团导航图切换
		$('#what_1yyg').click(function(){
			if($('#what_1yyg').hasClass('isHidden')){
				$('.f-step1').show();
				$('.f-step1').siblings().hide();
				slideLeftShow('#div_guide',0,500);
				
				$('#what_1yyg').removeClass('isHidden');
				checkArrow();
			}else{
				$('#what_1yyg').addClass('isHidden');
				slideLeftHide('#div_guide',709,500);
			}
		});
		
		//关闭
		$('#guide_close').click(function(){
			$('#what_1yyg').addClass('isHidden');
			slideLeftHide('#div_guide',709,500);
		});
		
		//下翻页
		$('#guide_next').click(function(){
			var index = $('#ul_guide').children(':visible').index()+1;
			var next = index+1;
			if(index<6){
				$('#ul_guide').children().hide();
				$('.f-step'+next).fadeIn();
				checkArrow();
			}else{
				checkArrow();
			}
		});
		//上翻页
		$('#guide_pre').click(function(){
			var index = $('#ul_guide').children(':visible').index()+1;
			var pre = index-1;
			if(index>1){
				$('#ul_guide').children().hide();
				$('.f-step'+pre).fadeIn();
				checkArrow();
			}else{
				checkArrow();
			}
		});
		//30秒了解
		$('.f-step1 a').click(function(){
			$('.f-step1').hide();
			$('.f-step2').fadeIn();
			checkArrow();
		});
		//下一步
		$('.f-step2 a,.f-step3 a,.f-step4 a,.f-step5 a').click(function(){
			var index = $('#ul_guide').children(':visible').index()+1;
			var next = index+1;
			if(index<6){
				$('#ul_guide').children().hide();
				$('.f-step'+next).fadeIn();
				checkArrow();
			}else{
				checkArrow();
			}
		});
		
		
		//导航图上下页箭头
		$('.pic-wrapper').hover(function(){
			$('.ctrl-prev').show();
			$('.ctrl-next').show(); 
		},function(){
			$('.ctrl-prev').hide();
			$('.ctrl-next').hide(); 
		});
		$('.ctrl-prev').hover(function(){
			$('.ctrl-prev').show();
			$('.ctrl-next').show(); 
		});
		$('.ctrl-next').hover(function(){
			$('.ctrl-prev').show();
			$('.ctrl-next').show(); 
		});
		
		
		//定时获取正在云购列表
		
		getShoppingList();
		
		
	});
	
	/**
	 * 上下页样式
	 */
	function checkArrow(){
		var index = $('#ul_guide').children(':visible').index()+1;
		if(index==1){
			$('#guide_pre').children().hide();
			$('#guide_next').children().show();
			$('#guide_pre').css({'cursor':'default'});
			$('#guide_next').css({'cursor':'pointer'});
			
		}else if(index==6){
			$('#guide_next').children().hide();
			$('#guide_pre').children().show();
			$('#guide_pre').css({'cursor':'pointer'});
			$('#guide_next').css({'cursor':'default'});
		}else{
			$('#guide_pre').children().show();
			$('#guide_next').children().show();
			$('#guide_pre').css({'cursor':'pointer'});
			$('#guide_next').css({'cursor':'pointer'});
		}
	}
	
	var index = 0;
	
	
	/**
	 * 获取正在云团列表
	 */
	function getShoppingList(){
		
		$.get(shoppingURL,function(data){
			setInterval(function(){
				eachList(data['list']);
			},3000);	
			//setTimeout(eachList(data),3000);
		},'json');
	}
	
	function eachList(data){
		
		//alert('a');
		
		var l = data.length;
		var userface = data[index%l]["userface"],
			username = data[index%l]["username"],
			prize = data[index%l]["prize"],
			userId = data[index%l]["userid"],
			goodsId = data[index%l]["goodsid"];
		
		
		var str = '<li>'+
					'<span class="fl">'+
						'<a title="'+username+'" rel="nofollow" target="_blank" href="' + shoppingStr + '&id=' + userId + '">'+
							'<img src="'+imageURL+userface+'" onerror="this.src=' + '\'/web/img/logo.png\'' + '"/>'+
							'<i class="transparent-png"></i>'+
						'</a>'+
					'</span>'+
					'<p>'+ 
						'<a class="blue" title="'+username+'" target="_blank" href="' + shoppingStr + '&id=' + userId + '">'+username+'</a>'+
						'<br>'+ 
						'<a class="u-ongoing" title="'+prize+'" target="_blank" href="' + goodsStr + '&id=' + goodsId +'">'+prize+'</a>'+ 
					'</p>'+
				  '</li>';
		$('#UserBuyNewList').css({'margin-top':'-90px'});
		$('#UserBuyNewList').children(':eq(0)').before(str);
		$('#UserBuyNewList').animate({
			'margin-top':'0'
		},500,function(){
			$(this).children(':eq(7)').remove();
		});
		index++;
		
		//setTimeout(eachList(data),3000);
		
	}
	
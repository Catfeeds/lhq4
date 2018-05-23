
function addCart(id, flag) {
	var flag = flag;
	$.post( addCartUrl, { id: id }, function(rel) {
		if(rel.info=="还未登录,正在为您跳转..."){
			layer.msg(rel.info,{icon:5});
			setTimeout(function(){
				location.href=rel.url;
			},1000);											
		}else{
			if(rel.num){
				$('.shop-cart-num').text(rel.num);
				$('.shop-cart').addClass('rubberBand animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
				      $(this).removeClass('rubberBand animated');
			    });
			}
			layer.msg(rel.info);
		}

		if (flag) {
			location.href = cartUrl;
		}
	});
}
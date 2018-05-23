/*
 * 后台
 */
$(function(){

	$('.admin-menu ul.nav').niceScroll({
		horizrailenabled: false,
	    cursorcolor: "rgba(255, 255, 255, .4)",
	    cursorwidth: "5px",
	    cursorborder: "1px solid rgba(255, 255, 255, .4)",
	    cursorborderradius: "5px"
	});

	$('.admin-menu ul.nav li').each(function(){
		if($('ul', this).length)
			$('a:first', this).append(' <i class="fa arrow"></i>');
	});


	$('a.active').each(function(){
		$(this).parents('li:last').addClass('open');

	});

	$('.admin-menu ul.nav li a').on('click', function(){
		
		$parent = $(this).parents('li:first');
		$parent.toggleClass('open');

		console.dir($parent.siblings());
		//$parent.siblings().removeClass('open').find('> ul').slideUp(300);


		if($parent.hasClass('open')){
			$('~ ul', this).css({visibility: "visible",display: "none"}).slideDown(300);
		}else{
			// $(this).s.css({visibility: "hidden"}).slideUp(300);
			$('~ ul', this).css({visibility: "hidden"}).slideUp(300);
		}
		
	});

	
});





function loading(off){
	if(off) return $('body').addClass('loaded');

	$('body').removeClass('loaded');
	if(! $('#loader-wrapper').length)
		$('body').append(' \
		<div id="loader-wrapper"> \
		    <div id="loader"></div> \
		    <div class="loader-section section-left"></div> \
		    <div class="loader-section section-right"></div> \
		</div>');
}



function msg(text, type){
	return noty({
		text: text,
		type: type || '',
		theme: 'relax',
		// relax defaultTheme bootstrapTheme
		layout: 'topCenter',
		/*
		animation : {
			open : 'animated bounceInDown',
			close : 'animated bounceOutDown',
			easing : 'swing',
			speed : 0,
			fadeSpeed: 'fast',
		},
		*/
		modal: (type && type == 'error' ? true : false),
		timeout: 1500
	});
}
























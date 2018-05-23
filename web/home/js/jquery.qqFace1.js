// QQ表情插件
(function($){  
	$.fn.qqFace = function(options){
		var defaults = {
			id : 'facebox',
			path : 'face/',
			assign : 'content',
			tip : 'em_'
		};
		var option = $.extend(defaults, options);
		var assign = $('#'+option.assign);
		var id = option.id;
		var path = option.path;
		var tip = option.tip;
		
		if(assign.length<=0){
			alert('缺少表情赋值对象。');
			return false;
		}
		
		$(this).click(function(e){
			var strFace, labFace;
			if($('#'+id).length<=0){
				strFace = '<div id="'+id+'" style="position:absolute;display:none;z-index:1000;" class="qqFace">' +
							  '<table border="0" cellspacing="0" cellpadding="0"><tr>';
				for(var i=1; i<=75; i++){
					labFace = '['+tip+i+']';
					strFace += '<td><img src="'+path+i+'.gif" onclick="$(\'#facebox\').find(\'td\').addEmotion('+i+');" /></td>';
					if( i % 15 == 0 ) strFace += '</tr><tr>';
				}
				strFace += '</tr></table></div>';
			}
			$(this).parent().append(strFace);
			var offset = $(this).position();
			var top = offset.top + $(this).outerHeight();
			$('#'+id).css('top',top);
			$('#'+id).css('left',offset.left);
			$('#'+id).show();
			e.stopPropagation();
		});

		$(document).click(function(){
			$('#'+id).hide();
			$('#'+id).remove();
		});
	};

})(jQuery);

jQuery.extend({ 
unselectContents: function(){ 
	if(window.getSelection) 
		window.getSelection().removeAllRanges(); 
	else if(document.selection) 
		document.selection.empty(); 
	} 
}); 
jQuery.fn.extend({ 
	selectContents: function(){ 
		$(this).each(function(i){ 
			var node = this; 
			var selection, range, doc, win; 
			if ((doc = node.ownerDocument) && (win = doc.defaultView) && typeof win.getSelection != 'undefined' && typeof doc.createRange != 'undefined' && (selection = window.getSelection()) && typeof selection.removeAllRanges != 'undefined'){ 
				range = doc.createRange(); 
				range.selectNode(node); 
				if(i == 0){ 
					selection.removeAllRanges(); 
				} 
				selection.addRange(range); 
			} else if (document.body && typeof document.body.createTextRange != 'undefined' && (range = document.body.createTextRange())){ 
				range.moveToElementText(node); 
				range.select(); 
			} 
		}); 
	}, 
	
	addEmotion: function(index){
		//alert($(this).children().attr('src'));
		//$('#facebox').find('td').addEmotion();
		var src = '../images/arclist/'+index+'.gif';
		//$("#Edit")[0].contentWindow.$("body").addClass('isEmpty');
		//$("#Edit")[0].contentWindow.$("body font").remove();
		$("#Edit")[0].contentWindow.$("body").append('<img src='+src+'/>');
		//$('.reply_unbotton').removeClass('disBtn');
		//var count = parseInt($('#countNumber').val());
		//$('#countNumber').val(count+3);
		//var emoCount = parseInt($('#wordNumber span').html());
		//$('#wordNumber span').html(emoCount-3);
		
		
		var comment = $("#Edit")[0].contentWindow.$("body").text();
  		if(comment==''){
  			$('.reply_unbotton').addClass('disBtn');
  			$('#countNumber').val(0);
  		}else{
  			$('.reply_unbotton').removeClass('disBtn');
  		}
  		//评论内容长度不含表情
  		var commentLen = comment.length;
  		var img_len = ($("#Edit")[0].contentWindow.$("body").children('img').length)*3;
  			
  		$('#countNumber').val(commentLen + img_len);
  		
  		if(commentLen + img_len>150){
  			var str = '<span class="orange">已经超过'+(commentLen + img_len-150)+'个字了，删除一些吧！</span>';
  			$('#wordNumber',parent.document).addClass('gray02').html(str);
  			$('.reply_unbotton', parent.document).addClass('disBtn');
  		}else{
  			var str = '您还可以输入<span class="orange">'+(150 - commentLen - img_len)+'</span>个字！';
  			$('#wordNumber',parent.document).html(str);
  			$('.reply_unbotton', parent.document).removeClass('disBtn');
  		}
		
	},
	
	setCaret: function(){ 
		if(!$.browser.msie) return; 
		var initSetCaret = function(){ 
			var textObj = $(this).get(0); 
			textObj.caretPos = document.selection.createRange().duplicate(); 
		}; 
		$(this).click(initSetCaret).select(initSetCaret).keyup(initSetCaret); 
	}, 

	insertAtCaret: function(textFeildValue){ 
		var textObj = $(this).get(0); 
		if(document.all && textObj.createTextRange && textObj.caretPos){ 
			var caretPos=textObj.caretPos; 
			caretPos.text = caretPos.text.charAt(caretPos.text.length-1) == '' ? 
			textFeildValue+'' : textFeildValue; 
		} else if(textObj.setSelectionRange){ 
			var rangeStart=textObj.selectionStart; 
			var rangeEnd=textObj.selectionEnd; 
			var tempStr1=textObj.value.substring(0,rangeStart); 
			var tempStr2=textObj.value.substring(rangeEnd); 
			textObj.value=tempStr1+textFeildValue+tempStr2; 
			textObj.focus(); 
			var len=textFeildValue.length; 
			textObj.setSelectionRange(rangeStart+len,rangeStart+len); 
			textObj.blur(); 
		}else{ 
			textObj.value+=textFeildValue; 
		} 
	} 
});
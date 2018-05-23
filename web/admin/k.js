



/*
<div class="input-group" style="width:100px;max-width:100%;">
	<img src="{$v.val}" style="height:60px;width:auto;" class="form-control id__{$v.id}" title="{$v.remarks}"/>
	<span data-id="{$v.id}" class="input-group-addon k-show-image poi">浏览</span>
	<span data-id="{$v.id}" class="input-group-addon k-upload-file poi">上传</span>
</div>
<input type="hidden" name="sys[{$v.id}][val]" value="{$v.val}" class="id__{$v.id}" required="required"/>
*/
$(function() {

	var imageManager = KindEditor.editor({
		fileManagerJson : ROOT + "/php/file_manager_json.php",
		uploadJson : ROOT + "/php/upload_json.php",
		allowFileUpload: true,
		allowFileManager : true
	});

	$(document)

		// 浏览文件
		.on('click', '.k-show-file', function() {
			var _id = $(this).data('id');
	
			imageManager.loadPlugin('filemanager', function() {
				imageManager.plugin.filemanagerDialog({
					viewType : 'VIEW',
					dirName : 'image',
					clickFn : function(url, title) {
						$('input.id__'+ _id).val(url);
						imageManager.hideDialog();
					}
				});
			});
		})
		
		// 上传文件
		.on('click', '.k-upload-file', function() {
			var _id = $(this).data('id');
			
			imageManager.loadPlugin('insertfile', function() {
				imageManager.plugin.fileDialog({
					allowFileUpload: false,
					clickFn : function(url, title) {
						$('input.id__'+ _id).val( url);
						imageManager.hideDialog();
					}
				});
			});
		})
	
	
		// 上传图片
		.on('click', '.k-upload-image', function() {
			var _id = $(this).data('id');
			
			imageManager.loadPlugin('image', function() {
				imageManager.plugin.imageDialog({
					showRemote : false,
					// imageUrl : K('.icon-url').val(),
					clickFn : function(url, title) {
						$('img.id__'+ _id).attr('src', url);
						$('input.id__'+ _id).val( url);
						imageManager.hideDialog();
					}
				});
			});
		})
	
		// 浏览图片
		.on('click', '.k-show-image', function() {
			var _id = $(this).data('id');
	
			imageManager.loadPlugin('filemanager', function() {
				imageManager.plugin.filemanagerDialog({
					viewType : 'VIEW',
					dirName : 'image',
					clickFn : function(url, title) {
						$('img.id__'+ _id).attr('src', url);
						$('input.id__'+ _id).val(url);
						imageManager.hideDialog();
					}
				});
			});
		})
	;




	// 图文详情 编辑器 初始化
	KindEditor.create('textarea[name="content"]', {
		resizeType: 1,
		fileManagerJson: ROOT+"/php/file_manager_json.php",
		uploadJson: ROOT+"/php/upload_json.php",
		urlType: 'absolute',
		syncType: 'auto',
		allowPreviewEmoticons: true,
		allowImageUpload: true,
		allowFileManager: true,
		afterBlur: function(){this.sync();},
		items : [
			'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
			'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
			'insertunorderedlist', '|', 'table', 'flash', 'media', 'image', 'multiimage', 'link', '|' ,'source' ,'fullscreen']
	});
	
	
	
	
	
	
	
	
	
});
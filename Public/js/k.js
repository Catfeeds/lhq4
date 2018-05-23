

KindEditor.ready(function(K) {
	var editor = K.editor({
		fileManagerJson :  ROOT+'/php/file_manager_json.php',
		uploadJson : ROOT+"/php/upload_json.php",
		allowFileUpload: true,
		allowFileManager : true
	});
	//上传图片
	K('.k-upload-image').click(function() {
		editor.loadPlugin('image', function() {
			editor.plugin.imageDialog({
				showRemote : false,
				imageUrl : K('icon-url').val(),
				clickFn : function(url, title, width, height, border, align) {
					K('img.icon-url').attr('src', url);
					K('input.icon-url').val(url);
					editor.hideDialog();
				}
			});
		});
	});
	//浏览图片
	K('.k-browse-image').click(function() {
		editor.loadPlugin('filemanager', function() {
			editor.plugin.filemanagerDialog({
				viewType : 'VIEW',
				dirName : 'image',
				clickFn : function(url, title) {
					K('img.icon-url').attr('src', url);
					K('input.icon-url').val(url);
					editor.hideDialog();
				}
			});
		});
	});
});
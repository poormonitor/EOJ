	<meta charset="utf-8" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zhblue/hustoj/trunk/web/kindeditor/themes/default/default.min.css" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zhblue/hustoj/trunk/web/kindeditor/plugins/code/prettify.min.css" />
	<script charset="utf-8" src="https://cdn.jsdelivr.net/gh/zhblue/hustoj/trunk/web/kindeditor/kindeditor.min.js"></script>
	<script charset="utf-8" src="https://cdn.jsdelivr.net/gh/zhblue/hustoj/trunk/web/kindeditor/lang/zh_CN.min.js"></script>
	<script charset="utf-8" src="https://cdn.jsdelivr.net/gh/zhblue/hustoj/trunk/web/kindeditor/plugins/code/prettify.min.js"></script>
	<script>
		KindEditor.ready(function(K) {
			var editor1 = K.create('textarea[class="kindeditor"]', {
				width : '100%',				
				cssPath : 'https://cdn.jsdelivr.net/gh/zhblue/hustoj/trunk/web/kindeditor/plugins/code/prettify.min.css',
				uploadJson : '../kindeditor/php/upload_json.php',
				fileManagerJson : '../kindeditor/php/file_manager_json.php',
				allowFileManager : false,
				afterCreate : function() {
					var self = this;
					K.ctrl(document, 13, function() {
						self.sync();
						K('form[name=example]')[0].submit();
					});
					K.ctrl(self.edit.doc, 13, function() {
						self.sync();
						K('form[name=example]')[0].submit();
					});
				}
			});
			prettyPrint();
		});
	</script>


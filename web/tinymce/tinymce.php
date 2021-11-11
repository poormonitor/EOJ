<?php $_SESSION[$OJ_NAME . '_' . 'uploadkey'] = strtoupper(substr(MD5($_SESSION[$OJ_NAME . '_' . 'user_id'] . rand(0, 9999999)), 0, 10)); ?>
<script src="<?php echo $OJ_CDN_URL . $path_fix . "tinymce/" ?>tinymce.min.js"></script>
<script>
    $("textarea[id^='tinymce']").each(function(index, elem) {
        tinymce.init({
            selector: "#tinymce" + index,
            language: 'zh_CN',
            inline: false,
            plugins: 'paste print preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor insertdatetime advlist lists wordcount imagetools textpattern help emoticons autosave autoresize',
            toolbar: 'code undo redo restoredraft | cut copy paste pastetext | forecolor backcolor bold italic underline strikethrough anchor | alignleft aligncenter alignright alignjustify outdent indent | \
                     formatselect fontselect fontsizeselect | bullist numlist | blockquote subscript superscript removeformat | \
                     table image media charmap emoticons hr pagebreak insertdatetime print preview | fullscreen | lineheight link',
            font_formats: '思源黑体=SourceHanSansCN-Medium',
            height: 650,
            min_height: 400,
            fontsize_formats: '14px 24px',
            image_class_list: [{
                    title: 'None',
                    value: ''
                },
                {
                    title: 'Some class',
                    value: 'class-name'
                }
            ],
            extended_valid_elements: 'script[src]',
            template_cdate_format: '[CDATE: %m/%d/%Y : %H:%M:%S]',
            template_mdate_format: '[MDATE: %m/%d/%Y : %H:%M:%S]',
            autosave_ask_before_unload: false,
            toolbar_mode: 'wrap',
            paste_auto_cleanup_on_paste: true,
            paste_remove_styles: true,
            paste_remove_styles_if_webkit: true,
            paste_strip_class_attributes: true,
            setup: function(editor) {
                editor.on('init', function(e) {
                    this.getBody().style.fontSize = '14px';
                    this.getBody().style.fontFamily = 'SourceHanSansCN-Medium';
                });
            },
            file_picker_callback: function(callback, value, meta) {
                var filetype = '.*';
                var upurl = '/tinymce/upfile.php';
                switch (meta.filetype) {
                    case 'image':
                        filetype = '.jpg, .jpeg, .png, .gif, .bmp';
                        upurl = '/tinymce/upimg.php';
                        break;
                    case 'media':
                        filetype = '.mp3, .flac, .aac, .wav, .mp4, .mkv, .wmv, .avi, .flv';
                        break;
                    default:
                        break;
                }
                var input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', filetype);
                input.click();
                input.onchange = function() {
                    var file = this.files[0];
                    var xhr, formData;
                    console.log(file.name);
                    xhr = new XMLHttpRequest();
                    xhr.withCredentials = false;
                    xhr.open('POST', upurl);
                    xhr.onload = function() {
                        var json;
                        if (xhr.status != 200) {
                            failure('HTTP Error: ' + xhr.status);
                            return;
                        }
                        json = JSON.parse(xhr.responseText);
                        if (!json || typeof json.location != 'string') {
                            failure('Invalid JSON: ' + xhr.responseText);
                            return;
                        }
                        callback(json.location, file.name);
                    };
                    formData = new FormData();
                    formData.append('file', file, file.name);
                    formData.append('uploadkey', '<?php echo $_SESSION[$OJ_NAME . '_' . 'uploadkey'] ?>')
                    xhr.send(formData);
                }
            }
        });
    })
</script>
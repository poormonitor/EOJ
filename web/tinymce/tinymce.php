<script src="../tinymce/tinymce.min.js"></script>
<script>
    $("textarea[id^='tinymce']").each(function(index, elem) {
        tinymce.init({
            selector: "#tinymce" + index,
            language: 'zh_CN',
            images_upload_url: '/tinymce/upload.php',
            inline: false,
            images_upload_base_path: '',
            plugins: 'paste print preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor insertdatetime advlist lists wordcount imagetools textpattern help emoticons autosave autoresize',
            toolbar: 'code undo redo restoredraft | cut copy paste pastetext | forecolor backcolor bold italic underline strikethrough link anchor | alignleft aligncenter alignright alignjustify outdent indent | \
                     formatselect fontselect fontsizeselect | bullist numlist | blockquote subscript superscript removeformat | \
                     table image media charmap emoticons hr pagebreak insertdatetime print preview | fullscreen | lineheight',
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
            }
        });
    })
</script>
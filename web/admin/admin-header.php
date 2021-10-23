<?php
require_once("../include/db_info.inc.php");
require_once("../include/my_func.inc.php");; ?>
<!DOCTYPE html>
<link rel=stylesheet href='<?php echo $OJ_CDN_URL . $path_fix ?>include/hoj.css' type='text/css'>
<?php require_once("../template/$OJ_TEMPLATE/css.php"); ?>
<style>
    @media (prefers-color-scheme: dark) {
        .btn {
            filter: invert(1) hue-rotate(180deg);
        }

        .btn-block {
            color: #000;
            filter: invert(0);
            background-color: #d5d5d5;
        }

        .btn-secondary {
            color: #000;
            filter: invert(0);
            background-color: #d5d5d5;
        }

        .dropdown-item {
            filter: invert(0);
        }

        .table {
            background-color: #e2e2e2;
        }
    }

    body {
        background-image: url(https://cdn.jsdelivr.net/gh/poormonitor/image@master/20210306/9570a8e4a6ee69b9e0ef5de25b729954.png);
    }
</style>
<script src="<?php echo $OJ_CDN_URL . $path_fix . "template/$OJ_TEMPLATE/" ?>jquery.min.js"></script>
<script src="../tinymce/tinymce.min.js"></script>
<script>
    $("document").ready(function() {
        $("form").append("<div id='csrf' />");
        $("#csrf").load("../csrf.php");
    });
    tinymce.init({
        selector: "#tinymce",
        language: 'zh_CN',
        plugins: 'image',
        toolbar: 'image',
        images_upload_url: '/tinymce/upload.php',
        images_upload_base_path: '',
        plugins: 'print preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template advcode codesample table charmap hr pagebreak nonbreaking anchor insertdatetime advlist lists wordcount imagetools textpattern help emoticons autosave bdmap indent2em autoresize formatpainter axupimgs importword kityformula-editor',
        toolbar: 'code undo redo restoredraft | cut copy paste pastetext | forecolor backcolor bold italic underline strikethrough link anchor | alignleft aligncenter alignright alignjustify outdent indent | \
                     styleselect formatselect fontselect fontsizeselect | bullist numlist | blockquote subscript superscript removeformat | \
                     table image media charmap emoticons hr pagebreak insertdatetime print preview | fullscreen | bdmap indent2em lineheight formatpainter axupimgs importword kityformula-editor',
        height: 650,
        min_height: 400,
        fontsize_formats: '12px 14px 16px 18px 24px 36px 48px 56px 72px',
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
    });
</script>

<?php
if (!(isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'contest_creator']) || isset($_SESSION[$OJ_NAME . '_' . 'problem_editor']) || isset($_SESSION[$OJ_NAME . '_' . 'password_setter']))) {
    echo "<a href='../loginpage.php'>请先登录</a>";
    exit(1);
}

if (file_exists("../lang/$OJ_LANG.php"))
    require_once("../lang/$OJ_LANG.php");
?>
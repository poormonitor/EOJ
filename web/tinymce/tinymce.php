<?php $_SESSION[$OJ_NAME . '_' . 'uploadkey'] = strtoupper(substr(MD5($_SESSION[$OJ_NAME . '_' . 'user_id'] . rand(0, 9999999)), 0, 10)); ?>
<script src="<?php echo $OJ_CDN_URL . "tinymce/" ?>tinymce.min.js"></script>
<script>
    var uploadkey = '<?php echo $_SESSION[$OJ_NAME . '_' . 'uploadkey'] ?>';
    create_mce(<?php echo isset($student_mode) ? "true" : "false" ?>);
</script>
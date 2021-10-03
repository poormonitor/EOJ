<!DOCTYPE html><html><head><meta charset="utf-8"><title><?php echo $OJ_NAME;?></title>
<style>
    @media (prefers-color-scheme: dark) {
        body {
            height: auto;
            background:#242424;
        }
</style>
</head><body></body></html>
<?php
$_SESSION[$OJ_NAME . '_' . 'OJ_LANG'] = "cn";
?>
<script>
    window.history.go(-1);
</script>

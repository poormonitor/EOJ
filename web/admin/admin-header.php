<!DOCTYPE html>
<?php
require_once("../include/db_info.inc.php");
require_once("../include/my_func.inc.php");; ?>
<link rel=stylesheet href='<?php echo $OJ_CDN_URL . $path_fix ?>include/hoj.min.css' type='text/css'>
<?php require_once("../template/$OJ_TEMPLATE/css.php"); ?>
<?php
if (!(isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'contest_creator']) || isset($_SESSION[$OJ_NAME . '_' . 'problem_editor']) || isset($_SESSION[$OJ_NAME . '_' . 'password_setter']))) {
    echo "<a href='../loginpage.php'>请先登录</a>";
    exit(1);
}

if (file_exists("../lang/$OJ_LANG.php"))
    require_once("../lang/$OJ_LANG.php");
?>
<script src="<?php echo $OJ_CDN_URL . $path_fix . "template/$OJ_TEMPLATE/" ?>jquery.min.js"></script>
<script>
    $("document").ready(function() {
        $("form").append("<div id='csrf' />");
        $("#csrf").load("../csrf.php");
    });
</script>
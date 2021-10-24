<!DOCTYPE html>
<?php
require_once("../include/db_info.inc.php");
require_once("../include/my_func.inc.php");; ?>
<?php require_once("../template/$OJ_TEMPLATE/css.php"); ?>
<link rel=stylesheet href='<?php echo $OJ_CDN_URL . $path_fix ?>include/hoj.css' type='text/css'>
<style>
    @media (prefers-color-scheme: dark) {
        .btn {
            filter: invert(1) hue-rotate(180deg);
        }

        .btn-secondary,
        .btn-block {
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

    input {
        height: 24px;
    }
</style>
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
<?php
require_once("../include/db_info.inc.php");
require_once("../include/my_func.inc.php");; ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet href='https://cdn.jsdelivr.net/gh/zhblue/hustoj/trunk/web/include/hoj.css' type='text/css'>
<script src="https://cdn.jsdelivr.net/gh/zhblue/hustoj/trunk/web/template/bs3/jquery.min.js"></script>
<script>
    $("document").ready(function() {
        $("form").append("<div id='csrf' />");
        $("#csrf").load("../csrf.php");
    });
</script>
<?php if (!(isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'contest_creator']) || isset($_SESSION[$OJ_NAME . '_' . 'problem_editor']) || isset($_SESSION[$OJ_NAME . '_' . 'password_setter']))) {
    echo "<a href='../loginpage.php'>请先登录</a>";
    exit(1);
}
require_once("../template/$OJ_TEMPLATE/css.php");
if (file_exists("../lang/$OJ_LANG.php")) require_once("../lang/$OJ_LANG.php");
?>
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
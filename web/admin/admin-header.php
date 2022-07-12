<?php
require_once("../include/db_info.inc.php");
require_once("../include/my_func.inc.php");

if (file_exists("../lang/$OJ_LANG.php"))
    require_once("../lang/$OJ_LANG.php");

if (!(isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'contest_creator']) || isset($_SESSION[$OJ_NAME . '_' . 'problem_editor']) || isset($_SESSION[$OJ_NAME . '_' . 'password_setter']))) {
    $view_swal_params = "{title:'$MSG_NOT_LOGINED',icon:'error'}";
    $error_location = "../loginpage.php";
    require("../template/error.php");
    exit(0);
}
$url = basename($_SERVER['REQUEST_URI']);
$ACTIVE = "class='active'";
$_SESSION[$OJ_NAME . '_' . 'profile_csrf'] = rand();
header("Cache-control: private");
$prefix = "../";
?>

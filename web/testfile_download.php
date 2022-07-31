<?php
require_once('./include/db_info.inc.php');
require_once('./include/my_func.inc.php');
require_once('./include/const.inc.php');
require_once('./include/setlang.php');


if (!isset($_SESSION[$OJ_NAME . '_' . 'user_id'])) {
    if (isset($OJ_GUEST) && $OJ_GUEST) {
        $_SESSION[$OJ_NAME . '_' . 'user_id'] = "Guest";
    } else {
        $view_swal = $MSG_NOT_LOGINED;
        $error_location = "loginpage.php";
        require("template/error.php");
        exit(0);
    }
}

if (!isset($_GET['pid']) || !isset($_GET["name"])) {
    $view_swal = "$MSG_NOT_EXISTED";
    require("template/error.php");
    exit(0);
}

$pid = intval($_GET['pid']);
$name = $_GET["name"];

if (!isset($_SESSION[$OJ_NAME . "_" . "testfile"]) || !in_array("$pid/$name", $_SESSION[$OJ_NAME . "_" . "testfile"])) {
    $view_swal = "$MSG_WARNING_ACCESS_DENIED";
    require("template/error.php");
    exit(0);
}

$file = "$OJ_DATA/$pid/$name";

if (!file_exists($file)) {
    $view_swal = $MSG_NOT_LOGINED;
    $error_location = "loginpage.php";
    require("template/error.php");
    exit(0);
};

$type = mime_content_type($file);

header("Content-type: $type");
header("Content-Disposition: attachment;filename=$name");
header("Content-Transfer-Encoding: binary");
header('Pragma: no-cache');
header('Expires: 0');
set_time_limit(0);
readfile($file);

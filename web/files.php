<?php
require_once('./include/db_info.inc.php');
require_once('./include/const.inc.php');
require_once('./include/memcache.php');
require_once('./include/setlang.php');
require_once('./include/my_func.inc.php');
if (!isset($_SESSION[$OJ_NAME . '_' . 'user_id'])) {
    if (isset($OJ_GUEST) && $OJ_GUEST) {
        $_SESSION[$OJ_NAME . '_' . 'user_id'] = "Guest";
    } else {
        $view_errors_js = "swal('需要登陆','$MSG_Login','error').then((onConfirm)=>{window.location.href='loginpage.php'})";
        require("template/" . $OJ_TEMPLATE . "/error.php");
        exit(0);
    }
}

$sql = "select gid from users where user_id = ?";
$gid = pdo_query($sql, $_SESSION[$OJ_NAME . '_' . 'user_id'])[0][0];
if ($gid == NULL && !isset($_SESSION[$OJ_NAME . '_' . 'administrator'])) {
    $view_swal = "您不属于任何组！";
    require("template/" . $OJ_TEMPLATE . "/error.php");
    exit(0);
}

define('FM_EMBED', true);
if (isset($_SESSION[$OJ_NAME . '_' . 'administrator'])) {
    define('FM_ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . "/upload/group");
} else {
    define('FM_ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . "/upload/group/" . $gid);
}
require_once('./include/filemanager.php');

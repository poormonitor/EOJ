<?php

$cache_time = 10;
$OJ_CACHE_SHARE = false;
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once('./include/setlang.php');
$view_title = $OJ_NAME;
if (!isset($_SESSION[$OJ_NAME . '_' . 'user_id'])) {
	$view_swal = $MSG_NOT_LOGINED;
	$error_location = "loginpage.php";
	require("template/error.php");
	exit(0);
}

$sql = "SELECT `school`,`nick`,`email` FROM `users` WHERE `user_id`=?";
$result = pdo_query($sql, $_SESSION[$OJ_NAME . '_' . 'user_id']);
$row = $result[0];



require("template/modifypage.php");

if (file_exists('./include/cache_end.php'))
	require_once('./include/cache_end.php');

<?php
require_once('./include/db_info.inc.php');
require_once('./include/const.inc.php');
require_once('./include/memcache.php');
require_once('./include/setlang.php');
require_once('./include/my_func.inc.php');

$view_title = "剪切板";

if (!isset($_SESSION[$OJ_NAME . '_' . 'user_id'])) {
	if (isset($OJ_GUEST) && $OJ_GUEST) {
		$_SESSION[$OJ_NAME . '_' . 'user_id'] = "Guest";
	} else {
		$view_errors_js = "swal('需要登陆','$MSG_Login','error').then((onConfirm)=>{window.location.href='loginpage.php'})";
		require("template/" . $OJ_TEMPLATE . "/error.php");
		exit(0);
	}
}

if (isset($_POST['content'])) {
	$sql = 'update users set clipboard=? where user_id=?';
	pdo_query($sql, $_POST['content'], $_SESSION[$OJ_NAME . '_' . 'user_id']);
	$flag = True;
}
$sql = 'select clipboard from users where user_id=?';
$result = pdo_query($sql,  $_SESSION[$OJ_NAME . '_' . 'user_id']);
if ($result[0][0] != NULL) {
	$content = $result[0][0];
}
require("template/" . $OJ_TEMPLATE . "/clipboard.php");

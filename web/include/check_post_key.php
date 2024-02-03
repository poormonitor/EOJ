<?php
require_once("db_info.inc.php");
print_r($_SESSION);
if (
	!isset($_SESSION[$OJ_NAME . '_' . 'postkey'])
	|| !isset($_POST['postkey'])
	|| !in_array($_POST['postkey'], $_SESSION[$OJ_NAME . '_' . 'postkey'])
) {
	$view_errors_js = "history.go(-1)";
	require_once("../template/error.php");
	exit(1);
} else {
	$key = array_search($_POST['postkey'], $_SESSION[$OJ_NAME . '_' . 'postkey']);
	unset($_SESSION[$OJ_NAME . '_' . 'postkey'][$key]);
}

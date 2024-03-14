<?php
require_once('./include/db_info.inc.php');
require_once('./include/const.inc.php');
require_once('./include/memcache.php');
require_once('./include/setlang.php');
require_once('./include/my_func.inc.php');

$view_title = $MSG_CLIPBOARD;

if (!isset($_SESSION[$OJ_NAME . '_' . 'user_id'])) {
	if (isset($OJ_GUEST) && $OJ_GUEST) {
		$_SESSION[$OJ_NAME . '_' . 'user_id'] = "Guest";
	} else {
		$view_errors_js = "swal('$MSG_NOT_LOGINED','$MSG_Login','error').then((onConfirm)=>{window.location.href='loginpage.php'})";
		require("template/error.php");
		exit(0);
	}
}

$sql = 'SELECT content, lang from clipboard where user_id=?';
$result = pdo_query($sql, $_SESSION[$OJ_NAME . '_' . 'user_id']);

if (!$result) {
	if (isset($_COOKIE['lastlang'])) $lang = $_COOKIE['lastlang'];
	else $lang = 6;

	$sql = 'INSERT INTO clipboard (user_id, lang) VALUES (?,?)';
	pdo_query($sql, $_SESSION[$OJ_NAME . '_' . 'user_id'], $lang);
	$content = "";
} else {
	$content = $result[0][0];
	$lang = $result[0][1];
}

if (isset($_POST['content'])) {
	if (strlen($_POST['content']) > 65536) $flag = False;

	$sql = 'UPDATE clipboard SET content=?, lang=? where user_id=?';
	pdo_query($sql, $_POST['content'], $_POST["language"], $_SESSION[$OJ_NAME . '_' . 'user_id']);

	$lang = $_POST["language"];
	$content = $_POST['content'];
	$flag = True;
}

require("template/clipboard.php");

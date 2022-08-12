<?php
$cache_time = 60;
$OJ_CACHE_SHARE = false;
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once('./include/setlang.php');
$view_title = $OJ_NAME;
if (!isset($_SESSION[$OJ_NAME . '_' . 'user_id'])) {
	header("Location: loginpage.php");
	exit(0);
}
require_once("./include/const.inc.php");
if (!isset($_GET['sid'])) {
	$view_swal = $MSG_NOT_EXISTED;
	require("template/error.php");
	exit(0);
}
function is_valid($str2)
{
	$n = strlen($str2);
	$str = str_split($str2);
	$m = 1;
	for ($i = 0; $i < $n; $i++) {
		if (is_numeric($str[$i])) $m++;
	}
	return $n / $m > 3;
}
if (!isset($_SESSION[$OJ_NAME . '_' . 'user_id'])) {
	$view_swal = $MSG_WARNING_ACCESS_DENIED;
	require("template/error.php");
	exit(0);
}

$ok = false;
$id = intval($_GET['sid']);
$sql = "SELECT * FROM `solution` WHERE `solution_id`=?";
$result = pdo_query($sql, $id);
$row = $result[0];
$slanguage = $row['language'];
$sresult = $row['result'];
$stime = $row['time'];
$smemory = $row['memory'];
$sproblem_id = $row['problem_id'];
$view_user_id = $suser_id = $row['user_id'];
$contest_id = $row['contest_id'];
$snick = $row['nick'];

if (
	$row
	&& ($row['user_id'] == $_SESSION[$OJ_NAME . '_' . 'user_id']
		|| isset($_SESSION[$OJ_NAME . '_' . 'administrator'])
		|| isset($_SESSION[$OJ_NAME . '_' . 'source_broswer']))
)
	$ok = true;
if (isset($_SESSION[$OJ_NAME . '_' . 'source_browser'])) $ok = true;
$view_reinfo = "";
if ($ok == true) {
	if ($row['user_id'] != $_SESSION[$OJ_NAME . '_' . 'user_id'])
		$view_mail_link = "<a href='mail.php?to_user={$row['user_id']}&title=$MSG_SUBMIT $id'>Mail the auther</a>";

	$sql = "SELECT `error` FROM `compileinfo` WHERE `solution_id`=?";
	$result = pdo_query($sql, $id);
	$row = $result[0];
	if ($row && is_valid($row['error']))
		$view_reinfo = htmlentities(str_replace("\n\r", "\n", $row['error']), ENT_QUOTES, "UTF-8");
} else {

	$view_swal = $MSG_WARNING_ACCESS_DENIED;
	require("template/error.php");
	exit(0);
}

$sql = "SELECT `source` FROM `source_code` WHERE `solution_id`=?";
$result = pdo_query($sql, $id);
$row = $result[0];
if ($row)
	$view_source = $row['source'];

require("template/ceinfo.php");

if (file_exists('./include/cache_end.php'))
	require_once('./include/cache_end.php');
